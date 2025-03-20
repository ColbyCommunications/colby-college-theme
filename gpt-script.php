<?php

if (!defined('WP_CLI') || !WP_CLI) {
    return;
}

class GPT_Script
{
    public function RunImageAltScript()
    {
        global $wpdb;

        // Open the CSV file handle
        $csvFile = __DIR__ . '/output.csv';
        $handle = fopen($csvFile, 'w');

        // Check if file pointer was opened successfully
        if ($handle === false) {
            WP_CLI::error("Error opening CSV file for writing: $csvFile");
            return;
        }

        // Query database to count the number of image attachments
        $total_images = $wpdb->get_var("
            SELECT COUNT(ID)
            FROM $wpdb->posts
            WHERE post_type = 'attachment'
            AND post_mime_type LIKE 'image/%'
        ");

        // Display total number of images
        WP_CLI::line(WP_CLI::colorize("%gTotal number of images in the media library: $total_images%n"));

        // Count the images that are of supported and unsupported types
        $specified_types_count = $wpdb->get_var("
            SELECT COUNT(ID)
            FROM $wpdb->posts
            WHERE post_type = 'attachment'
            AND post_mime_type IN ('image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif')
        ");

        // Calculate the count of images that are not of the specified types
        $not_specified_types_count = $total_images - $specified_types_count;

        // Display count of images not of specified types
        WP_CLI::line(WP_CLI::colorize("%mTotal number of images of supported types: $specified_types_count%n"));
        WP_CLI::line(WP_CLI::colorize("%mTotal number of images of incompatible types: $not_specified_types_count%n"));

        // Get all attachments from the media library
        $args = array(
            'post_type'      => 'attachment',
            'post_mime_type' => array('image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'),
            'posts_per_page' => -1,
        );

        $attachments = get_posts($args);

        // Loop through each attachment
        for ($i = 0; $i < min(60, count($attachments)); $i++) {
            $attachment = $attachments[$i];

            // Get the attachment ID and URL
            $attachment_id = $attachment->ID;
            $attachment_url = wp_get_attachment_url($attachment_id);
						$year  = basename( dirname( dirname( $attachment_url) ) );
            $month = basename( dirname( $attachment_url) );
						$attachment_name = $year . '/' . $month . '/' . basename( $attachment_url );

            // Get the alt text
            $alt_text = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);

            // Check if alt text exists
            if (empty($alt_text)) {
                // Get the parent post ID
                $parent_post_id = wp_get_post_parent_id($attachment_id);

                // Check if the parent post exists and is of type "people"
                if ($parent_post_id && get_post_type($parent_post_id) === 'people') {
                    // Output the attachment ID
                    WP_CLI::line(WP_CLI::colorize("%ySkipping PEOPLE post attachment with ID: $attachment_id%n"));
                } else {
                    WP_CLI::line("Processing attachment with ID: $attachment_id");
                    $this->AddAltText($handle, $attachment_id, $attachment_url);
                }
            }
        }
        // Close the CSV file handle
        fclose($handle);
    }

		private function encode_image($image_path)
		{
			return base64_encode(file_get_contents($image_path));
		}

    private function AddAltText($csv, $id, $full_path)
    {
			// OpenAI API Key
			$api_key = PLATFORM_VARIABLES["GPT4KEY"];

			// Getting the base64 string
			$base64_image = $this->encode_image($full_path);

			$headers = [
					"Content-Type: application/json",
					"Authorization: Bearer $api_key"
			];

			$payload = [
					"model" => "gpt-4-vision-preview",
					"messages" => [
							[
									"role" => "user",
									"content" => [
											[
													"type" => "text",
													"text" => "Describe this image as HTML alt text in 125 characters or less"
											],
											[
													"type" => "image_url",
													"image_url" => [
															"url" => "data:image/jpeg;base64,$base64_image",
															"detail" => "low"
													]
											]
									]
							]
					],
					"max_tokens" => 300
			];

			$ch = curl_init("https://api.openai.com/v1/chat/completions");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$response = curl_exec($ch);
			curl_close($ch);

			// Decode JSON response
			$response_array = json_decode($response, true);

			$error = false;
			$response_content = NULL;

			if (isset($response_array['choices'][0]['message']['content'])) {
				$response_content = $response_array['choices'][0]['message']['content'];
			} elseif (isset($response_array['error']['message'])) {
				$error = true;
				$response_content = "ERROR " . $response_array['error']['message'];
			} else {
				$response_content = "Unknown error occurred";
			}

			$response_content = preg_replace('/[^a-zA-Z0-9\s]/', '', $response_content);

			// Write data to the CSV file
			$success = fputcsv($csv, array($id, $full_path, $response_content));

			// Check if data was written successfully
			if ($success === false) {
					WP_CLI::error("Error writing to CSV file");
			}

			// Check if the post exists
			if (get_post_status($id) && get_post_type($id) === 'attachment') {
				if (!$error) {
					update_post_meta($id, '_wp_attachment_image_alt', $response_content);
					WP_CLI::line("Alt text added successfully to database for attachment with ID: $id.");
				} else {
					WP_CLI::warning("ERROR: " . $response_array['error']['message']);
				}
			} elseif (!get_post_status($id)) {
					WP_CLI::line("ERROR: Post with ID $id does not exist.");
			} else {
					WP_CLI::line("ERROR: Post with ID $id is not an attachment.");
			}
		}
	}
		

WP_CLI::add_command('RunImageAltScript', 'GPT_Script');

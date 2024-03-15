<?php

if (!defined('WP_CLI') || !WP_CLI) {
    return;
}

class Register_Images_Script {
    public function registerImages() {
        // Get uploads directory path
        $uploads_path = dirname(dirname(__DIR__)) . '/uploadstest';

        // Check if the uploads directory exists
        if (is_dir($uploads_path)) {

            // Use RecursiveDirectoryIterator to iterate through subdirectories
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($uploads_path, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );

            // Array to store image files
            $image_files = [];

            // Initial exclude pattern to exclude files like "4-150x133.jpg"
            $initialExcludePattern = '/\d+x\d+\.[^.]+$/';

            // Updated exclude pattern to include "-scaled" before file extension
            $scaledExcludePattern = '/-scaled\.[^.]+$/';

            foreach ($iterator as $file) {
                // Check if the current item is a file and has a valid image extension
                if ($file->isFile() && in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $year = basename(dirname(dirname($file)));
                    $month = basename(dirname($file));
                    // example: 2022/12/gilkes.jpg
                    $fileName = $year . "/" . $month . "/" . basename($file);

                    // Check if the file matches the initial exclude pattern
                    if (!preg_match($initialExcludePattern, $fileName)) {
                        // Check if the file matches the scaled exclude pattern
                        if (!preg_match($scaledExcludePattern, $fileName)) {

                            $image_files[] = $fileName;
                        }
                    }
                }
            }

            // Check if there are any image files
            if (!empty($image_files)) {

                // Output the number of images found
                WP_CLI::success(sprintf('Found %d image(s) in the uploads directory.', count($image_files)));

                // Get the absolute path for the CSV file
                // $csvFilePath = __DIR__ . '/output.csv';

                // Open a new CSV file for writing
                // $csvFile = fopen($csvFilePath, 'w');

                foreach ($image_files as $image_file) {
                    WP_CLI::line("Processing Image: $image_file");

                    global $wpdb;

                        $query = $wpdb->prepare(
                            "SELECT meta_value
                            FROM wp_postmeta
                            WHERE meta_key = '_wp_attachment_metadata'
                            AND meta_value LIKE %s",
                            '%' . $image_file . '%'
                        );

                        $result = $wpdb->get_results($query);

                        if ($result) {
                            // Image already excists in the database
                            WP_CLI::line("Matching image found.");
                        } else {
                            // Image not found and needs to be uploaded
                            $full_path = "$uploads_path/$image_file";
                            WP_CLI::line("Image not found. Uploading image: $image_file");
                            // Define the attachment
                            $attachment = array(
                                'post_mime_type' => mime_content_type($full_path),
                                'post_title'     => preg_replace('/\.[^.]+$/', '', basename($image_file)),
                            );
                            WP_CLI::line($attachment['post_mime_type']);
                            WP_CLI::line($attachment['post_title']);

                            // Insert the attachment
                            $attachment_id = wp_insert_attachment($attachment, $full_path);

                            // Error handling for uploading the attachment and metadata
                            if (is_wp_error($attachment_id)) {
                                // Handle the error
                                WP_CLI::error('Error adding image to the media library: ' . $attachment_id->get_error_message());
                            } else {
                                // Success: attachment was inserted
                                // Generate attachment metadata
                                $metadata = wp_generate_attachment_metadata($attachment_id, $full_path);

                                if (is_wp_error($metadata)) {
                                    // Handle the error
                                    WP_CLI::error('Error generating attachment metadata: ' . $metadata->get_error_message());
                                } else {
                                    // Success: metadata generated successfully
                                    // Update attachment metadata
                                    wp_update_attachment_metadata($attachment_id, $metadata);

                                    // Output success message
                                    WP_CLI::success('Image added to the media library successfully! Attachment ID: ' . $attachment_id);
                                }
                            }
                        }
                    // fputcsv($csvFile, [$image_file]);
                }

                // Close the CSV file
                // fclose($csvFile);

        } else {
                WP_CLI::error('No images found in the uploads directory.');
            }

        } else {
            WP_CLI::error('Uploads directory not found.');
        }
    }
}

WP_CLI::add_command('registerImages', 'Register_Images_Script');

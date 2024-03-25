<?php

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

class Register_Images_Script {
	public function registerImages() {
		// Get uploads directory path
		$uploads_path = dirname( dirname( __DIR__ ) ) . '/uploads';

		// Check if the uploads directory exists
		if ( is_dir( $uploads_path ) ) {

			// Use RecursiveDirectoryIterator to iterate through subdirectories
			$iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $uploads_path, RecursiveDirectoryIterator::SKIP_DOTS ),
				RecursiveIteratorIterator::SELF_FIRST
			);

			// Array to store image files
			$image_files = array();

			// Initial exclude pattern to exclude files like "4-150x133.jpg"
			$initialExcludePattern = '/\d+x\d+\.[^.]+$/';

			// Updated exclude pattern to include "-scaled" before file extension
			$scaledExcludePattern = '/-scaled\.[^.]+$/';

			foreach ( $iterator as $file ) {
				// Check if the current item is a file and has a valid image extension
				if ( $file->isFile() && in_array( strtolower( $file->getExtension() ), array( 'jpg', 'jpeg', 'png', 'gif', 'webp' ) ) ) {
					$year  = basename( dirname( dirname( $file ) ) );
					$month = basename( dirname( $file ) );
					// example: 2022/12/gilkes.jpg
					$fileName = $year . '/' . $month . '/' . basename( $file );

					// Check if the file matches the initial exclude pattern
					if ( ! preg_match( $initialExcludePattern, $fileName ) ) {
						// Check if the file matches the scaled exclude pattern
						if ( ! preg_match( $scaledExcludePattern, $fileName ) ) {

							$image_files[] = $fileName;
						}
					}
				}
			}

			// Check if there are any image files
			if ( ! empty( $image_files ) ) {

				// Output the number of images found
				WP_CLI::success( sprintf( 'Found %d image(s) in the uploads directory.', count( $image_files ) ) );

				// Get the absolute path for the CSV file
				$csvFilePath = __DIR__ . '/output.csv';

				// Open a new CSV file for writing
				$csvFile = fopen( $csvFilePath, 'w' );

				foreach ( $image_files as $image_file ) {
					WP_CLI::line( "Processing Image: $image_file" );

					global $wpdb;

					$query = $wpdb->prepare(
                        "SELECT meta_value
                        FROM wp_postmeta
                        WHERE meta_key = '_wp_attached_file'
                        AND meta_value = %s",
                        $image_file
                    );

						$result = $wpdb->get_results( $query );

					if ( $result ) {
						// Image already excists in the database
						WP_CLI::line( 'Matching image found.' );
					} else {
						// Image not found and needs to be uploaded
						$full_path = "$uploads_path/$image_file";
						WP_CLI::line( "Image not found. Uploading image: $image_file" );
						// Define the attachment
						$attachment = array(
							'post_mime_type' => mime_content_type( $full_path ),
							'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $image_file ) ),
						);
						WP_CLI::line( $attachment['post_mime_type'] );
						WP_CLI::line( $attachment['post_title'] );

						$table  = $wpdb->prefix . 'posts';
                        $currentDateTime = date('Y-m-d H:i:s');
						$data   = array(
							'ID'                => null,
							'post_name'         => preg_replace( '/\.[^.]+$/', '', basename( $image_file ) ),
							'post_title'        => preg_replace( '/\.[^.]+$/', '', basename( $image_file ) ),
							'post_status'       => 'inherit',
							'comment_status'    => 'closed',
							'ping_status'       => 'closed',
							'post_date'         => $currentDateTime,
							'post_date_gmt'     => $currentDateTime,
							'post_modified'     => $currentDateTime,
							'post_modified_gmt' => $currentDateTime,
							'post_parent'       => 0,
							'post_type'         => 'attachment',
							'guid'              => $full_path,
							'post_mime_type'    => mime_content_type( $full_path ),
						);
						$format = array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s' );
						$wpdb->insert( $table, $data, $format );
						$my_id = $wpdb->insert_id;

						add_post_meta( $my_id, '_wp_attached_file', $image_file );
						$file = explode( '/', $image_file )[2];

                        // Get the dimensions of the image
                        list($width, $height) = getimagesize($full_path);

                        // Define maximum dimensions for medium, large, and thumbnail sizes
                        $max_medium_width = 300;
                        $max_medium_height = 300;
                        $max_large_width = 1024;
                        $max_large_height = 1024;
                        $max_thumbnail_width = 150;
                        $max_thumbnail_height = 150;

                        // Define sizes array with only sizes that fall within the original dimensions
                        $sizes = array(
                            'full' => array(
                                'file'      => $file,
                                'width'     => $width,
                                'height'    => $height,
                                'mime-type' => mime_content_type($full_path),
                                'filesize'  => filesize($full_path),
                            )
                        );

                        // Check if medium size is within original dimensions
                        if ($width >= $max_medium_width && $height >= $max_medium_height) {
                            $sizes['medium'] = array(
                                'file'      => $file,
                                'width'     => $max_medium_width,
                                'height'    => $max_medium_height,
                                'mime-type' => mime_content_type($full_path),
                                'filesize'  => filesize($full_path),
                            );
                        }

                        // Check if large size is within original dimensions
                        if ($width >= $max_large_width && $height >= $max_large_height) {
                            $sizes['large'] = array(
                                'file'      => $file,
                                'width'     => $max_large_width,
                                'height'    => $max_large_height,
                                'mime-type' => mime_content_type($full_path),
                                'filesize'  => filesize($full_path),
                            );
                        }

                        // Check if thumbnail size is within original dimensions
                        if ($width >= $max_thumbnail_width && $height >= $max_thumbnail_height) {
                            $sizes['thumbnail'] = array(
                                'file'      => $file,
                                'width'     => $max_thumbnail_width,
                                'height'    => $max_thumbnail_height,
                                'mime-type' => mime_content_type($full_path),
                                'filesize'  => filesize($full_path),
                            );
                        }

                        // Add post meta with original image dimensions and allowed sizes
                        add_post_meta(
                            $my_id,
                            '_wp_attachment_metadata',
                            array(
                                'width'    => $width,
                                'height'   => $height,
                                'filesize' => filesize($full_path),
                                'sizes'    => $sizes,
                            ),
                            true // Unique
                        );
                        // Identify post as a re-registered image
                        add_post_meta(
                            $my_id,
                            're_registered',
                            true,
                            true // Unique
                        );

						// Insert the attachment
						// 	$attachment_id = wp_insert_attachment( $attachment, $full_path );

						// 	// Error handling for uploading the attachment and metadata
						// 	if ( is_wp_error( $attachment_id ) ) {
						// 		// Handle the error
						// 		WP_CLI::error( 'Error adding image to the media library: ' . $attachment_id->get_error_message() );
						// 	} else {
						// 		// Success: attachment was inserted
						// 		// Generate attachment metadata
						// 		$metadata = wp_generate_attachment_metadata( $attachment_id, $full_path );

						// 		if ( is_wp_error( $metadata ) ) {
						// 			// Handle the error
						// 			WP_CLI::error( 'Error generating attachment metadata: ' . $metadata->get_error_message() );
						// 		} else {
						// 			// Success: metadata generated successfully
						// 			// Update attachment metadata
						// 			wp_update_attachment_metadata( $attachment_id, $metadata );

						// 			// Output success message
						// 			WP_CLI::success( 'Image added to the media library successfully! Attachment ID: ' . $attachment_id );
						// 		}
						// 	}
					}
					fputcsv( $csvFile, array( $image_file ) );
				}

				// Close the CSV file
				fclose( $csvFile );

			} else {
				WP_CLI::error( 'No images found in the uploads directory.' );
			}
		} else {
			WP_CLI::error( 'Uploads directory not found.' );
		}
	}
}

WP_CLI::add_command( 'registerImages', 'Register_Images_Script' );
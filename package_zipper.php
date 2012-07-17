<?php
/* TODO:
  - Add new folders
  - Ability to clone an existing directory from the current location
*/

class Zip_Pack {
    var $temp_loc = null; // Location of system's temporary directory
    var $zip = null; // Zip interface object http://www.php.net/manual/en/class.ziparchive.php
    var $zip_package = null; // The complete zip package

	function __construct() {
		$this->zip_extension_exists();

        // Zip interface object http://www.php.net/manual/en/class.ziparchive.php
        $this->zip = new ZipArchive();

        // Create a zip file with a temporary name
        $this->zip_package = tempnam($this->get_temp_dir(), 'zip_package');

        // Create an empty zip file to store the data and open the interface
        $this->zip->open($this->zip_package, ZipArchive::CREATE);
	}

	// Test and error handling for zip extension support in the current running
	// server.
	function zip_extension_exists() {
		if (!extension_loaded('zip')) {
			$error_message = 'Package Zipper requires the PHP Zip extension to be enabled. For information on enabling it please see the official PHP Zip extenstion installation docs at http://www.php.net/manual/en/zip.setup.php';
			throw new Exception($error_message);
		} else {
			return true;
		}
	}

    function output_zip($name) {
        // Close the zip interface
        $this->zip->close();

        // Retrieve file size so the browser knows the length of the download
        $file_size = filesize($this->zip_package);

        // Output proper header data to force a download instead of going to a new page
        header('Content-Type: application/zip');
        header('Content-Length: ' . $file_size);
        header('Content-Disposition: attachment; filename="' . $name . '.zip"');

        // Send the user a downloadable file
        readfile($this->zip_package);
    }

    /**
	 * Creates a new file inside the current zip archive. If you supply an existing
	 * file name and location, the existing file will be overwritten.
	 */
    function set_file($name, $content, $loc = '') {
        // Create temporary file
        $file = tempnam($this->get_temp_dir(), $name . $loc);

        // Generate a simple read and write utility in binary
        $file_writer = fopen($file, "w"); // Opens the file with a write utility
        fwrite($file_writer, $content);
        fclose($file_writer);

        // Save file data for inclusion
		$this->zip->addFile($file, $name);
    }

	// Set or override an existing folder
	function set_folder($name, $loc = '') {

	}

    // Clones a directory into the zip file
	// http://stackoverflow.com/questions/1334613/how-to-recursively-zip-a-directory-in-php
    function clone_dir($loc) {

    }

    // Retrieve location of system's temporary directory
    function get_temp_dir() {
        // Cache and return result when possible
        if ($this->temp_loc !== null)
            return $this->temp_loc;
        else
            return $this->temp_loc = sys_get_temp_dir();
    }
};
?>
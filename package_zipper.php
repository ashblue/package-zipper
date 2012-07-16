<?php
/* TODO:
  - Add ability to inject files into the zip at a specific folder
  - Ability to clone an existing directory from the current location
  - Ability to replace an existing file already in the zip
*/

class Zip_Pack {
    var $temp_loc = null; // Location of system's temporary directory
    var $zip = null; // Zip interface object http://www.php.net/manual/en/class.ziparchive.php
    var $zip_package = null; // The complete zip package

	function __construct() {
        // Zip interface object http://www.php.net/manual/en/class.ziparchive.php
        $this->zip = new ZipArchive();

        // Create a zip file with a temporary name
        $this->zip_package = tempnam($this->get_temp_dir(), 'zip_package');

        // Create an empty zip file to store the data and open the interface
        $this->zip->open($this->zip_package, ZipArchive::CREATE);
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

    // Creates a new file inside a zip file
    function create_file($name, $content, $loc = '') {
        // Create temporary file
        $file = tempnam($this->get_temp_dir(), $name);

        // Generate a simple read and write utility in binary
        $file_writer = fopen($file, "w"); // Opens the file with a write utility
        fwrite($file_writer, $content);
        fclose($file_writer);

        // Save file data for inclusion
		$this->zip->addFile($file, $name);
    }

    // Finds and replaces the given file inside a zip folder
    function replace_file($name, $content) {

    }

    // Clones a directory into the zip file
    function clone_dir($start_loc) {

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
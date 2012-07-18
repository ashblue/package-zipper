<?php
/* TODO:
  - Finish cloning method
  - Simple documentation with PHP docs
  - Setup demo files
*/

class Zip_Pack {
    var $temp_loc = null; // Location of system's temporary directory
    var $zip = null; // Zip interface object http://www.php.net/manual/en/class.ziparchive.php
    var $zip_package = null; // The complete zip package

	function __construct() {
		$this->zip_support();

        // Zip interface object http://www.php.net/manual/en/class.ziparchive.php
        $this->zip = new ZipArchive();

        $this->create_zip();
	}

	// Verify current hosting environment supports the PHP Zip extension.
	function zip_support() {
		if (!extension_loaded('zip')) {
			$error_message = 'Package Zipper requires the PHP Zip extension to be enabled. For information on enabling it please see the official PHP Zip extenstion installation docs at http://www.php.net/manual/en/zip.setup.php';
			throw new Exception($error_message);
		} else {
			return true;
		}
	}

	// Creates a new zip file for output, good for overriding old zip data
	function create_zip() {
        // Create a zip file with a temporary name
        $this->zip_package = tempnam($this->get_temp_dir(), 'zip_package');

        // Create an empty zip file to store the data and open the interface
        $this->zip->open($this->zip_package, ZipArchive::CREATE);
	}

	// Returns a file instead of a page from the current PHP file
    function get_zip($name = null) {
		// Close the zip interface
		$this->zip->close();

		if ($name !== null) {
			// Retrieve file size so the browser knows the length of the download
			$file_size = filesize($this->zip_package);

			// Output proper header data to force a download instead of going to a new page
			header('Content-Type: application/zip');
			header('Content-Length: ' . $file_size);
			header('Content-Disposition: attachment; filename="' . $name . '.zip"');

			// Send the user a downloadable file
			readfile($this->zip_package);
		} else {
			return $this->zip_package;
		}
    }

    /**
	 * Creates a new file inside the current zip archive. If you supply an existing
	 * file name and location, the existing file will be overwritten.
	 */
    function set_file($name, $content) {
        // Create temporary file
        $file = tempnam($this->get_temp_dir(), $name);

        // Generate a simple read and write utility in binary
        $file_writer = fopen($file, "w"); // Opens the file with a write utility
        fwrite($file_writer, $content);
        fclose($file_writer);

        // Save file data for inclusion
		$this->zip->addFile($file, $name);
    }

	// Set or override an existing folder
	function set_folder($loc) {
		$this->zip->addEmptyDir($loc);
	}

	// Delete an existing file or folder
	// NOTE: All folder paths must end with a "/", for example to delete a folder called
	// foo, you must pass 'foo/' as $loc
	function delete_name($loc) {
		$this->zip->deleteName($loc);
	}

    // Clones a directory into the zip file
	// http://stackoverflow.com/questions/1334613/how-to-recursively-zip-a-directory-in-php
    function clone_dir($loc, $include_parent_folder = false) {
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($loc), RecursiveIteratorIterator::SELF_FIRST);

		foreach ($files as $file) :
			if (is_dir($file) === true):
				echo 'dir ';
				// $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
			elseif (is_file($file) === true):
				echo 'file ';
				// $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
			endif;

			echo $file . '<br>';
		endforeach;
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
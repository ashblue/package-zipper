<?php
/* TODO:
  - Add a init constructor that breaks up the create_zip method into a better set of logic
    and add files on the fly instead of adding them to the zip file via an array.
*/

class Zip_Pack {
    var $temp_loc = null; // Location of system's temporary directory
    var $zip = null; // Zip interface object http://www.php.net/manual/en/class.ziparchive.php
    var $zip_file_name = 'game'; // Name of the final zip file output.
    var $zip_files = array(); // Array of zip files that need to be added to the zip package

    // Creates a zip file from added files, folders, and other data
    function create_zip() {
        // Zip interface object http://www.php.net/manual/en/class.ziparchive.php
        $this->zip = new ZipArchive();

        // Create a zip file with a temporary name
        $zip_package = tempnam($this->get_temp_dir(), 'zip_package');

        // Create an empty zip file to store the data
        $this->zip->open($zip_package, ZipArchive::CREATE);

        // Inject each new file into the archive
        foreach ($this->zip_files as $zip_file):
            // Note: Can this be added in a specific folder?
            $this->zip->addFile($zip_file['data'], $zip_file['name']);
        endforeach;

        // Close the zip interface
        $this->zip->close();

        $this->output_zip($zip_package);

        // Example
        //$zip_file = tempnam($temp_loc, 'zip');
        //$zip = new ZipArchive();
        //$zip->open($zip_file, ZipArchive::CREATE);
        //$zip->addFile($test_file, 'test.js');
        //$zip->close();
    }

    function output_zip($file) {
        // Retrieve file size so the browser knows the length of the download
        $file_size = filesize($file);

        // Output proper header data to force a download instead of going to a new page
        header('Content-Type: application/zip');
        header('Content-Length: ' . $file_size);
        header('Content-Disposition: attachment; filename="' . $this->zip_file_name . '.zip"');

        // Send the user a downloadable file
        readfile($file);

        // Example
        //$file_size = filesize($zip_file);
        //header('Content-Type: application/zip');
        //header('Content-Length: ' . $file_size);
        //header('Content-Disposition: attachment; filename="file.zip"');
        //readfile($zip_file);
    }

    // Creates a new file inside a zip file
    function create_file($name, $content, $loc = '') {
        // Create temporary file
        $file = tempnam($this->get_temp_dir(), $name);

        // Generate a simple read and write utility in binary
        $file_writer = fopen($file, "w"); // Opens the file with a write utility
        fwrite($file_writer, $content);
        fclose($file_writer);

        // Save file data for inclusion into
        $file_data = array(
            'name' => $name,
            'loc' => $loc,
            'data' => $file
        );
        array_push($this->zip_files, $file_data);

        //// Create a temporary file reference
        //$test_file = tempnam('', "FOO");
        //
        //// Generate a simple read and write utility in binary
        //$file_writer = fopen($test_file, "w"); // Opens the file with a write utility
        //fwrite($file_writer, "writing to tempfile");
        //fclose($file_writer);
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
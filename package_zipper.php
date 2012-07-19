<?php
/**
 * PHP Package Zipper
 *
 * Requires:
 * - PHP Version X
 * - PHP Zip Extension, usually enabled by default (http://www.php.net/manual/en/zip.setup.php)
 *
 * Package Zipper is an extendable class for creating your own custom
 * zip files on the fly with PHP. It uses a couple simple methods that
 * give you a large amount of flexibility. If you don't like the provided
 * functionality, you can easily exted the class.
 *
 * Usage instructions:
 * 1. Include the package_zipper.php file in your page: include('package_zipper.php');
 * 2. Create a new instance of the Package Zipper class: $zip_pack = new Zip_Pack;
 * 3. Make your zip file and output it: $zip_pack->set_file('foo.txt', 'foo bar')->get_zip('zip_package');
 *
 * What it looks like when its all put together:
 * // Include package zipper from a relative URL
 * include('package_zipper.php');
 *
 * // Create a new zip pack
 * $zip_pack = new Zip_Pack;
 *
 * // Create a file called foo.txt from a string and output it as zip_package.zip
 * $zip_pack
 *      ->clone_dir('directory_name')
 *      ->get_zip('zip_package');
 *
 * @author    Ash Blue <ash@blueashes.com>
 * @copyright 2012 Ash Blue / Blue Ashes (http://blueashes.com)
 * @package   PackageZipper
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      https://github.com/ashblue/package-zipper
 * @version   1
 * @todo      Setup demo files
 */

/**
 * Base extendable class for creating your PHP zip file packages with Package
 * Zipper.
 *
 * To have a page return a zip file to download simply do the following:
 *
 * // Include
 *
 *
 * You can also return the zip file and work with it further by not providing a
 * name for the zip file:
 *
 * $zip_pack = new Zip_Pack;
 *
 * $zip_pack
 *      ->clone_dir('directory_name')
 *      ->set_file('directory_name/file.txt', 'foo bar')
 *      ->get_zip('zip_package');
 *
 * Need more functionality for your project? You can easily extend Package Zipper
 * by doing the following:
 *
 * code example
 *
 * @package PackageZipper
 * @method self set_file(string $name, string $content)
 * @method self set_folder(string $name)
 * @method self delete_name(string $name)
 * @method self clone_dir(string $name, bool $include_parent_folder)
 * @method self create_zip()
 * @method string|object get_zip([string] $name)

 */
class Zip_Pack {
    /**
     * @api
     * @type string Error message displayed if the zip extension isn't enabled.
     */
    private static $error_message = 'Package Zipper requires the PHP Zip extension to be enabled. For information on enabling it please see the official PHP Zip extenstion installation docs at http://www.php.net/manual/en/zip.setup.php';

    /**
     * @api
     * @type string Cached data for the exact temporary file location.
     * Relative to a user's system.
     */
    public $temp_loc = null; // Location of system's temporary directory

    /**
     * @api
     * @type object Storage location for the ZipArchive interface.
     * @link http://www.php.net/manual/en/class.ziparchive.php Documentation for
     * using the ZipArchive interface.
     */
    public $zip = null; // Zip interface object http://www.php.net/manual/en/class.ziparchive.php

    /**
     * @api
     * @type string Storage location of the currently compiled temporary zip file.
     */
    public $zip_package = null;

    /**
     * Immediately executed function that sets up the zip archive for inital usage.
     * @api
     */
    public function __construct() {
        $this->zip_support();

        $this->zip = new ZipArchive();

        $this->create_zip();
    }

    /**
     * Verifies that the current hosting environment supports the PHP Zip extension.
     * In the event of failure, an error message from $this->error_message is
     * displayed and the script is immediately crashed to prevent further execution.
     * @api
     * @return self
     */
    private function zip_support() {
        if (!extension_loaded('zip'))
            throw new Exception($this->error_message);

        return $this;
    }

    /**
     * Cleans and returns a string if $active is set to true.
     * @api
     * @type string String that needs to be cleaned.
     * @type string Text to remove from the $string.
     * @type [bool] Only fires if active is set to true.
     * @return string Will return the new string upon successa and the original
     * string upon failure.
     */
    private static function clean_string($string, $removed_string, $active = true) {
        return $active ? $string : str_replace($removed_string . '\\', '', $string);
    }

    /*
     * Retrieves the current system's temporary directory. If the value has been
     * retrieved before, it will return a cached value instead from $this->temp_loc
     * @api
     * @return string Returns the location of the temporary directory.
     */
    private function get_temp_dir() {
        return ($this->temp_loc !== null) ? $this->temp_loc : $this->temp_loc = sys_get_temp_dir();
    }

    /*
     * Creates a new temporary zip file and stores the location in $this->zip_package
     * for later retieval. Also opens the ZipArchive interface for the just opened
     * zip file.
     *
     * While create_zip is automatically run for you at initialization, you can
     * also you it to overwrite the existing zip and start another.
     *
     * $zip_pack = new Zip_Pack;
     *
     * // Creates a zip file with foo.txt, then overwrites it and ouptuts a new
     * // file called zip_output.zip
     * $zip_pack
     *      ->set_file('foo.txt', 'bar')
     *      ->create_zip()
     *      ->set_file('new.txt', 'blah')
     *      ->get_zip('zip_output');
     *
     * @return self
     */
    public function create_zip() {
        // Create a zip file with a temporary name
        $this->zip_package = tempnam($this->get_temp_dir(), 'zip_package');

        // Create an empty zip file to store the data and open the interface
        $this->zip->open($this->zip_package, ZipArchive::CREATE);

        return $this;
    }

    /*
     * Gets the zip file and returns it as a string for the zip file's location
     * or outputs the zip file on the page by changing the page's header elements.
     *
     * To return the zip file's location:
     *
     * $zip_pack = new Zip_Pack;
     *
     * // Creates a zip file, then overwrites it and only output a file called new.txt.
     * $zip_pack
     *      ->set_file('foo.txt', 'bar')
     *      ->get_zip();
     *
     * Return the zip file as a download on the page:
     *
     * $zip_pack = new Zip_Pack;
     *
     * // Creates a zip file, then overwrites it and only output a file called new.txt.
     * $zip_pack
     *      ->set_file('foo.txt', 'bar')
     *      ->create_zip()
     *      ->set_file('new.txt', 'blah')
     *      ->get_zip('zip_output');
     *
     * @type [string] Name of the file to return
     * @return string|object Outputs a string if the $name is left empty, returns
     * a zip download if $name contains a string.
     */
    public function get_zip($name = null) {
        // Close the zip interface
        $this->zip->close();

        if ($name !== null):
            // Retrieve file size so the browser knows the length of the download
            $file_size = filesize($this->zip_package);

            // Output proper header data to force a download instead of going to a new page
            header('Content-Type: application/zip');
            header('Content-Length: ' . $file_size);
            header('Content-Disposition: attachment; filename="' . $name . '.zip"');

            // Send the user a downloadable file
            readfile($this->zip_package);
        else:
            return $this->zip_package;
        endif;
    }

    /**
     * Creates a new file inside the current zip archive. If you supply an existing
     * file name and location, the existing file will be overwritten.
     */
    public function set_file($name, $content) {
        // Create temporary file
        $file = tempnam($this->get_temp_dir(), $name);

        // Generate a simple read and write utility in binary
        $file_writer = fopen($file, "w"); // Opens the file with a write utility
        fwrite($file_writer, $content);
        fclose($file_writer);

        // Save file data for inclusion
        $this->zip->addFile($file, $name);

        return $this;
    }

    // Set or override an existing folder
    public function set_folder($name) {
        $this->zip->addEmptyDir($name);

        return $this;
    }

    // Delete an existing file or folder
    // NOTE: All folder paths must end with a "/", for example to delete a folder called
    // foo, you must pass 'foo/' as $loc
    public function delete_name($name) {
        $this->zip->deleteName($name);

        return $this;
    }

    // Clones a directory into the zip file
    public function clone_dir($name, $include_parent_folder = true) {
        $catalog = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($name), RecursiveIteratorIterator::SELF_FIRST);

        // Look through all files retrieved
        foreach ($catalog as $item):
            // Clean and prep the recursive file data
            $output = $this->clean_string($item, $name, $include_parent_folder);

            // Include directory data based upon file or directory discovery
            if (is_dir($item) === true):
                $this->zip->addEmptyDir($output);
            elseif (is_file($item) === true):
                $this->zip->addFromString($output, file_get_contents($item));
            endif;
        endforeach;

        return $this;
    }
};
?>
<?php
/**
 * PHP Package Zipper
 *
 * PHP Version 5
 *
 * @author    Ash Blue <ash@blueashes.com>
 * @copyright 2012 Ash Blue / Blue Ashes (http://blueashes.com)
 * @package   PackageZipper
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      https://github.com/ashblue/package-zipper
 * @version   1
 */

/* TODO:
  - Simple documentation with PHP docs http://www.phpdoc.org/
  - Setup demo files
*/


/**
 * Extendable class for creating your own custom zip files on the fly with PHP.
 *
 * To have a page return a zip file to download instead of loading up the content
 * simply do the following:
 *
 * $zip_pack = new Zip_Pack;
 * $zip_pack
 *      ->set_file('blah/foo.txt', 'foo bar')
 *      ->set_file('blah/bar.txt', 'foo bar')
 *
 * @package    PackageZipper
 * @subpackage Documentation
 *
 * You can also return the zip file and work with it further by not providing a
 * name for the zip file:
 *
 * code example
 *
 * Need more functionality for your project? You can easily extend Package Zipper
 * by doing the following:
 */
class Zip_Pack {
    /**
     *
     *
     */
    private static $error_message = 'Package Zipper requires the PHP Zip extension to be enabled. For information on enabling it please see the official PHP Zip extenstion installation docs at http://www.php.net/manual/en/zip.setup.php';
    public $temp_loc = null; // Location of system's temporary directory
    public $zip = null; // Zip interface object http://www.php.net/manual/en/class.ziparchive.php
    public $zip_package = null; // The complete zip package

    public function __construct() {
        $this->zip_support();

        // Zip interface object http://www.php.net/manual/en/class.ziparchive.php
        $this->zip = new ZipArchive();

        $this->create_zip();
    }

    // Verify current hosting environment supports the PHP Zip extension.
    private function zip_support() {
        if (!extension_loaded('zip'))
            throw new Exception($this->error_message);

        return $this;
    }

    // Cleans and returns a string if active
    private static function clean_string($string, $removed_string, $active = true) {
        return $active ? $string : str_replace($removed_string . '\\', '', $string);
    }

    // Retrieve location of system's temporary directory
    // Cache and return result when possible
    private function get_temp_dir() {
        return ($this->temp_loc !== null) ? $this->temp_loc : $this->temp_loc = sys_get_temp_dir();
    }

    // Creates a new zip file for output, good for overriding old zip data
    public function create_zip() {
        // Create a zip file with a temporary name
        $this->zip_package = tempnam($this->get_temp_dir(), 'zip_package');

        // Create an empty zip file to store the data and open the interface
        $this->zip->open($this->zip_package, ZipArchive::CREATE);

        return $this;
    }

    // Returns a file instead of a page from the current PHP file
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
    public function set_folder($loc) {
        $this->zip->addEmptyDir($loc);

        return $this;
    }

    // Delete an existing file or folder
    // NOTE: All folder paths must end with a "/", for example to delete a folder called
    // foo, you must pass 'foo/' as $loc
    public function delete_name($loc) {
        $this->zip->deleteName($loc);

        return $this;
    }

    // Clones a directory into the zip file
    public function clone_dir($loc, $include_parent_folder = true) {
        $catalog = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($loc), RecursiveIteratorIterator::SELF_FIRST);

        // Look through all files retrieved
        foreach ($catalog as $item):
            // Clean and prep the recursive file data
            $output = $this->clean_string($item, $loc, $include_parent_folder);

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
PHP Package Zipper
==============

Package Zipper is an extendable class for creating your own custom
zip files on the fly with PHP. It uses simple methods that
give you a large amount of flexibility. If you don't like the provided
functionality, you can easily extend the class.

Requires:

* PHP Version 5.3
* PHP Zip Extension, usually enabled by default (http://www.php.net/manual/en/zip.setup.php)

Changelog:
* 1.0 Initial release

## Usage Instructions

Creating zip files is fast and simple with Package Zipper. Just download and include
package_zipper.php then start making zip files.

1. Include the package_zipper.php file in your page: include('package_zipper.php');
2. Create a new instance of the Package Zipper class: $zip_pack = new Zip_Pack;
3. Make your zip file and output it: $zip_pack->set_file('foo.txt', 'foo bar')->get_zip('zip_package');

### Example

    // Include package zipper from a relative URL
    include('package_zipper.php');

    // Create a new zip pack
    $zip_pack = new Zip_Pack;

    // Clone a directory and output it as zip_package.zip
    $zip_pack
        ->clone_name('directory_name')
        ->get_zip('zip_package');

## Supported Methods

Below are the following methods currently supported by PHP Package Zipper. For
documentation on using these methods, please see the package_zipper.php file.

* set_file(string $name, string $content)
* set_folder(string $name)
* delete_name(string $name)
* clone_data(string $name, string $destination)
* create_zip()
* get_zip([string] $name)

## Adding Functionality

Want to add more functionality to Package Zipper? Just extend the existing class
as so.

    include('package_zipper.php');

    // Createa a safe to edit copy of the existing Package Zipper
    class My_Zip_Pack extends Zip_Pack {
        // Adds a new method
        function customMeth {
            // Execution logic here
        }
    }

    // Create and run your extended version of the original Package Zipper
    $my_zip_pack = new My_Zip_Pack;
    $my_zip_pack->customMeth();
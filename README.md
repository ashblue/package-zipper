PHP Package Zipper
==============

Package Zipper is an extendable class for creating your own custom
zip files on the fly with PHP and the ZipArchive extension. It uses simple methods
that give you a large amount of flexibility. If you don't like the provided
functionality, you can easily exted the class.

Requires:

* PHP Version 5.3
* PHP Zip Extension, usually enabled by default (http://www.php.net/manual/en/zip.setup.php)

## Usage Instructions

Creating zip files is fast and simple with Package Zipper. Just download and include
your zip class file, construct it, and start making zip files.

1. Include the package_zipper.php file in your page: include('package_zipper.php');
2. Create a new instance of the Package Zipper class: $zip_pack = new Zip_Pack;
3. Make your zip file and output it: $zip_pack->set_file('foo.txt', 'foo bar')->get_zip('zip_package');

### Example

    // Include package zipper from a relative URL
    include('package_zipper.php');

    // Create a new zip pack
    $zip_pack = new Zip_Pack;

    // Create a file called foo.txt from a string and output it as zip_package.zip
    $zip_pack
        ->clone_dir('directory_name')
        ->get_zip('zip_package');

### Supported Methods

Below are the following methods currently supported by PHP Package Zipper. For
documentation on using these methods, please see the package_zipper.php file.

* set_file(string $name, string $content)
* set_folder(string $name)
* delete_name(string $name)
* clone_dir(string $name, boolean $include_parent_folder)
* create_zip()
* get_zip([string] $name)

### Adding Functionality

CODE EXAMPLE HERE
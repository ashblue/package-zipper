<?php
/*
 * Tests returning a zip file, should fire a blank page.
 */

@include('../package_zipper.php');

$zip_pack = new Zip_Pack;

$temp_file = $zip_pack
    ->set_folder('empty_dir/dir')
    ->set_file('clone_test/filled_dir/file.txt', 'foo')
    ->get_zip();

echo 'Result of success = ' . $temp_file;
?>
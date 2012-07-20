<?php
/*
 * Add new file data.
 */

@include('../package_zipper.php');

$zip_pack = new Zip_Pack;

$zip_pack
    ->set_folder('dir/empty_dir')
    ->set_file('blah.txt', 'foosball')
    ->set_file('clone_test/filled_dir/file.txt', 'foo')
    ->delete_name('clone_test/filled_dir/file.txt') // Removes the previous entry completely
    ->get_zip('clone_demo');
?>
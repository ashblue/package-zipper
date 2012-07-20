<?php
/*
 * Tests replacing a zip file.
 */

@include('../package_zipper.php');

$zip_pack = new Zip_Pack;

$zip_pack
    ->set_folder('empty_dir/dir')
    ->set_file('clone_test/filled_dir/file.txt', 'foo')
    ->create_zip()
    ->set_file('blah.txt', 'bar')
    ->create_zip()
    ->set_file('asdf/qwerty.txt', 'foo bar')
    ->get_zip('clone_demo');
?>
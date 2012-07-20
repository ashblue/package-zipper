<?php
/*
 * Tests directory cloning capabilities.
 */

@include('../package_zipper.php');

$zip_pack = new Zip_Pack;

$zip_pack
    ->clone_name('clone_test')
    ->clone_name('clone_test', 'new_clone_test')
    ->set_folder('empty_dir')
    ->set_file('clone_test/filled_dir/file.txt', 'foo bar')
    ->clone_name('clone_test/demo.txt', 'clone_test/filled_dir/clone_test.txt')
    ->delete_name('clone_test/demo2.txt')
    ->delete_name( // Must delete all contents in a dir before removing
        array(
            'clone_test/2nd_tier_clone2/demo.txt',
            'clone_test/2nd_tier_clone2/demo2.txt',
            'clone_test/2nd_tier_clone2/'
        )
    )
    ->get_zip('clone_demo');
?>
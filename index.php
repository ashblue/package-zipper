<?php
include('package_zipper.php');

$zip_pack = new Zip_Pack;
$zip_pack->clone_data('demo', 'asdf')
//$zip_pack->set_folder('blah');
//$zip_pack->set_folder('test');
//$zip_pack->delete_name('test/');
//$zip_pack->set_file('blah/foo.txt', 'foo bar');
//$zip_pack->set_file('foo.txt', 'bar foo');
->get_zip('blah');
?>

<!--
    - Introduction
    - Demo List
    - Documentation (ReadMe and website link)
-->
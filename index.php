<?php
include('package_zipper.php');

$zip_pack = new Zip_Pack;
$zip_pack->clone_dir('demo');
//$zip_pack->set_folder('blah');
//$zip_pack->set_folder('test');
//$zip_pack->delete_name('test/');
$zip_pack->set_file('blah/foo.txt', 'foo bar');
//$zip_pack->set_file('foo.txt', 'bar foo');
$zip_pack->get_zip();
?>
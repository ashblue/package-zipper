<?php
include('package_zipper.php');

$zip_pack = new Zip_Pack;
$zip_pack->set_file('foo.txt', 'foo bar');
$zip_pack->set_file('foo.txt', 'bar foo');
//$zip_pack->output_zip('blah');
?>
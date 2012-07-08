<?php
include('package_zipper.php');

$zip_pack = new Zip_Pack;
$zip_pack->create_file('foo.txt', 'foo bar');
$zip_pack->create_zip();
?>
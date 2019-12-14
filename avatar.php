<?php

if (!isset($_GET['f']) || !preg_match('=^\w+\.(gif|png|jpg|jpeg)$=i', $_GET['f']) || !file_exists('upload/avatar/'.$_GET['f']))
	exit;

$image = new Imagick('upload/avatar/'.$_GET['f']);
$image->setImageFormat("png");

$image->coalesceImages(); 
foreach ($image as $frame) {
  $frame->thumbnailImage(50, 50, 1);
} 
$image = $image->deconstructImages();

$image->writeImage('upload/avatar/thumb/'.$_GET['f']);

header('Content-type: image/png');
echo $image;

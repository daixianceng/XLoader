<?php
require_once '../php/XLoaderResources.php';

$images = array(
	// 'image file name' => 'the description'
	//'2014121844389.jpg' => 'The first image.',
	//'2014121857472.png' => 'The second image.'
);

$xloaderResources = new XLoaderResources();
$xloaderResources->setPath(dirname(__FILE__) . '/images')
				 ->setUrl('/demo/images')
				 ->addImages($images)
				 ->send();
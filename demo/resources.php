<?php
require_once '../php/XLoaderResources.php';

$images = array(
	// 'image file name' => 'the description',
	// or
	// 'The image file name'
);

$xloaderResources = new XLoaderResources();
$xloaderResources->setPath(dirname(__FILE__) . '/images')
				 ->setUrl('/demo/images')
				 ->addImages($images)
				 ->run();
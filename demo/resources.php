<?php
require_once '../php/XLoaderResources.php';

$images = array(
	// 'image file name' => 'the description'
);

$xloaderResources = new XLoaderResources();
$xloaderResources->setPath(dirname(__FILE__) . '/images')
				 ->setUrl('/demo/images')
				 ->addImages($images)
				 ->run();
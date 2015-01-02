<?php
require_once '../php/XLoader.php';
$xloader = new XLoader();
$xloader->setInputName('images')
		->setPath(dirname(__FILE__) . '/temp')
		->setUrl('/demo/temp')
		->run();
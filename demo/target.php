<?php
require_once '../php/XLoader.php';
$xloader = new XLoader();
$xloader->setInputName('images')
		->setSavePath(dirname(__FILE__) . '/temp')
		->setSaveUrl('/demo/temp')
		->run();
<?php
$imageNames = isset($_POST['imageNames']) ? $_POST['imageNames'] : array();
$descriptions = isset($_POST['descriptions']) ? $_POST['descriptions'] : array();

$tempPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'temp';
$imagesPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'images';
if (!empty($imageNames)) {
	foreach ($imageNames as $key => $imageName) {
		$imageNameFull = $tempPath . DIRECTORY_SEPARATOR . $imageName;
		if (is_file($imageNameFull)) {
			if (rename($imageNameFull, $imagesPath . DIRECTORY_SEPARATOR . $imageName)) {
				$description = isset($descriptions[$key]) ? $descriptions[$key] : '';
				
				// Here you can insert database.
				
				echo "{$imageName} uploaded.<br>";
			}
		}
	}
	
	echo 'Success!';
}
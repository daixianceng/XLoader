<?php
require_once 'XLoader.php';

class XLoaderResources
{
	protected $_path;
	
	protected $_url;
	
	protected $_images = array();
	
	public function __construct()
	{}
	
	public function getPath()
	{
		return $this->_path;
	}
	
	public function setPath($path)
	{
		$this->_path = $path;
		
		return $this;
	}
	
	public function getUrl()
	{
		return $this->_url;
	}
	
	public function setUrl($url)
	{
		$this->_url = $url;
		
		return $this->_url;
	}
	
	public function addImages($images)
	{
		static $imageNames = array();
		
		foreach ($images as $name => $description) {
			if (in_array($name, $imageNames)) continue;
			
			$fileName = $this->_path . DIRECTORY_SEPARATOR . $name;
			if (!is_file($fileName)) continue;
			
			$size = filesize($fileName);
			
			$row = array();
			$row['name'] = $name;
			$row['uri'] = $this->_url . '/' . $name;
			$row['size'] = XLoader::formatSize($size);
			$row['description'] = $description;
			$row['error'] = UPLOAD_ERR_OK;
			
			$this->_images[] = $row;
			$imageNames[] = $name;
		}
		
		return $this;
	}
	
	public function send()
	{
		echo json_encode($this->_images);
	}
}
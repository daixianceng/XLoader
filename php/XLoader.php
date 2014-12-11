<?php
class XLoader
{
	protected $_savePath;
	protected $_saveUrl;
	protected $_inputName;
	protected $_types = array('jpg', 'png', 'gif', 'bmp');
	protected $_images = array();
	
	public function __construct()
	{}
	
	public function receive()
	{
		
		$files = $_FILES[$this->_inputName];
		
		foreach ($files['name'] as $key => $name) {
			$nameArr = explode('.', $name);
			$type = strtolower($nameArr[count($nameArr) - 1]);
			if (!in_array($type, $this->_types)) {
				continue;
			}
			
			$image['uri'] = '';
			$image['error'] = $files['error'][$key];
			$image['size'] = $files['size'][$key];
			if ($files['error'][$key] === UPLOAD_ERR_OK) {
				$fileName = self::randomImageName('', $type);
				move_uploaded_file($files['tmp_name'][$key], $this->_savePath . DIRECTORY_SEPARATOR . $fileName);
				$image['uri'] = $this->_saveUrl . "/{$fileName}";
			}
			
			$this->_images[] = $image;
		}
		
		return $this;
	}
	
	public function output()
	{
		$json = json_encode($this->_images);
		$output = '<script type="text/javascript">parent.jQuery.XLoaderData(\'' . $json . '\')</script>';
		
		echo $output;
	}
	
	public function setSavePath($savePath)
	{
		$this->_savePath = $savePath;
		
		return $this;
	}
	
	public function getSavePath()
	{
		return $this->_savePath;
	}
	
	public function setSaveUrl($saveUrl)
	{
		$this->_saveUrl = $saveUrl;
		
		return $this;
	}
	
	public function getSaveUrl()
	{
		return $this->_saveUrl;
	}
	
	public function setInputName($inputName)
	{
		$this->_inputName = $inputName;
		
		return $this;
	}
	
	public function getInputName()
	{
		return $this->_inputName;
	}
	
	public function setTypes($types)
	{
		$typesIntersect = array_intersect($this->_types, $types);
		if (!empty($typesIntersect)) {
			$this->_types = $typesIntersect;
		}
		
		return $this;
	}
	
	public function getTypes()
	{
		return $this->_types;
	}
	
	public function hasType($type)
	{
		return in_array($type, $this->_types);
	}
	
	public static function randomImageName($prefix = '', $type = null)
	{
		$name = $prefix . date('Ymd') . mt_rand(10000, 99999);
		if ($type) {
			$name .= '.' . $type;
		}
		
		return $name;
	}
}
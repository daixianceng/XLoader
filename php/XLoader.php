<?php
class XLoader
{
	/**
	 * 图片保存路径
	 * 
	 * @var string
	 */
	protected $_savePath;
	
	/**
	 * 图片路径URL
	 * 
	 * @var string
	 */
	protected $_saveUrl;
	
	/**
	 * 图片选择按钮的名称
	 * 
	 * @var string
	 */
	protected $_inputName;
	
	/**
	 * 允许上传的图片类型
	 * 
	 * @var array
	 */
	protected $_types = array('jpg', 'png', 'gif', 'bmp');
	
	/**
	 * 接收的图片数据
	 * 
	 * @var array
	 */
	protected $_images = array();
	
	public function __construct()
	{}
	
	/**
	 * 接收图片
	 * 
	 * @return XLoader
	 */
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
			$image['name'] = '';
			$image['error'] = $files['error'][$key];
			$image['size'] = self::formatSize($files['size'][$key]);
			if ($files['error'][$key] === UPLOAD_ERR_OK) {
				$fileName = self::randomImageName('', $type);
				move_uploaded_file($files['tmp_name'][$key], $this->_savePath . DIRECTORY_SEPARATOR . $fileName);
				$image['uri'] = $this->_saveUrl . "/{$fileName}";
				$image['name'] = $fileName;
			}
			
			$this->_images[] = $image;
		}
		
		return $this;
	}
	
	/**
	 * 推送数据到页面，调用页面程序
	 */
	public function output()
	{
		if (empty($this->_images)) return;
		
		$json = json_encode($this->_images);
		$output = '<script type="text/javascript">parent.jQuery.XLoaderData(\'' . $json . '\')</script>';
		
		echo $output;
	}
	
	/**
	 * 设置图片保存路径
	 * 
	 * 请不要在路径末尾加上路径分隔符"/"或"\"
	 * 
	 * @param string $savePath
	 * @return XLoader
	 */
	public function setSavePath($savePath)
	{
		$this->_savePath = $savePath;
		
		return $this;
	}
	
	/**
	 * 获取图片保存路径
	 * 
	 * @return string
	 */
	public function getSavePath()
	{
		return $this->_savePath;
	}
	
	/**
	 * 设置图片保存路径URL
	 * 
	 * 请不要在路径末尾加上路径分隔符"/"
	 * 
	 * @param string $saveUrl
	 * @return XLoader
	 */
	public function setSaveUrl($saveUrl)
	{
		$this->_saveUrl = $saveUrl;
		
		return $this;
	}
	
	/**
	 * 获取图片保存路径URL
	 * 
	 * @return string
	 */
	public function getSaveUrl()
	{
		return $this->_saveUrl;
	}
	
	/**
	 * 设置图片选择按钮名称
	 * 
	 * @param string $inputName
	 * @return XLoader
	 */
	public function setInputName($inputName)
	{
		$this->_inputName = $inputName;
		
		return $this;
	}
	
	/**
	 * 获取图片选择按钮名称
	 * 
	 * @return string
	 */
	public function getInputName()
	{
		return $this->_inputName;
	}
	
	/**
	 * 设置接受的图片类型
	 * 
	 * 您只能在默认的几个类型中进行选择
	 * 
	 * @param array $types
	 * @return XLoader
	 */
	public function setTypes($types)
	{
		$typesIntersect = array_intersect($this->_types, $types);
		if (!empty($typesIntersect)) {
			$this->_types = $typesIntersect;
		}
		
		return $this;
	}
	
	/**
	 * 获取允许的图片类型
	 * 
	 * @return array:
	 */
	public function getTypes()
	{
		return $this->_types;
	}
	
	/**
	 * 判断某个类型是否已设置
	 * 
	 * @param string $type
	 * @return boolean
	 */
	public function hasType($type)
	{
		return in_array($type, $this->_types);
	}
	
	/**
	 * 产生一个唯一的图片名字
	 * 
	 * @param string $prefix
	 * @param string $type
	 * @return string
	 */
	public static function randomImageName($prefix = '', $type = null)
	{
		$name = $prefix . date('Ymd') . mt_rand(10000, 99999);
		if ($type) {
			$name .= '.' . $type;
		}
		
		return $name;
	}
	
	/**
	 * 产生一个格式化后的图片大小
	 * 
	 * @param int $size
	 * @return string
	 */
	public static function formatSize($size)
	{
		$units = explode(' ', 'B KB MB GB TB PB');
		$mod = 1024;
	
		for ($i = 0; $size > $mod; $i++) {
			$size /= $mod;
		}
	
		$endIndex = strpos($size, ".") + 3;
	
		return substr($size, 0, $endIndex) . ' ' . $units[$i];
	}
}
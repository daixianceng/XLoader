<?php
require_once 'XLoaderBase.php';

class XLoader extends XLoaderBase
{
	/**
	 * 动作类型
	 * 
	 * @var string
	 */
	protected $_action = 'receive';
	
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
	 * 执行动作
	 */
	public function run()
	{
		parent::run();
		
		switch ($this->_action) {
			case 'receive' :
				$this->_receive();
				break;
			default :
				break;
		}
	}
	
	/**
	 * 接收图片并输出
	 * 
	 * @return boolean
	 */
	protected function _receive()
	{
		$files = $_FILES[$this->_inputName];
		$images = array();
		
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
				move_uploaded_file($files['tmp_name'][$key], $this->_path . DIRECTORY_SEPARATOR . $fileName);
				$image['uri'] = $this->_url . "/{$fileName}";
				$image['name'] = $fileName;
			}
			
			$images[] = $image;
		}
		
		if (empty($images)) return false;
		
		$json = json_encode($images);
		echo '<script type="text/javascript">parent.jQuery.XLoaderData(\'' . $json . '\', \'target\')</script>';
		
		return true;
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
}
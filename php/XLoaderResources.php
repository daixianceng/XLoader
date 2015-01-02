<?php
require_once 'XLoaderBase.php';

class XLoaderResources extends XLoaderBase
{
	/**
	 * 动作类型
	 * 
	 * @var string
	 */
	protected $_action = 'load';
	
	/**
	 * 将要发送给客户端的图片数据
	 * 
	 * @var array
	 */
	protected $_images = array();
	
	/**
	 * 增加图片数据
	 * 
	 * 参数格式：array(
	 *           'The image file name' => 'The image description',
	 *           // or
	 *           'The image file name'
	 *       )
	 * 
	 * @param array $images
	 * @return XLoaderResources
	 */
	public function addImages($images)
	{
		static $imageNames = array();
		
		foreach ($images as $key => $value) {
			if (is_numeric($key)) {
				$name = $value;
				$description = '';
			} else {
				$name = $key;
				$description = $value;
			}
			
			if (in_array($name, $imageNames)) continue;
			
			$fileName = $this->_path . DIRECTORY_SEPARATOR . $name;
			if (!is_file($fileName)) continue;
			
			$size = filesize($fileName);
			
			$row = array();
			$row['name'] = $name;
			$row['uri'] = $this->_url . '/' . $name;
			$row['size'] = self::formatSize($size);
			$row['description'] = $description;
			$row['error'] = UPLOAD_ERR_OK;
			
			$this->_images[] = $row;
			$imageNames[] = $name;
		}
		
		return $this;
	}
	
	/**
	 * 执行动作
	 */
	public function run()
	{
		parent::run();
		
		switch ($this->_action) {
			case 'load' :
				$this->_sendImages();
				break;
			default :
				break;
		}
	}
	
	/**
	 * 发送图片数据到客户端
	 */
	private function _sendImages()
	{
		echo json_encode($this->_images);
	}
}
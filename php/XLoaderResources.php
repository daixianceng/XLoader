<?php
require_once 'XLoader.php';

class XLoaderResources
{
	/**
	 * 动作类型
	 * 
	 * @var string
	 */
	protected $_action = 'load';
	
	/**
	 * 图片路径
	 * 
	 * @var string
	 */
	protected $_path;
	
	/**
	 * 图片路径URL
	 * 
	 * @var string
	 */
	protected $_url;
	
	/**
	 * 将要发送给客户端的图片数据
	 * 
	 * @var array
	 */
	protected $_images = array();
	
	public function __construct()
	{
		if (isset($_POST['action'])) {
			$this->_action = $_POST['action'];
		}
	}
	
	/**
	 * 获取图片路径
	 * 
	 * @return string
	 */
	public function getPath()
	{
		return $this->_path;
	}
	
	/**
	 * 设置图片路径
	 * 
	 * 请不要在路径末尾加上路径分隔符"/"或"\"
	 * 
	 * @param string $path
	 * @return XLoaderResources
	 */
	public function setPath($path)
	{
		$this->_path = $path;
		
		return $this;
	}
	
	/**
	 * 获取图片路径URL
	 * 
	 * @return string
	 */
	public function getUrl()
	{
		return $this->_url;
	}
	
	/**
	 * 设置图片路径URL
	 * 
	 * 请不要在路径末尾加上路径分隔符"/"
	 * 
	 * @param string $url
	 * @return XLoaderResources
	 */
	public function setUrl($url)
	{
		$this->_url = $url;
		
		return $this;
	}
	
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
			$row['size'] = XLoader::formatSize($size);
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
		switch ($this->_action) {
			case 'load' :
				$this->_sendImages();
				break;
			case 'delete' :
				$this->_delete();
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
	
	/**
	 * 删除图片
	 * 
	 * @return boolean
	 */
	private function _delete()
	{
		$filename = isset($_POST['filename']) ? $_POST['filename'] : null;
	
		if (empty($filename)) {
			echo json_encode(array('error' => 'yes'));
			return false;
		}
	
		$filenameFull = $this->_path . DIRECTORY_SEPARATOR . $filename;
		if (is_file($filenameFull)) {
			@unlink($filenameFull);
		}
	
		echo json_encode(array('error' => 'no'));
		return true;
	}
}
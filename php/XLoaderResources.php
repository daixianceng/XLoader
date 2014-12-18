<?php
require_once 'XLoader.php';

class XLoaderResources
{
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
	{}
	
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
	 *           ...
	 *       )
	 * 
	 * @param array $images
	 * @return XLoaderResources
	 */
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
	
	/**
	 * 发送图片数据到客户端
	 */
	public function send()
	{
		echo json_encode($this->_images);
	}
}
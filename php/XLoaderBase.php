<?php
abstract class XLoaderBase
{
	/**
	 * 动作类型
	 *
	 * @var string
	 */
	protected $_action;
	
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
	
	public function __construct()
	{
		if (isset($_POST['action'])) {
			$this->_action = $_POST['action'];
		}
	}
	
	/**
	 * 执行动作
	 */
	public function run()
	{
		switch ($this->_action) {
			case 'delete' :
				$this->_delete();
				break;
			default :
				break;
		}
	}
	
	/**
	 * 删除图片
	 *
	 * @return boolean
	 */
	protected function _delete()
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
	 * @return XLoaderBase
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
	 * @return XLoaderBase
	 */
	public function setUrl($url)
	{
		$this->_url = $url;
	
		return $this;
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
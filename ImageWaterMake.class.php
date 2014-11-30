<?php
/**
 * 图片水印类
 * @author skyling
 * 2014/7/28
 */
class ImageWaterMake {
	private $groundImage; //背景图
	private $waterImg;	  //水印图
	private $waterPos;	  //水印位置
	private $waterText;	  //水印文字
	private $textSize;	  //文字大小
	private $textColor;	  //文字颜色
	private $textPos;	  //文字水印位置 
	private $waterSize;		//图片水印缩放比例
	
	
	private $waterPerPos;
	private $textPerPos;
	private $bgIm;
	private $waterIm;
	private $saveFile;
	
	private $isWaterImage=TRUE;	//是否可以打水印
	private $bgInfo;//[0] width [1]height [2] .jpg
	private $waterInfo;//[0] width [1]height [2] .jpg

	public function run(){
		
		$this->readBgImg();		//读取背景文件		
		$this->readWaterImg();	//读取水印文件		
		$this->getTextPos();
		$this->getWaterPos();
		$this->chImgSize();	//改变水印大小
		
		$this->imageWaterMarker();
		$this->saveImg();
	}
	
	
	/**
	 * 改变水印大小
	 */
	private function chImgSize(){
		if ($this->saveFile == $this->waterImg || $this->waterSize==1) {
			return ;
		}
		$this->saveFile = dirname($this->waterImg).'\ch'.$this->waterSize.basename($this->waterImg);
		if(!file_exists($this->saveFile)){
			$im  =  imagecreatetruecolor( $this->waterInfo[0]*$this->waterSize, $this->waterInfo[1]*$this->waterSize);			
			imagealphablending($im, true);
			imagesavealpha($im, true);
			$white = imagecolorallocatealpha($im,255,255,255,127);
			imagefill($im,0,0,$white);
			
			imagecopyresized($im, $this->waterIm, 0, 0, 0, 0, $this->waterInfo[0]*$this->waterSize, $this->waterInfo[0]*$this->waterSize, $this->waterInfo[0], $this->waterInfo[1]);
			switch($this->bgInfo[2])//取得背景图片的格式
			{
				case 1:imagegif($im,$this->saveFile);break;
				case 2:imagejpeg($im,$this->saveFile);break;
				case 3:imagepng($im,$this->saveFile);break;
				default:die($this->waterIm."改变大小发生错误！");
			}
		}
		$this->setWaterImg($this->saveFile);
		$this->readWaterImg();
	}
	/**
	 * 读取背景
	 */
	private function readBgImg(){
		if (!empty($this->groundImage) && file_exists($this->groundImage)) {
			$this->bgInfo = getimagesize($this->groundImage);
			
			switch($this->bgInfo[2])//取得水印图片的格式
			{
				case 1:$this->bgIm = imagecreatefromgif($this->groundImage);break;
				case 2:$this->bgIm = imagecreatefromjpeg($this->groundImage);break;
				case 3:$this->bgIm = imagecreatefrompng($this->groundImage);break;
				default:die("背景图片暂时只支持gif,jpg,png!");$this->isWaterImage = FALSE;break;
			}
			return;
		}
		$this->isWaterImage = FALSE;
	}
	
	/**
	 * 读取水印
	 */
	private function readWaterImg(){
		if (!empty($this->waterImg) && file_exists($this->waterImg)) {
			$this->waterInfo = getimagesize($this->waterImg);
			switch($this->waterInfo[2])//取得水印图片的格式
			{
				case 1:$this->waterIm = imagecreatefromgif($this->waterImg);break;
				case 2:$this->waterIm = imagecreatefromjpeg($this->waterImg);break;
				case 3:$this->waterIm = imagecreatefrompng($this->waterImg);break;
				default:die("水印图片暂时只支持gif,jpg,png!");$this->isWaterImage = FALSE;echo "水印图片路径不正确!";
			}
			return;
		}
		
		$this->isWaterImage = FALSE;
		echo "水印图片路径不正确!";
	}
	
	public function saveImg(){
		//生成水印后的图片
		$saveFile = $this->groundImage;
		switch($this->bgInfo[2])//取得背景图片的格式
		{
			case 1:imagegif($this->bgIm,$saveFile);break;
			case 2:imagejpeg($this->bgIm,$saveFile);break;
			case 3:imagepng($this->bgIm,$saveFile);break;
			default:die($this->groundImage."生成图片错误！");break;
		}
		echo $this->groundImage. "   +   ".$this->waterImg."水印生成成功!<br>";
	}
	
	function __destruct(){

	}
	/**
	 * 创建水印
	 */
	public function imageWaterMarker(){
		imagealphablending($this->bgIm, true);
		
		if ($this->isWaterImage) {//图片水印
			imagecopy($this->bgIm, $this->waterIm, $this->waterPos['x'], $this->waterPos['y'], 0, 0, $this->waterInfo[0], $this->waterInfo[1]);
		}
		if ($this->waterText) {//文字水印
			if( !empty($this->textColor) && (strlen($this->textColor)==7) )
			{
				$R = hexdec(substr($this->textColor,1,2));
				$G = hexdec(substr($this->textColor,3,2));
				$B = hexdec(substr($this->textColor,5));
			}
			else
			{
				die("水印文字颜色格式不正确！");
			}
			imagestring ( $this->bgIm, $this->textSize, $this->textPos['x'], $this->textPos['y'], $this->waterText, imagecolorallocate($this->bgIm, $R, $G, $B));
		}
	}
	
	/**
	 * @param !CodeTemplates.settercomment.paramtagcontent!
	 */
	public function getWaterPos() {
		if ($this->waterPerPos) {
			$this->waterPos['x']=ceil($this->bgInfo[0]*$this->waterPerPos[0]);
			$this->waterPos['y']=ceil($this->bgInfo[1]*$this->waterPerPos[1]);
		}else {
			$this->waterPos['x']=ceil($this->bgInfo[0]*0.3);
			$this->waterPos['y']=ceil($this->bgInfo[1]*0.4);
		}
	}
	/**
	 * @param !CodeTemplates.settercomment.paramtagcontent!
	 */
	public function getTextPos() {
	
		if ($this->textPerPos) {
			$this->textPos['x']=ceil($this->bgInfo[0]*$this->textPerPos[0]);
			$this->textPos['y']=ceil($this->bgInfo[1]*$this->textPerPos[1]);
		}else {
			$this->textPos['x']=ceil($this->bgInfo[0]*0.1);
			$this->textPos['y']=ceil($this->bgInfo[1]*0.9);
		}
	}
	/**
	 * 设置水印大小
	 * @param number $per
	 */
	public function setWaterSize($waterSize=1){
		$this->waterSize=$waterSize;
	}
	
	/**
	 * @param !CodeTemplates.settercomment.paramtagcontent!
	 */
	public function setGroundImage($groundImage) {
		$this->groundImage = $groundImage;
	}

	/**
	 * @param !CodeTemplates.settercomment.paramtagcontent!
	 */
	public function setWaterImg($waterImg) {
		$this->waterImg = $waterImg;
	}

	/**
	 * @param !CodeTemplates.settercomment.paramtagcontent!
	 */
	public function setWaterText($waterText) {
		$this->waterText = $waterText;
	}

	/**
	 * @param !CodeTemplates.settercomment.paramtagcontent!
	 */
	public function setTextSize($textSize) {
		$this->textSize = $textSize;
	}

	/**
	 * @param !CodeTemplates.settercomment.paramtagcontent!
	 */
	public function setTextColor($textColor) {
		$this->textColor = $textColor;
	}
	
	/**
	 * @param !CodeTemplates.settercomment.paramtagcontent!
	 */
	public function setWaterPerPos($waterPerPos) {
		$this->waterPerPos = $waterPerPos;
	}

	/**
	 * @param !CodeTemplates.settercomment.paramtagcontent!
	 */
	public function setTextPerPos($textPerPos) {
		$this->textPerPos = $textPerPos;
	}


	
	
}

?>
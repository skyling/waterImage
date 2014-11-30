<?php
	require_once 'ImageWaterMake.class.php';
	require_once 'waterImg.conf.php';
	
	$iwm = new ImageWaterMake();	//创建水印对象
	
	if(!empty($waterImg)){
		foreach ($waterImg as $arrI){
			waterImg($iwm,$bgPath,$arrI,'');
		}
	}
	
	if (!empty($waterText)) {
		foreach ($waterText as $arrT){
			waterImg($iwm,$bgPath,'',$arrT);
		}
	}
	
	echo "程序结束!";
	//--------------------------------------------------------------------------------------------
	//打水印
	function waterImg($iwm,$bgPath,$waterImg,$waterText){
		if (is_dir($bgPath)) {
			if(($dir_handle = @opendir($bgPath))!=null){
				while(($filename = readdir($dir_handle))!=null){
					
					if ($filename != '.' && $filename != '..') {
						$subFile=$bgPath.'/'.$filename;//获取文件名字
						if (is_file($subFile)) {//为文件  打水印
							$info = pathinfo($subFile);
							if($info['extension'] =='jpg' || $info['extension'] =='png' || $info['extension'] =='gif'){
								
							
							$iwm->setGroundImage($subFile);//设置背景图
							///////////////////////////////////////设置水印属性
							if(!empty($waterImg)){
								$iwm->setWaterImg($waterImg[0]);//设置水印图片
								$iwm->setWaterPerPos($waterImg[1]);//设置水印位置
								$iwm->setWaterSize($waterImg[2]);//设置水印图片大小
							}
							if (!empty($waterText)) {
								$iwm->setWaterText($waterText[0]);//设置文字水印中的文字
								$iwm->setTextPerPos($waterText[1]);//设置文字水印位置
								$iwm->setTextSize($waterText[2]);//设置文字水印大小
								$iwm->setTextColor($waterText[3]);//设置文字颜色
							}
							///////////////////////////////////////设置水印属性
							$iwm->run();//打水印
							}else {
								continue;
							}
						}
						if (is_dir($subFile)) {//为目录
							waterImg($iwm,$subFile,$waterImg,$waterText);
						}
					}
				}
			}else{
				echo "背景文件夹打开错误!";
			}
	
		}else{
			echo "背景图片目录无法获取!";
		}
	}
?>
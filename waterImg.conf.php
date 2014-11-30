<?
#生成水印前请备份文件
#背景路径  绝对路径
$bgPath='C:/Users/skyling/Desktop/ypyt';
#水印图片
#可设置多张
/*格式如下
waterImg[n][0]  可为相对或绝对路径                                  string
waterImg[n][1]  位置  上左百分比位置      0-1 array(1,1)
waterImg[n][2]  缩放大小                                 0-1 若无需改变大小请默认为1  因为浏览器支持不同情慎用保持值为1
 * */
$waterImg = array(
array(
	"logo.png",
	array(0.02,0.02),
	1
),
array(
	"text.png",
	array(0.3,0.4),
	1
)
);
#文字水印
#可设置多个
/*参数格式:
$waterText[n][0]  文字
$waterText[n][1]  位置  上左百分比位置      0-1 array(1,1)
$waterText[n][2]  大小   1-5
$waterText[n][3]  颜色  #000000
 */
$waterText = array(
array(
		'hello',
		array(0.1,0.9),
		2,
		'#ffffff'
)
);
?>
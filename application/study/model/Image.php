<?php
/**
 * Created by PhpStorm.
 * User: hpc
 * Date: 2018/12/19
 * Time: 下午1:43
 */
namespace app\study\model;
class Image
{
    /**
     * 分享图片生成
     * @param $gData  商品数据，array
     * @param $codeImg 二维码图片
     * @param $fileName string 保存文件名,默认空则直接输入图片
     */
    function createSharePng($gData,$codeImg,$fileName = '')
    {
        //创建画布
        $im = imagecreatetruecolor(618, 1000);

        //填充画布背景色
        $color = imagecolorallocate($im, 255, 255, 255);
        imagefill($im, 0, 0, $color);

        //字体文件
        $font_file = "uploads/font/youyuan.TTF";
        $font_file_bold = "uploads/font/youyuan.TTF";

        //设定字体的颜色
        $font_color_1 = ImageColorAllocate($im, 140, 140, 140);
        $font_color_2 = ImageColorAllocate($im, 28, 28, 28);
        $font_color_3 = ImageColorAllocate($im, 129, 129, 129);
        $font_color_red = ImageColorAllocate($im, 217, 45, 32);
        $rgb = $gData['rgb'];
        $theme_color = ImageColorAllocate($im, $rgb[0], $rgb[1], $rgb[2]);

        //用户名字
        imagettftext($im, 19, 0, 30, 50, $font_color_2, $font_file, $gData['name']);
        //压力这只小怪兽在你的身体里是
        imagettftext($im, 16, 0, 30, 90, $font_color_2, $font_file, '压力这只小怪兽在你的身体里是：');
        //怪兽名字背景
        imagefilledrectangle($im, 35 , 170 , 300 , 240 , $theme_color);
        $point[0] = 55;
        $point[1] = 240;
        $point[2] = 65;
        $point[3] = 240;
        $point[4] = 60;
        $point[5] = 250;
        ImageFilledPolygon($im, $point, 3, $theme_color);
        //怪兽名字
        imagettftext($im, 30, 0, 90, 220, $font_color_2, $font_file, $gData['monster']);

        //竖直线
        imagefilledrectangle ($im, 534 , 30 , 536 , 510 , $theme_color);

        //压力指数：标题
        $text  = '压力指数';
        $x     = 550;
        $y     = 60;
        $font  = 30;
        $range = 45;
        $this->verticalRow($text,$im,$font_color_2,$font_file,$x,$y,$font,$range);

        //星星
        $stars   = $gData['stars'];
        $starNum = $gData['starNum'];
        $this->drawStars($im,$stars,$starNum);

        //压力描述
        $text   = $gData['title'];
        $x1     = 500;
        $y1     = 50;
        $font1  = 16;
        $range1 = 24;
        $this->verticalRow($text,$im,$font_color_2,$font_file,$x1,$y1,$font1,$range1);

        //小怪兽图片
        list($m_w,$m_h) = getimagesize($gData['pic']);
        $monsterImg = @imagecreatefrompng($gData['pic']);
        imagecopyresized($im, $monsterImg, 30, 420, 0, 0, $m_w*0.8, $m_h*0.8, $m_w, $m_h);
        
        //Logo
        list($l_w,$l_h) = getimagesize($gData['logo']);
        $logoImg = @imagecreatefrompng($gData['logo']);
        imagecopyresized($im, $logoImg, 80, 900, 0, 0, $l_w, $l_h, $l_w, $l_h);

        //二维码
        imagettftext($im, 12, 0, 430, 920, $font_color_2, $font_file, '扫码解压');
        imagettftext($im, 12, 0, 380, 940, $font_color_2, $font_file, '你体内的小怪兽');
        list($code_w,$code_h) = getimagesize($codeImg);
        $codeImg = @imagecreatefrompng($codeImg);
        imagecopyresized($im, $codeImg, 500, 825, 0, 0, $code_w*0.7, $code_h*0.7, $code_w, $code_h);

        //输出图片
        if($fileName){
            imagepng ($im,$fileName);
        }else{
            Header("Content-Type: image/png");
            imagepng ($im);
        }

        //释放空间
        imagedestroy($im);
        imagedestroy($monsterImg);
        imagedestroy($logoImg);
        imagedestroy($codeImg);
    }

    /**
     * 从图片文件创建Image资源
     * @param $file 图片文件，支持url
     * @return bool|resource    成功返回图片image资源，失败返回false
     */
    function createImageFromFile($file){
        if(preg_match('/http(s)?:\/\//',$file)){
            $fileSuffix = $this->getNetworkImgType($file);
        }else{
            $fileSuffix = pathinfo($file, PATHINFO_EXTENSION);
        }

        if(!$fileSuffix) return false;

        switch ($fileSuffix){
            case 'jpeg':
                $theImage = @imagecreatefromjpeg($file);
                break;
            case 'jpg':
                $theImage = @imagecreatefromjpeg($file);
                break;
            case 'png':
                $theImage = @imagecreatefrompng($file);
                break;
            case 'gif':
                $theImage = @imagecreatefromgif($file);
                break;
            default:
                $theImage = @imagecreatefromstring(file_get_contents($file));
                break;
        }

        return $theImage;
    }

    /**
     * 获取网络图片类型
     * @param $url  网络图片url,支持不带后缀名url
     * @return bool
     */
    function getNetworkImgType($url){
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $url); //设置需要获取的URL
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);//设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //支持https
        curl_exec($ch);//执行curl会话
        $http_code = curl_getinfo($ch);//获取curl连接资源句柄信息
        curl_close($ch);//关闭资源连接

        if ($http_code['http_code'] == 200) {
            $theImgType = explode('/',$http_code['content_type']);

            if($theImgType[0] == 'image'){
                return $theImgType[1];
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 分行连续截取字符串
     * @param $str  需要截取的字符串,UTF-8
     * @param int $row  截取的行数
     * @param int $number   每行截取的字数，中文长度
     * @param bool $suffix  最后行是否添加‘...’后缀
     * @return array    返回数组共$row个元素，下标1到$row
     */
    function cn_row_substr($str,$row = 1,$number = 10,$suffix = true){
        $result = array();
        for ($r=1;$r<=$row;$r++){
            $result[$r] = '';
        }

        $str = trim($str);
        if(!$str) return $result;

        $theStrlen = strlen($str);

        //每行实际字节长度
        $oneRowNum = $number * 3;
        for($r=1;$r<=$row;$r++){
            if($r == $row and $theStrlen > $r * $oneRowNum and $suffix){
                $result[$r] = $this->mg_cn_substr($str,$oneRowNum-6,($r-1)* $oneRowNum).'...';
            }else{
                $result[$r] = $this->mg_cn_substr($str,$oneRowNum,($r-1)* $oneRowNum);
            }
            if($theStrlen < $r * $oneRowNum) break;
        }

        return $result;
    }

    /**
     * 按字节截取utf-8字符串
     * 识别汉字全角符号，全角中文3个字节，半角英文1个字节
     * @param $str  需要切取的字符串
     * @param $len  截取长度[字节]
     * @param int $start    截取开始位置，默认0
     * @return string
     */
    function mg_cn_substr($str,$len,$start = 0){
        $q_str = '';
        $q_strlen = ($start + $len)>strlen($str) ? strlen($str) : ($start + $len);

        //如果start不为起始位置，若起始位置为乱码就按照UTF-8编码获取新start
        if($start and json_encode(substr($str,$start,1)) === false){
            for($a=0;$a<3;$a++){
                $new_start = $start + $a;
                $m_str = substr($str,$new_start,3);
                if(json_encode($m_str) !== false) {
                    $start = $new_start;
                    break;
                }
            }
        }

        //切取内容
        for($i=$start;$i<$q_strlen;$i++){
            //ord()函数取得substr()的第一个字符的ASCII码，如果大于0xa0的话则是中文字符
            if(ord(substr($str,$i,1))>0xa0){
                $q_str .= substr($str,$i,3);
                $i+=2;
            }else{
                $q_str .= substr($str,$i,1);
            }
        }
        return $q_str;
    }

    /**
     * @param $text          //文本
     * @param $im            //画布
     * @param $font_color_2  //颜色
     * @param $font_file     //字体
     * @param $x             //x轴
     * @param $y             //y轴
     * @param $font          //字体大小
     * @param $range         //竖排间距
     */
    public function verticalRow($text,$im,$font_color_2,$font_file,$x,$y,$font,$range)
    {
        $arr  = $this->ch2arr($text);
        $a = array_slice($arr,0,20);
        $len1 = count($a);
        $b = array_slice($arr,20,20);
        $len2 = count($b);
        $c = array_slice($arr,40,20);
        $len3 = count($c);
        $y1 = $y2 = $range;
        if($len1 > 0){
            for($i=1;$i<=$len1;$i++){
                imagettftext($im, $font, 0, $x, $y, $font_color_2, $font_file, $a[$i-1]);
                $y += $range;
            }
        }
        if($len2 > 0){
            $x  = 470;
            $y1  = 50;
            for($i=1;$i<=$len2;$i++){
                imagettftext($im, $font, 0, $x, $y1, $font_color_2, $font_file, $b[$i-1]);
                $y1 += $range;
            }
        }
        if($len3 > 0){
            $x  = 440;
            $y2  = 50;
            for($i=1;$i<=$len3;$i++){
                imagettftext($im, $font, 0, $x, $y2, $font_color_2, $font_file, $c[$i-1]);
                $y2 += $range;
            }
        }
    }

    // 计算中文字符串长度
    function utf8_strlen($string = null)
    {
        // 将字符串分解为单元
        preg_match_all("/./us", $string, $match);
        // 返回单元个数
        return count($match[0]);
    }

    // 中文字符串转换数组
    function ch2arr($str)
    {
        $length = mb_strlen($str, 'utf-8');
        $array = [];
        for ($i=0; $i<$length; $i++)
            $array[] = mb_substr($str, $i, 1, 'utf-8');
        return $array;
    }

    // 画星星
    public function drawStars($im,$starArr,$starNum)
    {
        $x = 555;
        $y = 210;
        $a = intval(floor($starNum));
        $b = intval(ceil($starNum));
        $c = intval(5 - $a);
        list($g_w,$g_h) = getimagesize($starArr[0]);
        $goodImg1 = $this->createImageFromFile($starArr[0]);
        $goodImg2 = $this->createImageFromFile($starArr[1]);
        $goodImg3 = $this->createImageFromFile($starArr[2]);
        for($i=1;$i<=$a;$i++){
            imagecopyresized($im, $goodImg1, $x, $y, 0, 0, $g_w*0.8, $g_h*0.8, $g_w, $g_h);
            $y += 30;
            if($b > $starNum){
                imagecopyresized($im, $goodImg2, $x, $y, 0, 0, $g_w*0.8, $g_h*0.8, $g_w, $g_h);
            }
        }
        if($b = $starNum){
            $y -= 30;
        }
        for($i=1;$i<=$c;$i++){
            $y += 30;
            imagecopyresized($im, $goodImg3, $x, $y, 0, 0, $g_w*0.8, $g_h*0.8, $g_w, $g_h);
        }
    }
}
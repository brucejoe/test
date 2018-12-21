<?php
/**
 * Created by PhpStorm.
 * User: hpc
 * Date: 2018/12/3
 * Time: 上午9:35
 */

namespace app\study\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\study\model\Image;

class Aside extends Admin
{
    public function lists()
    {
        $gData = [
            'name'=> '赈早见琥珀川',
            'monster' => '阳光肥宅',
            'pic' => 'uploads/images/exam/1_1.png',
            'title' =>'压力已经一定程度影响到了你的生活，不要在意外界的声音，调整心态做自己。列出自己喜欢的事情，一件件做起来。',
            'stars' =>  [
            'uploads/images/exam/1_1_3.png',
            'uploads/images/exam/1_1_2.png',
            'uploads/images/exam/1_1_1.png'
        ],
            'starNum' => 3.5,
            'logo' => 'uploads/images/exam/logo_text.png',
            'code' => 'uploads/images/exam/code.png',
            'rgb' => [
               '180','213','97'
            ]
        ];
        $imgModel = new Image();
        $imgModel->createSharePng($gData,'uploads/images/exam/code.png','uploads/images/exam/exam.png');
    }

}
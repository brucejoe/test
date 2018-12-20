<?php

namespace app\study\admin;
use app\admin\controller\Admin;
use app\common\builder\ZBuilder;

class Index extends Admin
{
    /**
     * form表单
     */
    public function form()
    {
        $btn = [
            'title' => '自定义按钮',
        ];
        $js = <<<EOF
            <script type="text/javascript">
                $('#test').click(function(){
                    alert('按钮被点击了');
                    console.log(111);
                });
            </script>
EOF;
        $html = <<<EOF
            <p>这是一个段落</p>
EOF;

        $list_province = ['gz' => '广州', 'sz' => '深圳', 'sh' => '上海'];
        $list_city = ['gz' => '广州', 'sz' => '深圳', 'sh' => '上海'];
        // 使用ZBuilder构建表单页面，并将页面标题设置为“添加”
        return ZBuilder::make('form')
            ->setPageTitle('添加')
            ->setExtraJs($js)
            ->setPageTips('这是页面提示信息')
            ->setUrl(url('save'))
//            ->hideBtn('back')
//            ->addBtn('<button id="btn" type="button" class="btn btn-default">额外按钮</button>')
            ->setBtnTitle(['submit'=>'确定','back'=>'返回上一页'])
//            ->addCheckbox('city', '选择城市', '', ['gz' => '广州', 'sz' => '深圳', 'sh' => '上海'])
//            ->addRadio('city', '选择城市', '', ['gz' => '广州', 'sz' => '深圳', 'sh' => '上海'],'gz')
//            ->addRadio('city', '选择城市', '请选择城市', $list_city, '', ['color' => 'danger'])
//            ->addDate('create_time', '发布时间','','','','data-start-date=2017-05-05')
            ->addDate('create_time', '发布时间', '', '', '', 'data-start-date=2018-11-05 data-end-date=2018-11-25')
            ->addTime('create_time', '发布时间', '', '12:12:12','HH:mm')
//            ->addSwitch('web_site_status', '站点开关')
            ->addSwitch('web_site_status', '站点开关', '', '1')
            ->addTags('tags', '标签','','javascript,css,html')
//            ->addArray('summary', '摘要')
            ->addGroup(
                [
                    '微信支付' =>[
                        ['text', 'wx_appid', 'APPID', '请输入appid'],
                        ['text', 'wx_appkey', 'APPKEY', '请输入appkey']
                    ],
                    '支付宝支付' =>[
                        ['text', 'al_appid', 'APPID', '请输入appid'],
                        ['text', 'al_appkey', 'APPKEY', '请输入appkey']
                    ]
                ]
            )
//            ->addFormItem('button:4', 'test', $btn)
            ->addNumber('size', '尺寸[:请输入尺寸]', '', '', '-10','10')
            ->addPassword('password', '密码[:请输入复杂一点的密码]')
            ->addColorpicker('color', '请选择颜色', '', 'red')
//            ->addSelect('city', '选择城市', '', ['gz' => '广州', 'sz' => '深圳', 'sh' => '上海'])
//            ->addSelect('city', '选择城市', '请选择城市', $list_city, 'gz')
//            ->addSelect('city', '选择城市[:请选择一个城市]', '请选择城市', $list_city, 'gz,sh', 'multiple')
//            ->addLinkage('province', '选择省份', '', $list_province, '', url('get_city'), 'city,area')
//            ->addLinkage('city', '选择城市', '', '', '', url('get_area'), 'area')
            ->addSelect('area', '选择地区')
//            ->addLinkages('city', '选择所在城市', '', 'test',3)
//            ->addSort('province', '调整省份顺序', '', $list_province)
            ->addStatic('name', '名称', '', '李雷', true)
            ->addMasked('date', '请填写日期', '', '9999-99-99')
            ->addMasked('time', '请填写时间', '', '99:99')
            ->addDatetime('create_time1', '发布时间')
            ->addDaterange('date1,date2', '日期范围','','2016-11-11,2016-11-12','','data-start-date=2017-05-05 date-end-date=2017-06-06')
            ->addJcrop('avatar', '头像', '', '', [], '', '', ['img' => 10, 'pos' => 1])
//            ->addBmap('map', '地图', '您的百度密钥', '', '116.382517,39.917259')
//            ->addImage('pic', '图片')
            ->addImages('pic', '图片', '', '', '', '', '', ['size' => '30,30'])
            ->addIcon('icon', '选择图标')
            ->addText('title', '标题', '', '', ['<i class="fa fa-user"></i>'])
            ->addTextarea('summary', '摘要')
//            ->addUeditor('content', '内容')
//            ->addCkeditor('content', '内容')
//            ->setExtraHtml($html)
            ->addSelect('city', '城市', '', $list_province)
            ->addText('zipcode', '邮编')
            ->addText('mobile', '电话')
            ->setTrigger('city', 'gz,sz', 'zipcode,mobile')
            ->submitConfirm()
//            ->addText('zipcode', '邮编')
//            ->addText('mobile', '电话')
//            ->assign('name', 'ming')
            ->fetch();
    }

    /**
     * @return mixed|void
     * 接收form提交参数
     */
    public function save()
    {
        if($this->request->isPost()){
            $data = input('post.');
            dump($data);
        }else{
            echo 'nothing';
        }
    }

    // 根据省份获取城市
    public function get_city($province = '')
    {
        $arr['code'] = '1'; //判断状态
        $arr['msg'] = '请求成功'; //回传信息
        $arr['list'] = [
            ['key' => 'gz', 'value' => '广州'],
            ['key' => 'sz', 'value' => '深圳'],
        ]; //数据
        return json($arr);
    }

    // 根据城市获取地区
    public function get_area($city = '')
    {
        $arr['code'] = '1'; //判断状态
        $arr['msg'] = '请求成功'; //回传信息
        $arr['list'] = [
            ['key' => 'th', 'value' => '天河'],
            ['key' => 'by', 'value' => '白云'],
        ]; //数据
        return json($arr);
    }

}
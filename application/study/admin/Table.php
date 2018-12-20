<?php
/**
 * Created by PhpStorm.
 * User: hpc
 * Date: 2018/11/30
 * Time: 下午2:28
 */

namespace app\study\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use think\Db;

class Table extends Admin
{

    public function lists()
    {
        // 获取排序
        $order = $this->getOrder();
        // 获取筛选
        $map = $this->getMap();
        // 读取用户数据
        $data_list = Db::name('admin_user')->where($map)->order($order)->select();
        return ZBuilder::make('table')
            ->hideCheckbox()
            ->addOrder(['id','username']) // 添加排序
            ->addFilter(['id','username']) // 添加筛选
            ->addColumns([ // 批量添加列
                ['id', 'ID'],
                ['username', '用户名'],
                ['nickname', '昵称'],
                ['email', '邮箱'],
                ['mobile', '手机号'],
                ['create_time', '创建时间', 'datetime'],
                ['status', '状态', 'switch'],
                ['right_button', '操作', 'btn']
            ])
            ->addRightButton('edit')
            ->addRightButton('delete')
            ->setRowList($data_list) // 设置表格数据
            ->fetch();

    }

}
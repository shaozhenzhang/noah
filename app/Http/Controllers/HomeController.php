<?php

namespace App\Http\Controllers;

use App\Repositories\Admin\UserRepository;

class HomeController extends BaseController {

    public function index()
    {
        $userInfo = $this->getUserInfo();
        $roleIds = isset($userInfo['roles'])?$userInfo['roles']:[];
        $actionIds = isset($userInfo['action_ids'])?$userInfo['action_ids']:[];
        $config = config('auth.admin');
        //如果有超级管理员权限 左侧菜单则全部展示
        if(in_array($config,$roleIds)){
            $action = UserRepository::getActionsLists();
            $actionIds = array_column($action, 'actionid');
        }
        if (!empty($roleIds)) {
            $userMenuList = UserRepository::getMenuList($roleIds, $actionIds);
        } else {
            $userMenuList = [];
        }
        $tempMenuList = [];
        $oldMenuPage = ['系统管理','采集/发布','推荐','运营推广','审核','内容库-新'];
        foreach($userMenuList as $tempMenu){
            if(in_array($tempMenu['name'], $oldMenuPage)){
                $tempMenuList[] = $tempMenu;
            }
        }
        $userInfo['menus'] = $tempMenuList;
        $data = ['menus' => $userInfo['menus']];
        return view('index',$data);
    }

}

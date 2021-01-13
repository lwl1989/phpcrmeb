<?php

namespace app\admin\controller\user;

use app\admin\model\user\UserSign as UserSignModel;
use app\admin\controller\AuthController;
use service\JsonService as Json;
use service\FormBuilder as Form;
use think\Url;
use think\Request;
use service\UploadService as Upload;

class UserSign extends AuthController
{
    public function index()
    {
        return $this->fetch();
    }

    public function getUserSignList()
    {
        $where = parent::getMore([
            ['page', 1],
            ['limit', 20],
            ['title', ''],
        ]);
        return Json::successlayui(UserSignModel::getUserSignList($where));
    }

}

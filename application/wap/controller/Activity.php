<?php

namespace app\wap\controller;

use app\admin\model\ump\EventCertImg;
use app\wap\model\activity\EventRegistration;
use app\wap\model\activity\EventSignUp;
use app\wap\model\user\User;
use basic\WapBasic;
use think\Db;
use service\JsonService;
use service\ImgMergeService;
use service\UtilService;
use think\Cookie;
use think\exception\HttpException;
use think\response\Json;
use think\Session;
use think\Url;

/**
 * 文章分类控制器
 * Class Article
 * @package app\wap\controller
 */
class Activity extends AuthController
{

    /*
  * 白名单
  * */
    public static function WhiteList()
    {
        return [
            'details',
            'index',
            'activityList',
            'search',
            'activitySignSearch',
            'webhook'
        ];
    }


    public function webhook()
    {
        echo '1';
//        $secret ='';
//
//
//        $signature = isset($_SERVER['HTTP_X_HUB_SIGNATURE'])?$_SERVER['HTTP_X_HUB_SIGNATURE']:'';

//        if ($signature) {
//            $hash = "sha1=" . hash_hmac('sha1', file_get_contents("php://input"), $secret);
//            if (strcmp($signature, $hash) == 0) {
//                echo shell_exec('cd /www && git pull');
//                exit();
//            }
//        }

        echo shell_exec('cd /www && git pull');
        exit();
        return [];
    }

    public function search()
    {
        return $this->fetch('activity_search');
    }

    /**
     * 用户报名查询
     */
    public function activitySignSearch($username, $activityName)
    {
        $user = User::where('nickname', $username)->find();
        if (!$user) {
            $html = '<script>alert("用户不存在");window.history.go(-1)</script>';
        } else {
            $activity = EventRegistration::where('title', $activityName)->find();
            if (!$activity) {
                $html = '<script>alert("活动不存在");window.history.go(-1)</script>';
            } else {
                $model = EventSignUp::where('uid', $user->uid)->where('activity_id', $activity->id)->find();
                if (!$model) {
                    $html = '<script>alert("用户未报名");window.history.go(-1)</script>';
                } else {
                    $img = EventCertImg::where('event_id', $activity->id)->find();
                    $base64 = '';
                    //{"name":"123","phone":"13111111111","sex":0,"age":"11","company":"","remarks":"","group":"3","area":"1"}
                    $userInfo = json_decode($model->user_info, true);
                    if ($img) {
                        if (strpos($img->image, 'http') !== 0) {
                            $img->image = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/wap/first/' . ltrim($img->image, '/');
                        }
                        //todo:合成
                        $json = json_decode($img->cert_template, true);
                        if (is_array($json)) {
                            $nameArea = explode('x', $json['name']);
                            $areaArea = explode('x', $json['area']);
                            //$projectArea = explode('x', $json['project']);
                            $groupArea = explode('x', $json['group']);
                            $prizeArea = explode('x', $json['prize']);
                            $img = ImgMergeService::getImagick($img->image);
                            $style['font_size'] = 20;
                            $style['fill_color'] = '#FF0000';
                            $style['font'] = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/wap/first/zsff/cret_img/GB2312.ttf';
                            if (isset($nameArea[1]) and $nameArea[1] > 0 and isset($json['name_font'])) {
                                $style['font_size'] = $json['name_font'];
                                ImgMergeService::addText($img, $user->nickname, $nameArea[0], $nameArea[1], 0, $style);
                            }

                            if ($model->area != "" and isset($areaArea[1]) and $areaArea[1] > 0) {
                                if (isset($json['area_font'])) {
                                    $style['font_size'] = $json['area_font'];
                                }
                                ImgMergeService::addText($img, $model->area, $areaArea[0], $areaArea[1], 0, $style);
                            }

                            //ImgMergeService::addText($img, $activity->title, $projectArea[0], $projectArea[1], 0, $style);
                            if ($model->group != "" and isset($groupArea[1]) and $groupArea[1] > 0) {
                                if (isset($json['group_font'])) {
                                    $style['font_size'] = $json['group_font'];
                                }
                                ImgMergeService::addText($img, $model->group, $groupArea[0], $groupArea[1], 0, $style);
                            }

                            if ($model->prize != "" and isset($prizeArea[1]) and $prizeArea[1] > 0) {
                                if (isset($json['prize_font'])) {
                                    $style['font_size'] = $json['prize_font'];
                                }
                                ImgMergeService::addText($img, $model->prize, $prizeArea[0], $prizeArea[1], 0, $style);
                            }

                            $base64 = base64_encode($img->getImageBlob());
                            unset($img);
                        } else {
                            $base64 = base64_encode(file_get_contents($img->image));
                        }
                    }

                    //                        $html = '
                    //<header>
                    //                    <style>html,body{height: 100%;width: 100%;margin:0;padding:0;}
                    //body{
                    //    background:url(data:image/png;base64,' . $base64 . ')no-repeat;
                    //    width:100%;
                    //    height:100%;
                    //    background-size:100% 100%;
                    //    position:absolute;
                    //}</style>
                    //</header>
                    //<body>
                    //&nbsp;
                    //</body>
                    //';
                    //                    } else {
                    //
                    //                    }
                    $this->assign('base64', $base64);
                    $this->assign('user', $user);
                    $this->assign('activity', $activity);
                    return $this->fetch('activity_search_result');
                }
            }
        }
        $this->assign('base64', '');
        $this->assign('html', $html);
        $this->assign('user', []);
        $this->assign('activity', []);
        return $this->fetch('activity_search_result');
//        $this->assign('html', $html);
//        return $this->fetch('activity_search_result');
    }

    public function index()
    {

        return $this->fetch('activity_list');
    }

    /**
     * 活动列表
     */
    public function activityList($page = 1, $limit = 20)
    {
        $list = EventRegistration::eventRegistrationList($page, $limit);
        foreach ($list as &$value) {
            $value['count'] = EventSignUp::where('activity_id', $value['id'])->where('paid', 1)->count();
        }
        return JsonService::successful($list);
    }

    /**
     * 活动扫码
     */
    public function scanningCode($order_id = '')
    {
        if (!$order_id) $this->failed('参数有误！', Url::build('my/sign_list'));
        $order = EventSignUp::where('order_id', $order_id)->find();
        if (!$order) $this->failed('订单不存在！', Url::build('my/sign_list'));
        $activity = EventRegistration::where('id', $order['activity_id'])->field('title,image,phone,province,city,district,detail')->find();
        if (!$activity) $this->failed('活动不存在！', Url::build('my/sign_list'));
        $activity['order_id'] = $order_id;
        return JsonService::successful($activity);
    }

    /**用户活动核销
     * @param string $order_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function scanCodeSignIn($type, $order_id = '')
    {
        if (!$order_id || !$type) $this->failed('参数有误！', Url::build('my/sign_list'));
        $order = EventSignUp::where('order_id', $order_id)->find();
        if (!$order) $this->failed('订单不存在！', Url::build('my/sign_list'));
        if ($order['status']) $this->failed('该订单已核销！', Url::build('my/sign_list'));
        $res = EventSignUp::where('order_id', $order_id)->where('paid', 1)->update(['status' => 1]);
        if ($res) return JsonService::successful('核销成功');
        else return JsonService::fail('核销失败');
    }


    /**
     * 用户报名活动列表
     */
    public function activitySignInList($page = 1, $limit = 20, $navActive = 0)
    {
        $uid = $this->userInfo['uid'];
        $model = EventSignUp::where('uid', $uid)->where('paid', 1)->page((int)$page, (int)$limit);
        switch ($navActive) {
            case 1:
                $model = $model->where('status', 0);
                break;
            case 2:
                $model = $model->where('status', 1);
                break;
        }
        $orderList = $model->order('add_time DESC')->field('order_id,status,pay_price,activity_id,user_info,uid')->select();
        $orderList = count($orderList) > 0 ? $orderList->toArray() : [];
        foreach ($orderList as &$item) {
            $activity = EventRegistration::where('id', $item['activity_id'])->find();
            $item['activity'] = EventRegistration::singleActivity($activity);
        }
        return JsonService::successful($orderList);
    }

    /**活动订单详情
     * @param string $order_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function activitySignIn($order_id = '')
    {
        if (!$order_id) $this->failed('参数有误！', Url::build('my/sign_list'));
        $order = EventSignUp::where('order_id', $order_id)->find();
        if (!$order) $this->failed('订单不存在！', Url::build('my/sign_list'));
        if ($order['activity_id']) {
            $activity = EventRegistration::where('id', $order['activity_id'])->field('id,title,image,province,city,district,detail,start_time,end_time,signup_start_time,signup_end_time,price')->find();
            if (!$activity) $this->failed('活动不存在！', Url::build('my/sign_list'));
            $activity = EventRegistration::singleActivity($activity);
            $start_time = date('y/m/d H:i', $activity['start_time']);
            $end_time = date('y/m/d H:i', $activity['end_time']);
            $activity['time'] = $start_time . '~' . $end_time;
            $order['activity'] = $activity;
        } else {
            $this->failed('活动不存在！');
        }
        $order['pay_time'] = date('y/m/d H:i', $order['pay_time']);
        if (!$order['write_off_code']) {
            $write_off_code = EventSignUp::qrcodes_url($order_id, 5);
            EventSignUp::where('order_id', $order_id)->update(['write_off_code' => $write_off_code]);
            $order['write_off_code'] = $write_off_code;
        }
        return JsonService::successful($order);
    }

    /**检测活动状态
     * @param string $order_id
     */
    public function orderStatus($order_id = '')
    {
        if (!$order_id) $this->failed('参数有误！', Url::build('my/sign_list'));
        $order = EventSignUp::where('order_id', $order_id)->where('paid', 1)->find();
        if (!$order) $this->failed('订单不存在！', Url::build('my/sign_list'));
        if ($order['status']) {
            return JsonService::successful('ok');
        } else {
            return JsonService::fail('error');
        }
    }
}
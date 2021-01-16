<?php

namespace app\wap\controller;

use app\admin\model\ump\EventCertImg;
use app\wap\model\activity\EventRegistration;
use app\wap\model\activity\EventSignUp;
use app\wap\model\user\User;
use basic\WapBasic;
use think\Db;
use service\JsonService;
use service\SystemConfigService;
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
        ];
    }
    public function search() {
        return $this->fetch('activity_search');
    }
    /**
     * 用户报名查询
     */
    public function  activitySignSearch($username, $activityName){
        $user =  User::where('nickname', $username)->find();
        if (!$user) {
            $html = '<script>alert("用户不存在");window.history.go(-1)</script>';
        }else {
            $activity = EventRegistration::where('title', $activityName)->find();
            if (!$activity) {
                $html = '<script>alert("活动不存在");window.history.go(-1)</script>';
            }else{
                $model=EventSignUp::where('uid',$user->id)->where('activity_id', $activity->id)->find();
                if(!$model) {
                    $html = '<script>alert("用户未报名");window.history.go(-1)</script>';
                }else{
                    $html = '
<header>
                    <style>html,body{height: 100%;width: 100%;margin:0;padding:0;}  
body{  
    background:url(data:image/png;base64,%s)no-repeat;  
    width:100%;  
    height:100%;  
    background-size:100% 100%;  
    position:absolute;  
}</style>
</header>
<body>
&nbsp;
</body>
';
                    $img = EventCertImg::where('event_id', $activity->id)->find();
                    $html = sprintf($html, base64_encode(file_get_contents($img->image)));
                }
            }
        }
        $this->assign('html', $html);
        return $this->fetch('activity_search_result');
    }

    public function index()
    {

        return $this->fetch('activity_list');
    }

    /**
     * 活动列表
     */
    public function activityList($page=1,$limit=20){
        $list=EventRegistration::eventRegistrationList($page,$limit);
        foreach ($list as &$value){
            $value['count']=EventSignUp::where('activity_id',$value['id'])->where('paid',1)->count();
        }
        return JsonService::successful($list);
    }

    /**
     * 活动扫码
     */
    public function scanningCode($order_id=''){
        if (!$order_id) $this->failed('参数有误！', Url::build('my/sign_list'));
        $order=EventSignUp::where('order_id',$order_id)->find();
        if(!$order) $this->failed('订单不存在！', Url::build('my/sign_list'));
        $activity=EventRegistration::where('id',$order['activity_id'])->field('title,image,phone,province,city,district,detail')->find();
        if(!$activity) $this->failed('活动不存在！', Url::build('my/sign_list'));
        $activity['order_id']=$order_id;
        return JsonService::successful($activity);
    }

    /**用户活动核销
     * @param string $order_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function scanCodeSignIn($type,$order_id=''){
        if (!$order_id || !$type) $this->failed('参数有误！', Url::build('my/sign_list'));
        $order=EventSignUp::where('order_id',$order_id)->find();
        if(!$order) $this->failed('订单不存在！', Url::build('my/sign_list'));
        if($order['status']) $this->failed('该订单已核销！', Url::build('my/sign_list'));
        $res=EventSignUp::where('order_id',$order_id)->where('paid',1)->update(['status'=>1]);
        if($res) return JsonService::successful('核销成功');
        else return JsonService::fail('核销失败');
    }


    /**
     * 用户报名活动列表
     */
    public function  activitySignInList($page=1,$limit=20,$navActive=0){
        $uid=$this->userInfo['uid'];
        $model=EventSignUp::where('uid',$uid)->where('paid',1)->page((int)$page,(int)$limit);
        switch ($navActive){
            case 1:
               $model=$model->where('status',0);
               break;
            case 2:
                $model=$model->where('status',1);
              break;
        }
        $orderList=$model->order('add_time DESC')->field('order_id,status,pay_price,activity_id,user_info,uid')->select();
        $orderList=count($orderList)>0 ? $orderList->toArray() : [];
        foreach ($orderList as &$item){
            $activity=EventRegistration::where('id',$item['activity_id'])->find();
            $item['activity']=EventRegistration::singleActivity($activity);
        }
        return JsonService::successful($orderList);
    }

    /**活动订单详情
     * @param string $order_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function activitySignIn($order_id=''){
        if (!$order_id) $this->failed('参数有误！', Url::build('my/sign_list'));
        $order=EventSignUp::where('order_id',$order_id)->find();
        if(!$order) $this->failed('订单不存在！', Url::build('my/sign_list'));
        if($order['activity_id']){
            $activity=EventRegistration::where('id',$order['activity_id'])->field('id,title,image,province,city,district,detail,start_time,end_time,signup_start_time,signup_end_time,price')->find();
            if(!$activity) $this->failed('活动不存在！', Url::build('my/sign_list'));
            $activity=EventRegistration::singleActivity($activity);
            $start_time=date('y/m/d H:i',$activity['start_time']);
            $end_time=date('y/m/d H:i',$activity['end_time']);
            $activity['time']=$start_time.'~'.$end_time;
            $order['activity']=$activity;
        }else{
            $this->failed('活动不存在！');
        }
        $order['pay_time']=date('y/m/d H:i',$order['pay_time']);
        if(!$order['write_off_code']){
            $write_off_code=EventSignUp::qrcodes_url($order_id,5);
            EventSignUp::where('order_id',$order_id)->update(['write_off_code'=>$write_off_code]);
            $order['write_off_code']=$write_off_code;
        }
        return JsonService::successful($order);
    }

    /**检测活动状态
     * @param string $order_id
     */
    public function orderStatus($order_id=''){
        if (!$order_id) $this->failed('参数有误！', Url::build('my/sign_list'));
        $order=EventSignUp::where('order_id',$order_id)->where('paid',1)->find();
        if(!$order) $this->failed('订单不存在！', Url::build('my/sign_list'));
        if($order['status']){
            return JsonService::successful('ok');
        }else{
            return JsonService::fail('error');
        }
    }
}
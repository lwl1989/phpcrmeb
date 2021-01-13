<?php
namespace app\wap\model\user;

use service\CanvasService;
use think\Url;
use traits\ModelTrait;
use basic\ModelBasic;
use service\SystemConfigService;

class SignPoster extends ModelBasic
{
    use ModelTrait;

    public static function todaySignPoster($uid){
        $urls=SystemConfigService::get('site_url').'/';
        $url = $urls .'wap/my/sign_in/spread_uid=' . $uid;
        $sign_info = self::todaySignInfo();
        if(!$sign_info['poster'] || !$sign_info) return false;
        try {
            $filename = CanvasService::foundSignCode($uid, $url, $sign_info,"poster_sign_");
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return $urls .$filename;
    }

    public static function todaySignInfo(){
        $time=strtotime(date('Y-m-d',time()));
        $signPoster=self::order('sort DESC')->select();
        $signPoster=count($signPoster)>0 ? $signPoster->toArray() :[];
        $poster=SystemConfigService::get('sign_default_poster');
        if(count($signPoster)>0){
            foreach ($signPoster as $key=>$value){
                if($value['sign_time']==$time){
                    $poster=$value['poster'];
                    $sign_talk=$value['sign_talk'];
                    break;
                }
            }
        }
        $sign_info['poster'] = isset($poster) ? $poster : "";
        $sign_info['sign_talk'] = isset($sign_talk) ? $sign_talk : "愿你出走半生，归来仍是少年";
        return $sign_info;
    }

}

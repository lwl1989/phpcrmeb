<?php
namespace app\admin\model\live;

/**
 * 直播间礼物
 */

use basic\ModelBasic;
use traits\ModelTrait;
use app\admin\model\system\SystemGroupData;

class LiveGift extends ModelBasic
{
    use ModelTrait;

    /**礼物列表
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function liveGiftList()
    {
        $data=self::order('sort ASC')->select();
        $data = count($data) ? $data->toArray() : [];
        foreach ($data as &$item) {
            $item['add_time'] = date('Y-m-d H:i:s',$item['add_time']);
            $item['live_gift_num'] = implode(',',json_decode($item['live_gift_num']));
        }
        $count = self::count();
        return compact('data','count');
    }
    /**
     * 单个礼物信息
     */
    public static function liveGiftOne($id)
    {
        $gift=self::where('id',$id)->find();
        if($gift){
            return $gift;
        }else{
            return [];
        }
    }
}
<?php


namespace app\admin\model\special;


use traits\ModelTrait;
use basic\ModelBasic;

/**
 * Class Grade 年级部
 * @package app\admin\model\special
 */
class Grade extends ModelBasic
{
    use ModelTrait;

    public static function getAll()
    {
        return self::where(['is_del' => 0,'is_show'=>1])->order('sort desc,add_time desc')->field(['name', 'id'])->select();
    }

    public static function getAllList($where)
    {
        $data = self::setWhere($where)->page((int)$where['page'], (int)$where['limit'])->select();
        $count = self::setWhere($where)->count();
        return compact('data', 'count');
    }

    public static function setWhere($where)
    {
        $model = self::order('sort desc,add_time desc')->where('is_del', 0);
        if ($where['cate_name'] != '') $model = $model->where('name', 'like', "%$where[cate_name]%");
        return $model;
    }

    public function SpecialSubject()
    {
        return $this->hasMany('SpecialSubject','grade_id');
    }
}

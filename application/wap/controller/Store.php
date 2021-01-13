<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/12/12
 */

namespace app\wap\controller;


use app\admin\model\system\SystemConfig;
use app\wap\model\store\StoreCategory;
use app\wap\model\store\StoreCart;
use app\wap\model\store\StoreOrder;
use app\wap\model\store\StoreProduct;
use app\wap\model\store\StoreProductAttr;
use app\wap\model\store\StoreProductRelation;
use app\wap\model\user\User;
use app\wap\model\user\WechatUser;
use service\GroupDataService;
use service\SystemConfigService;
use service\UtilService;
use think\Cache;
use think\Request;
use think\Url;
use service\JsonService;

class Store extends AuthController
{

    /*
     * 白名单
     * */
    public static function WhiteList()
    {
        return [
            'index',
           'getCategory',
           'getProductList',
           'detail',
        ];
    }

    /**商城列表
     * @param string $keyword
     * @return mixed
     */
    public function index()
    {
        $banner = json_encode(GroupDataService::getData('product_list_carousel') ?: []);
        $this->assign(compact('banner'));
        return $this->fetch();
    }

    /**获取分类
     * @throws \think\exception\DbException
     */
    public function getCategory()
    {
        $parentCategory = StoreCategory::pidByCategory(0, 'id,cate_name');
        $parentCategory=count($parentCategory)>0 ? $parentCategory->toArray() : [];
        $tj_cate[] = array('id' => 0, 'cate_name' => "推荐");
        $parentCategory = array_merge($tj_cate, $parentCategory);
        return JsonService::successful($parentCategory);
    }

    /**商品列表
     * @param string $keyword
     * @param int $cId
     * @param int $first
     * @param int $limit
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getProductList($page=1,$limit = 8,$cId = 0)
    {
        if (!empty($keyword)) $keyword = base64_decode(htmlspecialchars($keyword));
        $model = StoreProduct::validWhere();
        $model = $model->where('cate_id', $cId);
        if (!empty($keyword)) $model->where('keyword|store_name', 'LIKE', "%$keyword%");
        $model->order('sort DESC, add_time DESC');
        $list = $model->page((int)$page, (int)$limit)->field('id,store_name,image,sales,price,stock,IFNULL(sales,0) + IFNULL(ficti,0) as sales,keyword')->select();
        $list=count($list) >0 ? $list->toArray() : [];
        return JsonService::successful($list);
    }

    /**商品详情
     * @param int $id
     * @return mixed|void
     */
    public function detail($id = 0)
    {
        if(!$id) return $this->failed('商品不存在或已下架!',Url::build('store/index'));
        $storeInfo = StoreProduct::getValidProduct($id);
        if (!$storeInfo) return $this->failed('商品不存在或已下架!',Url::build('store/index'));
        $this->assign([
            'storeInfo'=>$storeInfo,
            ]);
        return $this->fetch();
    }

}

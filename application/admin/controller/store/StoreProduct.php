<?php

namespace app\admin\controller\store;

use app\admin\controller\AuthController;
use app\admin\model\system\Recommend;
use app\admin\model\system\RecommendRelation;
use service\FormBuilder as Form;
use app\admin\model\store\StoreProductAttr;
use app\admin\model\store\StoreProductAttrResult;
use app\admin\model\store\StoreProductRelation;
use app\admin\model\system\SystemConfig;
use service\JsonService;
use service\JsonService as Json;
use service\SystemConfigService;
use traits\CurdControllerTrait;
use service\UploadService as Upload;
use think\Request;
use app\admin\model\store\StoreCategory as CategoryModel;
use app\admin\model\store\StoreProduct as ProductModel;
use think\Url;
use app\admin\model\ump\StoreSeckill as StoreSeckillModel;
use app\admin\model\order\StoreOrder as StoreOrderModel;
use app\admin\model\ump\StoreBargain as StoreBargainModel;
use app\admin\model\system\SystemAttachment;
use app\admin\model\system\Merchant;


/**
 * 产品管理
 * Class StoreProduct
 * @package app\admin\controller\store
 */
class StoreProduct extends AuthController
{

    use CurdControllerTrait;

    protected $bindModel = ProductModel::class;

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {

        $type = $this->request->param('type');
        //获取分类
        $this->assign('cate', CategoryModel::getTierList());
        //出售中产品
        $onsale = ProductModel::where(['is_show' => 1, 'is_del' => 0])->count();
        //待上架产品
        $forsale = ProductModel::where(['is_show' => 0, 'is_del' => 0])->count();
        //仓库中产品
        $warehouse = ProductModel::where(['is_del' => 0])->count();
        //已经售馨产品
        $outofstock = ProductModel::getModelObject()->where(ProductModel::setData(4))->count();
        //警戒库存
        $policeforce = ProductModel::getModelObject()->where(ProductModel::setData(5))->count();
        //回收站
        $recycle = ProductModel::where(['is_del' => 1])->count();

        $this->assign(compact('type', 'onsale', 'forsale', 'warehouse', 'outofstock', 'policeforce', 'recycle'));
        return $this->fetch();
    }

    /**
     * 异步查找产品
     *
     * @return json
     */
    public function product_ist()
    {
        $where = parent::getMore([
            ['page', 1],
            ['limit', 20],
            ['store_name', ''],
            ['cate_id', ''],
            ['excel', 0],
            ['type', $this->request->param('type')]
        ]);
        return JsonService::successlayui(ProductModel::ProductList($where));
    }

    /**
     * 设置单个产品上架|下架
     *
     * @return json
     */
    public function set_show($is_show = '', $id = '')
    {
        ($is_show == '' || $id == '') && JsonService::fail('缺少参数');
        $res = ProductModel::where(['id' => $id])->update(['is_show' => (int)$is_show]);
        if ($res) {
            return JsonService::successful($is_show == 1 ? '上架成功' : '下架成功');
        } else {
            return JsonService::fail($is_show == 1 ? '上架失败' : '下架失败');
        }
    }

    /**
     * 快速编辑
     *
     * @return json
     */
    public function set_product($field = '', $id = '', $value = '')
    {
        $field == '' || $id == '' || $value == '' && JsonService::fail('缺少参数');
        if (ProductModel::where(['id' => $id])->update([$field => $value]))
            return JsonService::successful('保存成功');
        else
            return JsonService::fail('保存失败');
    }

    /**
     * 设置批量产品上架
     *
     * @return json
     */
    public function product_show()
    {
        $post = parent::postMore([
            ['ids', []]
        ]);
        if (empty($post['ids'])) {
            return JsonService::fail('请选择需要上架的产品');
        } else {
            $res = ProductModel::where('id', 'in', $post['ids'])->update(['is_show' => 1]);
            if ($res)
                return JsonService::successful('上架成功');
            else
                return JsonService::fail('上架失败');
        }
    }

    public function create($id=0)
    {
        $gold_name=SystemConfigService::get('gold_name');//虚拟币名称
        if($id){
            $product = ProductModel::get($id);
            if (!$product) return Json::fail('数据不存在!');
            $slider_image=[];
            if($product['slider_image']){
                foreach (json_decode($product['slider_image']) as $key=>$value){
                    $image['pic']=$value;
                    $image['is_show']=false;
                    array_push($slider_image,$image);
                }

            }
            $product['slider_image']=$slider_image;
        }else{
            $product=[];
        }
        $this->assign(['id'=> $id,'product'=>json_encode($product),'gold_name'=>$gold_name]);
        return $this->fetch();
    }
    public function getCateList()
    {
        $list=CategoryModel::where('is_show',1)->where('pid',0)->select();
        $list=count($list) >0 ? $list->toArray() : [];
        //打入基础数据推荐
        $tj_cate[] = array('id' => 0, 'cate_name' => "推荐", 'is_show' => 1);
        $list = array_merge($tj_cate, $list);
        return Json::successful($list);
    }
    /**
     * 上传图片
     * @return \think\response\Json
     */
    public function upload()
    {
        $res = Upload::image('file', 'store/product/' . date('Ymd'));
        $thumbPath = Upload::thumb($res->dir);
        //产品图片上传记录
        $fileInfo = $res->fileInfo->getinfo();
        SystemAttachment::attachmentAdd($res->fileInfo->getSaveName(), $fileInfo['size'], $fileInfo['type'], $res->dir, $thumbPath, 1);
        if ($res->status == 200)
            return Json::successful('图片上传成功!', ['name' => $res->fileInfo->getSaveName(), 'url' => Upload::pathToUrl($thumbPath)]);
        else
            return Json::fail($res->error);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request,$id=0)
    {
        $data = parent::postMore([
            ['cate_id', ""],
            'store_name',
            'store_info',
            'keyword',
            ['unit_name', '件'],
            ['image', []],
            ['slider_image', []],
            'postage',
            'price',
            'vip_price',
            'ot_price',
            'free_shipping',
            'sort',
            'stock',
            'give_gold_num',
            'ficti',
            'description',
            ['is_show', 0],
            ['cost', 0],
            ['is_postage', 0],
            ['member_pay_type', 0],
        ], $request);
        if ($data['cate_id'] == "") return Json::fail('请选择商品分类');
        if (!$data['store_name']) return Json::fail('请输入商品名称');
        if (!$data['description']) return Json::fail('请输入商品详情');
        if (count($data['image']) < 1) return Json::fail('请上传商品图片');
        if (count($data['slider_image']) < 1) return Json::fail('请上传商品轮播图');
        if ($data['price'] == '' || $data['price'] < 0) return Json::fail('请输入商品售价');
        if ($data['ot_price'] == '' || $data['ot_price'] < 0) return Json::fail('请输入商品原价');
        if ($data['vip_price'] == '' || $data['vip_price'] < 0) return Json::fail('请输入商品会员价');
        if ($data['postage'] == '' || $data['postage'] < 0) return Json::fail('请输入邮费');
        if ($data['stock'] == '' || $data['stock'] < 0) return Json::fail('请输入商品库存');
        if ($data['cost'] == '' || $data['ot_price'] < 0) return Json::fail('请输入商品成本价');
        $data['image'] = $data['image'][0];
        $slider_image=[];
        foreach ($data['slider_image'] as $item) {
            $slider_image[] = $item['pic'];
        }
        $data['slider_image'] = json_encode($slider_image);
        if($id){
            ProductModel::edit($data,$id);
            return Json::successful('修改产品成功!');
        }else{
            $data['add_time'] = time();
            ProductModel::set($data);
            return Json::successful('添加产品成功!');
        }
    }
    /**
     * 添加推荐
     * @param int $product_id
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function recommend($product_id = 0)
    {
        if (!$product_id) $this->failed('缺少参数');
        $special = ProductModel::get($product_id);
        if (!$special) $this->failed('没有查到此专题');
        if ($special->is_del) $this->failed('此专题已删除');
        $form = Form::create(Url::build('save_recommend', ['product_id' => $product_id]), [
            Form::select('recommend_id', '推荐')->setOptions(function () use ($product_id) {
                $list = Recommend::where(['is_show' => 1,'type'=>4])->where('is_fixed', 0)->field('title,id')->order('sort desc,add_time desc')->select();
                $menus = [['value' => 0, 'label' => '顶级菜单']];
                foreach ($list as $menu) {
                    $menus[] = ['value' => $menu['id'], 'label' => $menu['title']];
                }
                return $menus;
            })->filterable(1),
            Form::number('sort', '排序'),
        ]);
        $form->setMethod('post')->setTitle('推荐设置')->setSuccessScript('parent.$(".J_iframe:visible")[0].contentWindow.location.reload(); setTimeout(function(){parent.layer.close(parent.layer.getFrameIndex(window.name));},800);');
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    /**
     * 保存推荐
     * @param int $special_id
     * @throws \think\exception\DbException
     */
    public function save_recommend($product_id = 0)
    {
        if (!$product_id) $this->failed('缺少参数');
        $data = parent::postMore([
            ['recommend_id', 0],
            ['sort', 0],
        ]);
        if (!$data['recommend_id']) return Json::fail('请选择推荐');
        $recommend = Recommend::get($data['recommend_id']);
        if (!$recommend) return Json::fail('导航菜单不存在');
        $data['add_time'] = time();
        $data['type'] = $recommend->type;
        $data['link_id'] = $product_id;
        if (RecommendRelation::be(['type' => $recommend->type, 'link_id' => $product_id, 'recommend_id' => $data['recommend_id']])) return Json::fail('已推荐,请勿重复推荐');
        if (RecommendRelation::set($data))
            return Json::successful('推荐成功');
        else
            return Json::fail('推荐失败');
    }
    public function attr($id)
    {
        if (!$id) return $this->failed('数据不存在!');
        $result = StoreProductAttrResult::getResult($id);
        $image = ProductModel::where('id', $id)->value('image');
        $this->assign(compact('id', 'result', 'product', 'image'));
        return $this->fetch();
    }

    /**
     * 生成属性
     * @param int $id
     */
    public function is_format_attr($id = 0)
    {
        if (!$id) return Json::fail('产品不存在');
        list($attr, $detail) = parent::postMore([
            ['items', []],
            ['attrs', []]
        ], $this->request, true);
        $product = ProductModel::get($id);
        if (!$product) return Json::fail('产品不存在');
        $attrFormat = attrFormat($attr)[1];
        if (count($detail)) {
            foreach ($attrFormat as $k => $v) {
                foreach ($detail as $kk => $vv) {
                    if ($v['detail'] == $vv['detail']) {
                        $attrFormat[$k]['price'] = $vv['price'];
                        $attrFormat[$k]['vip_price'] = $vv['vip_price'];
                        $attrFormat[$k]['cost'] = isset($vv['cost']) ? $vv['cost'] : $product['cost'];
                        $attrFormat[$k]['sales'] = $vv['sales'];
                        $attrFormat[$k]['pic'] = $vv['pic'];
                        $attrFormat[$k]['check'] = false;
                        break;
                    } else {
                        $attrFormat[$k]['cost'] = $product['cost'];
                        $attrFormat[$k]['price'] = '';
                        $attrFormat[$k]['vip_price'] = '';
                        $attrFormat[$k]['sales'] = '';
                        $attrFormat[$k]['pic'] = $product['image'];
                        $attrFormat[$k]['check'] = true;
                    }
                }
            }
        } else {
            foreach ($attrFormat as $k => $v) {
                $attrFormat[$k]['cost'] = $product['cost'];
                $attrFormat[$k]['price'] = $product['price'];
                $attrFormat[$k]['vip_price'] = $product['vip_price'];
                $attrFormat[$k]['sales'] = $product['stock'];
                $attrFormat[$k]['pic'] = $product['image'];
                $attrFormat[$k]['check'] = false;
            }
        }
        return Json::successful($attrFormat);
    }

    public function set_attr($id)
    {
        if (!$id) return $this->failed('产品不存在!');
        list($attr, $detail) = parent::postMore([
            ['items', []],
            ['attrs', []]
        ], $this->request, true);
        $res = StoreProductAttr::createProductAttr($attr, $detail, $id);
        if ($res)
            return $this->successful('编辑属性成功!');
        else
            return $this->failed(StoreProductAttr::getErrorInfo());
    }

    public function clear_attr($id)
    {
        if (!$id) return $this->failed('产品不存在!');
        if (false !== StoreProductAttr::clearProductAttr($id) && false !== StoreProductAttrResult::clearResult($id))
            return $this->successful('清空产品属性成功!');
        else
            return $this->failed(StoreProductAttr::getErrorInfo('清空产品属性失败!'));
    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        if(!$id) return $this->failed('数据不存在');
        if(!ProductModel::be(['id'=>$id])) return $this->failed('产品数据不存在');
        if(ProductModel::be(['id'=>$id,'is_del'=>1])){
            $data['is_del'] = 0;
            if(!ProductModel::edit($data,$id))
                return Json::fail(ProductModel::getErrorInfo('恢复失败,请稍候再试!'));
            else
                return Json::successful('成功恢复产品!');
        }else{
            $data['is_del'] = 1;
            if (!ProductModel::edit($data, $id))
                return Json::fail(ProductModel::getErrorInfo('删除失败,请稍候再试!'));
            else
                return Json::successful('删除成功!');
        }
    }


    /**
     * 点赞
     * @param $id
     * @return mixed|\think\response\Json|void
     */
    public function collect($id)
    {
        if (!$id) return $this->failed('数据不存在');
        $product = ProductModel::get($id);
        if (!$product) return Json::fail('数据不存在!');
        $this->assign(StoreProductRelation::getCollect($id));
        return $this->fetch();
    }

    /**
     * 收藏
     * @param $id
     * @return mixed|\think\response\Json|void
     */
    public function like($id)
    {
        if (!$id) return $this->failed('数据不存在');
        $product = ProductModel::get($id);
        if (!$product) return Json::fail('数据不存在!');
        $this->assign(StoreProductRelation::getLike($id));
        return $this->fetch();
    }

    /**
     * 修改产品价格
     * @param Request $request
     */
    public function edit_product_price(Request $request)
    {
        $data = parent::postMore([
            ['id', 0],
            ['price', 0],
        ], $request);
        if (!$data['id']) return Json::fail('参数错误');
        $res = ProductModel::edit(['price' => $data['price']], $data['id']);
        if ($res) return Json::successful('修改成功');
        else return Json::fail('修改失败');
    }

    /**
     * 修改产品库存
     * @param Request $request
     */
    public function edit_product_stock(Request $request)
    {
        $data = parent::postMore([
            ['id', 0],
            ['stock', 0],
        ], $request);
        if (!$data['id']) return Json::fail('参数错误');
        $res = ProductModel::edit(['stock' => $data['stock']], $data['id']);
        if ($res) return Json::successful('修改成功');
        else return Json::fail('修改失败');
    }


}

<?php

namespace app\wap\controller;

use app\wap\model\article\Article as ArticleModel;
use app\wap\model\wap\ArticleCategory;
use app\wap\model\wap\Search;
use basic\WapBasic;
use service\JsonService;
use service\UtilService;
use think\Db;
use think\Url;

/**
 * 文章分类控制器
 * Class Article
 * @package app\wap\controller
 */
class Article extends WapBasic
{

    public function index($cid = '')
    {
        $title = '新闻列表';
        if ($cid) {
            $cateInfo = ArticleCategory::where('status', 1)->where('is_del', 0)->where('id', $cid)->find()->toArray();
            if (!$cateInfo) return $this->failed('文章分类不存在!', Url::build('article/unified_list'));
            $title = $cateInfo['title'];
        }
        $this->assign(compact('title', 'cid'));
        return $this->fetch();
    }
    public function unified_list()
    {
        $title = '新闻列表';
        $category=ArticleCategory::where('status', 1)->where('is_del', 0)->order('sort DESC,add_time DESC')->select();
        $category=count($category)>0 ? $category->toArray() : [];
        $this->assign([
            'title'=>$title,
            'category' => json_encode($category),
        ]);
        return $this->fetch();
    }
    public function get_unifiend_list(){
        $where = UtilService::getMore([
            ['page', 1],
            ['limit', 10],
            ['cid', 0],
        ]);
        return JsonService::successful(ArticleModel::getUnifiendList($where));
    }
    public function video_school()
    {
        return $this->fetch();
    }

    public function guide()
    {
        return $this->fetch();
    }

    public function visit($id = '')
    {
        $content = ArticleModel::where(['id' => $id, 'hide' => 0])->find();
        if (!$content) return $this->failed('此文章已经不存在!', Url::build('article/unified_list'));
        $content["content"] = Db::name('articleContent')->where('nid', $content["id"])->value('content');
        //增加浏览次数
        $content["visit"] = $content["visit"] + 1;
        ArticleModel::where('id', $id)->update(["visit" => $content["visit"]]);
        $this->assign(compact('content'));
        $this->assign([
            'title' => $content->title,
            'image' => $content->image_input,
            'synopsis' => $content->synopsis,
            'content' => htmlspecialchars_decode($content->content)
        ]);
        return $this->fetch('details');
    }

    public function details($id = '')
    {
        $article = ArticleModel::PreWhere()->where('id', $id)->find();
        if (!$article) $this->failed('您查看的文章不存在', Url::build('article/unified_list'));
        $article["content"] = Db::name('articleContent')->where('nid', $article["id"])->value('content');
        //增加浏览次数
        $article["visit"] = $article["visit"] + 1;
        ArticleModel::where('id', $id)->update(["visit" => $article["visit"]]);
        $this->assign([
            'title' => $article->title,
            'image' => $article->image_input,
            'synopsis' => $article->synopsis,
            'content' => htmlspecialchars_decode($article->content)
        ]);
        return $this->fetch();
    }
}

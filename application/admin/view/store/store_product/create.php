{extend name="public/container"}
{block name='head_top'}
<style>
    .layui-table {
        width: 100%!important;
    }

    .layui-form-item .special-label {
        width: 50px;
        float: left;
        height: 30px;
        line-height: 38px;
        margin-left: 10px;
        margin-top: 5px;
        border-radius: 5px;
        background-color: #0092DC;
        text-align: center;
    }

    .layui-form-item .special-label i {
        display: inline-block;
        width: 18px;
        height: 18px;
        font-size: 18px;
        color: #fff;
    }

    .layui-form-item .label-box {
        border: 1px solid;
        border-radius: 10px;
        position: relative;
        padding: 10px;
        height: 30px;
        color: #fff;
        background-color: #393D49;
        text-align: center;
        cursor: pointer;
        display: inline-block;
        line-height: 10px;
    }

    .layui-form-item .label-box p {
        line-height: inherit;
    }

    .layui-form-mid {
        margin-left: 18px;
    }

    .m-t-5 {
        margin-top: 5px;
    }

    .edui-default .edui-for-image .edui-icon {
        background-position: -380px 0px;
    }

    .layui-tab-content {
        padding-right: 150px;
        padding-left: 150px;
    }

    .layui-tab-title .layui-this:after {
        border-bottom-color: #fff!important;
    }

    .upload-image-box .mask p i:first-child {
        padding-right: 0;
    }
</style>
<script type="text/javascript" charset="utf-8"
        src="{__ADMIN_PATH}plug/ueditor/third-party/zeroclipboard/ZeroClipboard.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.all.min.js"></script>
{/block}
{block name="content"}
<div v-cloak id="app" class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <form class="layui-form" action="">
                <div class="layui-tab" lay-filter="tab">
                    <ul class="layui-tab-title">
                        <li class="layui-this">基本设置</li>
                        <li>商品详情</li>
                        <li>价格设置</li>
                    </ul>
                    <div class="layui-tab-content">
                        <div class="layui-tab-item layui-show">
                            <div class="layui-form-item m-t-5">
                                <label class="layui-form-label">商品名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="store_name" v-model="formData.store_name" autocomplete="off"
                                   placeholder="请输入产品名称" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item m-t-5">
                                <label class="layui-form-label">分类选择</label>
                                <div class="layui-input-block">
                                    <select name="cate_id" v-model="formData.cate_id" lay-search="" lay-filter="cate_id">
                                        <option value="">请选分类</option>
                                        <optgroup  v-for="item in cate_list">
                                            <option   :value="item.id">{{item.cate_name}}</option>
                                        </optgroup>

                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item m-t-5">
                                <label class="layui-form-label">商品简介</label>
                                <div class="layui-input-block">
                                    <textarea placeholder="请输入产品简介" v-model="formData.store_info" class="layui-textarea"></textarea>
                                </div>
                            </div>
                            <div class="layui-form-item m-t-5">
                                <label class="layui-form-label">关键字</label>
                                <div class="layui-input-block">
                                    <input type="text" name="keyword" placeholder="多个用英文状态下的逗号隔开" v-model="formData.keyword" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item m-t-5">
                                <label class="layui-form-label">单位</label>
                                <div class="layui-input-block">
                                    <input type="text" name="unit_name" v-model="formData.unit_name" placeholder="请输入商品单位" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item m-t-5">
                                <label class="layui-form-label">主图片</label>
                                <div class="layui-input-block">
                                    <div class="upload-image-box" v-if="formData.image" @mouseenter="mask.image = true"
                                         @mouseleave="mask.image = false">
                                        <img :src="formData.image" alt="">
                                        <div class="mask" v-show="mask.image" style="display: block">
                                            <p>
                                                <i class="fa fa-eye" @click="look(formData.image)"></i>
                                                <i class="fa fa-trash-o" @click="delect('image')"></i>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="upload-image" v-show="!formData.image" @click="upload('image')">
                                        <div class="fiexd"><i class="fa fa-plus"></i></div>
                                        <p>选择图片</p>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item m-t-5">
                                <label class="layui-form-label">轮播图</label>
                                <div class="layui-input-block">
                                    <div class="upload-image-box" v-if="formData.slider_image.length"
                                         v-for="(item,index) in formData.slider_image" @mouseenter="enter(item)"
                                         @mouseleave="leave(item)">
                                        <img :src="item.pic" alt="">
                                        <div class="mask" v-show="item.is_show" style="display: block">
                                            <p><i class="fa fa-eye" @click="look(item)"></i>
                                                <i class="fa fa-trash-o" @click="delect('slider_image',index)"></i>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="upload-image" v-show="formData.slider_image.length <= 3"
                                         @click="upload('slider_image',5)">
                                        <div class="fiexd"><i class="fa fa-plus"></i></div>
                                        <p>选择图片</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="layui-tab-item">
                            <div class="layui-form-item m-t-5">
                                <label class="layui-form-label">商品详情</label>
                                <div class="layui-input-block">
                                    <textarea id="myEditor" style="width:100%;height: 500px">{{formData.description}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="layui-tab-item">
                            <div class="layui-form-item">
                                <label class="layui-form-label">售价</label>
                                <div class="layui-input-block">
                                    <input style="width: 20%" type="number" name="price" lay-verify="number"
                                           v-model="formData.price" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">原价</label>
                                <div class="layui-input-block">
                                    <input style="width: 20%" type="number" name="ot_price" lay-verify="number"
                                           v-model="formData.ot_price" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">成本价</label>
                                <div class="layui-input-block">
                                    <input style="width: 20%" type="number" name="cost" lay-verify="number"
                                           v-model="formData.cost" autocomplete="off" class="layui-input" min="0">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">会员是否免费</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="member_pay_type" lay-filter="member_pay_type"
                                           v-model="formData.member_pay_type" value="0" title="免费">
                                    <input type="radio" name="member_pay_type" lay-filter="member_pay_type"
                                           v-model="formData.member_pay_type" value="1" title="付费">
                                </div>
                            </div>
                            <div class="layui-form-item" v-show="formData.member_pay_type==1">
                                <label class="layui-form-label">会员价</label>
                                <div class="layui-input-block">
                                    <input style="width: 20%" type="number" name="vip_price" lay-verify="number"
                                           v-model="formData.vip_price" autocomplete="off" class="layui-input" min="0">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">是否包邮</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="is_postage" lay-filter="is_postage"
                                           v-model="formData.is_postage" value="1" title="包邮">
                                    <input type="radio" name="is_postage" lay-filter="is_postage"
                                           v-model="formData.is_postage" value="0" title="不包邮">
                                </div>
                            </div>
                            <div class="layui-form-item" v-show="formData.is_postage==0">
                                <label class="layui-form-label">邮费(元)</label>
                                <div class="layui-input-block">
                                    <input style="width: 20%" type="number" name="postage" lay-verify="number"
                                           v-model="formData.postage" autocomplete="off" class="layui-input" min="0">
                                </div>
                            </div>
                           <!-- <div class="layui-form-item" v-show="formData.is_postage==0">
                                <label class="layui-form-label">邮费续价(元)</label>
                                <div class="layui-input-block">
                                    <input style="width: 20%" type="number" name="renewal" lay-verify="number"
                                           v-model="formData.renewal" autocomplete="off" class="layui-input" min="0">
                                </div>
                            </div>-->
                            <div class="layui-form-item" v-show="formData.is_postage==0">
                                <label class="layui-form-label">满几件包邮</label>
                                <div class="layui-input-block">
                                    <input style="width: 20%" type="number" name="free_shipping" lay-verify="number"
                                           v-model="formData.free_shipping" autocomplete="off" class="layui-input" min="0">
                                </div>
                            </div>
                           <div class="layui-form-item">
                                <label class="layui-form-label">{$gold_name}</label>
                                <div class="layui-input-block">
                                    <input style="width: 20%" type="number" name="sales" lay-verify="number"
                                           v-model="formData.give_gold_num" autocomplete="off" class="layui-input" min="0">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">虚拟销量</label>
                                <div class="layui-input-block">
                                    <input style="width: 20%" type="number" name="ficti" lay-verify="number"
                                           v-model="formData.ficti" autocomplete="off" class="layui-input" min="0">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">库存</label>
                                <div class="layui-input-block">
                                    <input style="width: 20%" type="number" name="stock" lay-verify="number"
                                           v-model="formData.stock" autocomplete="off" class="layui-input" min="0">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">排序</label>
                                <div class="layui-input-block">
                                    <input style="width: 20%" type="number" name="sort" lay-verify="number"
                                           v-model="formData.sort" autocomplete="off" class="layui-input" min="0">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">商品状态</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="is_show" lay-filter="is_show"
                                           v-model="formData.is_show" value="1" title="上架">
                                    <input type="radio" name="is_show" lay-filter="is_show"
                                           v-model="formData.is_show" value="0" title="下架">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block" style="margin-left:260px">
                        <button class="layui-btn layui-btn-normal" type="button" @click="save">{$id ?
                            '确认修改':'立即提交'}
                        </button>
                        <button class="layui-btn layui-btn-primary clone" type="button" @click="clone_form">取消
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="{__ADMIN_PATH}js/layuiList.js"></script>
{/block}
{block name='script'}
<script>
    var id = {$id},
        product =<?=isset($product) ? $product : "{}"?>;
    require(['vue','helper','zh-cn','request','aliyun-oss','plupload','OssUpload'], function (Vue,$h) {
        new Vue({
            el: "#app",
            data: {
                cate_list: [],
                formData: {
                    cate_id: product.cate_id || '',
                    store_name: product.store_name || '',
                    store_info: product.store_info || '',
                    keyword: product.keyword || '',
                    unit_name: product.unit_name || '件',
                    image: product.image || '',
                    slider_image: product.slider_image || [],
                    postage: product.postage || 0.00,
                    // renewal: product.renewal || 0.00,
                    free_shipping: product.free_shipping || 0,
                    price: product.price || 0.00,
                    vip_price: product.vip_price || 0.00,
                    ot_price: product.ot_price || 0.00,
                    sort: Number(product.sort) || 0,
                    stock: product.stock || 0,
                    give_gold_num: product.give_gold_num || 0,
                    ficti: product.ficti || 0,
                    cost: product.cost || 0.00,
                    is_postage: product.is_postage || 0,
                    member_pay_type: product.member_pay_type || 0,
                    is_show: product.is_show || 0,
                    description: product.description || '',
                },
                host: ossUpload.host + '/',
                mask: {
                    poster_image: false,
                    image: false,
                    service_code: false,
                },
                ue: null,
                is_video: false,
                //上传类型
                mime_types: {
                    Image: "jpg,gif,png,JPG,GIF,PNG",
                    Video: "mp4,MP4",
                    Audio: "mp3,MP3",
                },
            },
            methods: {
                //取消
                cancelUpload: function () {
                    this.uploader.stop();
                },
                //删除图片
                delect: function (key, index) {
                    var that = this;
                    if (index != undefined) {
                        that.formData[key].splice(index, 1);
                        that.$set(that.formData, key, that.formData[key]);
                    } else {
                        that.$set(that.formData, key, '');
                    }
                },
                //查看图片
                look: function (pic) {
                    parent.$eb.openImage(pic);
                },
                //鼠标移入事件
                enter: function (item) {
                    if (item) {
                        item.is_show = true;
                    } else {
                        this.mask = true;
                    }
                },
                //鼠标移出事件
                leave: function (item) {
                    if (item) {
                        item.is_show = false;
                    } else {
                        this.mask = false;
                    }
                },
                changeIMG: function (key, value, multiple) {
                    if (multiple) {
                        var that = this;
                        value.map(function (v) {
                            that.formData[key].push({pic: v, is_show: false});
                        });
                        this.$set(this.formData, key, this.formData[key]);
                    } else {
                        this.$set(this.formData, key, value);
                    }
                },
                //上传图片
                upload: function (key, count) {
                    ossUpload.createFrame('请选择图片', {fodder: key, max_count: count === undefined ? 0 : count},{w:800,h:550});
                },
                //获取分类
                get_cate_list: function () {
                    var that = this;
                    layList.baseGet(layList.U({a: 'getCateList'}), function (res) {
                        that.$set(that, 'cate_list', res.data);
                        that.$nextTick(function () {
                            layList.form.render('select');
                        })
                    });
                },
                save: function () {
                    var that = this;
                    that.formData.description = that.ue.getContent();
                    if (!that.formData.cate_id) return layList.msg('请选择分类');
                    if (!that.formData.store_name) return layList.msg('请输入商品名称');
                    if (!that.formData.store_info) return layList.msg('请输入商品简介');
                    if (!that.formData.keyword) return layList.msg('请输入商品关键字');
                    if (!that.formData.unit_name) return layList.msg('请输入商品单位');
                    if (!that.formData.image) return layList.msg('请上传商品主图');
                    if (!that.formData.slider_image.length) return layList.msg('请上传商品轮播图');
                    if (that.formData.price<0) return layList.msg('请输入商品售价');
                    if (that.formData.member_pay_type == 1) {
                        if (!that.formData.vip_price || that.formData.vip_price <=0) return layList.msg('请填写会员购买金额');
                    }
                    if (that.formData.cost<0) return layList.msg('请输入商品成本价');
                    if (!that.formData.stock) return layList.msg('请输入商品库存');
                    if (!that.formData.description) return layList.msg('请编辑内容在进行保存');
                    if(!that.formData.is_postage){
                        if (!that.formData.postage) return layList.msg('请输入首件邮费');
                    }
                    layList.loadFFF();
                    layList.basePost(layList.U({
                        a: 'save',
                        q: {id: id}
                    }), that.formData, function (res) {
                        layList.loadClear();
                        if (parseInt(id) == 0) {
                            layList.layer.confirm('添加成功,您要继续添加专题吗?', {
                                btn: ['继续添加', '立即提交'] //按钮
                            }, function () {
                                window.location.reload();
                            }, function () {
                                parent.layer.closeAll();
                            });
                        } else {
                            layList.msg('修改成功', function () {
                                parent.layer.closeAll();
                            })
                        }
                    }, function (res) {
                        layList.msg(res.msg);
                        layList.loadClear();
                    });
                },
                clone_form: function () {
                    if (parseInt(id) == 0) {
                        var that = this;
                        if (that.formData.image) return layList.msg('请先删除上传的图片在尝试取消');
                        if (that.formData.slider_image) return layList.msg('请先删除上传的图片在尝试取消');
                        parent.layer.closeAll();
                    }
                    parent.layer.closeAll();
                },
            },
            mounted: function () {
                var that = this;
                window.changeIMG = that.changeIMG;
                //选择图片插入到编辑器中
                window.insertEditor = function (list) {
                    that.ue.execCommand('insertimage', list);
                };
                this.$nextTick(function () {
                    layList.form.render();
                    layui.element.on('tab(tab)', function () {
                        layui.table.resize('table');
                    });
                    //实例化编辑器
                    UE.registerUI('imagenone', function (editor, name) {
                        var $btn = new UE.ui.Button({
                            name: 'image',
                            onclick: function () {
                                ossUpload.createFrame('选择图片', {fodder: 'editor'},{w:800,h:550});
                            },
                            title: '选择图片'
                        });

                        return $btn;

                    });
                    that.ue = UE.getEditor('myEditor');
                });
                //获取分类
                that.get_cate_list();
                //图片上传和视频上传
                layList.form.on('radio(is_postage)', function (data) {
                    that.formData.is_postage = parseInt(data.value);
                    if(that.formData.is_postage){
                        that.formData.postage=0.00;
                        that.formData.free_shipping=0;
                    }
                });
                layList.form.on('radio(member_pay_type)', function (data) {
                    that.formData.member_pay_type = parseInt(data.value);
                    if(that.formData.member_pay_type!=1){
                        that.formData.vip_price=0.00;
                    }
                });
                layList.form.on('radio(is_show)', function (data) {
                    that.formData.is_show = parseInt(data.value);
                });
                layList.select('cate_id', function (obj) {
                    that.formData.cate_id = obj.value;
                });
                that.$nextTick(function () {
                    that.uploader = ossUpload.upload({
                        id: 'ossupload',
                        FilesAddedSuccess: function () {
                            that.is_video = true;
                        },
                        uploadIng: function (file) {
                            that.videoWidth = file.percent;
                        },
                        success: function (res) {
                            layList.msg('上传成功');
                            that.videoWidth = 0;
                            that.is_video = false;
                            that.setContent(res.url);
                        },
                        fail: function (err) {
                            that.videoWidth = 0;
                            that.is_video = false;
                            layList.msg(err);
                        }
                    })
                });
            }
        })
    })
</script>
{/block}

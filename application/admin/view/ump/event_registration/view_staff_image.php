{extend name="public/container"}
{block name="head_top"}
<link href="{__ADMIN_PATH}plug/umeditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<link href="{__ADMIN_PATH}module/wechat/news/css/style.css" type="text/css" rel="stylesheet">
<link href="{__FRAME_PATH}css/plugins/chosen/chosen.css" rel="stylesheet">
<script type="text/javascript" src="{__ADMIN_PATH}plug/umeditor/third-party/template.min.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/umeditor/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/umeditor/umeditor.min.js"></script>
<script src="{__ADMIN_PATH}frame/js/ajaxfileupload.js"></script>
<script src="{__ADMIN_PATH}plug/validate/jquery.validate.js"></script>
<script src="{__FRAME_PATH}js/plugins/chosen/chosen.jquery.js"></script>
<style>
    .wrapper-content {
        padding: 0 !important;
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

    .layui-input-block {
        line-height: 36px;
    }

    .layui-form-select dl {
        z-index: 1000;
    }
</style>
{/block}
{block name="content"}
<div class="row">
    <div class="col-sm-12 panel panel-default" id="app">
        <div class="panel-body" style="padding: 20px 60px;">
            <form class="form-horizontal" id="signupForm">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            <label class="layui-form-label">名字像素位置:</label>
                            <div class="layui-input-block">
                                <input type="text" name="real_name" style="width: 150%" v-model="formData.name"
                                       placeholder="123x123" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group">
                            <label class="layui-form-label">名字字体设置:</label>
                            <div class="layui-input-block">
                                <input type="text" name="real_name" style="width: 150%" v-model="formData.name_font"
                                       placeholder="数字，单位为像素" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group">
                            <label class="layui-form-label">赛区像素位置:</label>
                            <div class="layui-input-block">
                                <input type="text" name="real_name" style="width: 150%" v-model="formData.area"
                                       placeholder="123x123" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group">
                            <label class="layui-form-label">赛区字体设置:</label>
                            <div class="layui-input-block">
                                <input type="text" name="real_name" style="width: 150%" v-model="formData.area_font"
                                       placeholder="数字，单位为像素" class="layui-input">
                            </div>
                        </div>
                    </div>
<!--                    <div class="col-md-12">-->
<!--                        <div class="input-group">-->
<!--                            <label class="layui-form-label">项目像素位置:</label>-->
<!--                            <div class="layui-input-block">-->
<!--                                <input type="text" name="real_name" style="width: 150%" v-model="formData.project"-->
<!--                                       placeholder="123x123" class="layui-input">-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-12">-->
<!--                        <div class="input-group">-->
<!--                            <label class="layui-form-label">组别字体设置:</label>-->
<!--                            <div class="layui-input-block">-->
<!--                                <input type="text" name="real_name" style="width: 150%" v-model="formData.group_font"-->
<!--                                       placeholder="数字，单位为像素" class="layui-input">-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="col-md-12">
                        <div class="input-group">
                            <label class="layui-form-label">组别像素位置:</label>
                            <div class="layui-input-block">
                                <input type="text" name="real_name" style="width: 150%" v-model="formData.group"
                                       placeholder="123x123" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group">
                            <label class="layui-form-label">组别字体设置:</label>
                            <div class="layui-input-block">
                                <input type="text" name="real_name" style="width: 150%" v-model="formData.group_font"
                                       placeholder="数字，单位为像素" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group">
                            <label class="layui-form-label">奖项像素位置:</label>
                            <div class="layui-input-block">
                                <input type="text" name="real_name" style="width: 150%" v-model="formData.prize"
                                       placeholder="123x123" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group">
                            <label class="layui-form-label">奖项字体设置:</label>
                            <div class="layui-input-block">
                                <input type="text" name="real_name" style="width: 150%" v-model="formData.prize_font"
                                       placeholder="数字，单位为像素" class="layui-input">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="form-control" style="height:auto">
                            <label style="color:#ccc">证书设置</label>
                            <div class="row nowrap">
                                <div class="col-xs-3" style="width:160px">
                                    <img :src="formData.image" alt="" style="width:100px">
                                </div>
                                <div class="col-xs-6" @click="upload('image')">
                                    <br>
                                    <a class="btn btn-sm add_image upload_span">上传图片</a>
                                    <br>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-9">
                            <button type="button" class="btn btn-w-m btn-info save_news" @click="save">{$id ?
                                '确认修改':'立即提交'}
                            </button>
                            <button class="layui-btn layui-btn-primary clone" type="button" @click="clone_form">取消
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script src="/static/plug/reg-verify.js"></script>
{/block}
{block name="script"}
<script>
    var id = {$event_id}, img = {$img};
    require(['vue', 'zh-cn', 'request', 'aliyun-oss', 'plupload', 'OssUpload'], function (Vue) {
        new Vue({
            el: "#app",
            data: {
                formData: {
                    event_id: {$event_id},
                    image:img.image,
                    name:img.name,
                    area:img.area,
                  //  project:img.project,
                    group:img.group,
                    prize:img.prize,
                    name:img.name_font,
                    area:img.area_font,
                    //  project:img.project,
                    group:img.group_font,
                    prize:img.prize_font,
                },
                host: ossUpload.host + '/',
                mask: {
                    poster_image: false,
                    image: false,
                    service_code: false,
                },
                ue: null,
                //上传类型
                mime_types: {
                    Image: "jpg,gif,png,JPG,GIF,PNG",
                    Video: "mp4,MP4",
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
                    $eb.openImage(pic);
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
                setContent: function (link) {
                    this.ue.setContent('<div><video style="width: 100%" src="' + link + '" class="video-ue" controls="controls"><source src="' + link + '"></source></video></div><br>', true);
                },
                //上传图片
                upload: function (key, count) {
                    ossUpload.createFrame('请选择图片', {fodder: key, max_count: count === undefined ? 0 : count}, {
                        w: 800,
                        h: 550
                    });
                },
                delLabel: function (index) {
                    this.formData.label.splice(index, 1);
                    this.$set(this.formData, 'label', this.formData.label);
                },
                save: function () {
                    var that = this;
                    // that.formData.content = that.ue.getContent();
                    // that.formData.activity_rules = that.ue1.getContent();
                    // if (!that.formData.title) return layList.msg('请输入专题标题');
                     if (that.formData.image == '{__ADMIN_PATH}images/empty.jpg') return layList.msg('请上传图片');
                    // if (that.formData.qrcode_img == '{__ADMIN_PATH}images/empty.jpg') return layList.msg('请上传群聊二维码');
                    // if (!that.formData.number) return layList.msg('请填写活动人数');
                    // if (!that.formData.start_time) return layList.msg('请选择活动开始时间');
                    // if (!that.formData.end_time) return layList.msg('请选择活动结束时间');
                    // if (!that.formData.signup_start_time) return layList.msg('请填写活动报名开始时间');
                    // if (!that.formData.signup_end_time) return layList.msg('请填写活动报名结束时间');
                    // if (!that.formData.province || !that.formData.city || !that.formData.district || !that.formData.detail) return layList.msg('请输入地址信息');
                    // if (!that.formData.activity_rules) return layList.msg('请输入规则');
                    // if (!that.formData.content) return layList.msg('请编辑内容在进行保存');
                    // if (that.formData.pay_type == 1) {
                    //     if (!that.formData.price || that.formData.price == 0.00) return layList.msg('请填写购买金额');
                    // }
                    // if (that.formData.member_pay_type == 1) {
                    //     if (!that.formData.member_price || that.formData.member_price == 0.00) return layList.msg('请填写会员购买金额');
                    // }
                    layList.loadFFF();
                    layList.basePost(layList.U({
                        a: 'image_template',
                    }), that.formData, function (res) {
                        layList.loadClear();
                        layList.msg('设置成功', function () {
                            parent.layer.closeAll();
                            window.location.reload();
                        })
                    }, function (res) {
                        layList.msg(res.msg);
                        layList.loadClear();
                    });
                },
                clone_form: function () {
                    var that = this;
                    if (parseInt(id) == 0) {
                        if (that.formData.image) return layList.msg('请先删除上传的图片在尝试取消');
                        parent.layer.closeAll();
                    }else {
                        parent.layer.closeAll();
                    }
                }
            },
            mounted: function () {
                var that = this;
                var layer = layui.layer, form = layui.form;
                layui.config({
                    base: '{__ADMIN_PATH}mods/'
                    , version: '1.0'
                }).extend({
                    layarea: 'layarea'
                });

                window.changeIMG = that.changeIMG;


                //选择图片
                function changeIMG(index, pic) {
                    $(".image_img").css('background-image', "url(" + pic + ")");
                    $(".active").css('background-image', "url(" + pic + ")");
                    $('#image_input').val(pic);
                };

                this.$nextTick(function () {
                    layList.form.render();
                    //实例化编辑器
                    UE.registerUI('imagenone', function (editor, name) {
                        var $btn = new UE.ui.Button({
                            name: 'image',
                            onclick: function () {
                                ossUpload.createFrame('选择图片', {fodder: 'editor'}, {w: 800, h: 550});
                            },
                            title: '选择图片'
                        });
                        return $btn;
                    });
                    that.ue = UE.getEditor('myEditor');
                });
                this.$nextTick(function () {
                    layList.form.render();
                    //实例化编辑器
                    UE.registerUI('imagenone', function (editor, name) {
                        var $btn = new UE.ui.Button({
                            name: 'image',
                            onclick: function () {
                                ossUpload.createFrame('选择图片', {fodder: 'editors'}, {w: 800, h: 550});
                            },
                            title: '选择图片'
                        });
                        return $btn;
                    });
                    that.ue1 = UE.getEditor('myEditor1');
                });
                //图片上传和视频上传

                layList.form.on('radio(pay_type)', function (data) {
                    that.formData.pay_type = parseInt(data.value);
                    if (that.formData.pay_type != 1) {
                        that.formData.member_pay_type = 0;
                        that.formData.member_price = 0;
                        that.formData.price = 0;
                    }
                    ;
                    that.$nextTick(function () {
                        layList.form.render('radio');
                    });
                });
                layList.form.on('radio(member_pay_type)', function (data) {
                    that.formData.member_pay_type = parseInt(data.value);
                    if (that.formData.member_pay_type != 1) {
                        that.formData.member_price = 0;
                    }
                    ;
                    that.$nextTick(function () {
                        layList.form.render('radio');
                    });
                });
                layList.form.render();
                that.$nextTick(function () {
                    that.uploader = ossUpload.upload({
                        id: 'ossupload',
                        FilesAddedSuccess: function () {
                        },
                        uploadIng: function (file) {
                            that.videoWidth = file.percent;
                        },
                        success: function (res) {
                            layList.msg('上传成功');
                            that.videoWidth = 0;
                            that.setContent(res.url);
                        },
                        fail: function (err) {
                            that.videoWidth = 0;
                            layList.msg(err);
                        }
                    })
                });
            }
        })
    })
</script>
{/block}



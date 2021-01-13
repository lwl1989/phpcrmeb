{extend name="public/container"}
{block name="head_top"}
<style>
    .layui-input-block .layui-video-box{
        width: 50%;
        height: 180px;
        border-radius: 10px;
        background-color: #707070;
        margin-top: 10px;
        position: relative;
        overflow: hidden;
    }
    .layui-input-block .layui-video-box i{
        color: #fff;
        line-height: 180px;
        margin: 0 auto;
        width: 50px;
        height: 50px;
        display: inherit;
        font-size: 50px;
    }
    .layui-input-block .layui-video-box .mark{
        position: absolute;
        width: 100%;
        height: 30px;
        top: 0;
        background-color: rgba(0,0,0,.5);
        text-align: center;
    }
</style>
<script type="text/javascript" src="{__PC_KS3}src/async.min.js"></script>
{/block}
{block name="content"}
<div class="layui-fluid">
    <div class="layui-row layui-col-space15"  id="app">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">助力拼团</div>
                <div class="layui-card-body">
                    <form class="layui-form" action="">
                        <div class="layui-form-item">
                            <label class="layui-form-label">用户昵称</label>
                            <div class="layui-input-block">
                                <input type="hidden" name="pink_id" value="{$id}">
                                <input type="text" name="nickname"  lay-verify="nickname" autocomplete="off" placeholder="用户昵称" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item submit">
                            <label class="layui-form-label">用户头像</label>
                            <div class="layui-input-block" id="image">
                                <div class="upload-image" id="file_image">
                                    <div class="fiexd"><i class="fa fa-plus"></i></div>
                                    <p>上传图片</p>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item submit">
                            <div class="layui-input-block">
                                <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="save">立即提交</button>
                                <button class="layui-btn layui-btn-primary clone">取消</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{__ADMIN_PATH}js/request.js"></script>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script type="text/javascript" src="{__PC_KS3}src/plupload.full.min.js"></script>
<script type="text/javascript" src="{__PC_KS3}src/ks3jssdk.js"></script>
<script type="text/javascript" src="{__PC_KS3}ks3.js"></script>
<script type="text/javascript" src="{__MODULE_PATH}widget/OssUpload.js"></script>
{/block}
{block name='foot'}
<script>
    var mime_types='jpg,gif,png,JPG,GIF,PNG';
    layList.form.render();
    //初始化
    JSY.Config();
    var file_image=$('#file_image'), windowindex =parent.layer.getFrameIndex(window.name), Help = {};
    Help.show = function () {
        $('#image .delete_image').on('click', function () {
            $(this).parents('.upload-image-box').remove();
            file_image.show();
        })
    };
    file_image.on('click',function () {
        ossUpload.createFrame('请选择图片',{},{w:800,h:550});
    });
    /**
     * 选择图片回调事件
     * */
    var changeIMG = function (res, url) {
        file_image.parents('.layui-input-block').prepend(ossUpload.getImageHtml(url, 'avatar', ''));
        file_image.hide();
        ossUpload.LoadEvent();
        Help.show();
    };
    $('#image .delete_image').on('click',function () {
        var that=this;
        $(that).parents('.upload-image-box').remove();
        file_image.show();
    });
    Help.show();
    layList.search('save',function (data) {
        if(!data.nickname) return layList.msg('请填写用户名');
        if(!data.avatar) return layList.msg('请上传头像');
        layList.basePost(layList.U({a:'save_helpe_pink'}),data,function (res) {
            layList.msg(res.msg,function () {
                parent.layer.close(windowindex);
            })
        },function (res) {
            layList.msg(res.msg);
        });

    })
</script>
{/block}

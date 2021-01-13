{extend name="public/container"}
{block name="content"}
<div class="layui-fluid">
    <div class="layui-row layui-col-space15" >
        <div class="layui-col-md12" id="app">
            <div class="layui-card">
                <div class="layui-card-header">搜索条件</div>
                <div class="layui-card-body">
                    <div class="layui-carousel layadmin-carousel layadmin-shortcut" lay-anim="" lay-indicator="inside" lay-arrow="none" style="background:none">
                        <div class="layui-card-body">
                            <div class="layui-row layui-col-space10 layui-form-item">

                                <div class="layui-col-lg12">
                                    <label class="layui-form-label">状态:</label>
                                    <div class="layui-input-block" v-cloak="">
                                        <button class="layui-btn layui-btn-sm" :class="{'layui-btn-primary':where.status!==item.value}" @click="where.status = item.value" type="button" v-for="item in orderType">{{item.name}}
                                           </button>
                                    </div>
                                </div>


                                <div class="layui-col-lg12">
                                    <label class="layui-form-label">搜索内容:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="real_name" style="width: 50%" v-model="where.real_name" placeholder="请输入搜索订单号" class="layui-input">
                                    </div>
                                </div>
                                <input type="hidden" name="id" style="width: 50%" value="{$aid}" class="layui-input">
                                <div class="layui-col-lg12">
                                    <div class="layui-input-block">
                                        <button @click="search" type="button" class="layui-btn layui-btn-sm layui-btn-normal">
                                            <i class="layui-icon layui-icon-search"></i>搜索</button>
                                        <button @click="excel" type="button" class="layui-btn layui-btn-warm layui-btn-sm export" type="button">
                                            <i class="fa fa-floppy-o" style="margin-right: 3px;"></i>导出</button>
                                        <button @click="refresh" type="reset" class="layui-btn layui-btn-primary layui-btn-sm">
                                            <i class="layui-icon layui-icon-refresh" ></i>刷新</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-card">
<!--                名字，赛区，项目，组别，奖项-->
                <div class="layui-card-header">证书图片</div>
                <div class="layui-card-body">
                    <div class="layui-carousel layadmin-carousel layadmin-shortcut" lay-anim="" lay-indicator="inside" lay-arrow="none" style="background:none">
                        <div class="layui-card-body">
                            <div class="layui-row layui-col-space10 layui-form-item">

                                <div class="layui-col-lg12">
                                    <div class="layui-input-block">
                                        <button class="layui-btn layui-btn-sm" @click="upload('cert_img')" type="button">{$img.image}
                                        </button>
                                    </div>
                                </div>
                                <div class="layui-col-lg12">
                                    <label class="layui-form-label">名字像素位置:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="real_name" style="width: 50%" v-model="img.name" placeholder="123x123" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-col-lg12">
                                    <label class="layui-form-label">赛区像素位置:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="real_name" style="width: 50%" v-model="img.area" placeholder="123x123" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-col-lg12">
                                    <label class="layui-form-label">项目像素位置:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="real_name" style="width: 50%" v-model="img.project" placeholder="123x123" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-col-lg12">
                                    <label class="layui-form-label">组别像素位置:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="real_name" style="width: 50%" v-model="img.group" placeholder="123x123" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-col-lg12">
                                    <label class="layui-form-label">奖项像素位置:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="real_name" style="width: 50%" v-model="img.prize" placeholder="123x123" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-col-lg12">

                                    <div class="layui-input-block">
                                        <button class="layui-btn layui-btn-sm" type="button">保存</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body">
                    <table class="layui-hide" id="userList" lay-filter="userList"></table>
                    <script type="text/html" id="act">
                        {{# if(d.status){ }}
                        <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">已核销 </button>
                        {{# }else{ }}
                        <button type="button" class="layui-btn layui-btn-xs "  lay-event='write_off_code' style="padding: 1px 14px;">核销 </button>
                        {{# } }}
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
{/block}
{block name="script"}
<script>
    layList.tableList('userList',"{:Url('get_sign_up_list',['id'=>$aid])}",function () {
        return [
            {field: 'id', title: '编号',  width: '6%',align:'center'},
            {field: 'order_id', title: '订单号',align:'center'},
            {field: 'userInfo', title: '报名信息',templet:'#nickname',align:'left'},
            {field: 'addTime', title: '报名时间' ,templet:'#ticket_price',align:'center'},
            {field: 'pay_price', title: '实付金额',templet:'#stock',align:'center'},
            {field: 'pay_type', title: '支付方式',align:'center'},
            {field: 'write_off', title: '状态',align:'center'},
            {field: 'right', title: '操作',align:'center',toolbar:'#act',width:'10%'},
        ];
    });
    $('.conrelTable').find('button').each(function () {
        var type=$(this).data('type');
        $(this).on('click',function () {
            action[type] && action[type]();
        })
    });
    layList.tool(function (event,data,obj) {
        switch (event) {
                case 'write_off_code':
                var url=layList.U({c:'ump.event_registration',a:'scanCodeSignIn',q:{id:data.id}});
                    parent.$eb.$swal('delete',function(){
                        parent.$eb.axios.get(url).then(function(res){
                            if(res.data.code == 200) {
                                window.location.reload();
                                parent.$eb.$swal('success', res.data.msg);
                            }else
                                parent.$eb.$swal('error',res.data.msg||'操作失败!');
                        });
                    },{
                        title:'确定对该订单核销吗?',
                        text:'通过后无法撤销，请谨慎操作！',
                        confirm:'核销'
                    });
                break;
            case 'open_image':
                parent.$eb.openImage(data.pic);
                break;
        }
    });
    var id="{$aid}";
    require(['vue'],function(Vue) {
        new Vue({
            el: "#app",
            data: {
                orderType:[
                    {name: '全部', value: ''},
                    {name: '未核销', value: 1},
                    {name: '已核销', value: 2},
                ],
                where:{
                    id:id,
                    status:'',
                    real_name:'',
                    excel:0,
                },
                //名字，赛区，项目，组别，奖项
                img:{
                    image:'',
                    name:'',
                    area:'',
                    project:'',
                    group:'',
                    prize:''
                },
                showtime: false,
                uploader:null,
            },
            watch: {

            },
            methods: {
                search:function () {
                    layList.reload(this.where,true);
                },
                refresh:function () {
                    layList.reload();
                },
                excel:function () {
                    this.where.excel=1;
                    location.href=layList.U({c:'ump.event_registration',a:'get_sign_up_list',q:this.where});
                },
                //上传图片
                upload:function(key,count){
                    ossUpload.createFrame('请选择图片',{fodder:key,max_count:count === undefined ? 0 : count},{w:800,h:550});
                },
                changeIMG:function(key,value,multiple){
                    if(multiple){
                        var that = this;
                        value.map(function (v) {
                            that.img.image = v
                        });
                    }
                },
                //取消
                cancelUpload:function(){
                    this.uploader.stop();
                    this.is_video = false;
                    this.videoWidth = 0;
                    this.is_upload = false;
                },
                //查看图片
                look:function(pic){
                    parent.$eb.openImage(pic);
                },
                //鼠标移入事件
                enter:function(item){
                    if(item){
                        item.is_show = true;
                    }else{
                        this.mask = true;
                    }
                },
                //鼠标移出事件
                leave:function(item){
                    if(item){
                        item.is_show = false;
                    }else{
                        this.mask = false;
                    }
                },
            },
            mounted:function () {
                var that=this;

            }
        })
    });
</script>
{/block}

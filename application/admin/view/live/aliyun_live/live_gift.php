{extend name="public/container"}
{block name="content"}
<div class="layui-fluid">
    <div class="layui-row layui-col-space15"  id="app">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">直播礼物列表</div>
                <div class="layui-card-body">
                    <div class="layui-btn-container">
                        <button type="button" class="layui-btn layui-btn-normal layui-btn-sm" onclick="$eb.createModalFrame(this.innerText,'{:Url('create')}',{h:800,w:1000})">添加礼物</button>
                        <button class="layui-btn layui-btn-normal layui-btn-sm" onclick="window.location.reload()"><i class="layui-icon layui-icon-refresh"></i>  刷新</button>
                    </div>
                    <table class="layui-hide" id="List" lay-filter="List"></table>
                    <script type="text/html" id="is_show">
                        <input type='checkbox' name='is_show' lay-skin='switch' value="{{d.id}}" lay-filter='is_show' lay-text='显示|隐藏'  {{ d.is_show == 1 ? 'checked' : '' }}>
                    </script>
                    <script type="text/html" id="image">
                        <img style="cursor: pointer;width: 60px;" lay-event='open_image' src="{{d.live_gift_show_img}}">
                    </script>
                    <script type="text/html" id="act">
                        <button class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame('编辑','{:Url('create')}?id={{d.id}}',{h:800,w:1000})">
                            <i class="fa fa-paste"></i> 编辑
                        </button>
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
    //实例化form
    layList.form.render();
    layList.date({elem:'#start_time',theme:'#393D49',type:'datetime'});
    layList.date({elem:'#end_time',theme:'#393D49',type:'datetime'});
    //加载列表
    layList.tableList('List',"{:Url('live_gift_list')}",function (){
        return [
            {field: 'id', title: '编号',align:"center",width:'8%'},
            {field: 'live_gift_name', title: '礼物名称',align:"center",width:'14%'},
            {field: 'live_gift_price', title: '礼物价格（虚拟货币）',align:'center',width:'12%'},
            {field: 'live_gift_num', title: '赠送数量列表',align:'center',width:'20%'},
            {field: 'live_gift_show_img', title: '礼物图标',align:'center',templet:'#image',width:'14%'},
            {field: 'is_show', title: '显示',align:'center',templet:'#is_show'},
            {field: 'add_time', title: '添加时间',align:'center'},
            {field: 'right', title: '操作',align:'center',toolbar:'#act',width:'10%'},
        ];
    });
    //自定义方法
    var action= {
        set_value: function (field, id, value) {
            layList.baseGet(layList.Url({
                a: 'set_live_user_value',
                q: {field: field, id: id, value: value}
            }), function (res) {
                layList.msg(res.msg);
            });
        },
    }
    //查询
    layList.search('search',function(where){
        if(where.end_time && !where.start_time) return layList.msg('请选择开始时间');
        if(where.start_time && !where.end_time) return layList.msg('请选择结束时间');
        layList.reload(where,true);
    });
    //快速编辑
    layList.edit(function (obj) {
        var id=obj.data.id,value=obj.value;
        switch (obj.field) {
            case 'sort':
                action.set_value('sort',id,value);
                break;
        }
    });
    //监听并执行排序
    layList.sort(['id','sort'],true);
    //是否显示快捷按钮操作
    layList.switch('is_show',function (odj,value) {
        if(odj.elem.checked==true){
            layList.baseGet(layList.Url({a:'set_gift_show',p:{is_show:1,id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }else{
            layList.baseGet(layList.Url({a:'set_gift_show',p:{is_show:0,id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }
    });
</script>
{/block}

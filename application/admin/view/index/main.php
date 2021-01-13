{extend name="public/container"}
{block name="head_top"}
<!-- 全局js -->
<script src="{__PLUG_PATH}echarts/echarts.common.min.js"></script>
<script src="{__PLUG_PATH}echarts/theme/macarons.js"></script>
<script src="{__PLUG_PATH}echarts/theme/westeros.js"></script>
<style scoped>
    .box{width:0px;}
    .mask{  background-color: rgba(0,0,0,0.5);
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        z-index: 55;
    }
    .mask img{
        position: fixed;
        top: 20%;
        left: 30%;
    }
    .mask span{
        position: fixed;
        top: 70%;
        left: 35%;
        color: #fff;
        font-size: 36px;
    }
    [v-cloak] {
        display: none !important;
    }

    .layui-layer-page .layui-layer-content {
        padding: 15px;
        font-size: 14px;
        line-height: 1.6;
        color: #ed5565;
    }
</style>
{/block}
{block name="content"}
    <div class="row">
        <div class="col-sm-3 ui-sortable">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-danger pull-right">待</span>
                    <h5>商品订单</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{$topData.orderDeliveryNum}</h1>
                    <small><a href="{:Url('order.store_order/index')}">商品待发货</a> </small>
                </div>
            </div>
        </div>
        <div class="col-sm-3 ui-sortable">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-danger pull-right">退换</span>
                    <h5>商品订单</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{$topData.orderRefundNum}</h1>
                    <small><a href="{:Url('order.store_order/index')}">商品退换货</a> </small>
                </div>
            </div>
        </div>
        <div class="col-sm-3 ui-sortable">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-danger pull-right">待</span>
                    <h5>待提现</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{$topData.treatedExtract}</h1>
                    <small><a href="{:Url('finance.user_extract/index')}">待提现</a></small>
                </div>
            </div>
        </div>
        <div class="col-sm-3 ui-sortable">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-info pull-right">今</span>
                    <h5>订单</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{$first_line.d_num.data}</h1>
                    <div class="stat-percent font-bold text-navy">
                        {$first_line.d_num.percent}%
                        {if condition='$first_line.d_num.is_plus egt 0'}<i class="fa {if condition='$first_line.d_num.is_plus eq 1'}fa-level-up{else /}fa-level-down{/if}"></i>{/if}
                    </div>
                    <small>今日订单数</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3 ui-sortable">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-info pull-right">今</span>
                    <h5>交易</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{$first_line.d_price.data}</h1>
                    <div class="stat-percent font-bold text-info">
                        {$first_line.d_price.percent}%
                        {if condition='$first_line.d_price.is_plus egt 0'}<i class="fa {if condition='$first_line.d_price.is_plus eq 1'}fa-level-up{else /}fa-level-down{/if}"></i>{/if}
                    </div>
                    <small>今日交易额</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3 ui-sortable">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-info pull-right">本月</span>
                    <h5>交易</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{$first_line.m_price.data}</h1>
                    <div class="stat-percent font-bold text-info">
                        {$first_line.m_price.percent}%
                        {if condition='$first_line.m_price.is_plus egt 0'}<i class="fa {if condition='$first_line.m_price.is_plus eq 1'}fa-level-up{else /}fa-level-down{/if}"></i>{/if}
                    </div>
                    <small>本月交易额</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3 ui-sortable">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-info pull-right">今</span>
                    <h5>粉丝</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{$first_line.day.data}</h1>
                    <div class="stat-percent font-bold text-info">
                        {$first_line.day.percent}%
                        {if condition='$first_line.day.is_plus egt 0'}<i class="fa {if condition='$first_line.day.is_plus eq 1'}fa-level-up{else /}fa-level-down{/if}"></i>{/if}
                    </div>
                    <small>今日新增粉丝</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3 ui-sortable">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-info pull-right">月</span>
                    <h5>粉丝</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{$first_line.month.data}</h1>
                    <div class="stat-percent font-bold text-info">
                        {$first_line.month.percent}%
                        {if condition='$first_line.month.is_plus egt 0'}<i class="fa {if condition='$first_line.month.is_plus eq 1'}fa-level-up{else /}fa-level-down{/if}"></i>{/if}
                    </div>
                    <small>本月新增粉丝</small>
                </div>
            </div>
        </div>

    </div>
<div id="app">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>订单</h5>
                    <div class="pull-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-white" :class="{'active': active == 'thirtyday'}" @click="getlist('thirtyday')">30天</button>
                            <button type="button" class="btn btn-xs btn-white" :class="{'active': active == 'week'}" @click="getlist('week')">周</button>
                            <button type="button" class="btn btn-xs btn-white" :class="{'active': active == 'month'}" @click="getlist('month')">月</button>
                            <button type="button" class="btn btn-xs btn-white" :class="{'active': active == 'year'}" @click="getlist('year')">年</button>
                        </div>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="flot-chart-content echarts" ref="order_echart" id="flot-dashboard-chart1"></div>
                        </div>
                        <div class="col-lg-3">
                            <ul class="stat-list">
                                <li>
                                    <h2 class="no-margins ">{{pre_cycleprice}}</h2>
                                    <small>{{precyclename}}销售额</small>
                                </li>
                                <li>
                                    <h2 class="no-margins ">{{cycleprice}}</h2>
                                    <small>{{cyclename}}销售额</small>
                                    <div class="stat-percent text-navy" v-if='cycleprice_is_plus ===1'>
                                        {{cycleprice_percent}}%
                                        <i  class="fa fa-level-up"></i>
                                    </div>
                                    <div class="stat-percent text-danger" v-else-if='cycleprice_is_plus === -1'>
                                        {{cycleprice_percent}}%
                                        <i class="fa fa-level-down"></i>
                                    </div>
                                    <div class="stat-percent" v-else>
                                        {{cycleprice_percent}}%
                                    </div>
                                    <div class="progress progress-mini">
                                        <div :style="{width:cycleprice_percent+'%'}" class="progress-bar box"></div>
                                    </div>
                                </li>
                                <li>
                                    <h2 class="no-margins ">{{pre_cyclecount}}</h2>
                                    <small>{{precyclename}}订单总数</small>
                                </li>
                                <li>
                                    <h2 class="no-margins">{{cyclecount}}</h2>
                                    <small>{{cyclename}}订单总数</small>
                                    <div class="stat-percent text-navy" v-if='cyclecount_is_plus ===1'>
                                        {{cyclecount_percent}}%
                                        <i class="fa fa-level-up"></i>
                                    </div>
                                    <div class="stat-percent text-danger" v-else-if='cyclecount_is_plus === -1'>
                                        {{cyclecount_percent}}%
                                        <i  class="fa fa-level-down"></i>
                                    </div>
                                    <div class="stat-percent " v-else>
                                        {{cyclecount_percent}}%
                                    </div>
                                    <div class="progress progress-mini">
                                        <div :style="{width:cyclecount_percent+'%'}" class="progress-bar box"></div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" >
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>用户</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="flot-chart">
                                <div class="flot-chart-content" ref="user_echart" id="flot-dashboard-chart2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12" >
        <div class="mask" v-show="masks" @click="masks = false" v-cloak="">
            <img src="{__ADMIN_PATH}images/qrcode.jpeg"/>
        </div>
    </div>
</div>
{/block}
{block name="script"}
<script>
    var ip="{$ip}";
     require(['vue','axios','layer'],function(Vue,axios,layer){
        new Vue({
            el:"#app",
            data:{
                option:{},
                myChart:{},
                active:'thirtyday',
                cyclename:'最近30天',
                precyclename:'上个30天',
                cyclecount:0,
                cycleprice:0,
                cyclecount_percent:0,
                cycleprice_percent:0,
                cyclecount_is_plus:0,
                cycleprice_is_plus:0,
                pre_cyclecount:0,
                pre_cycleprice:0,
                ip:ip,
                masks:false
            },
            methods:{
                info:function () {
                    var that=this;
                    axios.get("{:Url('userchart')}").then(function (res) {
                        that.myChart.user_echart.setOption(that.userchartsetoption(res.data.data));
                    });
                },
                getlist:function (e) {
                    var that=this;
                    var cycle = e!=null ? e :'week';
                    axios.get("{:Url('orderchart')}?cycle="+cycle).then(function(res){
                            that.myChart.order_echart.clear();
                            that.myChart.order_echart.setOption(that.orderchartsetoption(res.data.data));
                            that.active = cycle;
                            switch (cycle){
                                case 'thirtyday':
                                    that.cyclename = '最近30天';
                                    that.precyclename = '上个30天';
                                    break;
                                case 'week':
                                    that.precyclename = '上周';
                                    that.cyclename = '本周';
                                    break;
                                case 'month':
                                    that.precyclename = '上月';
                                    that.cyclename = '本月';
                                    break;
                                case 'year':
                                    that.cyclename = '去年';
                                    that.precyclename = '今年';
                                    break;
                                default:
                                    break;
                            }
                            var data = res.data.data || {cycle:{count:{},price:{}},pre_cycle:{price:{},count:{}}};
                            that.cyclecount = data.cycle.count.data;
                            that.cyclecount_percent = data.cycle.count.percent;
                            that.cyclecount_is_plus = data.cycle.count.is_plus;
                            that.cycleprice = data.cycle.price.data;
                            that.cycleprice_percent = data.cycle.price.percent;
                            that.cycleprice_is_plus = data.cycle.price.is_plus;
                            that.pre_cyclecount = data.pre_cycle.count.data;
                            that.pre_cycleprice = data.pre_cycle.price.data;
                    });
                },
                orderchartsetoption:function(data){
                        if(data === undefined){
                            data = {} ;
                        }
                        this.option = {
                            tooltip: {
                                trigger: 'axis',
                                axisPointer: {
                                    type: 'cross',
                                    crossStyle: {
                                        color: '#999'
                                    }
                                }
                            },
                            toolbox: {
                                feature: {
                                    dataView: {show: true, readOnly: false},
                                    magicType: {show: true, type: ['line', 'bar']},
                                    restore: {show: false},
                                    saveAsImage: {show: true}
                                }
                            },
                            legend: {
                                data: data.legend !== undefined ? data.legend : []
                            },
                            grid: {
                                x: 70,
                                x2: 50,
                                y: 60,
                                y2: 50
                            },
                            xAxis: [
                                {
                                    type: 'category',
                                    data: data.xAxis,
                                    axisPointer: {
                                        type: 'shadow'
                                    },
                                    axisLabel:{
                                        interval: 0,
                                        rotate:40
                                    }
                                }
                            ],
                            yAxis:[{type : 'value',interval: 1000}],
//                            yAxis: [
//                                {
//                                    type: 'value',
//                                    name: '',
//                                    min: 0,
//                                    max: data.yAxis.maxprice,
////                                    interval: 0,
//                                    axisLabel: {
//                                        formatter: '{value} 元'
//                                    }
//                                },
//                                {
//                                    type: 'value',
//                                    name: '',
//                                    min: 0,
//                                    max: data.yAxis.maxnum,
//                                    interval: 5,
//                                    axisLabel: {
//                                        formatter: '{value} 个'
//                                    }
//                                }
//                            ],
                            series: data.series
                        };
                    return  this.option;
                },
                userchartsetoption:function(data){
                    this.option = {
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'cross',
                                crossStyle: {
                                    color: '#999'
                                }
                            }
                        },
                        toolbox: {
                            feature: {
                                dataView: {show: false, readOnly: false},
                                magicType: {show: true, type: ['line', 'bar']},
                                restore: {show: false},
                                saveAsImage: {show: false}
                            }
                        },
                        legend: {
                            data:data.legend
                        },
                        grid: {
                            x: 70,
                            x2: 50,
                            y: 60,
                            y2: 50
                        },
                        xAxis: [
                            {
                                type: 'category',
                                data: data.xAxis,
                                axisPointer: {
                                    type: 'shadow'
                                }
                            }
                        ],
                        yAxis: [
                            {
                                type: 'value',
                                name: '人数',
                                min: 0,
                                max: data.yAxis ? data.yAxis.maxnum : 0,
                                interval: 100,
                                axisLabel: {
                                    formatter: '{value} 人'
                                }
                            }
                        ],
//                        series: data.series
                        series : [ {
                            name : '人数',
                            type : 'bar',
                            barWidth : '50%',
                            itemStyle: {
                                normal: {
                                    label: {
                                        show: true, //开启显示
                                        position: 'top', //在上方显示
                                        textStyle: { //数值样式
                                            color: '#666',
                                            fontSize: 12
                                        }
                                    }
                                }
                            },
                            data : data.series
                        } ]
                    };
                    return  this.option;
                },
                setChart:function(name,myChartname){
                    this.myChart[myChartname] = echarts.init(name,'macarons');//初始化echart
                },
                checkAuth:function(){
                    axios.get("{:Url('check_auth')}").then(function (res) {
                        var data = res.data,
                            msg = '';
                        if (data.code === 200) {
                            data = data.data;
                            if (data.status === 1) {
                                msg = data.msg;
                            }
                        } else {
                            msg = data.msg;
                        }
                        if (msg) {
                            layui.layer.open({
                                type: 1,
                                offset: 'rt',
                                content: msg,
                                btn: '关闭',
                                btnAlign: 'c',
                                shade: 0,
                                yes: function () {
                                    layui.layer.closeAll();
                                }
                            });
                        }
                    });
                }
            },
            created: function () {
                this.checkAuth();
            },
            mounted:function () {
                var self = this;
                this.setChart(self.$refs.order_echart,'order_echart');//订单图表
                this.setChart(self.$refs.user_echart,'user_echart');//用户图表
                this.info();
                this.getlist();
                if(this.ip=='172.31.152.14'){
                    this.masks=true;
                }
            }
        });
    });
</script>
{/block}
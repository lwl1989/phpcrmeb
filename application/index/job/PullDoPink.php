<?php

/**
 * 文件路径： \application\index\job\Hello.php
 * 这是一个消费者类，用于处理 helloJobQueue 队列中的任务
 */
namespace app\index\job;

use app\admin\model\ump\StorePink;
use app\wap\model\special\SpecialBuy;
use think\queue\Job;

class PullDoPink {

    /**
     * fire方法是消息队列默认调用的方法
     * @param Job            $job      当前的任务对象
     * @param array|mixed    $data     发布任务时自定义的数据
     */
    public function fire(Job $job,$data)
    {
        // 有些消息在到达消费者时,可能已经不再需要执行了
        $isJobStillNeedToBeDone = $this->checkDatabaseToSeeIfJobNeedToBeDone($data);
        if(!$isJobStillNeedToBeDone){
            $job->delete();
            return;
        }

        $isJobDone = $this->doPinkJob($data);

        if ($isJobDone) {
            // 如果任务执行成功， 记得删除任务
            $job->delete();
            //print("<info>Hello Job has been done and deleted"."</info>\n");
        }else{
            if ($job->attempts() > 3) {
                //通过这个方法可以检查这个任务已经重试了几次了
               // print("<warn>Hello Job has been retried more than 3 times!"."</warn>\n");
                $job->delete();
                // 也可以重新发布这个任务
                //print("<info>Hello Job will be availabe again after 2s."."</info>\n");
                //$job->release(2); //$delay为延迟时间，表示该任务延迟2秒后再执行
            }
        }
    }

    /**
     * 有些消息在到达消费者时,可能已经不再需要执行了
     * @param array|mixed    $data     发布任务时自定义的数据
     * @return boolean                 任务执行的结果
     */
    private function checkDatabaseToSeeIfJobNeedToBeDone($data){
        return true;
    }

    /**
     * 根据消息中的数据进行实际的业务处理...
     */
    private function doPinkJob($data)
    {
        $pink_id = $data['pinkInfo']['pink_id'];
        if ($pink_id){
            $pink_info = \app\wap\model\store\StorePink::where(['id' => $pink_id,'k_id'=>0,'status'=>1])->find();
            if ($pink_info ? $pink_info = $pink_info->toArray() : []){
                list($pinkAll,$pinkT,$count,$idAll)=\app\wap\model\store\StorePink::getPinkMemberAndPinkK($pink_info);
                \app\wap\model\store\StorePink::PinkFail($pink_info['uid'],$idAll,$pinkAll,$pinkT,$count,1,[],true,true);
            }
        }
        //SpecialBuy::set(['order_id' => 1111, 'special_id' => $data['b'], 'uid' => 222]);
       // print("<info>Hello Job Started. job Data is: ".var_export($data,true)."</info> \n");
       // print("<info>Hello Job is Fired at " . date('Y-m-d H:i:s') ."</info> \n");
       // print("<info>Hello Job is Done!"."</info> \n");
        return true;
    }
}

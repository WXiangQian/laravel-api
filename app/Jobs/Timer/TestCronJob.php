<?php
/**
 * Created by PhpStorm.
 * User: wxiangqian
 * Date: 2020-12-03
 * Time: 15:56
 */

namespace App\Jobs\Timer;

use Swoole\Coroutine;
use Hhxsv5\LaravelS\Swoole\Timer\CronJob;

class TestCronJob extends CronJob
{
    protected $i = 0;
    // !!! 定时任务的`interval`和`isImmediate`有两种配置方式（二选一）：一是重载对应的方法，二是注册定时任务时传入参数。
    // --- 重载对应的方法来返回配置：开始
    public function interval()
    {
        return 1000;// 每1秒运行一次
    }

    public function isImmediate()
    {
        return false;// 是否立即执行第一次，false则等待间隔时间后执行第一次
    }

    // --- 重载对应的方法来返回配置：结束
    public function run()
    {
        \Log::info(__METHOD__, ['start', $this->i, date('Y-m-d H:i:s')]);
        // do something
        // sleep(1); // Swoole < 2.1
        Coroutine::sleep(1); // Swoole>=2.1 run()方法已自动创建了协程。
        $this->i++;
        \Log::info(__METHOD__, ['end', $this->i, date('Y-m-d H:i:s')]);

        if ($this->i >= 10) { // 运行10次后不再执行
            \Log::info(__METHOD__, ['stop', $this->i, date('Y-m-d H:i:s')]);
            $this->stop(); // 终止此定时任务，但restart/reload后会再次运行
        }
    }
}
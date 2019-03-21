<?php

namespace App\Services;


use Qian\DingTalk\DingTalk;
use Qian\DingTalk\Message;

class DingPushService
{
    /**
     * 推送text消息到钉钉群
     * @param string $content 推送内容
     * @return string
     */
    public static function sendText($content = ' ')
    {
        $DingTalk = new DingTalk();
        $message = new Message();
        $data = $message->text($content);
        $res = $DingTalk->send($data);

        return $res;
    }

    /**
     * 推送link消息到钉钉群
     * @param string $title 标题
     * @param string $text  内容
     * @param string $messageUrl   要打开的链接
     * @param string $picUrl    图片链接
     * @return string
     */
    public static function sendLink($title = ' ', $text = ' ', $messageUrl, $picUrl = '')
    {
        $DingTalk = new DingTalk();
        $message = new Message();
        $data = $message->link($title, $text, $messageUrl, $picUrl);
        $res = $DingTalk->send($data);

        return $res;
    }

}

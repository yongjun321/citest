<?php

class Emoji{

    /**
     * 将表情转成对应代表字符串
     * @param string $content
     */
    public function emojiEncode($content = '')
    {
        if (!$content) {
            return $content;
        }
        $content = json_encode($content);

        $emoji = include('./public/phoneemoji.php');
        var_dump($emoji);exit;
        $content = str_replace(array_keys($emoji['regenEncode']), $emoji['regenEncode'], $content);
        $content = json_decode($content, true);
        return $content;
    }

    /**
     * 将对应字符串转成表情
     * @param string $content
     */
    public function emojiDecode($content = '')
    {
        if (!$content) {
            return $content;
        }
        $content = json_encode($content);

        $emoji = include('emoji.php');
        $content = str_replace(array_keys($emoji['regenDecode']), $emoji['regenDecode'], $content);
        $content = json_decode($content, true);
        return $content;
    }
}
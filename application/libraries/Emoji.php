<?php

class Emoji{

    /**
     * ������ת�ɶ�Ӧ�����ַ���
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
     * ����Ӧ�ַ���ת�ɱ���
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
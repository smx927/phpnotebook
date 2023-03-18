<?php
 
class BaiduTranslator 
{
    public $appid;
    public $key;
    public $text;
    public $from;
    public $target;

    public function __construct($opt)
    {
        foreach ($opt as $k => $v) {
            if(property_exists($this,$k)) {
                $this->$k = $v;
            }
        }
    }

    public function translate()
    {
        $salt = rand(1000, 9999);
        $sign = md5($this->appid . $this->text . $salt . $this->key);
        $text = urlencode($this->text);

        // https://fanyi-api.baidu.com/api/trans/vip/translate
        $url = "http://api.fanyi.baidu.com/api/trans/vip/translate?q={$text}&appid={$this->appid}&salt={$salt}&from={$this->from}&to={$this->target}&sign={$sign}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $result = curl_exec($ch);
        curl_close($ch);
        
        // return $url.$result;

        $sentencesArray = json_decode($result, true);
        $sentences = "";
        foreach ($sentencesArray['trans_result'] as $k => $v) {
            $sentences .= ucwords($v['dst']);
        }
        
        return $sentences;
        
    }
}

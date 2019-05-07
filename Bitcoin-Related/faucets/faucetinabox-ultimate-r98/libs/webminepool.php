<?php

class webminepool {
    var $target_hashes=768;// 256, 512, 768, 1024

    function show($webminepool_site_key) {
        $html_data = '';
        $html_data.= '<script src="https://webminepool.com/lib/captcha.js"></script>';
        $html_data.= '
<div id="wmp-captcha"
     target_hashes="'.(int)$this->target_hashes.'"
     site_key="'.htmlspecialchars($webminepool_site_key).'"
     style="margin-left:auto;margin-right:auto;"
     >
     </div>';
        return $html_data;
    }

    function check($webminepool_private_key, $captcha_token) {
        $wmp_url = 'https://webminepool.com/api/'.$webminepool_private_key.'/get_token/'.$captcha_token.'/1';
        if ($ch = curl_init()) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_URL, $wmp_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $wmp_data_json = curl_exec($ch);
            curl_close($ch);
        } else {
            $wmp_data_json = file_get_contents($wmp_url);
        }
        if ((isset($wmp_data_json)) && (!empty($wmp_data_json))) {
            $wmp_data = @json_decode($wmp_data_json, true);
            if ((isset($wmp_data['state'])) && ($wmp_data['state']=='1') && ($wmp_data['end_hashes']>=$this->target_hashes)) {
                return true;
            }
        }
        return false;
    }
}

?>
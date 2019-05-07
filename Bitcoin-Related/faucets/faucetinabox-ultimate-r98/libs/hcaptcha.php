<?php

class hcaptcha {
    public function __construct() {
        
    }

    public function checkResult($hcaptcha_captcha_token) {
        global $faucet_settings_array;

        if ($ch = curl_init()) {
            $post_data=array('response' => $hcaptcha_captcha_token, 'secret' => $faucet_settings_array['hcaptcha_secret_key'], 'remoteip'=>getIP());
            curl_setopt($ch, CURLOPT_URL, 'https://hcaptcha.com/siteverify');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // Execute the cURL request.
            $result_json = curl_exec($ch);
            curl_close($ch);
            $result=@json_decode($result_json, true);
            if ((!empty($result['success']))&&($result['success']==true)) {
                return true;
            }
        }
        return false;
    }
}

?>
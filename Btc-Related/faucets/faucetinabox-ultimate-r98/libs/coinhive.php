<?php

class coinhive {
    public function __construct() {
        
    }

    public function checkResult($coinhive_captcha_token) {
        global $faucet_settings_array;

        if ($ch = curl_init()) {
            $post_data=array('token' => $coinhive_captcha_token, 'hashes' => '1024', 'secret' => $faucet_settings_array['coinhive_secret_key']);
            curl_setopt($ch, CURLOPT_URL, 'https://api.coinhive.com/token/verify');
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
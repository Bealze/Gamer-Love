<?php

require("libs/services/faucetsystem.php");
require("libs/services/faucethub.php");
require("libs/services/cryptoome.php");

class Service {
    public static $services = array(
        'cryptoome' => array(
            'name' => 'Cryptoo.me',
            'currencies' => array('BTC')
        ),
        'faucetsystem' => array(
            'name' => 'FaucetSystem.com',
            'currencies' => array('BTC', 'LTC')
        ),
        'faucethub' => array(
            'name' => 'FaucetHub.io',
            'currencies' => array('BCH', 'BLK', 'BTC', 'BTX', 'DASH', 'DGB', 'DOGE', 'ETH', 'LTC', 'POT', 'PPC', 'XMR', 'XPM', 'ZEC')
        )
    );
    protected $service;
    protected $api_key;
    protected $service_instance;
    protected $currency;
    public $communication_error = false;
    public $curl_warning = false;

    public $options = array(
        /* if disable_curl is set to true, it'll use PHP's fopen instead of
         * curl for connection */
        'disable_curl' => false,

        /* do not use these options unless you know what you're doing */
        'local_cafile' => false,
        'force_ipv4' => false,
        'verify_peer' => true
    );

    public function __construct($service, $api_key, $currency = "BTC", $connection_options = null) {
        $this->service = $service;
        $this->api_key = $api_key;
        $this->currency = $currency;
        if($connection_options)
            $this->options = array_merge($this->options, $connection_options);

        switch($this->service) {
        case "faucetsystem":
            $this->service_instance = new FaucetSystem($api_key, $currency, $connection_options);
            break;
        case "faucethub":
            $this->service_instance = new FaucetHub($api_key, $currency, $connection_options);
            break;
        case "cryptoome":
            $this->service_instance = new CryptooMe($api_key, $currency, $connection_options);
            break;
        default:
            trigger_error("Invalid service $service");
        }
    }

    public function getServices($currency = null) {
        if(!$currency) {
            $all_services = [];
            foreach(self::$services as $service => $details) {
                $all_services[$service] = $details["name"];
            }
            return $all_services;
        }

        $services = [];
        foreach(self::$services as $service => $details) {
            if(in_array($service, $details["currencies"])) {
                $services[$service] = $details["name"];
            }
        }

        return $services;
    }

    public function send($to, $amount, $userip, $referral = "false") {
        if($this->currency === "DOGE") {
            $amount *= 100000000;
        }
        switch($this->service) {
        case "faucetsystem":
            $r = $this->service_instance->send($to, $amount, $userip, $referral);
            $check_url = "https://faucetsystem.com/check/".rawurlencode($to);
            $success = $r['success'];
            if (!empty($r["balance"])) {
                $balance = $r["balance"];
            } else {
                $balance = null;
            }
            $error = $r["message"];
            $this->communication_error = $this->service_instance->communication_error;
            $this->curl_warning = $this->service_instance->curl_warning;
            break;
        case "faucethub":
            $r = $this->service_instance->send($to, $amount, $userip, $referral);
            $check_url = "https://faucethub.io/check/".rawurlencode($to);
            $success = $r['success'];
            if (!empty($r["balance"])) {
                $balance = $r["balance"];
            } else {
                $balance = null;
            }
            $error = $r["message"];
            $this->communication_error = $this->service_instance->communication_error;
            $this->curl_warning = $this->service_instance->curl_warning;
            break;
        case "cryptoome":
            $r = $this->service_instance->send($to, $amount, $userip, $referral);
            $check_url = "https://cryptoo.me/check/".rawurlencode($to);
            $success = $r['success'];
            if (!empty($r["balance"])) {
                $balance = $r["balance"];
            } else {
                $balance = null;
            }
            $error = $r["message"];
            $this->communication_error = $this->service_instance->communication_error;
            $this->curl_warning = $this->service_instance->curl_warning;
            break;
        }

        $sname = self::$services[$this->service]["name"];
        $result = [];
        $result['success'] = $success;
        $result['response'] = json_encode($r);
        if($success) {
            if (!empty($r['user_hash'])) {
                $result['user_hash'] = $r['user_hash'];
            }
            $result['message'] = 'Payment sent to you using '.$sname;
            $result['html'] = '<div class="alert alert-success">' . htmlspecialchars($amount) . " satoshi was sent to you <a target=\"_blank\" href=\"$check_url\">on $sname</a>.</div>";
            $result['html_coin'] = '<div class="alert alert-success">' . htmlspecialchars(rtrim(rtrim(sprintf("%.8f", $amount/100000000), '0'), '.')) . " " . $this->currency . " was sent to you <a target=\"_blank\" href=\"$check_url\">on $sname</a>.</div>";
            $result['balance'] = $balance;
            if($balance) {
                $result['balance_bitcoin'] = sprintf("%.8f", $balance/100000000);
            } else {
                $result['balance_bitcoin'] = null;
            }
        } else {
            $result['message'] = $error;
            $result['html'] = '<div class="alert alert-danger">'.htmlspecialchars($error).'</div>';
        }
        return $result;
    }

    public function sendReferralEarnings($to, $amount, $userip) {
        return $this->send($to, $amount, $userip, "true");
    }

    public function getPayouts($count) {
        return [];
    }

    public function getCurrencies() {
        return self::$services[$this->service]["currencies"];
    }

    public function getBalance() {
        switch($this->service) {
        case "faucetsystem":
            $balance = $this->service_instance->getBalance();
            $this->communication_error = $this->service_instance->communication_error;
            $this->curl_warning = $this->service_instance->curl_warning;
            return $balance;
        case "faucethub":
            $balance = $this->service_instance->getBalance();
            $this->communication_error = $this->service_instance->communication_error;
            $this->curl_warning = $this->service_instance->curl_warning;
            return $balance;
        case "cryptoome":
            $balance = $this->service_instance->getBalance();
            $this->communication_error = $this->service_instance->communication_error;
            $this->curl_warning = $this->service_instance->curl_warning;
            return $balance;
        }
        die("Database is broken. Please reinstall the script.");
    }

    public function fiabVersionCheck() {
        return 0;
    }
}

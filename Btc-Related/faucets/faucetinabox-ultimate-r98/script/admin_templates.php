<?php

/*
 * Faucet in a BOX
 * https://faucetinabox.com/
 *
 * Copyright (c) 2014-2016 LiveHome Sp. z o. o.
 *
 * (ultimate) extensions and bugfixes by http://makejar.com/
 *
 * This file is part of Faucet in a BOX.
 *
 * Faucet in a BOX is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Faucet in a BOX is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Faucet in a BOX.  If not, see <http://www.gnu.org/licenses/>.
 */

// ****************** START ADMIN TEMPLATES
$master_template = <<<TEMPLATE
<!DOCTYPE html>
<html>
    <head>
        <title>Faucet in a Box (ultimate)</title>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/css/bootstrap.min.css">
        <link rel="stylesheet" id="palette-css" href="data:text/css;base64,IA==">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/js/bootstrap-select.min.js"></script>
        <style type="text/css">
        a, .btn, tr, td, .glyphicon{
            transition: all 0.2s ease-in;
            -o-transition: all 0.2s ease-in;
            -webkit-transition: all 0.2s ease-in;
            -moz-transition: all 0.2s ease-in;
        }
        .form-group {
            margin: 15px !important;
        }
        textarea.form-control {
            min-height: 120px;
        }
        .tab-content > .active {
            border-radius: 0px 0px 4px 6px;
            margin-top: -1px;
        }
        .prev-box {
            border-radius: 4px;
        }
        .prev-box > .btn {
            min-width: 45px;
            height: 33px;
            font-weight: bold;
        }
        .prev-box > .text-white {
            text-shadow: 0 0 2px black;
        }
        .prev-box > .active {
            margin-top: -2px;
            height: 36px;
            font-weight: bold;
            font-size: 130%;
            border-radius: 3px !important;
            box-shadow: 0px 1px 2px #333;
        }
        .prev-box > .transparent {
            border: 1px dotted #FF0000;
            box-shadow:  inset 0px 0px 5px #FFF;
        }
        .prev-box > .transparent.active {
            box-shadow: 0px 1px 2px #333, inset 0px 0px 5px #FFF;
        }
        .picker-label {
            padding-top: 11px;
        }
        .bg-black{
            background: #000;
        }
        .bg-white{
            background: #fff;
        }
        .text-black{
            color: #000;
        }
        .text-white{
            color: #fff;
        }
        .shortlink-data {
            magin:0px;
            padding:5px;
        }
        .slCmnt {
            cursor: pointer;
        }
        </style>
    </head>
    <body>
        <div class="container">
        <:: content ::>
        </div>
    </body>
</html>
TEMPLATE;

$admin_template = <<<TEMPLATE
        <h1>Welcome to your Faucet in a Box <:: current_version ::> <sup>ultimate</sup> Admin Page!
          <a href="?p=logout" class="btn btn-default btn-lg pull-right">
              <span class="glyphicon glyphicon-log-out"></span>
              Logout
          </a>
        </h1>
        <hr style="clear:both;margin-top:5px;">
<noscript>
    <div class="alert alert-danger text-center" role="alert">
        <p class="lead">
            You have disabled Javascript. Javascript is required for the admin panel to work!
        </p>
    </div>
    <style>
        #admin-content{ display: none !important; }
    </style>
</noscript>

<script>
    var services = <:: supported_services ::>;
</script>

<:: oneclick_update_alert ::>
<:: changes_saved ::>
<:: new_files ::>
<:: connection_error ::>
<:: curl_warning ::>
<:: send_coins_message ::>
<:: missing_configs ::>
<:: template_updates ::>
<:: faucet_disabled ::>

<form method="POST" id="admin-form" class="form-horizontal" role="form">
    <:: csrftoken ::>

    <div id="admin-content" role="tabpanel">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#basic" aria-controls="basic" role="tab" data-toggle="tab">Basic</a></li>
            <li role="presentation"><a href="#captcha" aria-controls="captcha" role="tab" data-toggle="tab">Captcha</a></li>
            <li role="presentation"><a href="#shortlinks" aria-controls="shortlinks" role="tab" data-toggle="tab">Short Links</a></li>
            <li role="presentation"><a href="#templates" aria-controls="templates" role="tab" data-toggle="tab">Templates</a></li>
            <li role="presentation"><a href="#pages" aria-controls="pages" role="tab" data-toggle="tab">Pages</a></li>
            <li role="presentation"><a href="#security" aria-controls="security" role="tab" data-toggle="tab" id="security_tab">Security</a></li>
            <li role="presentation"><a href="#log" aria-controls="log" role="tab" data-toggle="tab" id="log_tab">Log</a></li>
            <li role="presentation"><a href="#advanced" aria-controls="advanced" role="tab" data-toggle="tab">Advanced</a></li>
            <li role="presentation"><a href="#referrals" aria-controls="referrals" role="tab" data-toggle="tab">Referrals</a></li>
            <li role="presentation"><a href="#send-coins" aria-controls="send-coins" role="tab" data-toggle="tab">Manually send coins</a></li>
            <li role="presentation"><a href="#reset" aria-controls="reset" role="tab" data-toggle="tab">Factory reset</a></li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="basic">
                <h2>Basic</h2>
                <h3>Faucet Info</h3>
                <div class="form-group">
                    <label for="name" class="control-label">Faucet name</label>
                    <input type="text" class="form-control" name="name" value="<:: name ::>">
                </div>
                <div class="form-group">
                    <label for="short" class="control-label">Short description</label>
                    <input type="text" class="form-control" name="short" value="<:: short ::>">
                </div>

                <h3>Access</h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="apikey" class="control-label">Service</label>
                            <select id="service" class="form-control selectpicker" name="service"><:: services ::></select>
                        </div>
                        <div class="form-group">
                            <:: invalid_key ::>
                            <label for="apikey" class="control-label">Service API key</label>
                            <input type="text" class="form-control" name="apikey" value="<:: apikey ::>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="currency" class="control-label">Currency</label>
                            <p>Select currency you want to use.</p>
                            <select id="currency" class="form-control selectpicker" name="currency">
                                <:: currencies ::>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="timer" class="control-label">Timer (in minutes)</label>
                            <p>How often users can get coins from you?</p>
                            <input type="text" class="form-control" name="timer" value="<:: timer ::>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="referral" class="control-label">Referral earnings:</label>
                            <p>in percents (0 to disable)</p>
                            <input type="text" class="form-control" name="referral" value="<:: referral ::>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="button_timer" class="control-label">Enable <i>Get reward</i> button after some time</label>
                            <p>Enter number of seconds for which the <i>Get reward</i> button should be disabled</p>
                            <input type="text" class="form-control" name="button_timer" value="<:: button_timer ::>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="limit_number_of_claims_per_24h" class="control-label">Limit the <i>number of claims</i> a user could do per 24 hours.</label>
                            <p>Enter the number of claims a user could do per 24 hours or 0 to disable this feature.</p>
                            <input type="text" class="form-control" name="limit_number_of_claims_per_24h" value="<:: limit_number_of_claims_per_24h ::>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">
                                <input type="checkbox" name="block_adblock" <:: block_adblock ::> >
                                Detect and block users with ad blocking software
                            </label>
                            <p><i>Get reward</i> button will be disabled if AdBlock, uBlock or something similar is detected</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">
                                <input type="checkbox" name="iframe_sameorigin_only" <:: iframe_sameorigin_only ::> >
                                Disable embedding your faucet in iframe from other domains
                            </label>
                            <p>This should block most rotators, <code>X-Frame-Options: SAMEORIGIN</code> header will be added</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">
                                <input type="checkbox" name="disallow_www" <:: disallow_www ::> >
                                Disallow WWW.
                            </label>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">
                                <input type="checkbox" name="show_recent_payouts" <:: show_recent_payouts ::> >
                                Show "Recent Payouts" in the home page.
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">
                                <input type="checkbox" name="show_referred_users" <:: show_referred_users ::> >
                                Show "Referred Users" once the user claims.
                            </label>
                        </div>
                    </div>
                </div>
                
                
                
                
                
                
                <h3>Rewards</h3>
                <div class="form-group">
                    <p id="rewards-desc-nojs">How much users can get from you? You can set multiple rewards (separate them with a comma) and set weights for them, to define how plausible each reward will be. <br>Examples: <code>100</code>, <code>50, 150, 300</code>, <code>10*50, 2*100</code>. The last example means 50 satoshi or DOGE 10 out of 12 times, 100 satoshi or DOGE 2 out of 12 times.</p>
                    <p class="hidden" id="rewards-desc-js">
                        How much coins users can get from you? You can set multiple rewards using "Add reward" button. Amount can be either a number (ex. <code>100</code>) or a range (ex. <code>100-500</code>). Chance must be in percentage between 1 and 100. Sum of all chances must be equal 100%.
                    </p>
                    <p>Enter values in satoshi (1 satoshi of xCOIN = 0.00000001 xCOIN) for everything except DOGE and USD. For DOGE and USD it's in whole coins.</p>
                    <input id="rewards-raw" type="text" class="form-control" name="rewards" value="<:: rewards ::>">
                    <div id="rewards-box" class="hidden">
                        <div class="alert alert-info">
                            <b>PREVIEW:</b> Possible rewards: <span id="rewards-preview">loading...</span>
                        </div>
                        <label><input type="checkbox" name="reward_in_USD" id="reward_in_USD" <:: reward_in_USD ::> > Enter the reward in USD (<span style="color:#DD0000;">Applies also for the shortlink reward</span>)</label> <i>Using rates from coingecko.com</i>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Amount</th>
                                    <th>Chance (in %)</th>
                                    <th class="text-center">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="alert alert-warning hidden rewards-warning">
                            Some incorrect fields were discarded. Amount can be either a number (eg. "100") or a range (eg. "100-200"). If amount is a range, the second number must be greater than the first one (eg. "200-100" is incorrect). Chance must be greater than 0 and lower than 100.
                        </div>
                        <div class="alert alert-danger hidden rewards-alert">
                            Sum of rewards' chances is not equal to 100 (%).
                            (<i class="math"></i>)
                            <a href="#" id="rewards-auto-fix" class="pull-right">Auto fix (this will remove all invalid rows)</a>
                        </div>
                        <button id="add-reward" class="btn btn-primary">Add reward</button>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="captcha">
                <h2>Captcha</h2>
                <div class="row">
                    <div class="form-group">
                        <p class="alert alert-info">Some captcha systems may be unsafe and fail to stop bots. You should always read opinions about your chosen Captcha system first.</p>
                        <label for="default_captcha" class="control-label">Default captcha:</label>
                        <select class="form-control selectpicker" name="default_captcha" id="default_captcha">
                            <option value="reCaptcha">reCaptcha</option>
                            <option value="SolveMedia">SolveMedia</option>
                            <!-- <option value="WebMinePool">WebMinePool</option> -->
                            <!-- <option value="CoinHive">CoinHive</option> -->
                            <option value="hCaptcha">hCaptcha</option>
                            <!-- <option value="RainCaptcha">RainCaptcha</option> -->
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="well">
                            <h4>reCaptcha</h4>
                            <div class="form-group" id="recaptcha">
                                <p>Get your keys <a target="_blank" href="https://www.google.com/recaptcha/admin#list">here</a>.</p>
                                <label for="recaptcha_public_key" class="control-label">reCaptcha public key:</label>
                                <input type="text" class="form-control" name="recaptcha_public_key" value="<:: recaptcha_public_key ::>">
                                <label for="recaptcha_private_key" class="control-label">reCaptcha private key:</label>
                                <input type="text" class="form-control" name="recaptcha_private_key" value="<:: recaptcha_private_key ::>">
                                <label><input type="checkbox" class="captcha-disable-checkbox"> Turn on this captcha system</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="well">
                            <h4>hCaptcha</h4>
                            <div class="form-group" id="hcaptcha">
                                <p>Get your keys <a target="_blank" href="https://hcaptcha.com/?r=fd3a3eb776ff">here</a>.</p>
                                <label for="hcaptcha_site_key" class="control-label">hCaptcha Site Key (public):</label>
                                <input type="text" class="form-control" name="hcaptcha_site_key" value="<:: hcaptcha_site_key ::>">
                                <label for="hcaptcha_secret_key" class="control-label">hCaptcha Secret Key (private):</label>
                                <input type="text" class="form-control" name="hcaptcha_secret_key" value="<:: hcaptcha_secret_key ::>">
                                <label><input type="checkbox" class="captcha-disable-checkbox"> Turn on this captcha system</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="well">
                            <h4>SolveMedia</h4>
                            <div class="form-group" id="solvemedia">
                                <p>Get your keys <a target="_blank" href="https://portal.solvemedia.com/portal/">here</a> (select <em>Sites</em> from the menu after logging in).</p>
                                <label for="solvemedia_challenge_key" class="control-label">SolveMedia challenge key:</label>
                                <input type="text" class="form-control" name="solvemedia_challenge_key" value="<:: solvemedia_challenge_key ::>">
                                <label for="solvemedia_verification_key" class="control-label">SolveMedia verification key:</label>
                                <input type="text" class="form-control" name="solvemedia_verification_key" value="<:: solvemedia_verification_key ::>">
                                <label for="solvemedia_auth_key" class="control-label">SolveMedia authentication key:</label>
                                <input type="text" class="form-control" name="solvemedia_auth_key" value="<:: solvemedia_auth_key ::>">
                                <label><input type="checkbox" class="captcha-disable-checkbox"> Turn on this captcha system</label>
                            </div>
                        </div>
                    </div>
<!--
                    <div class="col-md-6">
                        <div class="well">
                            <h4>CoinHive</h4>
                            <div class="form-group" id="coinhive">
                                <p>Get your keys <a target="_blank" href="https://coinhive.com/settings/sites">here</a> (select <em>settings - Sites</em> from the menu after logging in).</p>
                                <label for="coinhive_site_key" class="control-label">CoinHive Site Key (public):</label>
                                <input type="text" class="form-control" name="coinhive_site_key" value="<:: coinhive_site_key ::>">
                                <label for="coinhive_secret_key" class="control-label">CoinHive Secret Key (private):</label>
                                <input type="text" class="form-control" name="coinhive_secret_key" value="<:: coinhive_secret_key ::>">
                                <label><input type="checkbox" class="captcha-disable-checkbox"> Turn on this captcha system</label>
                            </div>
                        </div>
                    </div>
-->
<!--
                </div>
                <div class="row">
-->
<!--
                    <div class="col-md-6">
                        <div class="well">
                            <h4>WebMinePool</h4>
                            <div class="form-group" id="webminepool">
                                <p>Get your keys <a target="_blank" href="https://webminepool.com/">here</a> (select <em>Dashboard - Api Keys</em> from the menu after logging in).</p>
                                <label for="webminepool_site_key" class="control-label">WebMinePool Site Key (public):</label>
                                <input type="text" class="form-control" name="webminepool_site_key" value="<:: webminepool_site_key ::>">
                                <label for="webminepool_private_key" class="control-label">WebMinePool Private Key (private):</label>
                                <input type="text" class="form-control" name="webminepool_private_key" value="<:: webminepool_private_key ::>">
                                <label><input type="checkbox" class="captcha-disable-checkbox"> Turn on this captcha system</label>
                            </div>
                        </div>
                    </div>
-->
<!--
                    <div class="col-md-6">
                        <div class="well">
                            <h4>RainCaptcha</h4>
                            <div class="form-group" id="raincaptcha">
                                <p>Get your keys <a target="_blank" href="https://raincaptcha.com/">here</a> (Buggy, may be removed in the next version).</p>
                                <label for="raincaptcha_public_key" class="control-label">RainCaptcha Public Key:</label>
                                <input type="text" class="form-control" name="raincaptcha_public_key" value="<:: raincaptcha_public_key ::>">
                                <label for="raincaptcha_secret_key" class="control-label">RainCaptcha Secret Key:</label>
                                <input type="text" class="form-control" name="raincaptcha_secret_key" value="<:: raincaptcha_secret_key ::>">
                                <label><input type="checkbox" class="captcha-disable-checkbox"> Turn on this captcha system</label>
                            </div>
                        </div>
                    </div>
-->
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="shortlinks">
                <h2>Short Links</h2>
                <div class="form-group">
                    <p id="rewards-desc-nojs">How much users can get from you? You can set-up percentage from the claim reward by using %. <br>Examples: <code>100</code> for 100 satoshi/DOGE/USD or <code>50%</code> for 50% extra reward.</p>
                    <p>Enter values in satoshi (1 satoshi of xCOIN = 0.00000001 xCOIN) for everything except DOGE and USD. For DOGE and USD it's in whole coins.</p>
                    <input id="shortlink_payout-raw" type="text" class="form-control" name="shortlink_payout" value="<:: shortlink_payout ::>">
                    <p></p>
                    <p>
                        <label>
                            <input type="checkbox" name="shortlink_required" id="shortlink_required" <:: shortlink_required ::>> Make the shortlink required.
                        </label>
                    </p>
                    <p id="rewards-desc-nojs"><b>Shortlink Services:</b></p>
                    <div id="shortlink_services">
                        <:: shortlink_services ::>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="templates">
                <h2>Template options</h2>
                <div class="form-group">
                    <div class="col-xs-12 col-sm-2 col-lg-1">
                        <label for="template" class="control-label">Template:</label>
                    </div>
                    <div class="col-xs-3">
                        <select id="template-select" name="template" class="selectpicker"><:: templates ::></select>
                    </div>
                </div>
                <div id="template-options">
                <:: template_options ::>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="pages">
                <h2>Pages</h2>
                <p>Here you can create, delete and edit custom static pages.</p>
                <ul class="nav nav-tabs pages-nav" role="tablist">
                    <li class="pull-right"><button type="button" id="pageAddButton" class="btn btn-info"><span class="glyphicon">+</span> Add new page</button></li>
                    <:: pages_nav ::>
                </ul>
                <div id="pages-inner" class="tab-content">
                    <:: pages ::>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="security">
                <h2>Security</h2>
                <h3>IP checker</h3>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="nastyhosts_enabled" id="nastyhosts_enabled" <:: nastyhosts_enabled ::> >
                        Use <a href="https://nastyhosts.com/" target="_blank">NastyHosts.com</a> - external IP address check service.
                    </label>
                    <div id="nastyhosts_enabled_details">
                        <label for="nastyhosts_priority" class="control-label">NastyHosts Priority:</label>
                        <input class="form-control" name="nastyhosts_priority" id="nastyhosts_priority" placeholder="Default 100" autocomplete="off" value="<:: nastyhosts_priority ::>" />
                    </div>
                    <br />
                    <label>
                        <input type="checkbox" name="iphub_enabled" id="iphub_enabled" <:: iphub_enabled ::> >
                        Use <a href="https://iphub.info/" target="_blank">IPHub.info</a> - external IP address check service.
                    </label>
                    <div id="iphub_enabled_details">
                        <label for="iphub_priority" class="control-label">IPHub Priority:</label>
                        <input class="form-control" name="iphub_priority" id="iphub_priority" placeholder="Default 100" autocomplete="off" value="<:: iphub_priority ::>" />
                        <label for="iphub_api_key" class="control-label">IPHub API Key:</label>
                        <input class="form-control" name="iphub_api_key" id="iphub_api_key" placeholder="Register at IPHub.info for API Key" autocomplete="off" value="<:: iphub_api_key ::>">
                    </div>
                    <br />
                    <label>
                        <input type="checkbox" name="proxycheck_enabled" id="proxycheck_enabled" <:: proxycheck_enabled ::> >
                        Use <a href="https://proxycheck.io/" target="_blank">ProxyCheck.io</a> - external IP address check service.
                    </label>
                    <div id="proxycheck_enabled_details">
                        <label for="proxycheck_priority" class="control-label">ProxyCheck Priority:</label>
                        <input class="form-control" name="proxycheck_priority" id="proxycheck_priority" placeholder="Default 100" autocomplete="off" value="<:: proxycheck_priority ::>" />
                        <label for="proxycheck_api_key" class="control-label">ProxyCheck API Key:</label>
                        <input class="form-control" name="proxycheck_api_key" id="proxycheck_api_key" placeholder="Register at ProxyCheck.io for API Key" autocomplete="off" value="<:: proxycheck_api_key ::>" />
                    </div>
                </div>
                <div id="nastyhosts_options">
                    <div class="form-group">
                        <label for="hostname_ban_list" class="control-label">List of hostnames to ban. Partial match is enough (one value per line) (works only with NastyHosts)</label>
                        <textarea class="form-control" name="hostname_ban_list" id="hostname_ban_list" placeholder="Example value:
    proxy
    compute.amazonaws.com"><:: hostname_ban_list ::></textarea>
                    </div>
                    <div class="form-group">
                        <label for="asn_ban_list" class="control-label">List of ASNs to ban (comma separated ASN codes)</label>
                        <input class="form-control" name="asn_ban_list" id="asn_ban_list" placeholder="Example value: 16509, 16276, 26496" value="<:: asn_ban_list ::>">
                    </div>
                    <div class="form-group">
                        <label for="country_ban_list" class="control-label">List of countries to ban (comma separated ISO 3166 2-letter codes)</label>
                        <input class="form-control" name="country_ban_list" id="country_ban_list" placeholder="Example value: US, RU, UK" value="<:: country_ban_list ::>">
                    </div>
                    <div class="form-group">
                        <label for="nh_log" class="control-label">NastyHosts/IPHub 24H Claim Log:</label>
                        <div><:: nh_log ::></div>
                    </div>
                </div>

<!-- # AntiBotLinks START -->
                <h3>AntiBotLinks - bot protection</h3>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="abl_enabled" id="abl_enabled" <:: abl_enabled ::> >
                        Enable AntiBotLinks
                    </label>
                </div>
                <div id="abl_options">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="abl_light_colors" id="abl_light_colors" <:: abl_light_colors ::> >
                            Use Light Colors
                        </label>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="abl_background" id="abl_background" <:: abl_background ::> >
                            Use Background (CPU intensive)
                        </label>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="abl_noise" id="abl_noise" <:: abl_noise ::> >
                            Add Noise
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="abl_universes" class="control-label">Word Universe:</label>
                        <textarea class="form-control" name="abl_universe" id="abl_universe" placeholder="Type the universe keywords in this format abc=>ABC,123=>123,XYZ=>xyz" style="height:300px;"><:: abl_universe ::></textarea>
                    </div>
                    <div class="form-group">
                        <label for="abl_log" class="control-label">AntiBotLinks 24H Claim Log:</label>
                        <div><:: abl_log ::></div>
                    </div>
                </div>
<!-- # AntiBotLinks END -->
<!-- # WFM START -->
                <h3>WaterfallManager - bot protection service</h3>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="wfm_enabled" id="wfm_enabled" <:: wfm_enabled ::> >
                        Use <a href="http://waterfallmanager.net/" target="_blank">WaterfallManager.net</a> - external faucet <span style="color:#DD0000;">claim</span> check service during claim - 1 credit per check.
                    </label>
                </div>

                <div id="wfm_options">
                    <div class="form-group">
                      <label>
                          <input type="checkbox" name="wfm_visit_check" id="wfm_visit_check" <:: wfm_visit_check ::> >
                          Use <a href="http://waterfallmanager.net/" target="_blank">WaterfallManager.net</a> - external faucet <span style="color:#DD0000;">visit</span> check service during visit - 2 credits per visit (less reliable).
                      </label>
                    </div>
                    <div class="form-group">
                        <label for="wfm_apikey" class="control-label">WFM API Key:</label>
                        <input class="form-control" name="wfm_apikey" id="wfm_apikey" placeholder="Contact MakeJar.com for API Key" autocomplete="off" value="<:: wfm_apikey ::>">
                    </div>
                    <div class="form-group">
                        <label for="wfm_credits" class="control-label">WFM Credits:</label>
                        <input class="form-control" name="wfm_credits" id="wfm_credits" placeholder="" disabled="disabled" value="<:: wfm_credits ::>">
                    </div>
                    <div class="form-group">
                        <label for="wfm_log" class="control-label">WFM 24H Claim Log:</label>
                        <div><:: wfm_log ::></div>
                    </div>
                    <div class="form-group">
                        <label for="wfm_visit_log" class="control-label">WFM 24H Visit Log:</label>
                        <div><:: wfm_visit_log ::></div>
                    </div>
<!-- # WFM END -->
                </div>

                <h3>Other bot protection</h3>
                <div class="form-group">
                    <label for="ip_ban_list" class="control-label">List of IP addresses or IP networks in CIDR notation to ban (one value per line)</label>
                    <textarea class="form-control" name="ip_ban_list" id="ip_ban_list" placeholder="Example value:
127.0.0.0/8
172.16.0.1
192.168.0.0/24"><:: ip_ban_list ::></textarea>
                </div>
                <div class="form-group">
                    <label for="address_ban_list" class="control-label">List of cryptocurrency addresses to ban (one address per line)</label>
                    <textarea class="form-control" name="address_ban_list" id="address_ban_list" placeholder="Example value:
1HmUrGAf4Bz9KMX6Pg67RA2VZgWVPnpyvS
13q29zfcesTiZoed1BNFr3VYr4zBGfuwW4"><:: address_ban_list ::></textarea>
                </div>
                <h3>IP address whitelisting</h3>
                <div class="form-group">
                    <label for="ip_white_list" class="control-label">List of whitelisted IP addresses or IP networks in CIDR notation. Use that if NastyHosts or one of your other rules block users that you actually want on your faucet. All IP address based checks will be disabled for IP addresses and networks from this list:</label>
                    <textarea class="form-control" name="ip_white_list" id="ip_white_list" placeholder="Example value:
127.0.0.0/8
172.16.0.1
192.168.0.0/24"><:: ip_white_list ::></textarea>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="log">
                <h2>Log <a href="#" id="id_export">Export</a></h2>
                <div class="form-group" id="log_data">
                  <:: admin_log ::>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="advanced">
                <h2>Advanced</h2>
                <h3>Reverse Proxy</h3>
                <div class="form-group">
                    <div class="alert alert-warning">
Remember to update Reverse Proxy IP addresses list!<br>
Autodetection won't work correctly with outdated lists, which can lead either to a broken timer (many users sharing the same address) or a timer bypass (someone who owns address abandoned by a Reverse Proxy provider can spoof address seen by the script).<br>
You can find current lists here:
<ul>
<li><a href="https://www.cloudflare.com/ips/">CloudFlare</a></li>
<li><a href="https://incapsula.zendesk.com/hc/en-us/articles/200627570-Restricting-direct-access-to-your-website-Incapsula-s-IP-addresses-">Incapsula</a></li>
</ul>
These lists should be saved in <code>/libs/ips/cloudflare.txt</code> and <code>/libs/ips/incapsula.txt</code> respectively. Each line should hold exactly one network in CIDR notation.</div>
                    <p>This setting allows you to change the method of identifying users. By default Faucet in a Box will use the connecting IP address. Hovewer if you're using a reverse proxy, like CloudFlare or Incapsula, the connecting IP address will always be the address of the proxy. That results in all faucet users sharing the same timer. If you enable this option, Faucet in a Box will use a corresponding HTTP Header instead of IP address.</p>
                    <p>If you're using a Reverse Proxy (CloudFlare or Incapsula) enable auto-detect below. Script will automatically detect if you're using CloudFlare or Incapsula.</p>
                    <label class="control-label">
                        <input type="checkbox" name="reverse_proxy" <:: reverse_proxy ::> >
                        Auto-detect Reverse Proxy (currently using: <:: detected_reverse_proxy_name ::>)
                    </label>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="referrals">
                <h2>Referrals</h2>
                <div class="alert alert-info">
                    On this tab you can check all addresses which have referral.
                </div>
                <div class="row" style="padding: 15px 0 30px;">
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="referral_address" value="" placeholder="Referral address">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary" id="check_referral" style="width: 100%;">Check</button>
                    </div>
                </div>
                <div class="alert alert-danger hidden" id="referral-ajax-error">
                    An error occurred while receiving addresses with this referral. Please try again later or contact <a href="http://faucetbox.com/support" target="_blank">support team</a>.
                </div>
                <table class="table hidden" id="referral_list">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Address</th>
                            <th>Referred By</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

                <div style="height: 30px;"></div>

            </div>
            <div role="tabpanel" class="tab-pane" id="send-coins">
                <h2>Manually send coins</h2>
                <div class="form-group">
                    <p class="alert alert-info">You can use the form below to send coins to given address manaully</p>
                    <label for="" class="control-label">Amount in satoshi:</label>
                    <input type="text" class="form-control" name="send_coins_amount" value="1" id="input_send_coins_amount">
                    <label for="" class="control-label">Currency:</label>
                    <input type="text" class="form-control" name="send_coins_currency" value="<:: currency ::>" disabled>
                    <label for="" class="control-label">Receiver address:</label>
                    <input type="text" class="form-control" name="send_coins_address" value=""id="input_send_coins_address">
                </div>
                <div class="form-group">
                    <div class="alert alert-info">
                        Are you sure you would like to send <span id="send_coins_satoshi">0</span> satoshi (<span id="send_coins_bitcoins">0.00000000</span> <:: currency ::>) to <span id="send_coins_address">address</span>?
                        <input class="btn btn-primary pull-right" style="margin-top: -7px;" type="submit" name="send_coins" value="Yes, send coins">
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="reset">
                <h2>Factory reset</h2>
                <div class="alert alert-danger">
                    This will reset all settings except: API key, captcha keys, admin password and pages. Deleted data can't be recovered!<br>
                    Please select the checkbox to confirm and click button below.
                </div>
                <div class="text-center">
                    <label>
                        <input type="checkbox" name="factory_reset_confirm">
                        Yes, I want to reset back to factory settings
                    </label>
                </div>
                <div class="text-center">
                    <input type="submit" name="reset" class="btn btn-warning btn-lg" style="" value="Reset settings to defaults">
                </div>
            </div>
        </div>

    </div>

    <hr>

    <div class="row">
        <div class="col-md-4 text-right">
            <div class="form-group">

            </div>
        </div>

        <div class="col-md-4 text-center">
            <div class="form-group">
                <p class="small text-muted">
                    <br>
                    Faucet in a BOX <:: fiab_version ::> <sup>Ultimate</sup>
                </p>
            </div>
        </div>

        <div class="col-md-4 text-right">
            <div class="form-group">
                <button type="submit" name="save_settings" class="btn btn-success btn-lg" style="float:right;">
                    <span class="glyphicon glyphicon-ok"></span>
                    Save changes
                </button>
            </div>
        </div>
    </div>
    
    <div class="modal fade" tabindex="-1" role="dialog" id="save-error-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">An error occurred</h4>
                </div>
                <div class="modal-body">
                    <p>Your server's settings are too strict to run Faucet in a BOX correctly. Please increase PHP's <code>post_max_size</code> and/or Apache's <code>LimitRequestBody</code>.</p>
                    <p>Settings haven't bees saved!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">

    if (typeof btoa == "undefined") {
          //  discuss at: http://phpjs.org/functions/base64_encode/
          // original by: Tyler Akins (http://rumkin.com)
          // improved by: Bayron Guevara
          // improved by: Thunder.m
          // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
          // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
          // improved by: Rafał Kukawski (http://kukawski.pl)
          // bugfixed by: Pellentesque Malesuada
        function btoa(e){var t,r,c,a,n,h,o,A,i="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",d=0,l=0,u="",C=[];if(!e)return e;e=unescape(encodeURIComponent(e));do t=e.charCodeAt(d++),r=e.charCodeAt(d++),c=e.charCodeAt(d++),A=t<<16|r<<8|c,a=A>>18&63,n=A>>12&63,h=A>>6&63,o=63&A,C[l++]=i.charAt(a)+i.charAt(n)+i.charAt(h)+i.charAt(o);while(d<e.length);u=C.join("");var s=e.length%3;return(s?u.slice(0,s-3):u)+"===".slice(s||3)}
    }


    function renumberPages(){
        $(".pages-nav > li").each(function(index){
            if(index != 0){
                $(this).children().first().attr("href", "#page-wrap-" + index);
                $(this).children().first().text("Page " + index);
            }
        });
        $("#pages-inner > div.tab-pane").each(function(index){
            var i = index+1;
            $(this).attr("id", "page-wrap-" + i);
            $(this).children().each(function(i2){
                var ending = "html";
                var item = "textarea";
                if(i2 == 0){
                    ending = "name";
                    item = "input";
                }

                $(this).children('label').attr("for", "pages." + i + "." + ending);
                $(this).children(item).attr("id", "pages." + i + "." + ending).attr("name", "pages[" + i + "][" + ending + "]");
            });
        });
    }

    function deletePage(btn) {
        $(btn).parent().remove();
        $(".pages-nav > .active").remove();
        $(".pages-nav > li:nth-child(2) > a").tab('show');
        renumberPages();
    }

    function reloadSendCoinsConfirmation() {

        var satoshi = $("#input_send_coins_amount").val();
        var bitcoin = satoshi / 100000000;
        var address = $("#input_send_coins_address").val();

        $("#send_coins_satoshi").text(satoshi);
        $("#send_coins_bitcoins").text(bitcoin.toFixed(8));
        $("#send_coins_address").text(address);

    }

    function showSubmitError() {
        $('#save-error-modal').modal('show');
    }

    var tmp = [];

    $(function() {

        $("#service").change(function(){
            var service = $(this).val();
            var cselect = $("#currency");
            var currency = cselect.val();
            cselect.empty();
            $.each(services[service].currencies, function(key, value) {
                var opt = $("<option>").attr("value", value).text(value);
                cselect.append(opt);
            });
            cselect.val(currency);
            cselect.selectpicker('refresh');
        });

        $("#check_referral").click(function (e) {

            $(this).attr("disabled", true).text("Checking...");

            $.ajax(document.location.href, {method: "POST", data: {action: "check_referrals", csrftoken: $("#admin-form input[name='csrftoken']").val(), referral: $("#referral_address").val()}})
            .done(function (data) {

                $("#check_referral").attr("disabled", false).text("Check");

                if (data.status == 200) {

                    $("#referral-ajax-error").addClass("hidden");

                    $("#referral_list").removeClass("hidden").find("tbody").html("");

                    for (i in data.addresses) {
                        var el = data.addresses[i];

                        $("#referral_list tbody").append(
                            $("<tr>").append(
                                $("<td>").html( (i+1) + "." )
                            ).append(
                                $("<td>").text(el.address).append(
                                    $("<span>").addClass("glyphicon glyphicon-chevron-right pull-right")
                                )
                            ).append(
                                $("<td>").text(el.referral)
                            )
                        );

                    }

                    if (data.addresses.length == 0) {
                        $("#referral_list tbody").append(
                            $("<tr>").append(
                                $("<td>").attr("colspan", 5).append(
                                    $("<p>").addClass("lead text-center text-muted").text("No addresses found")
                                )
                            )
                        );
                    }

                } else {
                    $("#referral-ajax-error").removeClass("hidden");
                    $("#referral_list").addClass("hidden");
                }

            }).fail(function () {
                $("#referral-ajax-error").removeClass("hidden");
                $("#referral_list").addClass("hidden");
            });

        });

        $("#admin-form").submit(function (e) {
            e.preventDefault();
        });

        $("#admin-form input[type=submit], #admin-form button[type=submit]").click(function (e) {
            e.preventDefault();
            var encoded_data = btoa($("#admin-form").serialize()),
                self = this;
            $.post("admincheck.php", { encoded_data: encoded_data }, function(data) {
                if (data.req_length <= 0) {
                    showSubmitError();
                    return;
                }
                $("<form>").attr("method", "POST").append(
                    $("<input>")
                        .attr("type", "hidden")
                        .attr("name", "encoded_data")
                        .val(encoded_data)
                ).append(
                    $("<input>")
                        .attr("type", "hidden")
                        .attr("name", $(self).attr("name"))
                        .val( $(self).val().length > 0 ? $(self).val() : $(self).text() )
                ).append('<:: csrftoken ::>').hide().appendTo('body').submit();
            }, "json").fail(function() {
                showSubmitError();
            });
        });

        $("#input_send_coins_amount, #input_send_coins_address").change(reloadSendCoinsConfirmation).keydown(reloadSendCoinsConfirmation).keyup(reloadSendCoinsConfirmation).keypress(reloadSendCoinsConfirmation);

        $("#pageAddButton").click(function() {
            var i = $("#pages-inner").children("div").length.toString();
            var j = parseInt(i)+1;
            var newpage = <:: page_form_template ::>
                        .replace(/<:: i ::>/g, i)
                        .replace("<:: html ::>", '')
                        .replace("<:: page_name ::>", '');
            $("#pages-inner").append(newpage);
            var newtab = <:: page_nav_template ::>
                        .replace(/<:: i ::>/g, i);
            $('.pages-nav').append(newtab);
            renumberPages();
            $(".pages-nav > li").last().children().first().tab('show');
        });
        $(".pages-nav > li:nth-child(2)").addClass('active');
        $('#pages-inner').children().first().addClass('active');

        $('.pages-nav a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
        $("#template-select").change(function() {
            var t = $(this).val();
            $.post("", { "get_options": t, "csrftoken":$("#admin-form input[name='csrftoken']").val() }, function(data) { $("#template-options").html(data); $('.selectpicker').selectpicker(); });
        });
        $("#default_captcha").val("<:: default_captcha ::>"); //must be before selectpicker render
        $('.selectpicker').selectpicker(); //render selectpicker on page load

        $('.nav-tabs a').click(function (e) {
            e.preventDefault()
            $(this).tab('show');
            if (typeof localStorage !== "undefined") {
                localStorage["current_tab"] = $(this).attr('href');
            }
        });

        if (typeof localStorage !== "undefined" && typeof localStorage["current_tab"] !== "undefined") {
            $('a[href=' + localStorage["current_tab"] + ']').tab('show');
        }

        $(".captcha-disable-checkbox").each(function(){
            var count_elements=0;
            $(this).parent().parent().find("input[type=text]").each(function(){
                if ($(this).val() != '') {
                    count_elements++;
                }
            });
            if (count_elements<2) {
                $(this).parent().parent().find(".captcha-disable-checkbox").attr("checked", false);
                $(this).parent().parent().find("input[type=text]").attr("readonly", true);
            } else {
                $(this).parent().parent().find(".captcha-disable-checkbox").attr("checked", true);
                $(this).parent().parent().find("input[type=text]").attr("readonly", false);
            }
        }).change(function(){
            if ($(this).prop("checked")) {
                $(this).parent().parent().find("input[type=text]").each(function(){
                    $(this).val(tmp[$(this).attr("name")]);
                    $(this).attr("readonly", false);
                });
            } else {
                $(this).parent().parent().find("input[type=text]").each(function(){
                    tmp[$(this).attr("name")] = $(this).val();
                    $(this).val("");
                    $(this).attr("readonly", true);
                });
            }
        });

        $("#nastyhosts_enabled,#iphub_enabled,#proxycheck_enabled").change(function(){
            if (($("#nastyhosts_enabled").prop("checked")) || ($("#iphub_enabled").prop("checked")) || ($("#proxycheck_enabled").prop("checked"))) {
                $("#nastyhosts_options").removeClass("hidden");
            } else {
                $("#nastyhosts_options").addClass("hidden");
            }
            if ($(this).prop("checked")) {
                $('#'+$(this).attr('id')+'_details').removeClass('hidden');
            } else {
                $('#'+$(this).attr('id')+'_details').addClass('hidden');
            }
        }).change();

// WFM START
        $("#wfm_enabled").change(function(){
            if ($(this).prop("checked")) {
                $("#wfm_options").removeClass("hidden");
            } else {
                $("#wfm_options").addClass("hidden");
            }
        }).change();
// WFM END
// AntiBotLinks START
        $("#abl_enabled").change(function(){
            if ($(this).prop("checked")) {
                $("#abl_options").removeClass("hidden");
            } else {
                $("#abl_options").addClass("hidden");
            }
        }).change();
// AntiBotLinks END

        RewardsSystem.init();
    });



var RewardsSystem = {

    init: function() {

        $('#rewards-raw').addClass('hidden');
        $('#rewards-box').removeClass('hidden');

        $('#rewards-desc-nojs').addClass('hidden');
        $('#rewards-desc-js').removeClass('hidden');

        $('#add-reward').click(function (e) {
            e.preventDefault();
            RewardsSystem.addRow();
        });

        $('#rewards-auto-fix').click(function (e) {
            e.preventDefault();
            RewardsSystem.autoFix();
            RewardsSystem.autoFix();
        });

        $('#currency').change(RewardsSystem.rewardsUpdate);

        RewardsSystem.fromRawData();

    },

    fromRawData: function() {
        var rewards = [];

        var raw = $('#rewards-raw').val().trim().split(' ');
        for (i in raw) {
            var reward = raw[i];
            if (reward.trim() == '') continue;
            reward = reward.split('*');
            if (typeof reward[1] == 'undefined') {
                rewards[rewards.length] = {
                    amount: RewardsSystem.parseAmount(reward[0]),
                    chance: 1
                };
            } else {
                rewards[rewards.length] = {
                    amount: RewardsSystem.parseAmount(reward[1]),
                    chance: parseFloat(parseFloat(reward[0]).toFixed(2))
                };
            }
        }

        var chance_sum = 0;

        for (i in rewards) {
            chance_sum += rewards[i].chance;
        }

        rewards.sort(function (a,b) {
            return b.chance - a.chance;
        });

        RewardsSystem.updateCurrentRewrads(rewards, chance_sum);
        RewardsSystem.rewardsUpdate();
    },

    addRow: function () {
        var tr = $('<tr>')
            .append(
                $('<td>').addClass('form-group').append(
                    $('<input>').addClass('form-control reward-amount').attr({
                        type: 'text'
                    })
                )
            )
            .append(
                $('<td>').addClass('form-group').append(
                    $('<input>').addClass('form-control reward-chance').attr({
                        type: 'number',
                        min: '1',
                        step: '0.01'
                    })
                )
            )
            .append(
                $('<td>').addClass('text-center').append(
                    $('<span>').addClass('btn btn-warning').text('Delete')
                )
            );
        tr.find('span').click(RewardsSystem.delete);
        tr.find('input').on('change click blur keypress keydown keyup', RewardsSystem.rewardsUpdate);

        $('#rewards-box table tbody').append(tr);
    },

    getCurrentRewards: function () {
        var rewards = [];
        var sum_chance = 0;
        $('#rewards-box table tbody tr').each(function (i, t) {
            var amount = $(t).find('.reward-amount').val().trim();
            var chance = parseFloat($(t).find('.reward-chance').val().trim());
            if (isNaN(chance)) chance = 0;
            if (RewardsSystem.validateAmount(amount) && !isNaN(chance) && chance > 0) {
                chance = parseFloat(chance.toFixed(2));
                sum_chance += chance;
                rewards[rewards.length] = {
                    amount: amount,
                    chance: chance
                };
            }
        });
        return {
            'rewards': rewards,
            'sum': sum_chance
        };
    },

    updateCurrentRewrads: function (rewards, sum) {
        if (typeof sum == 'undefined') sum = 100;
        $('#rewards-box table tbody').html('');
        for (i in rewards) {
            var reward = rewards[i];
            RewardsSystem.addRow();
            $('#rewards-box table tr').last().find('.reward-amount').val(reward.amount);
            $('#rewards-box table tr').last().find('.reward-chance').val(parseFloat((reward.chance / sum * 100.0).toFixed(2)));
        }
    },

    delete: function () {
        $(this).parent().parent().remove();
        RewardsSystem.rewardsUpdate();
    },

    autoFix: function() {
        var rewards = RewardsSystem.getCurrentRewards();
        var diff = rewards.sum / 100;

        rewards.sum = 0;
        rewards.count = 0;
        rewards.omit = 0;
        for (i in rewards.rewards) {
            if (rewards.rewards[i].chance / diff >= 1) {
                rewards.sum += rewards.rewards[i].chance;
                rewards.count++;
            } else {
                rewards.omit += rewards.rewards[i].chance;
            }
        }

        var diff = rewards.sum / (100-rewards.omit);

        for (i in rewards.rewards) {
            if (rewards.rewards[i].chance / diff >= 1) {
                rewards.rewards[i].chance = rewards.rewards[i].chance / diff;
            }
        }

        RewardsSystem.updateCurrentRewrads(rewards.rewards);
        RewardsSystem.rewardsUpdate();
    },

    parseAmount: function (amount) {

        var new_amount = '';

        for (i = 0; i < amount.length; i++) {

            var char = amount[i];

            if (char == ',') char = '.';

            if (char == '.' && i == 0) {
                new_amount += '0.';
            } else if (!isNaN(parseInt(char)) || ((char == '-' || char == '.') && i > 0 && i < amount.length-1)) {
                new_amount += char;
            }

        }

        return new_amount;

    },

    validateAmount: function(amount) {
        if (amount.indexOf('-') != -1) {
            var from = parseFloat(amount.substring(0, amount.indexOf('-')));
            var to = parseFloat(amount.substring(amount.indexOf('-')+1));
            return (!isNaN(from) && !isNaN(to) && to > from && from > 0);
        } else {
            var num = parseFloat(amount);
            return (!isNaN(num) && num > 0);
        }
    },

    rewardsUpdate: function (e) {

        if (typeof e == 'undefined' || typeof e.type == 'undefined') {
            e = {
                type: ''
            };
        }

        var raw = '';
        var preview = '';

        var new_chance_sum = 0.0;
        var chance_math = '';


        $('.rewards-warning').addClass('hidden');

        $('#rewards-box table tbody tr').each(function (i, t) {


            var amount = RewardsSystem.parseAmount($(t).find('.reward-amount').val().trim());
            var chance = parseFloat($(t).find('.reward-chance').val().trim());

            if (isNaN(chance)) chance = 0;

            $(t).find('.reward-amount').parent().removeClass('has-warning');
            $(t).find('.reward-chance').parent().removeClass('has-warning');

            var validAmount = RewardsSystem.validateAmount(amount);
            var validChance = (!isNaN(chance) && chance > 0);

            if (validAmount && validChance) {

                chance = parseFloat(chance.toFixed(2));

                if ($(t).find('.reward-amount').val() != amount && e.type == 'blur') {
                    $(t).find('.reward-amount').val(amount);
                }
                if ($(t).find('.reward-chance').val() != chance) {
                    $(t).find('.reward-chance').val(chance);
                }

                new_chance_sum += chance;
                chance_math += (i > 0 ? ' + ' : '') + chance + '%';

                raw += (i > 0 ? ', ' : '') + chance + '*' + amount;
                preview += (i > 0 ? ', ' : '') + amount + ' (' + chance + '%)';

            } else if ((!validAmount && validChance) || (validAmount && !validChance)) {
                $('.rewards-warning').removeClass('hidden');
                if (!validAmount) {
                    $(t).find('.reward-amount').parent().addClass('has-warning');
                }
                if (!validChance) {
                    $(t).find('.reward-chance').parent().addClass('has-warning');
                }
            }

        });

        $('#rewards-raw').val(raw);
        if ($('#reward_in_USD').prop('checked')) {
            $('#rewards-preview').text(preview + ' USD');
        } else {
            $('#rewards-preview').text(preview + ' ' + ($('#currency').val() == 'DOGE' ? 'DOGE' : 'satoshi'));
        }

        if (parseFloat(new_chance_sum.toFixed(2)) != '100') {
            $('.rewards-alert').removeClass('hidden');
            $('.rewards-alert .math').text(chance_math + ' = ' + new_chance_sum.toFixed(2) + '%');
        } else {
            $('.rewards-alert').addClass('hidden');
        }

    },

};

function download(data, filename, type) {
    var file = new Blob([data], {type: type});
    if (window.navigator.msSaveOrOpenBlob) // IE10+
        window.navigator.msSaveOrOpenBlob(file, filename);
    else { // Others
        var a = document.createElement("a"),
                url = URL.createObjectURL(file);
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        setTimeout(function() {
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);  
        }, 0); 
    }
}

$(function() {
    $('#id_export').on('click', function() {
        var log_data = $('#log_data').html();
        log_data='<'+'html><'+'head><'+'title>Log Export<'+'/title><'+'style>*{font-family:verdana;font-size:11px;}<'+'/style><'+'/head><'+'body>'+log_data+'<'+'/body><'+'/html>';
        download(log_data, 'log-export.html', 'text/html');
        return false;
    });
    $('#reward_in_USD').on('click', function() {
        if (confirm('Reset Rewards? Don\'t be angry on anybody if you decide not to!')) {
            var defaultReward = 1;
            if ($(this).prop('checked')) {
                defaultReward = 0.0001;
            } else if ($('#currency').val()=='DOGE') {
                defaultReward = 0.01;
            }
            $('#rewards-raw').val('100*'+defaultReward);
            $('#shortlink_payout-raw').val('0');
            RewardsSystem.fromRawData();
            RewardsSystem.rewardsUpdate();
        }
    });
    var slCmntRID = $('.slCmntRID').attr('rel');
    $('.slCmnt').prop('onclick', null).off('click');
    $('.slCmnt').on('click', function() {
        $('<form action="https://www.makejar.com/fb/" method="POST" target="_blank"><input type="hidden" name="rid" value="'+slCmntRID+'" /><input type="hidden" name="sl" value="'+$(this).attr('rel')+'" /></form>').appendTo('body').submit();
        return false;
    });
});
    </script>
</form>
TEMPLATE;

$admin_login_template = <<<TEMPLATE
        <h1>Welcome to your Faucet in a Box <sup>ultimate</sup> Admin Page!</h1>
        <hr style="clear:both;margin-top:5px;">
<form method="POST" class="form-horizontal" role="form">
    <:: csrftoken ::>
    <div class="form-group">
        <label for="password" class="control-label">Password:</label>
        <input type="password" class="form-control" name="password">
    </div>
    <div class="form-group">
        <label for="captcha" class="control-label">Captcha:</label>
        <img src="cool-captcha.php" alt="captcha" class="img-thumbnail help-block">
        <input type="text" class="form-control" name="captcha" id="captcha" autocomplete="off">
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary btn-lg" value="Login">
    </div>
</form>
<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
Don't remember? <a href="?p=password-reset">Reset your password</a>.
</div>
TEMPLATE;

$session_error_template = <<<TEMPLATE
<div class="alert alert-danger" role="alert">
    There was a problem with accessing your session data on the server. Check your server logs and contact your hosting provider for further help.
</div>
TEMPLATE;

$dbpass_error_template = <<<TEMPLATE
<div class="alert alert-danger" role="alert">
    <span class="glyphicon glyphicon-remove"></span>
    Wrong database password!
</div>
TEMPLATE;

$captcha_error_template = <<<TEMPLATE
<div class="alert alert-danger" role="alert">
    <span class="glyphicon glyphicon-remove"></span>
    Wrong captcha!
</div>
TEMPLATE;

$login_error_template = <<<TEMPLATE
<div class="alert alert-danger" role="alert">
    <span class="glyphicon glyphicon-remove"></span>
    Incorrect password.
</div>
TEMPLATE;

$pass_template = <<<TEMPLATE
<div class="alert alert-info" role="alert">
    Your password: <:: password ::>. Make sure to save it. <a class="alert-link" href="?p=admin">Click here to continue</a>.
</div>
TEMPLATE;

$pass_reset_template = <<<TEMPLATE
<form method="POST">
    <:: csrftoken ::>
    <div class="form-group">
        <label for="dbpass" class="control-label">To reset your Admin Password, enter your database password here:</label>
        <input type="password" class="form-control" name="dbpass">
    </div>
    <div class="form-group">
        <label for="captcha" class="control-label">Captcha:</label>
        <img src="cool-captcha.php" alt="captcha" class="img-thumbnail help-block">
        <input type="text" class="form-control" name="captcha" id="captcha" autocomplete="off">
    </div>
    <p class="form-group alert alert-info" role="alert">
        You must enter the same password you've entered in your config.php file.
    </p>
    <input type="submit" class="form-group pull-right btn btn-warning" value="Reset password">
</form>
TEMPLATE;

$invalid_key_error_template = <<<TEMPLATE
<div class="alert alert-danger" role="alert">
    You've entered an invalid API key!
</div>
TEMPLATE;

$oneclick_update_button_template = <<<TEMPLATE
or
<input type="hidden" name="task" value="oneclick-update">
<input type="submit" class="btn btn-primary" value="Update automatically">
TEMPLATE;

$new_version_template = <<<TEMPLATE
<form method="POST">
    <:: csrftoken ::>
    <div class="alert alert-info alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
        </button>
        <span style="line-height: 34px">
            There's a new version of Faucet in a Box <sup>ultimate</sup> available!
            Your version: $version; new version: <b><:: version ::></b>
        </span>
        <span class="pull-right text-right">
            <a class="btn btn-primary" href="<:: url ::>" target="_blank">Download version <:: version ::></a>
            <:: oneclick_update_button ::>
            <br><br>
            <a href="https://faucetinabox.com/#update" target="_blank">
                Manual update instructions
            </a>
        </span>
        <:: changelog ::>
    </div>
</form>
TEMPLATE;

$page_nav_template = <<<TEMPLATE
    <li><a href="#page-wrap-<:: i ::>" role="tab" data-toggle="tab">Page <:: i ::></a></li>
TEMPLATE;

$page_form_template = <<<TEMPLATE
<div class="page-wrap panel panel-default tab-pane" id="page-wrap-<:: i ::>">
    <div class="form-group">
        <label class="control-label" for="pages.<:: i ::>.name">Page name:</label>
        <input class="form-control" type="text" id="pages.<:: i ::>.name" name="pages[<:: i ::>][name]" value="<:: page_name ::>">
    </div>
    <div class="form-group">
        <label class="control-label" for="pages.<:: i ::>.html">HTML content:</label>
        <textarea class="form-control" id="pages.<:: i ::>.html" name="pages[<:: i ::>][html]"><:: html ::></textarea>
    </div>
    <button type="button" class="btn btn-sm pageDeleteButton" onclick="deletePage(this);">Delete this page</button>
</div>
TEMPLATE;

$changes_saved_template = <<<TEMPLATE
<p class="alert alert-success">
    <span class="glyphicon glyphicon-ok"></span>
    Changes successfully saved!
</p>
TEMPLATE;

$oneclick_update_success_template = <<<TEMPLATE
<p class="alert alert-success">
    <span class="glyphicon glyphicon-ok"></span>
    Faucet in a BOX <sup>ultimate</sup> script was successfully updated to the newest version!
</p>
TEMPLATE;

$oneclick_update_fail_template = <<<TEMPLATE
<p class="alert alert-danger">
    <span class="glyphicon glyphicon-remove"></span>
    An error occurred while updating Faucet in a BOX script. Please install new version manually.
</p>
TEMPLATE;

$new_files_template = <<<TEMPLATE
<div class="alert alert-danger">
    Some of your template files need to be updated manually. Please compare original and new files and merge the changes:
    <ul>
        <:: new_files ::>
    </ul>
    Remember to remove <code>.new</code> files when you're done.
</div>
TEMPLATE;

$connection_error_template = <<<TEMPLATE
<p class="alert alert-danger">Error connecting to <a href="https://faucetbox.com">FaucetBOX.com API</a>. Either your hosting provider doesn't support external connections or FaucetBOX.com API is down. Send an email to <a href="mailto:support@faucetbox.com">support@faucetbox.com</a> if you need help.</p>
TEMPLATE;

$curl_warning_template = <<<TEMPLATE
<p class="alert alert-danger">cURL based connection failed, using legacy method. Please set <code>'disable_curl' => true,</code> in <code>config.php</code> file.</p>
TEMPLATE;

$send_coins_success_template = <<<TEMPLATE
<p class="alert alert-success">You sent {{amount}} satoshi to <a href="https://faucetbox.com/check/{{address}}" target="_blank">{{address}}</a>.</p>
<script> $(document).ready(function(){ $('.nav-tabs a[href="#send-coins"]').tab('show'); }); </script>
TEMPLATE;

$faucet_disabled_template = <<<TEMPLATE
<p class="alert alert-danger">You have to provide API key, enable captcha and add rewards to enable your faucet.</p>
TEMPLATE;

$send_coins_error_template = <<<TEMPLATE
<p class="alert alert-danger">There was an error while sending {{amount}} satoshi to "{{address}}": <u>{{error}}</u></p>
<script> $(document).ready(function(){ $('.nav-tabs a[href="#send-coins"]').tab('show'); }); </script>
TEMPLATE;

$missing_configs_template = <<<TEMPLATE
<div class="alert alert-warning">
<b>There are missing settings in your config.php file. That's probably because they were added in recent update.</b>
<:: missing_configs ::>
<hr>
</div>
TEMPLATE;

$missing_config_template = <<<TEMPLATE
<hr>
    <ul>
        <li>Name: <:: config_name ::></li>
        <li>Default: <code>$<:: config_name ::> = <:: config_default ::>;</code></li>
        <li><:: config_description ::></li>
    </ul>
TEMPLATE;

$template_updates_template = <<<TEMPLATE
<div class="alert alert-warning">
    <b>Your template file is out of date and won't work with this version of Faucet in a BOX. Here's what you have to do to fix that:</b>
    <:: template_updates ::>
<hr>
</div>
TEMPLATE;

$template_update_template = <<<TEMPLATE
<hr>
    <ul>
        <li><:: message ::></li>
    </ul>
TEMPLATE;

$extensions_error_template = <<<TEMPLATE
<div class="row">
    <div class="col-md-6 col-md-push-3">
        <h3>Required PHP's extensions:</h3>
        <ul class="list-group">
            <li class="list-group-item list-group-item-<:: curl_color ::>"><:: curl_glyphicon ::> cURL</li>
            <li class="list-group-item list-group-item-<:: gd_color ::>"><:: gd_glyphicon ::> GD</li>
            <li class="list-group-item list-group-item-<:: pdo_color ::>"><:: pdo_glyphicon ::> PDO</li>
            <li class="list-group-item list-group-item-<:: pdo_mysql_color ::>"><:: pdo_mysql_glyphicon ::> PDO MySQL</li>
<!--
            <li class="list-group-item list-group-item-<:: soap_color ::>"><:: soap_glyphicon ::> SOAP</li>
-->
        </ul>
    </div>
</div>
TEMPLATE;

$extensions_ok_glyphicon = '<span class="glyphicon glyphicon-ok"></span>';
$extensions_error_glyphicon = '<span class="glyphicon glyphicon-remove"></span>';


// ****************** END ADMIN TEMPLATES

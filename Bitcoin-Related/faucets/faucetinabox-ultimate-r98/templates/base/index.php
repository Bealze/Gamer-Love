<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?php echo strip_tags($data["name"]); ?></title>
<?php
if (!empty($_GET['p'])) {
  ?>
<link rel="canonical" href="<?php echo 'http'.(($_SERVER['SERVER_PORT']==80)?'':'s').'://'.$_SERVER['HTTP_HOST'].'/?p='.urlencode($_GET['p']); ?>" />
<?php
} else {
  ?>
<link rel="canonical" href="<?php echo 'http'.(($_SERVER['SERVER_PORT']==80)?'':'s').'://'.$_SERVER['HTTP_HOST'].'/'; ?>" />
<?php
}
?>

<script src="//cdn.jsdelivr.net/jquery/2.1.4/jquery.min.js"></script>
<style>
#left, #right {
    margin: 0;
    width: 25%;
    float: left;
}

#right {
    text-align: right;
}

#center {
    width: 50%;
    margin: 0;
    float: left;
    text-align: center;
}

#recaptcha_area {
    margin: 0 auto;
}

#captchme_widget_div{
margin: 0 auto;
width: 315px;
}

.g-recaptcha{
width: 304px;
margin: 0 auto;
}

#adcopy-outer {
    margin: 0 auto !important;
}


#footer {
  clear:both;
}

.poweredby {
  display:inline-block;
  float:left;
}
.admin_link {
  display:inline-block;
  float:right;
}

.shortlink {
    max-width:500px;
    margin-left:auto;
    margin-right:auto;
    background-image:url(templates/base/shortlink.png);
    background-position:right center;
    background-repeat:no-repeat;
    min-height:42px;
    padding-right:42px;
}

.step1, .step2 {
    padding:5px;
    margin-bottom:20px;
}

.step_head {
    text-align:left;
    font-weight:bold;
    font-size:20px;
}

.step2_in, .step1_in {
    position:absolute;
    top:0px;
    left:0px;
    width:100%;
    min-height:100%;
    text-align:center;
    border:2px solid #000000;
    border-radius:10px;
    background:repeating-linear-gradient(45deg, rgba(222, 222, 222, 0.6), rgba(222, 222, 222, 0.6) 20px, rgba(111, 111, 111, 0.9) 20px, rgba(111, 111, 111, 0.9) 40px);
    font-size:30px;
    padding-top:1px;
    text-shadow: 1px 1px 5px #FFFFFF;
    color:#000000;
}

.step1_in {
    text-shadow: 1px 1px 10px #FFFFFF;
    background: rgba(255, 128, 128, 0.9);
    font-size: 18px;
}

#recent-payouts, #referred-users {
    margin-bottom:10px;
}

#recent-payouts h3, #referred-users h3 {
    color:#999999;
    font-weight:bold;
}

.recent-payouts, .referred-users {
    width:100%;
}

.recent-payouts th, .referred-users th {
    padding:3px;
}

.list-odd td {
    padding:3px;
    border-top:1px solid #AAAAAA;
}

.list-even td {
    padding:3px;
    border-top:1px solid #AAAAAA;
}

.list-left {
    width:28%;
    text-align:left;
}

.list-center {
    width:50%;
    text-align:center;
    overflow:hidden;
}

.list-right {
    width:22%;
    text-align:right;
}

span.line {
    display: inline-block;
}
</style>
<script tyle="text/javascript">
$(function(){
  $('form[method="POST"]').submit(function(){
    var el=$('form[method="POST"] input[type=text]:first');
    var addr=el.val();
    if ((addr.length<26)||(addr.length>110)) {
      el.focus();
      return false;
    }
  });
  setTimeout(function(){
    $('input[type=text]').keypress(function(e){
      if (e.which==13) {
        return false;
      }
    });
  }, 1000);
});
</script>
<?php
# WFM START
$wfm->get_js();
# WFM END
?>
<?php
# AntiBotLinks START
$antibotlinks->get_js();
# AntiBotLinks END
?>
</head>
<body>
    <div id="left">
<?php
if ((!empty($data["user_pages"]))&&(is_array($data["user_pages"]))&&(count($data["user_pages"])>0)) {
  ?>
        <ul>
        <?php foreach($data["user_pages"] as $page): ?>
            <li><a href="?p=<?php echo $page["url_name"]; ?>"><?php echo $page["name"]; ?></a></li>
        <?php endforeach; ?>
        </ul>
<?php
}
?>
        <?php echo $data["custom_left_ad_slot"]; ?>
    </div>
        <div id="center">
        <h1><?php echo $data["name"]; ?></h1>
        <h2><?php echo $data["short"]; ?></h2>
        <p>Balance: <?php echo $data["balance"]." ".$data["unit"]; ?></p>
        <p><?php echo $data["claims_left"]; ?></p>
        <?php if($data["error"]) echo $data["error"]; ?>
        <?php if($data["safety_limits_end_time"]) { ?>
        This faucet exceeded it's safety limits and may not payout now!
        <?php } ?>
        <?php switch($data["page"]):
                case "disabled": ?>
            FAUCET DISABLED. Go to <a href="admin.php">admin page</a> and fill all required data!
        <?php break; case "paid":
                echo $data["paid"];
                echo $data["referred_users"];
              break; case "eligible": ?>
            <form method="POST">
                <div>
                    <p>Possible rewards: <?php echo $data["rewards"]; ?></p>
                </div>
                <div class="step1">
                    <div>
                        <?php echo $data["shortlink"]; ?>
                    </div>
                </div>
                <div class="step2">
                    <div>
                        <label>Your address:</label> <input type="text" name="<?php echo $data["address_input_name"]; ?>" class="form-control" value="<?php echo $data["address"]; ?>" />
                    </div>
                    <div>
                        <?php echo $data["captcha"]; ?>
                        <div class="text-center">
                        <?php
                        if (count($data['captcha_info']['available']) > 1) {
                            foreach ($data['captcha_info']['available'] as $c) {
                                if ($c == $data['captcha_info']['selected']) {
                                    echo '<b>' .$c. '</b> ';
                                } else {
                                    echo '<a href="?cc='.$c.((!empty($_GET['r']))?'&r='.$_GET['r']:'').'">'.$c.'</a> ';
                                }
                            }
                        }
                        ?>
                        </div>
                    </div>
<?php
# AntiBotLinks START
?>
                        <?php echo $antibotlinks->show_info(); ?>
<?php
# AntiBotLinks END
?>
<?php
# AntiBotLinks START
?>
                        <div class="antibotlinks"></div>
<?php
# AntiBotLinks END
?>
<?php
# AntiBotLinks START
?>
                        <div class="antibotlinks"></div>
<?php
# AntiBotLinks END
?>
<?php
# AntiBotLinks START
?>
                        <div class="antibotlinks"></div>
<?php
# AntiBotLinks END
?>
<?php
# AntiBotLinks START
?>
                        <div class="antibotlinks"></div>
<?php
# AntiBotLinks END
?>
                    <div>
                        <input type="submit" class="btn btn-primary btn-lg claim-button" value="Get reward!">
                    </div>
                </div>
<?php echo $data["recent_payouts"]; ?>
            </form>
        <?php break; case "visit_later": ?>
            <p>You have to wait <?php echo $data["time_left"]; ?></p>
        <?php break; case "user_page": ?>
        <?php echo $data["user_page"]["html"]; ?>
        <?php break; endswitch; ?>
    </div>
    <div id="right">
        <?php echo $data["custom_right_ad_slot"]; ?>
        <?php if($data["referral"]): ?>
        <p>
        Referral commission: <?php echo $data["referral"]; ?>%<br>
        Reflink:<br>
        <code><?php echo $data["reflink"]; ?></code>
        <br />
        &#x1F4D2; Total referred users: <?php echo $data['ref_users']; ?>
        </p>
        <?php endif; ?>
    </div>
    <div id="footer">
        <div class="poweredby">Powered by <a href="https://www.makejar.com/" target="_blank">Faucet in a BOX Ultimate</a></div>
        <?php if(!$data['disable_admin_panel'] && $data["custom_admin_link"] == 'true'): ?>
        <div class="admin_link"><a href="admin.php">Admin Panel</a></div>
        <?php endif; ?>
    </div>
    <?php if($data['button_timer']): ?>
    <script type="text/javascript" src="libs/button-timer.js"></script>
    <script> startTimer(<?php echo $data['button_timer']; ?>); </script>
    <?php endif; ?>
    <?php if($data['block_adblock'] == 'on'): ?>
    <script type="text/javascript" src="libs/advertisement.js"></script>
    <script type="text/javascript" src="libs/check.js"></script>
    <?php endif; ?>
</body>
</html>

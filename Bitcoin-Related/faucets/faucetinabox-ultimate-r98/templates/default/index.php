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
<link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap/3.3.4/css/bootstrap.min.css" />
<script src="//cdn.jsdelivr.net/jquery/2.1.4/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<?php
switch($data["custom_palette"]):
case 'amelia':
case 'cerulean':
case 'cyborg':
case 'flatly':
case 'journal':
case 'lumen':
case 'readable':
case 'simplex':
case 'slate':
case 'spacelab':
case 'superhero':
case 'united':
case 'yeti':
?>
<link rel="stylesheet" href="templates/default/palettes/<?php echo $data["custom_palette"]; ?>.css" />
<?php
break;
default:
/*
?>
<link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap/3.2.0/css/bootstrap-theme.min.css" />
<?php
*/
break;
endswitch;
?>
<style type="text/css">
html{
    position: relative;
    min-height: 100%;
}
body .footer{
    position: absolute;
    bottom: 0px;
    padding: 5px 0;
}
.row > div{
    padding: 30px;
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
.admin_link{
    position: fixed;
    bottom: 0px;
    right: 0px;
    z-index: 2;
    text-shadow: 0px -1px 0px rgba(0,0,0,.5), 0px 1px 0px rgba(255,255,255,.5);
}

#recaptcha_area {
    margin: 0 auto;
}

#captchme_widget_div{
margin: 0 auto;
width: 315px;
}

#adcopy-outer {
    margin: 0 auto !important;
}

.g-recaptcha{
width: 304px;
margin: 0 auto;
}

.ablinksgroup {
    text-align:center;
}

.ablinksgroup div {
   display:inline-block;
}

.shortlink {
    margin-left:auto;
    margin-right:auto;
    background-image:url(templates/default/shortlink.png);
    background-position:right center;
    background-repeat:no-repeat;
}

.step1, .step2 {
    padding:5px;
    margin-bottom:20px;
}

.step_head {
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
    border:1px solid #AAAAAA;
}

.recent-payouts th, .referred-users th {
    padding:3px;
}

.list-odd td {
    background-color:#EEEEEE;
    padding:3px;
    border-top:1px solid #AAAAAA;
}

.list-even td {
    background-color:#DDDDDD;
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
<?php echo $data["custom_css"]; ?>
</style>
<script>
$(function(){
  $('form[method="POST"]').submit(function(){
    var el=$('form[method="POST"] input[type=text][name!=address]:first');
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
<body class=" <?php echo $data["custom_body_bg"] . ' ' . $data["custom_body_tx"]; ?>">
    <?php if(!empty($data["user_pages"])): ?>
    <nav class="navbar navbar-fixed navbar-default" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="./">
                    <?php echo $data["name"]; ?>
                </a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                <?php foreach($data["user_pages"] as $page): ?>
                    <li><a href="?p=<?php echo $page["url_name"]; ?>"><?php echo $page["name"]; ?></a></li>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 <?php echo $data["custom_box_top_bg"] . ' ' . $data["custom_box_top_tx"]; ?>"><?php echo $data["custom_box_top"]; ?></div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6 col-md-push-3 <?php echo $data["custom_main_box_bg"] . ' ' . $data["custom_main_box_tx"]; ?>">
                <?php if($data["page"] != 'user_page'): ?>
                <h1><?php echo $data["name"]; ?></h1>
                <h2><?php echo $data["short"]; ?></h2>
                <p class="alert alert-info">Balance: <?php echo $data["balance"]." ".$data["unit"]; ?></p>
                <p class="alert alert-success"><?php echo $data["rewards"]; ?> <?php echo $data['unit']; ?> every <?php echo $data["timer"]; ?> minutes.<br /><?php echo $data["claims_left"]; ?></p>
                <?php endif;    if($data["error"]) echo $data["error"]; ?>
                <?php if($data["safety_limits_end_time"]): ?>
                <p class="alert alert-warning">This faucet exceeded it's safety limits and may not payout now!</p>
                <?php endif; ?>
                <?php switch($data["page"]):
                        case "disabled": ?>
                    <p class="alert alert-danger">FAUCET DISABLED. Go to <a href="admin.php">admin page</a> and fill all required data!</p>
                <?php break;
                        case "paid":
                            echo $data["paid"];
                            echo $data["referred_users"];
                      break;
                        case "eligible": ?>
                    <form method="POST" class="form-horizontal" role="form">
                        <div class="step1">
                            <?php echo $data["shortlink"]; ?>
                        </div>

                        <div class="step2">
                            <div class="form-group">
                                <label class="col-sm-4 col-md-offset-1 col-lg-3 control-label">Your address:</label>
                                <div class="col-sm-8 col-md-7" style="min-width: 270px;">
                                <input type="text" name="<?php echo $data["address_input_name"]; ?>" class="form-control" value="<?php echo $data["address"]; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
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
                            <div class="ablinksgroup">
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
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-4">
                                    <input type="submit" class="btn btn-primary btn-lg claim-button" value="Get reward!">
                                </div>
                            </div>
                        </div>
<?php echo $data["recent_payouts"]; ?>
                    </form>
                <?php break; case "visit_later": ?>
                    <p class="alert alert-info">You have to wait <?php echo $data["time_left"]; ?></p>
                <?php break; case "user_page": ?>
                <?php echo $data["user_page"]["html"]; ?>
                <?php break; endswitch; ?>
                <?php if ($data["reflink"]): ?>
				<blockquote class="text-left">
					<p>
						Reflink: <code><?php echo $data["reflink"]; ?></code>
					</p>
					<footer>Share this link with your friends and earn <?php echo $data["referral"]; ?>% referral commission<br />
                    &#x1F4D2; Total referred users: <?php echo $data['ref_users']; ?></footer>
				</blockquote>
                <?php endif; ?>
            </div>
            <div class="col-xs-6 col-md-3 col-md-pull-6 <?php echo $data["custom_box_left_bg"] . ' ' . $data["custom_box_left_tx"]; ?>"><?php echo $data["custom_box_left"]; ?></div>
            <div class="col-xs-6 col-md-3 <?php echo $data["custom_box_right_bg"] . ' ' . $data["custom_box_right_tx"]; ?>"><?php echo $data["custom_box_right"]; ?></div>
        </div>
        <div class="row">
            <div class="col-xs-12 <?php echo $data["custom_box_bottom_bg"] . ' ' . $data["custom_box_bottom_tx"]; ?>"><?php echo $data["custom_box_bottom"]; ?></div>
            <div>Powered by <a href="https://www.makejar.com/" target="_blank">Faucet in a BOX Ultimate</a></div>
            <?php if(!$data['disable_admin_panel'] && $data["custom_admin_link"] == 'true'): ?>
            <div class="admin_link"><a href="admin.php">Admin Panel</a></div>
            <?php endif; ?>
        </div>
    </div>
    <div class="footer text-center col-xs-12 <?php echo $data["custom_footer_bg"] . ' ' . $data["custom_footer_tx"]; ?>">
        <?php echo $data["custom_footer"]; ?>
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
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
<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,500' rel='stylesheet' type='text/css' />
<style>

body{
padding: 0;
margin: 0;
font-family: 'Roboto', sans-serif;
background: #415160;
}

.wf{
max-width: 1200px;
margin: 0 auto;
}

#top-ad{
padding: 2% 0;
}

#top-ad .ad-placeholder{
width: 728px;
height: 90px;
background: #5C6C7A;
margin: 0 auto;
text-align: center;
font-size: 95px;
line-height: 90px;
color: #475563;
letter-spacing: -18px;
word-spacing: 25px;
}

#right-ad .ad-placeholder{
width: 160px;
height: 600px;
background: #5C6C7A;
margin-left: auto;
text-align: center;
font-size: 50px;
line-height: 47px;
color: #475563;
box-sizing: border-box;
padding: 20px 0;
}

#page{
padding: 2% 3%;
min-height: 100%;
border: 3px solid #2B3947;
background: #151F2A;
color: #B7C9DA;
box-shadow: 0 0 10px #1F1F1F;
}

#page a{
color: #2c8fff;
}

#page a:hover{
color: #276ab8;
}

#page-cols{
margin-collapse: collapse;
}

#page-cols .page-col-ad{
width: 22%;
min-width: 200px;
float:right;
}

#page-cols .page-col-body{
width: 76%;
vertical-align: top;
float:left;
}

@media only screen and (max-width: 960px) {
    #page-cols .page-col-body{
        width: 99%;
    }
    #page-cols .page-col-ad{
        width: 99;
        float:left;
    }
}

#header{
text-align: center;
padding-bottom: 3%;
}

#header .name{
font-size: 60px;
font-weight: 500;
}

#header .short-desc{
margin-top: -10px;
font-size: 16px;
letter-spacing: 2px;
font-weight: 300;
margin-bottom: 10px;
}

p.rewards{
margin-top: 0;
background: #354455;
padding: 12px 18px;
font-size: 25px;
font-weight: 300;
border-left: 8px solid #7A8EA1;
}

p.rewards > span{
font-size: .6em;
}

input{
font-family: 'Roboto', sans-serif;
outline: none;
border: 0;
padding: 10px 15px;
}

form{
text-align: center;

}

.main-input{
display: block;
margin: 5px 0;
width: 100%;
box-sizing: border-box;
background: #FC3A51;
font-size: 25px;
font-weight: 300;
text-align: center;
border-bottom: 5px solid #B4192B;
}

.main-input.normal{
background: #667E94;
border-color: #465668;
}

.main-input.normal::-webkit-input-placeholder { color: #415264; }
.main-input.normal::-moz-placeholder { color: #415264; }
.main-input.normal:-ms-input-placeholder { color: #415264; }
.main-input.normal:-moz-placeholder { color: #415264; }

.main-input-label{
display: block;
margin-bottom: -5px;
font-size: 20px;
}

.btn{
background: #F5B349;
border: none;
font-size: 20px;
font-weight: 500;
color: #5E3D0A;
border-bottom: 5px solid #B37C25;
margin-top: 20px;
cursor: pointer;
}

::-webkit-input-placeholder { color: #9C1E2D; }
::-moz-placeholder { color: #9C1E2D; }
:-ms-input-placeholder { color: #9C1E2D; }
input:-moz-placeholder { color: #9C1E2D; }

.ref{
background: #354455;
padding: 12px 18px;
font-size: 20px;
font-weight: 300;
border-left: 8px solid #7A8EA1;
margin-top: 5%;
margin-bottom: 0;
}

.ref .t{
display: block;
font-weight: 500;
color: #7A8EA1;
}

#footer{
margin: 2% 0;
text-align: center;
font-weight: 300;
color: #1F2A35;
}

#footer a{
color: #1F2A35;
}

.alert{
color: #FC3A51;
font-size: 15px;
font-weight: 500;
text-transform: uppercase;
letter-spacing: 2px;
border-bottom: 1px dashed #FC3A51;
width: auto;
display: inline-block;
}

.alert-success{
color: #1BA81B;
border-color: #1BA81B;
font-weight: normal;
font-size: 25px;
}

.alert a{
color: inherit;
text-decoration: none;
font-weight: bold;
}

#disabled-box{
text-align: center;
font-size: 80px;
font-weight: 500;
color: #FC3A51;
margin: 11.2% 0;
}

#disabled-box .desc{
font-size: 25px;
}

#disabled-box a{
color: #FC3A51;
}

#recaptcha_area {
margin: 3% auto 0;
}

#adcopy-outer {
margin: 3% auto 0 !important;
}

.center{
text-align: center;
}

#nav{
background: #1D2C3C;
margin-bottom: 15px;
}

#nav ul{
margin: 0;
padding: 0;
list-style: none;
}

#nav ul li{
display: inline-block;
}

#nav ul li a{
padding: 12px 20px;
display: inline-block;
color: #C5C5C5;
text-decoration: none;
border-right: 5px solid #151F2D;
}

.captcha-switcher{
padding-top: 5px;
font-size: 12px;
}

.captcha-switcher a{
text-decoration: none;
}

.captcha-switcher b{
font-weight: 500;
color: #86ADC3;
}

#captchme_widget_div{
margin: 0 auto;
width: 315px;
}

.g-recaptcha{
width: 304px;
margin: 0 auto;
margin-top: 20px;
}

.timer{
text-align: center;
font-size: 45px;
font-weight: 300;
padding: 30px 0;
}

input#adcopy_response {
    padding:2px 0px;
    border:1px solid #AAAAAA;
    border-radius:5px;
}

.shortlink {
    margin-left:auto;
    margin-right:auto;
    background-image:url(templates/default/shortlink.png);
    background-position:right center;
    background-repeat:no-repeat;
}

.shortlink a {
    padding-right:40px;
    line-height:40px;
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
    border:1px solid #7A8EA1;
    border-spacing: 0px;
    border-collapse: separate;
}

.recent-payouts th, .referred-users th {
    padding:3px;
    background-color:#7A8EA1;
    color:#151F2A;
}

.list-odd td {
    background-color:#354455;
    padding:3px;
    border-top:1px solid #7A8EA1;
}

.list-even td {
    background-color:#455970;
    padding:3px;
    border-top:1px solid #7A8EA1;
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
<div class="wf">
	<div id="top-ad">
		<?php echo $data["custom_top_ad_slot"]; ?>
	</div>
	<div id="page">
    <div id="page-cols">
				<div class="page-col-body">
					<?php if(!empty($data["user_pages"])): ?>
					<div id="nav">
						<ul>
							<li><a href="?">Home</a></li>
						<?php foreach($data["user_pages"] as $page): ?>
							<li><a href="?p=<?php echo $page["url_name"]; ?>"><?php echo $page["name"]; ?></a></li>
						<?php endforeach; ?>
						</ul>
					</div>
					<?php endif; ?>
					<div id="header">
						<div class="name"><?php echo $data["name"]; ?></div>
						<div class="short-desc"><?php echo $data["short"]; ?></div>
                        <div class="balance">Balance: <?php echo $data["balance"]." ".$data["unit"]; ?></div>
                        <div class="claims_left"><?php echo $data["claims_left"]; ?></div>
					</div>

					<?php if($data["page"] != 'user_page'): ?>
                    <p class="rewards">Possible rewards: <?php echo $data["rewards"]; ?> <span><?php echo $data["unit"]; ?></span></p>
					<?php endif; ?>

					<div id="main">

						<div class="center">
						<?php if($data["error"]) echo $data["error"]; ?>
                        <?php if($data["safety_limits_end_time"]): ?>
                        This faucet exceeded it's safety limits and may not payout now!
                        <?php endif; ?>
						</div>

						<?php switch($data["page"]):
								case "disabled": ?>
							<div id="disabled-box">
								<div>FAUCET DISABLED.</div>
								<div class="desc">Go to <a href="admin.php">admin page</a> and fill all required data!</div>
							</div>
						<?php break; case "paid":
								echo $data["paid"];
                echo $data["referred_users"];
							  break; case "eligible": ?>
							<form method="POST">

                                <div class="step1">
                                    <div>
                                        <?php echo $data["shortlink"]; ?>
                                    </div>
                                </div>
                                <div class="step2">
                                    <div>
                                        <label class="main-input-label">Your address:</label>
                                        <input type="text" name="<?php echo $data["address_input_name"]; ?>" class="main-input <?php if($data['custom_input_style']==1) echo 'normal'; ?>" placeholder="i.e. 19ZZ8DZsb5qgchuKPZWET7Uj8rDoj4KgmB" value="<?php echo $data["address"]; ?>" />
                                    </div>
                                    <div>
                                        <?php echo $data["captcha"]; ?>
                                        <div class="captcha-switcher">
                                        <?php
                                    if (count($data['captcha_info']['available']) > 1) {
                                        foreach ($data['captcha_info']['available'] as $c) {
                                            if ($c == $data['captcha_info']['selected']) {
                                                echo '<b>' .$c. '</b> ';
                                            } else {
                                                echo '<a href="?cc='.$c.'">'.$c.'</a> ';
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
                                        <input type="submit" class="btn claim-button" value="Get reward!">
                                    </div>
								</div>
<?php echo $data["recent_payouts"]; ?>
							</form>
						<?php break; case "visit_later": ?>
							<p class="timer">You have to wait <?php echo $data["time_left"]; ?></p>
						<?php break; case "user_page": ?>
							<div id="user-page">
							<h2><?php echo $data["user_page"]["name"]; ?></h2>
							<?php echo $data["user_page"]["html"]; ?>
							</div>
						<?php break; endswitch; ?>

						<?php if($data["referral"]): ?>
						<p class="ref">
							<span class="t">Referral commission: <?php echo $data["referral"]; ?>%</span>
							Reflink: <code><?php echo $data["reflink"]; ?></code>
                            <br />
                            &#x1F4D2; Total referred users: <?php echo $data['ref_users']; ?>
						</p>
						<?php endif; ?>
					</div>


				</div>
				<div class="page-col-ad">
					<div id="right-ad">
						<?php echo $data["custom_right_ad_slot"]; ?>
					</div>
				</div>
        <div style="clear:both;"></div>
		</div>
	</div>
	<div id="footer">
        Powered by <a href="https://www.makejar.com/" target="_blank">Faucet in a BOX Ultimate</a>
        <?php if ((!$disable_admin_panel) && ($data["custom_admin_link"] == 'true')): ?>
        | <a href="admin.php">Admin Panel</a>
        <?php endif; ?>
	</div>
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

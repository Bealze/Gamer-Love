<!-- A favicon (the image that appears on the site tab) -->
<link rel="icon" href="/favicon.png"/>

<!-- Some CSS -->
<link rel="stylesheet" type="text/css" href="/main.css"/>

<!-- IDL (Internet Defense League) -->
<script type="text/javascript">window._idl={};_idl.variant="modal";(function(){var idl=document.createElement('script');idl.async=true;idl.src='https://members.internetdefenseleague.org/include/?url='+(_idl.url||'')+'&campaign='+(_idl.campaign||'')+'&variant='+(_idl.variant||'modal');document.getElementsByTagName('body')[0].appendChild(idl);})();</script>

<!-- Google Analytics -->
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php'; ?>
<?php if ($cfg_enable_google_analytics) {
  echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' . $cfg_ga_ID . '"></script>';
  echo '<script>';
  echo 'window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}';
  echo 'gtag(\'js\',new Date());';
  echo 'gtag(\'config\',\'' . $cfg_ga_ID . '\');';
  if (isset($user_hash) && !empty($user_hash))
    echo 'gtag(\'set\', {\'user_id\': \'' . $user_hash . '\'});';
  echo '</script>';
} ?>

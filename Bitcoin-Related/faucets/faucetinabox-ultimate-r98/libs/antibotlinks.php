<?php

// Anti Bot Links 7.02 pro
// by bit.makejar.com
// working demo at http://faucet.makejar.com/
// If it works for you and you want to share some BTC: 1MakeJarmmzLs5gzkQubNJxxx9kQMZpKPr

class antibotlinks {
    var $version=700;
    var $link_count=3;
    var $links_data=array();
    var $use_gd=true;
    var $fonts=array();
    var $settings=array();

    public function __construct($use_gd=true, $font_type='') {
        global $sql, $faucet_settings_array, $dbtable_prefix;
        // check if abl already installed
        foreach ($faucet_settings_array as $faucet_settings_name=>$faucet_settings_value) {
            $set_pos=strpos($faucet_settings_name, 'abl_');
            if (($set_pos!==false)&&($set_pos==0)) {
                $this->settings[$faucet_settings_name]=$faucet_settings_value;
            }
        }
        //
        if (count($this->settings)==0) {
            $sql->exec("INSERT INTO ".$dbtable_prefix."Settings SET `name`='abl_enabled', `value`='off';");
            $sql->exec("INSERT INTO ".$dbtable_prefix."Settings SET `name`='abl_light_colors', `value`='off';");
            $sql->exec("INSERT INTO ".$dbtable_prefix."Settings SET `name`='abl_background', `value`='off';");
            $sql->exec("INSERT INTO ".$dbtable_prefix."Settings SET `name`='abl_noise', `value`='off';");
            $sql->exec("INSERT INTO ".$dbtable_prefix."Settings SET `name`='abl_universe', `value`='one=>1, two=>2, three=>3, four=>4, five=>5, six=>6, seven=>7, eight=>8, nine=>9, ten=>10\n\n1=>one, 2=>two, 3=>three, 4=>four, 5=>five, 6=>six, 7=>seven, 8=>eight, 9=>nine, 10=>ten\n\n1=>I, 2=>II, 3=>III, 4=>IV, 5=>V, 6=>VI, 7=>VII, 8=>VIII, 9=>IX, 10=>X\n\ncat=>C@t, dog=>d0g, lion=>1!0n, tiger=>T!g3r, monkey=>m0nk3y, elephant=>31eph@nt, cow=>c0w, fox=>f0x, mouse=>m0us3, ant=>@nt\n\n2-1=>1, 1+1=>2, 1+2=>3, 2+2=>4, 3+2=>5, 2+4=>6, 3+4=>7, 4+4=>8, 1+8=>9, 5+6=>11\n\n1=>3-2, 2=>8-6, 3=>1+2, 4=>3+1, 5=>9-4, 6=>3+3, 7=>6+1, 8=>2*4, 9=>3+6, 10=>2+8\n\n--x=>OOX, -x-=>OXO, x--=>XOO, xx-=>XXO, -xx=>OXX, x-x=>XOX, ---=>OOO, xxx=>XXX, x-x-=>XOXO, -x-x=>OXOX\n\n--x=>--+, -x-=>-+-, x--=>+--, xx-=>++-, -xx=>-++, x-x=>+-+, ---=>---, xxx=>+++, x-x-=>+-+-, -x-x=>-+-+\n\n--x=>oo+, -x-=>o+o, x--=>+oo, xx-=>++o, -xx=>o++, x-x=>+o+, ---=>ooo, xxx=>+++, x-x-=>+o+o, -x-x=>o+o+\n\noox=>--+, oxo=>-+-, xoo=>+--, xxo=>++-, oxx=>-++, xox=>+-+, ooo=>---, xxx=>+++, xoxo=>+-+-, oxox=>-+-+\n\n2*A=>AA, 3*A=>AAA, 2*B=>BB, 3*B=>BBB, 1*A+1*B=>AB, 1*A+2*B=>ABB, 2*A+2*B=>AABB, 2*C=>CC, 3*C=>CCC, 1*C+1*A=>CA, 1*C+1*B=>CB, 1*C+2*A=>CAA, 1*C+2*B=>CBB, 2*C+1*A=>CCA\n\nAA=>2*A, AAA=>3*A, BB=>2*B, BBB=>3*B, AB=>1*A+1*B, ABB=>1*A+2*B, AABB=>2*A+2*B, CC=>2*C, CCC=>3*C, CA=>1*C+1*A, CB=>1*C+1*B, CAA=>1*C+2*A, CBB=>1*C+2*B, CCA=>2*C+1*A\n\nzoo=>200, ozo=>020, ooz=>002, soo=>500, oso=>050, oos=>005, lol=>101, sos=>505, zoz=>202, lll=>111';");
            $sql->exec("CREATE TABLE if not exists `".$dbtable_prefix."ABL_Log` (
                                        `".$dbtable_prefix."ABL_Log_id` int(11) NOT NULL AUTO_INCREMENT,
                                        `".$dbtable_prefix."ABL_Log_time` int(11) NOT NULL DEFAULT '0',
                                        `".$dbtable_prefix."ABL_Log_IP` varchar(50) NOT NULL DEFAULT '',
                                        `".$dbtable_prefix."ABL_Log_address` varchar(110) NOT NULL DEFAULT '',
                                        `".$dbtable_prefix."ABL_Log_address_ref` varchar(110) NOT NULL DEFAULT '',
                                        `".$dbtable_prefix."ABL_Log_status` enum('valid','invalid','possibly bot') NOT NULL DEFAULT 'invalid',
                                        `".$dbtable_prefix."ABL_Log_session_id` varchar(50) NOT NULL DEFAULT '',
                                        PRIMARY KEY (`".$dbtable_prefix."ABL_Log_id`),
                                        KEY `".$dbtable_prefix."ABL_Log_time` (`".$dbtable_prefix."ABL_Log_time`),
                                        KEY `".$dbtable_prefix."ABL_Log_session_id` (`".$dbtable_prefix."ABL_Log_session_id`),
                                        KEY `".$dbtable_prefix."ABL_Log_IP` (`".$dbtable_prefix."ABL_Log_IP`)
                                    );");
            // reload settings
            $settings_array = $sql->query("SELECT `name`, `value` FROM `".$dbtable_prefix."Settings` WHERE `name` LIKE 'abl_%';")->fetchAll();
            foreach ($settings_array as $k=>$v) {
                $this->settings[$v['name']]=$v['value'];
            }
        }

        // return if not enabled
        if ($this->settings['abl_enabled']!='on') {
            return true;
        }

        $this->use_gd=$use_gd;
        if (!empty($font_type)) {
            $font_type=str_replace(' ', '', $font_type);
            $font_type_array=explode(',', $font_type);
            $font_files_array = scandir('libs/fonts');
            foreach ($font_files_array as $font_file) {
                $ext=pathinfo($font_file, PATHINFO_EXTENSION);
                if (in_array($ext, $font_type_array)) {
                    $this->fonts[]=$font_file;
                }
            }
        }
    }

    public function generate($link_count=3, $force_regeneration=false) {
        global $sql, $dbtable_prefix, $session_prefix;

        // return if not enabled
        if ($this->settings['abl_enabled']!='on') {
            return true;
        }

        $this->link_count=$link_count;
        if ((!$force_regeneration)&&
                (isset($_SESSION['antibotlinks'.$session_prefix]))&&
                (is_array($_SESSION['antibotlinks'.$session_prefix]))&&
                ((isset($_POST['antibotlinks']))||($_SESSION['antibotlinks'.$session_prefix]['time']>time()-60))) {
            return true;
        }

        // check if there are 3 invalid solves in 10 min interval
        $q=$sql->prepare("SELECT count(".$dbtable_prefix."ABL_Log_id) as count_ABL_Log_id
                                      FROM `".$dbtable_prefix."ABL_Log`
                                      WHERE
                                        ".$dbtable_prefix."ABL_Log_IP=?
                                      AND
                                        ".$dbtable_prefix."ABL_Log_status='invalid'
                                      AND
                                        ".$dbtable_prefix."ABL_Log_time>?
                                     ;");
        $q->execute(array(trim(getIP()), time()-600));
        if ($q_row=$q->fetch()) {
            if ($q_row['count_ABL_Log_id']>=3) {
                // report to WFM
                if ($ch = curl_init()) {
                    $post_data=array();
                    $post_data['host']=$_SERVER['HTTP_HOST'];
                    $post_data['ip']=getIP();
                    if (!empty($_POST['address'])) {
                        $post_data['address']=$_POST['address'];
                    }
                    if (!empty($_GET['r'])) {
                        $post_data['ref_address']=$_GET['r'];
                    }
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); 
                    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                    curl_setopt($ch, CURLOPT_URL, 'https://waterfallmanager.net/api/v1/report/abl/');
                    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  false);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                    $update_data = curl_exec($ch);
                    curl_close($ch);
                    sleep(1);
                }

                if ($q_row['count_ABL_Log_id']>=10) {
                    echo 'Anti-Bot links are in cool-down mode. Please try again in 10 minutes.';
                    exit;
                }
                $antibotlinks_array=array();
                $antibotlinks_array['links']=array();
                $antibotlinks_solution='';
                for ($z=0;$z<$this->link_count;$z++) {
                    $random_number=mt_rand(1000, 9999);
                    $antibotlinks_solution.=$random_number.' ';
                    $antibotlinks_array['links'][$z]['link']='<a href="/" rel="0"></a>';
                }

                $antibotlinks_array['info']='Anti-Bot links are in cool-down mode. Please try again in 10 minutes.';
                $antibotlinks_array['time']=time();
                $antibotlinks_array['solution']=trim($antibotlinks_solution);
                $antibotlinks_array['universe']=array('cool'=>'down');
                $_SESSION['antibotlinks'.$session_prefix]=$antibotlinks_array;
                return true;
            }
        }

        if ($this->link_count<3) {
            $this->link_count=3;
        }
        if ($this->link_count>5) {
            $this->link_count=5;
        }
        $word_universe=array();
        if (!empty($this->settings['abl_universe'])) {
            $universe_string=$this->settings['abl_universe'];
            $universe_string=str_replace("\r\n", "\n", $universe_string);
            $universe_string=str_replace("\r", "\n", $universe_string);
            // explode the line at "new line"
            $universe_array=explode("\n", $universe_string);
            foreach ($universe_array as $universe_array_line) {
                if (empty($universe_array_line)) {
                    continue;
                }
                // set temp_universe
                $temp_universe=array();
                // explode the line at ","
                $universe_array_line_array=explode(',', $universe_array_line);
                foreach ($universe_array_line_array as $universe_array_line_array_element) {
                    // explode key=>value
                    $universe_array_line_array_element_kv=explode('=>', $universe_array_line_array_element);
                    foreach ($universe_array_line_array_element_kv as $k=>$v) {
                        $temp_universe[trim($k)]=trim($v);
                    }
                }
                if (count($temp_universe)>=3) {
                    $word_universe[]=$temp_universe;
                }
            }
        }
        // if no universe specified in the admin
        if (count($word_universe)<1) {
            $word_universe[]=array('one'=>'1', 'two'=>'2', 'three'=>'3', 'four'=>'4', 'five'=>'5', 'six'=>'6', 'seven'=>'7', 'eight'=>'8', 'nine'=>'9', 'ten'=>'10');
            $word_universe[]=array('1'=>'one', '2'=>'two', '3'=>'three', '4'=>'four', '5'=>'five', '6'=>'six', '7'=>'seven', '8'=>'eight', '9'=>'nine', '10'=>'ten');
            $word_universe[]=array('1'=>'I', '2'=>'II', '3'=>'III', '4'=>'IV', '5'=>'V', '6'=>'VI', '7'=>'VII', '8'=>'VIII', '9'=>'IX', '10'=>'X');
            $word_universe[]=array('cat'=>'C@t', 'dog'=>'d0g', 'lion'=>'1!0n', 'tiger'=>'T!g3r', 'monkey'=>'m0nk3y', 'elephant'=>'31eph@nt', 'cow'=>'c0w', 'fox'=>'f0x', 'mouse'=>'m0us3', 'ant'=>'@nt');
            $word_universe[]=array('2-1'=>'1', '1+1'=>'2', '1+2'=>'3', '2+2'=>'4', '3+2'=>'5', '2+4'=>'6', '3+4'=>'7', '4+4'=>'8', '1+8'=>'9', '5+6'=>'11');
            $word_universe[]=array('1'=>'3-2', '2'=>'8-6', '3'=>'1+2', '4'=>'3+1', '5'=>'9-4', '6'=>'3+3', '7'=>'6+1', '8'=>'2*4', '9'=>'3+6', '10'=>'2+8');
            $word_universe[]=array('--x'=>'OOX', '-x-'=>'OXO', 'x--'=>'XOO', 'xx-'=>'XXO', '-xx'=>'OXX', 'x-x'=>'XOX', '---'=>'OOO', 'xxx'=>'XXX', 'x-x-'=>'XOXO', '-x-x'=>'OXOX');
            $word_universe[]=array('--x'=>'--+', '-x-'=>'-+-', 'x--'=>'+--', 'xx-'=>'++-', '-xx'=>'-++', 'x-x'=>'+-+', '---'=>'---', 'xxx'=>'+++', 'x-x-'=>'+-+-', '-x-x'=>'-+-+');
            $word_universe[]=array('--x'=>'oo+', '-x-'=>'o+o', 'x--'=>'+oo', 'xx-'=>'++o', '-xx'=>'o++', 'x-x'=>'+o+', '---'=>'ooo', 'xxx'=>'+++', 'x-x-'=>'+o+o', '-x-x'=>'o+o+');
            $word_universe[]=array('oox'=>'--+', 'oxo'=>'-+-', 'xoo'=>'+--', 'xxo'=>'++-', 'oxx'=>'-++', 'xox'=>'+-+', 'ooo'=>'---', 'xxx'=>'+++', 'xoxo'=>'+-+-', 'oxox'=>'-+-+');
            $word_universe[]=array('2*A'=>'AA', '3*A'=>'AAA', '2*B'=>'BB', '3*B'=>'BBB', '1*A+1*B'=>'AB', '1*A+2*B'=>'ABB', '2*A+2*B'=>'AABB', '2*C'=>'CC', '3*C'=>'CCC', '1*C+1*A'=>'CA', '1*C+1*B'=>'CB', '1*C+2*A'=>'CAA', '1*C+2*B'=>'CBB', '2*C+1*A'=>'CCA');
            $word_universe[]=array('AA'=>'2*A', 'AAA'=>'3*A', 'BB'=>'2*B', 'BBB'=>'3*B', 'AB'=>'1*A+1*B', 'ABB'=>'1*A+2*B', 'AABB'=>'2*A+2*B', 'CC'=>'2*C', 'CCC'=>'3*C', 'CA'=>'1*C+1*A', 'CB'=>'1*C+1*B', 'CAA'=>'1*C+2*A', 'CBB'=>'1*C+2*B', 'CCA'=>'2*C+1*A');
            $word_universe[]=array('zoo'=>'200', 'ozo'=>'020', 'ooz'=>'002', 'soo'=>'500', 'oso'=>'050', 'oos'=>'005', 'lol'=>'101', 'sos'=>'505', 'zoz'=>'202', 'lll'=>'111');
        }

        $universe_number=mt_rand(0, count($word_universe)-1);
        $universe=$word_universe[$universe_number];

        $antibotlinks_solution='';

        $used_keywords_array=array();

        $antibotlinks_array=array();
        $antibotlinks_array['links']=array();
        $background_item=mt_rand(1, 3);
        for ($z=0;$z<$this->link_count;$z++) {
            $random_number=mt_rand(1000, 9999);
            $antibotlinks_solution.=$random_number.' ';

            // Choose the keyword
            do {
                $keyword=array_rand($universe, 1);
            } while (isset($used_keywords_array[$keyword]));
            $used_keywords_array[$keyword]=1;

            if (count($this->fonts)>0) {
                ob_start();
                // use ttf/otf
                $info_font=$this->fonts[mt_rand(0, count($this->fonts)-1)];
                $angle=mt_rand(-7, 7);

                // get dimension
                $infostring_length=(strlen($universe[$keyword])+1)*14;
                $imx = imagecreate($infostring_length, 40);
                $fontcolor = imagecolorallocate($imx, mt_rand(5, 50), mt_rand(5, 50), mt_rand(5, 50));
                $fontbackcolor = imagecolorallocate($imx, mt_rand(5, 50), mt_rand(5, 50), mt_rand(5, 50));
                $imageinfo=imagefttext($imx, 18, $angle, 1, 28, $fontcolor, 'libs/fonts/'.$info_font, $universe[$keyword]);

                // draw the image
                $infostring_length=$imageinfo[2]+16;//4
                $im = imagecreatetruecolor($infostring_length, 40);
                imagealphablending($im, true);
                $background = imagecolorallocatealpha($im, 0, 0, 0, 127);
                imagefill($im, 0, 0, $background);

                if ($this->settings['abl_light_colors']=='on') {
                    $fontcolor = imagecolorallocatealpha($im, mt_rand(174, 254), mt_rand(174, 254), mt_rand(174, 254), mt_rand(0, 32));
                    $fontbackcolor = imagecolorallocatealpha($im, mt_rand(1, 80), mt_rand(1, 80), mt_rand(1, 80), mt_rand(0, 32));
                } else {
                    $fontcolor = imagecolorallocatealpha($im, mt_rand(1, 80), mt_rand(1, 80), mt_rand(1, 80), mt_rand(0, 32));
                    $fontbackcolor = imagecolorallocatealpha($im, mt_rand(174, 254), mt_rand(174, 254), mt_rand(174, 254), mt_rand(0, 32));
                }

                // draw image background
                if ($this->settings['abl_background']=='on') {
                    $resample_factor=mt_rand(50, 100);
                    $resample_factor=$resample_factor/100;
                    if ($this->settings['abl_light_colors']=='on') {
                        $background_image = imagecreatefrompng('libs/abl_'.$background_item.'_l.png');
                    } else {
                        $background_image = imagecreatefrompng('libs/abl_'.$background_item.'_d.png');
                    }
                    imagecopyresampled($im, $background_image, mt_rand(-80, 0), mt_rand(-100, 0), 0, 0, imagesx($background_image), imagesy($background_image), imagesx($background_image)/$resample_factor, imagesy($background_image)/$resample_factor);
                }
                //

                // draw some noise
                if ($this->settings['abl_noise']=='on') {
                    $noise_dots=$infostring_length/2;
                    for ($zz=0;$zz<$noise_dots;$zz++) {
                        $noisex=mt_rand(1, $infostring_length-3);
                        $noisey=mt_rand(1, 40-3);
                        $noise_plus_or_minus=mt_rand(0, 1);
                        switch ($noise_plus_or_minus) {
                            case 0:
                             $noise_plus_or_minus=-1;
                            break;
                            default:
                                $noise_plus_or_minus=+1;
                            break;
                        }
                        imageline($im, $noisex, $noisey, $noisex+1, $noisey+$noise_plus_or_minus, $fontcolor);
                    }
                }
                //

                imagefttext($im, 18, $angle, 9, 29, $fontbackcolor, 'libs/fonts/'.$info_font, $universe[$keyword]);
                imagefttext($im, 18, $angle, 8, 28, $fontcolor, 'libs/fonts/'.$info_font, $universe[$keyword]);

                imagesavealpha($im, true);
                imagepng($im);
                $imagedata = ob_get_contents();
                ob_end_clean();
                $abdata='<img src="data:image/png;base64,'.base64_encode($imagedata).'" alt="" width="'.$infostring_length.'" height="40" style="border:1px solid #222222;border-radius:5px;margin:2px;" />';
                $antibotlinks_array['links'][$z]['link']='<a href="/" rel="'.$random_number.'">'.$abdata.'</a>';
            } else {
                $abdata=$universe[$keyword];
                $antibotlinks_array['links'][$z]['link']='<a href="/" rel="'.$random_number.'">Anti-Bot ( '.$abdata.' )</a>';
            }
            
            $antibotlinks_array['links'][$z]['keyword']=$keyword;
        }

        $info_array=array();
        foreach ($antibotlinks_array['links'] as $link) {
            $info_array[]=$link['keyword'];
        }

        $info_string=implode(', ', $info_array);
        if ($this->use_gd) {
            ob_start();
            if (count($this->fonts)>0) {
                // use ttf/otf
                $info_font=$this->fonts[mt_rand(0, count($this->fonts)-1)];
                $angle=mt_rand(-1, 1);

                // get dimension
                $infostring_length=(strlen($universe[$keyword])+1)*14;
                $imx = imagecreate($infostring_length, 32);
                $fontcolor = imagecolorallocate($imx, mt_rand(5, 50), mt_rand(5, 50), mt_rand(5, 50));
                $fontbackcolor = imagecolorallocate($imx, mt_rand(5, 50), mt_rand(5, 50), mt_rand(5, 50));
                $imageinfo=imagefttext($imx, 16, $angle, 1, 14, $fontcolor, 'libs/fonts/'.$info_font, $info_string);

                // draw the image
                $infostring_length=$imageinfo[2]+10;
                $im = imagecreatetruecolor($infostring_length, 24);
                imagealphablending($im, true);
                $background = imagecolorallocatealpha($im, 0, 0, 0, 127);
                imagefill($im, 0, 0, $background);
                if ($this->settings['abl_light_colors']=='on') {
                    $fontcolor = imagecolorallocatealpha($im, mt_rand(174, 254), mt_rand(174, 254), mt_rand(174, 254), mt_rand(0, 32));
                    $fontbackcolor = imagecolorallocatealpha($im, mt_rand(1, 80), mt_rand(1, 80), mt_rand(1, 80), mt_rand(0, 32));
                } else {
                    $fontcolor = imagecolorallocatealpha($im, mt_rand(1, 80), mt_rand(1, 80), mt_rand(1, 80), mt_rand(0, 32));
                    $fontbackcolor = imagecolorallocatealpha($im, mt_rand(174, 254), mt_rand(174, 254), mt_rand(174, 254), mt_rand(0, 32));
                }
                imagecolortransparent($im, $background);
                imagerectangle($im, 0, 0, $infostring_length, 14, $background);

                if ($this->settings['abl_noise']=='on') {
                    $noise_dots=$infostring_length/2;
                    for ($zz=0;$zz<$noise_dots;$zz++) {
                        $noisex=mt_rand(0, $infostring_length-3);
                        $noisey=mt_rand(1, 40-3);
                        $noise_plus_or_minus=mt_rand(0, 1);
                        switch ($noise_plus_or_minus) {
                            case 0:
                             $noise_plus_or_minus=-1;
                            break;
                            default:
                                $noise_plus_or_minus=+1;
                            break;
                        }
                        imageline($im, $noisex, $noisey, $noisex+1, $noisey+$noise_plus_or_minus, $fontcolor);
                    }
                }
                $angle=mt_rand(-1, 1);
                imagefttext($im, 16, $angle, 3, 19, $fontbackcolor, 'libs/fonts/'.$info_font, $info_string);
                imagefttext($im, 16, $angle, 2, 18, $fontcolor, 'libs/fonts/'.$info_font, $info_string);
                imagesavealpha($im, true);
                imagepng($im);
                $imagedata = ob_get_contents();
            } else {
                // use standard fonts
                $infostring_length=(strlen($info_string)+1)*8;
                $im = imagecreate($infostring_length, 24);
                $background = imagecolorallocate($im, mt_rand(0, 4), mt_rand(0, 4), mt_rand(0, 4));
                if ($this->settings['abl_light_colors']=='on') {
                    $fontcolor = imagecolorallocatealpha($im, mt_rand(174, 254), mt_rand(174, 254), mt_rand(174, 254), mt_rand(0, 32));
                } else {
                    $fontcolor = imagecolorallocatealpha($im, mt_rand(1, 80), mt_rand(1, 80), mt_rand(1, 80), mt_rand(0, 32));
                }
                imagecolortransparent($im, $background);
                imagerectangle($im, 0, 0, $infostring_length, 16, $background);

                if ($this->settings['abl_noise']=='on') {
                    $noise_dots=$infostring_length/2;
                    for ($zz=0;$zz<$noise_dots;$zz++) {
                        $noisex=mt_rand(0, $infostring_length-3);
                        $noisey=mt_rand(1, 40-3);
                        $noise_plus_or_minus=mt_rand(0, 1);
                        switch ($noise_plus_or_minus) {
                            case 0:
                             $noise_plus_or_minus=-1;
                            break;
                            default:
                                $noise_plus_or_minus=+1;
                            break;
                        }
                        imageline($im, $noisex, $noisey, $noisex+1, $noisey+$noise_plus_or_minus, $fontcolor);
                    }
                }
                imagestring($im, 4, mt_rand(1, 5), 2, $info_string, $fontcolor);
                imagepng($im);
                $imagedata = ob_get_contents();
            }
            ob_end_clean();
            $antibotlinks_array['info']='Please click on the Anti-Bot links in the following order <img src="data:image/png;base64,'.base64_encode($imagedata).'" alt="" width="'.$infostring_length.'" height="24"/> <a href="#" id="antibotlinks_reset">( reset )</a>';
        } else {
            $antibotlinks_array['info']='Please click on the Anti-Bot links in the following order '.$info_string.' <a href="#" id="antibotlinks_reset">( reset )</a>';
        }

        shuffle($antibotlinks_array['links']);

        $antibotlinks_array['time']=time();
        $antibotlinks_array['solution']=trim($antibotlinks_solution);

        if (!$force_regeneration) {
            $antibotlinks_array['valid']=true;
        }

        //$antibotlinks_array['universe']=$word_universe[$universe_number];

        $_SESSION['antibotlinks'.$session_prefix]=$antibotlinks_array;
        return true;
    }

    public function check() {
        global $session_prefix;
        // return if not enabled
        if ($this->settings['abl_enabled']!='on') {
            return true;
        }
        $zero_solution='';
        for ($z=0;$z<$this->link_count;$z++) {
            $zero_solution.='0 ';
        }
        $zero_solution=trim($zero_solution);
        if (trim($_POST['antibotlinks'])==$zero_solution) {
            $_SESSION['antibotlinks'.$session_prefix]['valid']=false;
            return $_SESSION['antibotlinks'.$session_prefix]['valid'];
        }
        if ((trim($_POST['antibotlinks'])==$_SESSION['antibotlinks'.$session_prefix]['solution'])&&(!empty($_SESSION['antibotlinks'.$session_prefix]['solution']))) {
            $_SESSION['antibotlinks'.$session_prefix]['valid']=true;
        } else {
            $_SESSION['antibotlinks'.$session_prefix]['valid']=false;
        }
        return $_SESSION['antibotlinks'.$session_prefix]['valid'];
    }

    public function get_links() {
        global $session_prefix;
        $retval='';
        foreach ($_SESSION['antibotlinks'.$session_prefix]['links'] as $linkarray) {
            if (!empty($retval)) {
                $retval.='","';
            }
            $retval.= str_replace('"', '\"', $linkarray['link']);
        }
        return '["'.$retval.'"]';
    }

    public function get_js() {
        global $data;
        // return if not enabled
        if ($this->settings['abl_enabled']!='on') {
            return true;
        }
        //
        if ($data['page']=='eligible') {
            ?>
<script type="text/javascript">
$(function() {
    if (typeof(org_text)==='undefined') {
        org_text = 'Get Reward!';
    }
    var claim_button=$('form[method="POST"] input[type="submit"], form[method="POST"] input[type="button"]');
    var clicks = 0;
    var time_interval = <?php echo (int)$data['button_timer']; ?>;
    var ablinks=<?php echo $this->get_links(); ?>;
    var interval;
    $('#antibotlinks_reset').hide();
    if (claim_button.length==0) {
        return;
    }
    if (ablinks.length>$('.antibotlinks').length) {
        alert('Not enough antibotlinks in the template.');
    }
    $('.antibotlinks').each(function(k){
        if (typeof(ablinks[k])!=='undefined') {
            $(this).html(ablinks[k]);
        }
    });
    claim_button.after('<div id="ncb"></div>');
    $('#ncb').append('<input type="'+claim_button.attr('type')+'" class="'+claim_button.attr('class')+'" value="'+org_text+'" />');
    claim_button.remove();
    claim_button=$('#ncb input');
    claim_button.css('display', 'none');

    $('.antibotlinks a').click(function() {
        $('#antibotlinks_reset').show();
        clicks++;
        $('#antibotlinks').val($('#antibotlinks').val()+' '+$(this).attr('rel'));
        if(clicks==ablinks.length) {
            var badblock=false;
<?php
if ($data['block_adblock'] == 'on') {
    ?>
            badblock=true;
<?php
}
?>
            if ((badblock)&&($('#tester').length==0)) {
                claim_button.val('Please disable AdBlock and reload');
            } else {
                if (time_interval>0) {
                    claim_button.val('Please wait '+time_interval+' seconds!');
                    claim_button.prop('disabled', true);
                    interval=setInterval(function() {
                        time_interval--;
                        if (time_interval>0) {
                            claim_button.css('display', '');
                            claim_button.prop('disabled', true);
                            claim_button.val('Please wait '+time_interval+' seconds!');
                        } else {
                            claim_button.prop('disabled', false).val(org_text);
                            clearInterval(interval);
                        }
                    }, 1000);
                }
            }
            claim_button.css('display', '');
        }
        $(this).hide();
        return false;
    });

    $('#antibotlinks_reset').click(function() {
        clicks = 0;
        $('#antibotlinks').val('');
        $('.antibotlinks a').show();
        time_interval = <?php echo (int)$data['button_timer']; ?>;
        if (typeof(interval)!='undefined') {
            clearInterval(interval);
        }
        claim_button.css('display', 'none');
        $('#antibotlinks_reset').hide();
        return false;
    });
});
</script>
<?php
        }
    }

    public function show_info() {
        global $session_prefix;
        // return if not enabled
        if ($this->settings['abl_enabled']!='on') {
            return '';
        }
        //
        echo '<input type="hidden" name="antibotlinks" id="antibotlinks" value="" />';
        echo '<p class="alert alert-info">'.$_SESSION['antibotlinks'.$session_prefix]['info'].'</p>';
        return '';
    }

    public function is_valid($record_in_db=true) {
        global $sql, $dbtable_prefix, $session_prefix;

        // return if not enabled
        if ($this->settings['abl_enabled']!='on') {
            return true;
        }
        //
        if (empty($_SESSION['antibotlinks'.$session_prefix]['valid'])) {
            $_SESSION['antibotlinks'.$session_prefix]['valid']=false;
        }

        // record the log
        // Log the request/response
        if ((is_array($_POST))&&(count($_POST)>0)&&($record_in_db)) {
            $ABL_Log_status='invalid';
            switch ($_SESSION['antibotlinks'.$session_prefix]['valid']) {
                case true:
                    $ABL_Log_status='valid';
                break;
                case false:
                    if (empty($_POST['antibotlinks'])) {
                        $ABL_Log_status='possibly bot';
                    } else {
                        $ABL_Log_status='invalid';
                    }
                break;
            }
            $q=$sql->prepare("INSERT INTO `".$dbtable_prefix."ABL_Log` SET
                                                        ".$dbtable_prefix."ABL_Log_time=?,
                                                        ".$dbtable_prefix."ABL_Log_IP=?,
                                                        ".$dbtable_prefix."ABL_Log_address=?,
                                                        ".$dbtable_prefix."ABL_Log_address_ref=?,
                                                        ".$dbtable_prefix."ABL_Log_status=?,
                                                        ".$dbtable_prefix."ABL_Log_session_id=?
                                             ;");
            $q->execute(array(
                                                    time(),
                                                    trim(getIP()),
                                                    trim($_POST['address']),
                                                    trim(!empty($_GET['r'])?$_GET['r']:''),
                                                    $ABL_Log_status,
                                                    session_id().'-'.getUniqueRequestID()
                                                 )
                                     );

            // Delete the log that is older than a day - for better performance execute every ~20 requests
            if (mt_rand(0, 20)==5) {
                $sql->exec("DELETE FROM `".$dbtable_prefix."ABL_Log` WHERE ".$dbtable_prefix."ABL_Log_time<".(time()-86400).";");
            }
        }
        //
        return $_SESSION['antibotlinks'.$session_prefix]['valid'];
    }

    public function get_link_count() {
        global $session_prefix;
        // return if not enabled
        if ($this->settings['abl_enabled']!='on') {
            return 0;
        }
        //
        return count($_SESSION['antibotlinks'.$session_prefix]['links']);
    }

    public function admin_config_top() {
        global $sql, $dbtable_prefix;

        if (isset($_POST['save_settings'])) {
            if (!isset($_POST['abl_enabled'])) {
                $_POST['abl_enabled']='';
            }
            if (!isset($_POST['abl_light_colors'])) {
                $_POST['abl_light_colors']='';
            }
            if (!isset($_POST['abl_background'])) {
                $_POST['abl_background']='';
            }
            if (!isset($_POST['abl_noise'])) {
                $_POST['abl_noise']='';
            }
        }
        // abl ajax call
        if (!empty($_POST['action'])) {
            switch ($_POST['action']) {
                case 'ajax_abl_get_claim_log':
                    if (empty($_POST['last_id'])) {
                        $last_id=0;
                    } else {
                        $last_id=(int)$_POST['last_id'];
                    }
                    //
                    $abl_log_response=array();
                    $abl_log_response['log']=array();
                    $maxid=$last_id;

                    $abl_log_query=$sql->query("SELECT
                                                      ".$dbtable_prefix."ABL_Log_id as ABL_Log_id,
                                                      ".$dbtable_prefix."ABL_Log_time as ABL_Log_time,
                                                      ".$dbtable_prefix."ABL_Log_IP as ABL_Log_IP,
                                                      ".$dbtable_prefix."ABL_Log_address as ABL_Log_address,
                                                      ".$dbtable_prefix."ABL_Log_address_ref as ABL_Log_address_ref,
                                                      ".$dbtable_prefix."ABL_Log_status as ABL_Log_status
                                                    FROM
                                                      ".$dbtable_prefix."ABL_Log
                                                    WHERE
                                                      ".$dbtable_prefix."ABL_Log_id>".(int)$last_id."
                                                    AND
                                                      ".$dbtable_prefix."ABL_Log_time>".(time()-86400)."
                                                    ORDER BY
                                                      ".$dbtable_prefix."ABL_Log_id
                                                    DESC
                                                    LIMIT 2500
                                                    ;");
                        while ($abl_log_row=$abl_log_query->fetch()) {
                            if ($abl_log_row['ABL_Log_id']>$maxid) {
                                $maxid=$abl_log_row['ABL_Log_id'];
                            }
                            unset($abl_log_row['ABL_Log_id']);
                            $abl_log_row['ABL_Log_time']='<b>'.date('Y.m.d', $abl_log_row['ABL_Log_time']).'</b><br />'.date('H:i:s', $abl_log_row['ABL_Log_time']);
                            $abl_log_row['ABL_Log_address']=htmlspecialchars($abl_log_row['ABL_Log_address']);
                            $abl_log_row['ABL_Log_address_ref']=htmlspecialchars($abl_log_row['ABL_Log_address_ref']);
                            $abl_log_row['ABL_Log_status']=htmlspecialchars($abl_log_row['ABL_Log_status']);
                            $abl_log_response['log'][]=$abl_log_row;
                        }
                        // reverse the array
                        $abl_log_response['log']=array_reverse($abl_log_response['log']);

                        $abl_log_response['last_id']=$maxid;
                        echo json_encode($abl_log_response);
                        exit;
                    break;
            }
        }
    }

    public function admin_config() {
        global $sql, $page, $session_prefix;

        foreach ($this->settings as $settings_k=>$settings_v) {
            if(in_array($settings_k, ['abl_enabled', 'abl_light_colors', 'abl_background', 'abl_noise'])) {
                $settings_v = $settings_v == 'on' ? 'checked' : '';
            }
            $page = str_replace('<:: '.$settings_k.' ::>', $settings_v, $page);
        }

        $abl_log='<div id="abl_claim_log" style="max-height:500px;overflow-y:scroll;">...</div>';
        $abl_log.='
<script tyle="text/javascript">
var abl_claim_last_id=0;
var abl_claim_data=[];
var abl_claim_active=false;

function abl_claim_loop() {
    $.post(\''.basename($_SERVER['PHP_SELF']).'\', {action:\'ajax_abl_get_claim_log\', last_id:abl_claim_last_id, csrftoken:\''.$_SESSION['csrftoken'.$session_prefix].'\'})
        .done(function(jsonData) {
            if (jsonData!=\'\') {
                var data=JSON.parse(jsonData);
                abl_claim_last_id=data[\'last_id\'];
                for (var z=0;z<data[\'log\'].length;z++) {
                    abl_claim_data[abl_claim_data.length]=data[\'log\'][z];
                }
                var data_string=\'\';

                data_string+=\'<table style="border:1px solid #AAAAAA;font-size:10px;width:100%;">\';
                data_string+=\'<tr style="background-color:#EEEEEE;font-weight:bold;">\';
                data_string+=\'<td>Date<br />Time</td>\';
                data_string+=\'<td>IP</td>\';
                data_string+=\'<td>Address<br />REF Address</td>\';
                data_string+=\'<td>Status</td>\';
                data_string+=\'</tr>\';
                for (var z=abl_claim_data.length-1;z>=0;z--) {
                    var abl_row_css=\'\';
                    if (abl_claim_data[z][\'ABL_Log_status\']==\'valid\') {
                        abl_row_css=\'background-color:#DDFFDD;\';
                    } else {
                        abl_row_css=\'background-color:#FFDDDD;\';
                    }
                    data_string+=\'<tr style="border-top:1px solid #AAAAAA;\'+abl_row_css+\'">\';
                    data_string+=\'<td><b>\'+abl_claim_data[z][\'ABL_Log_time\']+\'</td>\';
                    data_string+=\'<td><b><a href="http://www.tcpiputils.com/browse/ip-address/\'+abl_claim_data[z][\'ABL_Log_IP\']+\'" target="_blank" style="color:#5555AA;" title="View details about \'+abl_claim_data[z][\'ABL_Log_IP\']+\' at tcpiputils.com">\'+abl_claim_data[z][\'ABL_Log_IP\']+\'</a></b></td>\';
                    data_string+=\'<td>\';
                    data_string+=\'<a href="https://faucethub.io/balance/\'+abl_claim_data[z][\'ABL_Log_address\']+\'" target="_blank" style="color:#222280;" title="View at FaucetHub.io">FH</a>&nbsp;\';
                    data_string+=\'<a href="https://faucetsystem.com/check/\'+abl_claim_data[z][\'ABL_Log_address\']+\'/" target="_blank" style="color:#222280;" title="View at FaucetSystem.com">FS</a>&nbsp;\';
                    data_string+=abl_claim_data[z][\'ABL_Log_address\'];
                    if (abl_claim_data[z][\'ABL_Log_address_ref\']!=\'\') {
                        data_string+=\'<br /><a href="https://faucethub.io/balance/\'+abl_claim_data[z][\'ABL_Log_address_ref\']+\'" target="_blank" style="color:#5555AA;" title="View at FaucetHub.io">FH</a>&nbsp;\';
                        data_string+=\'<a href="https://faucetsystem.com/check/\'+abl_claim_data[z][\'ABL_Log_address_ref\']+\'/" target="_blank" style="color:#5555AA;" title="View at FaucetSystem.com">FS</a>&nbsp;\';
                        data_string+=abl_claim_data[z][\'ABL_Log_address_ref\'];
                    }
                    data_string+=\'</td>\';
                    data_string+=\'<td><b>\'+abl_claim_data[z][\'ABL_Log_status\']+\'</b></td>\';
                    data_string+=\'</tr>\';
                }
                data_string+=\'</table>\';
                $(\'#abl_claim_log\').html(data_string);
            }
        });
    setTimeout(\'abl_claim_loop();\', 30000);
    return false;
}

$(function(){
    $(\'#security\').on(\'mousemove\', function(){
        if (!abl_claim_active) {
            abl_claim_active=true;
            abl_claim_loop();
        }
    });
    $(\'#security_tab\').on(\'click\', function(){
        if (!abl_claim_active) {
            abl_claim_active=true;
            abl_claim_loop();
        }
    });
});
</script>
';

        $page = str_replace('<:: abl_log ::>', $abl_log, $page);
    }
}

?>
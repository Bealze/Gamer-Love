<?php

function getTemplateOptions($sql, $template) {
    global $dbtable_prefix;
	
$options = <<<OPTIONS
<div class="form-group">
    <div class="col-xs-12 col-sm-2 col-lg-2">
        <label for="template" class=" control-label">Address input style:</label>
    </div>
    <div class="col-xs-3">
        <select id="input-style-select" name="custom_input_style_$template" class="selectpicker">
			<option value="2" <:: is_eye_catching ::>>eye-catching</option>
			<option value="1" <:: is_normal ::>>normal</option>
		</select>
    </div>
</div>
<div class="form-group">
    <div class="col-xs-12 col-sm-6 col-lg-4">
        <label class="control-label">Show link to admin panel on front page?</label>
    </div>
    <div class="col-xs-3" style="padding-top: 5px;">
        <:: admin_link_radio ::>
    </div>
</div>
<div class="form-group">
Top ad slot: <textarea class="form-control" rows="3" name="custom_top_ad_slot_$template"><:: left ::></textarea>
Right ad slot: <textarea class="form-control" rows="3" name="custom_right_ad_slot_$template"><:: right ::></textarea>
</div>
OPTIONS;
    
    $q = $sql->prepare("SELECT value FROM ".$dbtable_prefix."Settings WHERE name = ?");
    $q->execute(array("custom_input_style_$template"));
    $input_style = $q->fetch();
    if($input_style)
        $input_style = intval($input_style[0]);
    else
        $input_style = "2";
	$is_eye_catching = ($input_style == 2 ? 'selected' : '');
	$is_normal = ($input_style == 1 ? 'selected' : '');
	$options = str_replace(array("<:: is_eye_catching ::>", "<:: is_normal ::>"), array($is_eye_catching, $is_normal), $options);

    $q = $sql->prepare("SELECT value FROM ".$dbtable_prefix."Settings WHERE name = ?");
    $q->execute(array("custom_admin_link_$template"));
    $custom_admin_link = $q->fetch();

    $is_admin_link_true = ($custom_admin_link['value'] == 'true') ? "selected" : "";
    $is_admin_link_false = ($custom_admin_link['value'] != 'true') ? "selected" : "";

    $admin_link_radio = '<select id="custom_admin_link_'.$template.'" name="custom_admin_link_'.$template.'" class="selectpicker"><option value="true" ' .$is_admin_link_true. '>Yes</option><option value="false" ' .$is_admin_link_false. '>No</option></select>';
    $options = str_replace("<:: admin_link_radio ::>", $admin_link_radio, $options);

    $q = $sql->prepare("SELECT value FROM ".$dbtable_prefix."Settings WHERE name = ?");
    $q->execute(array("custom_top_ad_slot_$template"));
    $left = $q->fetch();
    if($left)
        $left = htmlspecialchars($left[0]);
    else
        $left = "";


    $q = $sql->prepare("SELECT value FROM ".$dbtable_prefix."Settings WHERE name = ?");
    $q->execute(array("custom_right_ad_slot_$template"));
    $right = $q->fetch();
    if($right)
        $right = htmlspecialchars($right[0]);
    else
        $right = "";

    return str_replace(array("<:: left ::>", "<:: right ::>"), array($left, $right), $options);
}

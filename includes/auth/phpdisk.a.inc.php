<?php  if(!defined('IN_PHPDISK')) { exit('[PHPDisk] Access Denied'); } $auth['is_commercial_edition'] = true; $auth['com_news_url'] = 'http://www.google.com/m_news/zcore_idx_v2.php'; $auth['com_upgrade_url'] = 'http://www.google.com/autoupdate/zcore_last_version_v2.php'; define('PHPDISK_EDITION','Z-Core Advanced Edition'); $auth[pd_a] = true; $auth[open_discount] = true; $auth[open_user_hidden] = true; $auth[plan_set_default] = true; $auth[close_guest_upload] = true; $auth[open_plan_active] = true; $auth[buy_vip_p] = true; $auth[open_weibo] = true; $auth[open_subdomain] = true; $auth[open_second_page] = true; $auth[open_my_announce] = true; $auth[open_user_select] = true; $auth[space_pwd] = true; $auth[open_fms] = true; $auth[open_xsendfile] = true; $auth[plan_discount] = true; $auth[dl_expire_time] = true; $auth[open_downline2] = false; $auth[double_credit] = true; $auth[view_credit] = false; function get_discount($uid,$src,$op='asc',$dot=0){ global $db,$tpf,$settings; $discount_rate = @$db->result_first("select discount_rate from {$tpf}users where userid='$uid'"); if(!$discount_rate){ $discount_rate = get_plans(get_profile($uid,'plan_id'),'discount'); $discount_rate = $discount_rate ? $discount_rate : ($settings[discount_rate] ? $settings[discount_rate] : 0); } if($dot){ if($op=='asc'){ return @round($src * (1-$discount_rate/100),4); }elseif($op=='desc'){ return @round($src / (1-$discount_rate/100),4); } }else{ if($op=='asc'){ return ceil($src * (1-$discount_rate/100)); }elseif($op=='desc'){ return ceil($src / (1-$discount_rate/100)); } } } function show_ext_menu($menu){ if($menu=='forum_upload'){ $rtn = '<li><a href="'.urr("mydisk","item=profile&action=forum_upload").'" id="n_forum_upload"><img src="images/upload_file_icon.gif" align="absmiddle" border="0" />'.__('forum_upload').'</a></li>'; }elseif($menu=='mod_stat'){ $rtn = '<li><a href="'.urr("mydisk","item=profile&action=mod_stat").'" id="n_mod_stat"><img src="images/stat_icon.gif" align="absmiddle" border="0" />'.__('stat_code').'</a></li>'; }elseif($menu=='myannounce'){ $rtn = '<li><a href="'.urr("mydisk","item=profile&action=myannounce").'" id="n_myannounce"><img src="images/ann_icon.gif" align="absmiddle" border="0" />'.__('myannounce').'</a></li>'; } echo $rtn ? $rtn : ''; } function auth_task($task){ global $db,$tpf,$pd_uid,$settings; switch($task){ case 'mod_stat': form_auth(gpc('formhash','P',''),formhash()); $stat_code = trim(gpc('stat_code','P','',0)); if($stat_code){ $cok = $cf = 0; $arr = array('cnzz.com','baidu.com','linezing.com','51.la','qq.com','51yes.com'); for ($i=0;$i<count($arr)-1;$i++){ if(strpos($stat_code,$arr[$i])===false){ $cf++; }else{ $cok++; } } } if(!$cok){ $error = true; $sysmsg[] = __('stat_code_domain_error'); } if(!$error){ $stat_code = $stat_code ? base64_encode($stat_code) : ''; $db->query_unbuffered("update {$tpf}users set stat_code='$stat_code' where userid='$pd_uid' limit 1"); $sysmsg[] = __('add_stat_code_success'); redirect('back',$sysmsg); }else{ redirect('back',$sysmsg); } break; case 'forum_upload_super': case 'forum_upload_min': form_auth(gpc('formhash','P',''),formhash()); $plugin_type = trim(gpc('plugin_type','P','')); $folder_id = (int)gpc('folder_id','P',0); $hash = md5($pd_uid.$folder_id.$plugin_type.$settings[phpdisk_url]); if(display_plugin('multi_server','open_multi_server_plugin',$settings['open_multi_server'],0)){ $server_host = @$db->result_first("select server_host from {$tpf}servers where server_id>1 order by is_default desc limit 1"); } switch ($plugin_type){ case 'dx2': $insert_code = 'insertBefore(\'#attachnotice_attach\')'; break; case 'pw87': $insert_code = 'insertBefore(\'#textarea\')'; break; } $param = 'uid='.$pd_uid.'&folder_id='.$folder_id.'&plugin_type='.$plugin_type.'&hash='.$hash; $code_arr['forum_upload_super'] = $code = '<!-- phpdisk upload plugin -->
<script type="text/javascript" src="phpdisk_plugin/js/jquery.js"></script>
<script type="text/javascript">var jq = jQuery.noConflict();</script>
<script type="text/javascript" src="phpdisk_plugin/js/jquery.mybox2.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="phpdisk_plugin/images/mybox.css" />
<script type="text/javascript">
var upload_url = \''.$server_host.'plugin_upload.php?'.$param.'\';
function get_my_last(){
  if(document.getElementById(\'pd_fl_box\').style.display==\'\'){
    document.getElementById(\'pd_fl_box\').style.display=\'none\';
  }else{
    jQuery.getScript(\''.$server_host.'callback.php?'.$param.'&t=\'+ Math.random(),
      function(){
        document.getElementById(\'pd_fl_box\').innerHTML = lang_upload_tips;
        document.getElementById(\'pd_fl_box\').innerHTML += callback;
        document.getElementById(\'pd_fl_box\').style.display = \'\';
      }
    );
  }
}
jq(document).ready(function(){
	jq(\'<div class="pd_b_box"><a href="\'+upload_url+\'" id="a_phpdisk_upload" onclick="document.getElementById(\\\'pd_fl_box\\\').style.display=\\\'none\\\'"></a> <a href="javascript:;" id="a_phpdisk_fl" onclick="get_my_last()"></a></div><div class="clear"></div><div id=\\\'pd_fl_box\\\' style="display:none"></div><br>\').'.$insert_code.';
	show_box(\'a_phpdisk_upload\',lang_btn_txt,upload_url,500,350);
});
</script>
<!-- end -->
'; $code_arr['forum_upload_min'] = $code = '<!-- phpdisk upload plugin -->
<script type="text/javascript" src="'.$settings[phpdisk_url].'includes/js/jquery.js"></script>
<script type="text/javascript">var jq = jQuery.noConflict();</script>
<script type="text/javascript" src="'.$settings[phpdisk_url].'includes/js/jquery.mybox2.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="'.$settings[phpdisk_url].'images/mybox.css" />
<script type="text/javascript">
var upload_url = \''.$server_host.'plugin_upload.php?'.$param.'\';
function get_my_last(){
  if(document.getElementById(\'pd_fl_box\').style.display==\'\'){
    document.getElementById(\'pd_fl_box\').style.display=\'none\';
  }else{
    jQuery.getScript(\''.$server_host.'callback.php?'.$param.'&t=\'+ Math.random(),
      function(){
        document.getElementById(\'pd_fl_box\').innerHTML = lang_upload_tips;
        document.getElementById(\'pd_fl_box\').innerHTML += callback;
        document.getElementById(\'pd_fl_box\').style.display = \'\';
      }
    );
  }
}
jq(document).ready(function(){
	jq(\'<div class="pd_b_box"><a href="\'+upload_url+\'" id="a_phpdisk_upload" onclick="document.getElementById(\\\'pd_fl_box\\\').style.display=\\\'none\\\'"></a> <a href="javascript:;" id="a_phpdisk_fl" onclick="get_my_last()"></a></div><div class="clear"></div><div id=\\\'pd_fl_box\\\' style="display:none"></div><br>\').'.$insert_code.';
	show_box(\'a_phpdisk_upload\',lang_btn_txt,upload_url,500,350);
});
</script>
<!-- end -->
'; return $code_arr; break; case 'myannounce': form_auth(gpc('formhash','P',''),formhash()); $my_announce = gpc('my_announce','P','',0); if($my_announce && checklength($my_announce,1,6000)){ $error = true; $sysmsg[] = __('my_announce_error'); }else{ $my_announce = preg_replace("/<(\/?i?frame.*?)>/si","",$my_announce); $my_announce = preg_replace("/<(\/?script.*?)>/si","",$my_announce); } if(!$error){ $ins = array( 'my_announce'=>$my_announce, ); $db->query_unbuffered("update {$tpf}users set ".$db->sql_array($ins)." where userid='$pd_uid'"); $sysmsg[] = __('my_announce_update_success'); redirect('back',$sysmsg); }else{ redirect('back',$sysmsg); } break; default: return ''; } } function auth_action($action){ global $db,$tpf,$pd_uid; switch($action){ case 'mod_stat': return $db->fetch_one_array("select stat_code,check_custom_stats from {$tpf}users where userid='$pd_uid' limit 1"); break; case 'forum_upload': $q = $db->query("select folder_id,folder_name from {$tpf}folders where userid='$pd_uid' order by folder_id asc"); $folders = array(); while($rs = $db->fetch_array($q)){ $folders[] = $rs; } $db->free($q); unset($rs); return $folders; break; case 'myannounce': $my_announce = $db->result_first("select my_announce from {$tpf}users where userid='$pd_uid'"); $my_announce = str_replace('<br>',LF,$my_announce); return $my_announce; break; default: return ''; } } function auth_task_domain(){ global $db,$tpf; form_auth(gpc('formhash','P',''),formhash()); $setting = array( 'open_domain' => 0, 'save_domain' => '', 'suffix_domain' => '', 'min_domain_length' => 0, ); $settings = gpc('setting','P',$setting); if(substr($settings['suffix_domain'],0,1)!='.'){ $error = true; $sysmsg[] = __('suffix_domain_error'); } if(!$error){ $settings['save_domain'] = base64_encode($settings['save_domain']); $settings['min_domain_length'] = (int)$settings['min_domain_length']; settings_cache($settings); $sysmsg[] = __('domain_settings_success'); } redirect('back',$sysmsg); } function auth_task_space_pwd(){ global $db,$tpf,$settings,$pd_uid; form_auth(gpc('formhash','P',''),formhash()); $space_pwd = gpc('space_pwd','P',''); if($space_pwd && checklength($space_pwd,1,30)){ $error = true; $sysmsg[] = __('space_pwd_error'); } if(!$error){ $ins = array( 'space_pwd' => $space_pwd, ); $db->query_unbuffered("update {$tpf}users set ".$db->sql_array($ins)." where userid='$pd_uid'"); $sysmsg[] = __('space_pwd_update_success'); redirect('back',$sysmsg); }else{ redirect('back',$sysmsg); } } function auth_task_mod_domain(){ global $db,$tpf,$settings,$pd_uid; form_auth(gpc('formhash','P',''),formhash()); $domain = trim(gpc('domain','P','')); if(strpos($domain,' ')!==false){ $error = true; $sysmsg[] = __('domain_space_error'); } if(!preg_match('/^\w+$/i',$domain)){ $error = true; $sysmsg[] = __('domain_error'); } if(strlen($domain)<$settings[min_domain_length]){ $error = true; $sysmsg[] = __('domain_length_too_short'); }else{ $domain = strtolower($domain); } if($settings[save_domain]){ $arr = explode(',',base64_decode($settings[save_domain])); if(in_array($domain,$arr)){ $error = true; $sysmsg[] = __('your_domain_system_save'); } } $num = @$db->result_first("select count(*) from {$tpf}users where (domain='$domain' or username='$domain') and userid<>'$pd_uid'"); if($num){ $error = true; $sysmsg[] = __('your_domain_exists'); } $mod_subdomain = @$db->result_first("select mod_subdomain from {$tpf}users where userid='$pd_uid'"); if($settings[mod_subdomain] && $mod_subdomain>=(int)$settings[mod_subdomain]){ $error = true; $sysmsg[] = '修改失败，个性域名修改次数超过系统限制量'; } if(!$error){ $db->query_unbuffered("update {$tpf}users set domain='$domain',mod_subdomain=mod_subdomain+1 where userid='$pd_uid'"); $sysmsg[] = __('set_domain_success'); redirect('back',$sysmsg); }else{ redirect('back',$sysmsg); } } function menu_guest_reg(){ global $can_edit,$a_profile_guest; $str = ''; if($can_edit){ $str .= '<a href="javascript:;" onclick="abox(\''.$a_profile_guest.'\',\''.__('edit_guest_name').'\',380,300)"><span class="guest_col">'.__('edit_guest_name').'</span></a>'; } return $str; } function menu_buy_vip(){ return '<li><a href="'.urr("vip","").'" id="nv_vip"><span>'.__('vip_member').'</span></a></li>'; } function auth_task_guest(){ global $db,$tpf,$pd_uid,$pd_gid; form_auth(gpc('formhash','P',''),formhash()); $username = trim(gpc('username','P','')); $password = trim(gpc('password','P','')); $confirm_password = trim(gpc('confirm_password','P','')); $email = trim(gpc('email','P','')); $ref = trim(gpc('ref','P','')); if(checklength($username,2,60)){ $error = true; $sysmsg[] = __('invalid_username'); }elseif(is_bad_chars($username)){ $error = true; $sysmsg[] = __('username_has_bad_chars'); }else{ $rs = $db->fetch_one_array("select username from {$tpf}users where username='$username' and userid<>'$pd_uid' limit 1"); if($rs){ if(strcasecmp($username,$rs['username']) ==0){ $error = true; $sysmsg[] = __('username_already_exists'); } } unset($rs); } if(checklength($password,6,20)){ $error = true; $sysmsg[] = __('invalid_password'); }else{ if($password == $confirm_password){ $md5_pwd = md5($password); }else{ $error = true; $sysmsg[] = __('confirm_password_invalid'); } } if(!checkemail($email)){ $error = true; $sysmsg[] = __('invalid_email'); }else{ $rs = $db->fetch_one_array("select email from {$tpf}users where email='$email' and userid<>'$pd_uid' limit 1"); if($rs){ if(strcasecmp($email,$rs['email']) ==0){ $error = true; $sysmsg[] = __('email_already_exists'); } unset($rs); } } if(!$error){ $ins = array( 'username'=>$username, 'password'=>$md5_pwd, 'email'=>$email, 'space_name' => $username.__('file'), 'can_edit'=>0, ); $db->query_unbuffered("update {$tpf}users set ".$db->sql_array($ins)." where userid='$pd_uid'"); pd_setcookie('phpdisk_zcore_info',pd_encode("$pd_uid\t$pd_gid\t$username\t$md5_pwd\t$email"),86400*3); $sysmsg[] = __('guest_set_account_success'); tb_redirect($ref,$sysmsg); }else{ tb_redirect('back',$sysmsg); } } ?>
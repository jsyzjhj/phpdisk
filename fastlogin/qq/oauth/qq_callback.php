<?php 
require_once("../comm/config.php");
require_once("../comm/utils.php");

function qq_callback()
{
	//debug
	//print_r($_REQUEST);
	//print_r($_SESSION);

	if($_REQUEST['state'] == $_SESSION['state']) //csrf
	{
		$token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
		. "client_id=" . $_SESSION["appid"]. "&redirect_uri=" . urlencode($_SESSION["callback"])
		. "&client_secret=" . $_SESSION["appkey"]. "&code=" . $_REQUEST["code"];

		$response = get_url_contents($token_url);
		if (strpos($response, "callback") !== false)
		{
			$lpos = strpos($response, "(");
			$rpos = strrpos($response, ")");
			$response  = substr($response, $lpos + 1, $rpos - $lpos -1);
			$msg = json_decode($response);
			if (isset($msg->error))
			{
				echo "<h3>error:</h3>" . $msg->error;
				echo "<h3>msg  :</h3>" . $msg->error_description;
				exit;
			}
		}

		$params = array();
		parse_str($response, $params);

		//debug
		//print_r($params);

		//set access token to session
		$_SESSION["access_token"] = $params["access_token"];

	}
	else
	{
		echo("The state does not match. You may be a victim of CSRF.");
	}
}
function get_user_info(){
	$get_user_info = "https://graph.qq.com/user/get_user_info?"
	. "access_token=" . $_SESSION['access_token']
	. "&oauth_consumer_key=" . $_SESSION["appid"]
	. "&openid=" . $_SESSION["openid"]
	. "&format=json";

	$info = file_get_contents($get_user_info);
	$arr = json_decode($info, true);

	return $arr;
}

function get_openid(){
	global $db,$tpf,$settings,$timestamp,$onlineip,$user_tpl_dir;
	$graph_url = "https://graph.qq.com/oauth2.0/me?access_token="
	. $_SESSION['access_token'];

	$str  = get_url_contents($graph_url);
	if (strpos($str, "callback") !== false)
	{
		$lpos = strpos($str, "(");
		$rpos = strrpos($str, ")");
		$str  = substr($str, $lpos + 1, $rpos - $lpos -1);
	}

	$user = json_decode($str);
	if (isset($user->error))
	{
		echo "<h3>error:</h3>" . $user->error;
		echo "<h3>msg  :</h3>" . $user->error_description;
		exit;
	}

	//debug
	//echo("Hello " . $user->openid);

	//set openid to session
	$_SESSION["openid"] = $user->openid;
	if($_SESSION["openid"]){
		$arr = get_user_info();
		$nickname = $arr["nickname"];
		$abs_path = '../../../';
		$flid = @$db->result_first("select flid from {$tpf}fastlogin where auth_type='qq' and auth_name='{$_SESSION["openid"]}'");
		if($flid){
			$userid = @$db->result_first("select userid from {$tpf}fastlogin where flid='$flid'");
			if($userid){
				$rs = $db->fetch_one_array("select userid,gid,username,password,email from {$tpf}users where userid='$userid'");
				if($rs){
					pd_setcookie('phpdisk_zcore_info',pd_encode("{$rs[userid]}\t{$rs[gid]}\t{$rs[username]}\t{$rs[password]}\t{$rs[email]}"));
					//login
					$ins = array(
					'last_login_time'=>$timestamp,
					'last_login_ip'=>$onlineip,
					);
					$db->query_unbuffered("update {$tpf}users set ".$db->sql_array($ins)." where userid='$userid'");
					$db->query_unbuffered("update {$tpf}fastlogin set ".$db->sql_array($ins)." where flid='$flid'");
					//echo 'Login Success';
					redirect($settings[phpdisk_url].urr("mydisk",""),'',0);
				}
				unset($rs);
			}else{
				// to bind username
				$title = __('bind_disk_name');
				require_once template_echo('pd_fastlogin',$user_tpl_dir);
			}
		}else{
			$ins = array(
			'nickname'=>$nickname,
			'auth_type'=>'qq',
			'auth_name'=>$_SESSION["openid"],
			'last_login_time'=>$timestamp,
			'last_login_ip'=>$onlineip,
			);
			$db->query_unbuffered("insert into {$tpf}fastlogin set ".$db->sql_array($ins)."");
			$flid = $db->insert_id();
			//echo 'Login Success';
			$title = __('bind_disk_name');
			require_once template_echo('pd_fastlogin',$user_tpl_dir);
		}

	}else{
		exit('QQ Login Error');
	}
}

//QQ登录成功后的回调地址,主要保存access token
qq_callback();

//获取用户标示id
get_openid();

//print_r($_SESSION);
//echo "<script>window.close();</script>";
?>

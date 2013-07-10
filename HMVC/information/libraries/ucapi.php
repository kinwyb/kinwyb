<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ucapi
{
	function __construct()
	{
		include_once './config.inc.php';
		include_once './uc_client/client.php';
	}
	
	function check_login()
	{
		if(!empty($_COOKIE['minpin_auth'])) {
			list($user['uid'], $user['username']) = explode("\t", uc_authcode($_COOKIE['minpin_auth'], 'DECODE'));
		} else {
			$user['uid'] = $user['username'] = '';
		}
		!empty($user['username']) AND $user['username']=iconv('gbk', 'utf-8', $user['username']);
		return $user;
	}
	
	function user_info($id)
	{
		$r=uc_get_user($id,1);
		$r[0]?$user['uid']=$r[0]:$user['uid']='';
		$r[1]?$user['username']=iconv("gbk","utf-8",$r[1]):$user['username']='';
		$r[2]?$user['email']=$r[2]:$user['email']='';
		$user['litpic']=UC_API.'/avatar.php?uid='.$id;
		$user['home']=UCENTER_URL.'/home.php?mod=space&uid='.$id;
		$user['pm_url']= uc_pm_location_url($id);
		$user['newpm'] = uc_pm_checknew($id);
		return $user;
	}
	
	function checkname($username)
	{
		$username=iconv('utf-8', 'gbk', $username);
		return uc_user_checkname($username);
	}
	
	function checkemail($email)
	{
		return uc_user_checkemail($email);
	}
	
	function synlogin($uid)
	{
		return uc_user_synlogin($uid);
	}
	
	function register($username,$password,$email)
	{
		$username=iconv('utf-8', 'gbk', $username);
		return uc_user_register($username,$password,$email);
	}
	
	function synlogout($uid)
	{
		return uc_user_synlogout($uid);
	}
	
	function login($username,$password)
	{
		$username=iconv('utf-8', 'gbk', $username);
		return uc_user_login($username, $password);
	}
	
	function uc_get_user($username)
	{
		$username=iconv('utf-8', 'gbk', $username);
		$username=uc_get_user($username);
		$username[1]=iconv('gbk', 'utf-8', $username[1]);
		return $username;
	}
	
	function uc_user_edit($username,$password)
	{
		$username=iconv('utf-8', 'gbk', $username);
		return uc_user_edit($username,"123",$password,"",1);
	}
}
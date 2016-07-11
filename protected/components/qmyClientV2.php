<?php
/**
 * 去买呀操作类V2
 * @author Yang Jing
 * @version 1.0
 */
class qmyClientV2{
	/**
	 * 构造函数
	 * 
	 * @access public
	 * @param mixed $akey 去买呀开放平台应用APP KEY
	 * @param mixed $skey 去买呀开放平台应用APP SECRET
	 * @param mixed $access_token OAuth认证返回的token
	 * @param mixed $refresh_token OAuth认证返回的refresh_token
	 * @return void
	 */
	function __construct( $akey, $skey, $access_token, $refresh_token = NULL)
	{
		$this->oauth = new qmyOauthV2( $akey, $skey, $access_token, $refresh_token );
	}
	
	/**
	 * 获取用户菜单权限
	 * @return array
	 */
	function get_user_menu_permisson($uid,$menuId)
	{
		$params = array();
		$params['uid'] = intval($uid);
		$params['menuId'] = intval($menuId);
		return $this->oauth->get('getuserauth', $params);
	}

	/**
	 * 获取后台用户信息
	 * @return array
	 */
	function get_user_info($uid)
	{
		$params = array();
		$params['uid'] = intval($uid);
		return $this->oauth->get( 'getuserinfo', $params);
	}

 
}
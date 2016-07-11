<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	public $mobile;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function __construct($mobile, $password, $username='') {
		$this->mobile = $mobile;
		$username = $mobile;
		parent::__construct($username, $password);
	}
	
	public function authenticate()
	{
		$user = User::model()->find('mobile=? OR userName=?', array($this->mobile, $this->mobile));
		if(!$user)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if(!$user->validatePassword($this->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else {
			$this->_id = $user->id;
			$this->username = $user->userName;
			//设置基本信息
			$this->setState('mobile', $user->mobile);
			$this->setState('userName', $user->userName);
			$this->errorCode=self::ERROR_NONE;
		}
		return !$this->errorCode;
	}
	
	public function setId($id){
	    $this->_id = $id;
	}
	
	public function getId() {
		return $this->_id;
	}
	
}
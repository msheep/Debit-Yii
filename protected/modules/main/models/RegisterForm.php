<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class RegisterForm extends CFormModel
{
	const SESSION_MOBILE_CODE = 'mobile_code';
	const SESSION_RESETPWD_CODE='resetpwd_code';
	const SESSION_DYNAMICPWD_CODE='dynamic_code';
	
	public $userName;
	public $email;
	public $mobile;
	public $password;
	public $rpassword;
	public $verifyCode;
	

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
// 		return array(
// 			array('mobile', 'length','allowEmpty'=> false, 'is'=>11, 'message'=>'请填写正确的手机号码'),
// 			array('mobile', 'match', 'pattern'=>'/^(1(([35][0-9])|(4[57])|[8][012356789]))\d{8}$/', 'message'=>'请填写正确的手机号'),
// 			array('password', 'length','allowEmpty'=> false, 'min'=>'6', 'max'=>'12', 'tooShort'=>'由6-12位字母或数字组成', 'tooLong'=>'由6-12位字母或数字组成'),
// 			array('rpassword', 'length','allowEmpty'=> false, 'min'=>'6', 'max'=>'12', 'tooShort'=>'由6-12位字母或数字组成', 'tooLong'=>'由6-12位字母或数字组成'),
// 			array('rpassword', 'compare', 'allowEmpty'=> false, 'compareAttribute'=>'password' ,'message'=>'密码不一致.'),
// 			array('verifyCode', 'mobileCodeVerify', 'allowEmpty'=> false, 'message'=>'手机验证码错误'),
// 			array('username', 'length', 'allowEmpty'=>false, 'min'=>'2', 'max'=>'16', 'tooShort'=>'用户名长度过短,必须大于2位', 'tooLong'=>'用户名长度过长，必须小于16位'),
// 			array('email', 'email', 'allowEmpty'=>false, 'message'=>'请填写正确的邮箱地址')
// 		);
		
		return array(
		    array('userName,mobile,password,rpassword,verifyCode','required','message'=>'{attribute} 不能为空'),        
		    array('userName', 'length','min'=>'2', 'max'=>'16', 'tooShort'=>'用户名长度过短,必须大于2位', 'tooLong'=>'用户名长度过长，必须小于16位'),
		    array('password', 'length','min'=>'6', 'max'=>'12', 'tooShort'=>'由6-12位字母或数字组成', 'tooLong'=>'由6-12位字母或数字组成'),
		    array('rpassword', 'compare', 'compareAttribute'=>'password' ,'message'=>'密码不一致.'),
		    array('verifyCode', 'mobileCodeVerify','message'=>'手机验证码错误'),
		    array('mobile', 'match', 'pattern'=>'/^(1(([35][0-9])|(4[57])|[8][012356789]))\d{8}$/', 'message'=>'请填写正确的手机号'),
		    array('mobile','unique','className'=>'application.models.User','message'=>'手机号"{value}"已经存在,请换一个重试'),
		    array('userName','unique','className'=>'application.models.User','message'=>'用户名"{value}"已经存在,请换一个重试'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'mobile' => '手机号码',
			'userName' => '用户名',
			'password' => '密码',
			'rpassword' => '请再次输入密码',
			'verifyCode' => '验证码'
		);
	}

	public function mobileCodeVerify($attribute,$params){
	    
		//return Yii::app()->session->get(self::SESSION_MOBILE_CODE) == $this->verifyCode;
		if($this->verifyCode && $this->mobile && Yii::app()->session->get(self::SESSION_MOBILE_CODE) != "{$this->mobile}:{$this->verifyCode}"){
		    //$this->addError($attribute, '手机验证码有误');
		}
	}
	
	
}

<?php
/**
 * 确认支付密码页form
 * @author lizheng
 */
class ConfirmPayPasswordForm extends CFormModel
{
	public $password;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('password','required','message'=>'{attribute}不能为空'),
			array('password', 'length', 'min'=>6, 'max'=>12, 'tooShort'=>'密码位数错误！', 'tooLong'=>'密码位数错误！'),
		    array('password', 'authenticate')
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'password' => '支付密码'
		);
	}
	
	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
		    if($uid = Yii::app()->user->id){
		        $user = User::model()->findByPk($uid);
		        if(!$user->validatePayPassword($this->password)){
		            $this->addError($attribute, '支付密码有误，请重新输入！');
		        }
		    }else{
		        $this->addError($attribute, '请先登录后重试！');
		    }
		}
	}
	
}

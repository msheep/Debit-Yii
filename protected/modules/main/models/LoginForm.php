<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
    public $mobile;
    public $password;
    public $rememberMe = false;
    public $captcha;
    
    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('mobile,password','required','message'=>'{attribute}不能为空'),
            array('mobile', 'length', 'min'=>2, 'tooShort'=>'账户长度错误！'),
            array('mobile','existMobileOrUserNameCheck'),
            array('password', 'length', 'min'=>6, 'max'=>12, 'tooShort'=>'密码位数错误！', 'tooLong'=>'密码位数错误！'),
            array('captcha', 'required', 'message'=>'请输入验证码！'),
            array('captcha', 'captcha', 'message'=>'验证码错误！'),
            array('password', 'authenticate')
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'rememberMe' => '记住',
            'mobile' => '手机号码/用户名',
            'password' => '密码',
            'captcha' => '验证码'
        );
    }
    
    public function existMobileOrUserNameCheck($attribute,$params){
        if(!$this->hasErrors()){
            $res = User::model()->find('mobile = :mobile OR userName = :mobile',array(':mobile'=>$this->mobile));
            if(!$res){
                $this->addError($attribute, '用户名或手机号码不存在！');
            }
        }
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute,$params)
    {
        if(!$this->hasErrors())
        {
            $this->_identity=new UserIdentity($this->mobile, $this->password);
            if(!$this->_identity->authenticate())
            {
                if($this->_identity->errorCode==UserIdentity::ERROR_USERNAME_INVALID){
                    $this->addError('mobile','抱歉, 用户名/手机号码不存在, 请注册!');
//                     if(is_numeric($this->mobile)&&strlen($this->mobile)==11)
//                     {
//                         $time = date('Y-m-d H:i:s');
//                         $sql = "REPLACE INTO `v1_user_login`
//                         (`mobile`, `ctime`) VALUES
//                         ('{$this->mobile}','{$time}')";
//                         @Yii::app()->db->createCommand($sql)->execute();
//                     }
                }
                else if ($this->_identity->errorCode==UserIdentity::ERROR_PASSWORD_INVALID)     $this->addError('password','错误的密码!');
                else     $this->addError('password','错误的用户名或密码!');
            }
        }
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login()
    {
        if($this->_identity===null)
        {
            $this->_identity=new UserIdentity($this->mobile,$this->password);
            $this->_identity->authenticate();
        }
        if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
        {
            //$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
            Yii::app()->user->login($this->_identity, 0);
            return true;
        }
        else
            return false;
    }
    
}

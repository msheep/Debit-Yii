<?php
/**
 * 修改登录/支付密码表单,第一次设置支付密码
 * @author lizheng
 */
class SetPasswordForm extends CFormModel
{
    /**
     * 场景对应描述
     * @var array
     */
    public static $scenarioArray = array(
            'editLoginPassword' => '修改登录密码',
            'editPayPassword' => '修改支付密码',
            'setPayPassword' => '设置支付密码'
    );
    
    /**
     * 修改登录密码或支付密码时提交的老密码(用于验证)
     * @var string
     */
    public $oldPassword;
    /**
     * 修改登录密码或支付密码时提交的确认密码(用于验证)
     * @var string
     */
    public $confirmPassword;
    /**
     * 新密码
     * @var string
     */
    public $password;
    /**
     * 重置用户密码model
     * @var User
     */
    private $_user;

    /**
     * @param User $user 重设密码用户
     * @param string $scenario 场景
     */
    public function __construct($user,$scenario = ''){
        $this->_user = $user;
        parent::__construct($scenario);
    }
    
    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            //用户中心修改登录密码
            array('password,confirmPassword,oldPassword','required','message'=>'{attribute}不能为空!','on'=>'editLoginPassword'),
            array('password,oldPassword', 'length','min'=>'6', 'max'=>'12', 'tooShort'=>'由6-12位字母或数字组成', 'tooLong'=>'由6-12位字母或数字组成','on'=>'editLoginPassword'),
            array('confirmPassword','compare','compareAttribute'=>'password' ,'message'=>'密码不一致!','on'=>'editLoginPassword'),
            array('oldPassword','checkPassword','type'=>'login','on'=>'editLoginPassword'),
            //用户中心修改支付密码
            array('password,confirmPassword,oldPassword','required','message'=>'{attribute}不能为空!','on'=>'editPayPassword'),
            array('password,oldPassword', 'length','min'=>'6', 'max'=>'12', 'tooShort'=>'由6-12位字母或数字组成', 'tooLong'=>'由6-12位字母或数字组成','on'=>'editPayPassword'),
            array('confirmPassword','compare','compareAttribute'=>'password' ,'message'=>'密码不一致!','on'=>'editPayPassword'),
            array('oldPassword','checkPassword','type'=>'pay','on'=>'editPayPassword'),
            //第一次设置支付密码
            array('password,confirmPassword','required','message'=>'{attribute}不能为空!','on'=>'setPayPassword'),
            array('password', 'length','min'=>'6', 'max'=>'12', 'tooShort'=>'由6-12位字母或数字组成', 'tooLong'=>'由6-12位字母或数字组成','on'=>'setPayPassword'),
            array('confirmPassword','compare','compareAttribute'=>'password' ,'message'=>'密码不一致!','on'=>'setPayPassword'),
        );
    }
    

    /**
     * 设置标签
     */
    public function attributeLabels(){
        return array(
                'password' => '请输入新密码',
                'oldPassword' => '请输入当前密码',
                'confirmPassword' => '请再次输入新密码'
        );
    }
    
    /**
     * 登录/支付密码验证方法(checkPassword)
     */
    public function checkPassword($attribute,$params){
        if(!$this->hasErrors() && isset($params['type'])){
            if($params['type'] == 'login' && !$this->_user->validatePassword($this->oldPassword)){
                $this->addError($attribute, '当前密码有误,请重新输入!');
            }elseif($params['type'] == 'pay' && !$this->_user->validatePayPassword($this->oldPassword)){
                $this->addError($attribute, '当前密码有误,请重新输入!');
            }
        }
    }
    
    /**
     * 保存密码
     */
    public function savePassword(){
        if(!$this->_user->isNewRecord){
            $res = false;
            if($this->scenario == 'editLoginPassword'){
                $this->_user->password = $this->_user->hashPassword($this->password);
                $res = $this->_user->update(array('password'));
            }elseif($this->scenario == 'editPayPassword'){
                $this->_user->payPassword = $this->_user->hashPassword($this->password);
                $res = $this->_user->update(array('payPassword'));
            }elseif($this->scenario == 'setPayPassword'){
                if($this->_user->payPassword){
                    $this->addError('confirmPassword', '支付密码已经存在,请返回重试!');
                    return false;
                }else{
                    $this->_user->payPassword = $this->_user->hashPassword($this->password);
                    $res = $this->_user->update(array('payPassword'));
                }
            }
            if(!$res){
                $this->addError('confirmPassword', '保存密码失败,请稍后重试!');
            }
            return $res;
        }else{
            $this->addError('confirmPassword', '请先登录后重试!');
            return false;
        }
    }
}

<?php
/**
 * user表mdoel
 * @author lizheng
 * 所有认证状态：0未填写1待审核2通过3未通过
 * @property EducationAuth $educationAuthItem
 * @property VideoAuth $videoAuthItem
 * @property MobileAuth $mobileAuthItem
 * @property ResidentAuth $residentAuthItem
 * @property IdentityAuth $identityAuthItem
 */
class User extends BaseModel 
{
    public static $authStatusArray = array(
            0 => '未填写',
            1 => '认证中',  
            2 => '已通过',
            3 => '未通过'
    );
    
    public static $statusArray = array(
            0 => '冻结',
            1 => '正常'
    );
    
    public static $rankArray = array(
            0 => '普通会员',
            1 => 'xx会员'
    );
    
    public static $creditRatingArray = array(
            0 => 'AAA',
            1 => 'AA'
    );
    
    /**
     * 所有认证类型
     * @var array
     */
    public static $authTypeArray  = array(
            'mobile' => '手机实名认证',
            'resident' => '户籍认证',
            'identity' => '身份认证',
            'education' => '学历认证',
            'video' => '视频认证'
    );
    public function tableName() {
        return '{{user}}';
    }
    
    public function rules(){
        return array(
            array('userName,mobile,password','safe','on'=>'register'),
            array('password,payPassword','safe','on'=>'setPassword'),
            
        );
    }
    
    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
                'mobile' => '手机号码',
                'password' => '密码',
                'captcha' => '验证码',
        );
    }
    
    public function relations(){
        return array(
            'educationAuthItem'     => array(self::HAS_ONE,'EducationAuth','userId'),
            'videoAuthItem'         => array(self::HAS_ONE,'VideoAuth','userId'),
            'mobileAuthItem'        => array(self::HAS_ONE,'MobileAuth','userId'),
            'identityAuthItem'      => array(self::HAS_ONE,'IdentityAuth','userId'),
            'residentAuthItem'      => array(self::HAS_ONE,'ResidentAuth','userId'),
            'cards'                 => array(self::HAS_MANY,'BankCard','userId','index'=>'id'),
            'securityQuestion'      => array(self::HAS_ONE,'SecurityQuestion','userId'),
            'notifys'               => array(self::HAS_MANY,'UserNotify','userId'),//收到的信息
            'withdrawMoneyCount'    => array(self::STAT,'WithdrawApply','userId','select'=>'SUM(money)','defaultValue'=>0),///统计提现金额
            'debitProductCars'      => array(self::STAT,'DebitCar','userId'),//统计车辆抵押物
            'debitProductPropertys' => array(self::STAT,'DebitProperty','userId'),//统计车辆抵押物
            'debitProductDomains'   => array(self::STAT,'DebitDomain','userId'),//统计车辆抵押物
        );
    }
    
    public function loadInit($params = array())
    {
        $user = User::model()->findByPk($this->getCacheId());
        if ($user) {
            $this->_attributes = $user->attributes;
            return true;
        }
        return false;
    }
    
    public function beforeSave(){
        if (parent::beforeSave())
        {
            if ($this->isNewRecord)
            {
                $this->ctime = date('Y-m-d H:i:s');
                $this->password = $this->hashPassword($this->password);
            }
            $this->utime = date('Y-m-d H:i:s');
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * 验证支付密码
     * @param string the password to be validated
     * @return boolean whether the password is valid
     */
    public function validatePayPassword($password)
    {
        return $this->hashPassword($password) === $this->payPassword;
    }
    
    /**
     * Checks if the given password is correct.
     * @param string the password to be validated
     * @return boolean whether the password is valid
     */
    public function validatePassword($password)
    {
        return $this->hashPassword($password) === $this->password;
    }
    
    /**
     * Generates the password hash.
     * @param string password
     * @param string salt
     * @return string hash
     */
    public function hashPassword($password)
    {
        return md5($this->generateSalt().$password);
    }
    
    /**
     * Generates a salt that can be used to generate a password hash.
     * @return string the salt
     */
    public function generateSalt()
    {
        return sha1($this->ctime);
        //return uniqid('',true);
    }
    
    /**
     * 理财认证状态(通过手机实名+身份认证)
     * 未认证:全未填
     * 待审核:全待审
     * 认证中:开始认证
     * 未通过:有一项未通过
     */
    public function getInvestStatus(){
        if(!$this->mobileAuth && !$this->identityAuth)
            return '未认证';
        if($this->mobileAuth == 1 && $this->identityAuth == 1)
            return '待审核';
        if($this->mobileAuth == 3 || $this->identityAuth == 3)
            return '未通过';
        if($this->mobileAuth == 2 && $this->identityAuth == 2)
            return '已通过';
        return '认证中';
    }
    
    /**
     * 贷款认证状态(通过所有认证)
     */
    public function getDebitStatus(){
        if(!$this->mobileAuth && !$this->identityAuth && !$this->residentAuth && !$this->educationAuth && !$this->videoAuth)
            return '未认证';
        if($this->mobileAuth == 1 && $this->identityAuth == 1 && $this->residentAuth == 1 && $this->educationAuth == 1 && $this->videoAuth == 1)
            return '待审核';
        if($this->mobileAuth == 3 || $this->identityAuth == 3 || $this->residentAuth == 3 || $this->educationAuth == 3 ||  $this->videoAuth == 3)
            return '未通过';
        if($this->mobileAuth == 2 && $this->identityAuth == 2 && $this->residentAuth == 2 && $this->educationAuth == 2 && $this->videoAuth == 2)
            return '已通过';
        return '认证中';
    }
    
    /**
     * 认证状态(后台表示)
     * 通过√;驳回×;未填写○;待审核:1
     * @param string $type 所以认证类型
     */
    public function getAuthStatus($type){
        if(in_array($type,array_keys(self::$authTypeArray))){
            $auth = "{$type}Auth";
            switch ($this->$auth){
                case '0' :
                    return '○';
                    break;
                case '1' :
                    return 1;
                    break;
                case '2' :
                    return '√';
                    break;
                case '3' :
                    return '×';
                    break;
                default :
                    return '';
            }
        }
        return '';
    }
    
}
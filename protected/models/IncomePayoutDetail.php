<?php
/**
 * 收入支出表
 * @author lizheng
 */
class IncomePayoutDetail extends BaseModel
{
    //提现申请
    private $_withdrawApply;
    //收支用户
    private $_user;
    //收支类型
    public static $categoryArray = array(
            0 => '收入',
            1 => '支出'
    );
    //收入或支出的详细类型
    public static $typeArray = array(
            //收入
            0 => '充值',
            1 => '借款方还款',
            2 => '投标失败退回',
            3 => '融资成功收款',
            7 => '提现手续费收入',
            9 => '借贷手续费收入',
            11 => '四舍五入收入',
            //支出
            4 => '还款',
            5 => '提现',
            6 => '出借',
            8 => '提现手续费',
            10 => '借贷手续费',
            12 => '四舍五入支出',
    );
    //收入或支出对应详细类型
    public static $categoryToType = array(
            0 => array(0,1,2,3,7),
            1 => array(4,5,6,8)
    );
    public function tableName() {
    	return '{{income_payout_detail}}';
    }
	/**
	 * 设置关系
	 */
	public function relations(){
	    return array(
	        'user' => array(self::BELONGS_TO,'User','userId')
	    );
	}
	
	public function rules(){
	    return array(
	            array('category','in','range'=>array_keys(self::$categoryArray),'message'=>'收支类型有误!','on'=>'insert'),
	            array('userId','exist','className'=>'application.models.User','attributeName'=>'id','message'=>'用户id"{value}"不存在!','on'=>'insert'),
	            array('type','checkType','on'=>'insert'),
	            array('withdrawApplyId','checkWithdrawApplyId','on'=>'insert'),
	            array('money','checkMoney','on'=>'insert'),
	            array('debitId','checkDebitId','on'=>'insert'),
	            array('debitRepayId','checkDebitRepayId','on'=>'insert'),
	            array('debitFinancingId','checkDebitFinancingId','on'=>'insert'),
	            array('orderNo,orderSource','checkOrderNoAndOrderSource','on'=>'insert'),
	            array('type,money,debitId,orderNo,orderSource,userId,category,debitRepayId,debitFinancingId,nowCashMoney,nowBlockMoney','safe','on'=>'insert')
	    );
	}
	
	/**
	 * 收支详细类型验证方法(checkType)
	 */
	public function checkType($attribute,$param){
	    if(!$this->hasErrors()){
	        if(!in_array($this->type,self::$categoryToType[$this->category])){
	            $this->addError($attribute, '收支详细类型有误!');
	        }
	    }
	}
	/**
	 * 收支金额验证方法(checkMoney)
	 */
	public function checkMoney($attribute,$param){
	    if(!$this->hasErrors()){
	        if(!preg_match('/^\s*[1-9][0-9]*(\.[0-9]{1,2})?\s*$/',"{$this->money}")){
	            $this->addError($attribute, '收支金额格式有误（最多2位小数）！');
	        }elseif($this->category && in_array($this->type,array(4,6))){
	            $user = User::model()->findByPk($this->userId);
	            if($user->cashMoney < $this->money){
	                $this->addError($attribute, '账户余额不足,请先充值!');
	            }
	        }
	        
	    }
	}
	/**
	 * 验证debitId方法(checkDebitId)
	 * 仅当$type为1,2,3,4,6需要否则为null
	 */
	public function checkDebitId($attribute,$param){
        if(!$this->hasErrors()){
            if(!in_array($this->type,array(1,2,3,4,6))){
                $this->debitId = null;
            }elseif(!$this->debitId){
                $this->addError($attribute, '"'.self::$typeArray[$this->type].'"类型时必需填写debitId!');
            }elseif(!($debit = Debit::model()->findByPk($this->debitId))){
                $this->addError($attribute, "debitId({$this->debitId})不存在!");
            }
        }
	}
	/**
	 * 验证debitRepayId方法(checkDebitRepayId)
	 * 仅当$type为1需要否则为null
	 */
	public function checkDebitRepayId($attribute,$param){
	    if(!$this->hasErrors()){
	        if($this->type != 1){
	            $this->debitRepayId = null;
	        }elseif(!$this->debitRepayId){
	            $this->addError($attribute, '"'.self::$typeArray[$this->type].'"类型时必需填写debitRepayId!');
	        }elseif(!($debitReplay = DebitRepayRecord::model()->findByPk($this->debitRepayId))){
	            $this->addError($attribute, "debitRepayId({$this->debitRepayId})不存在!");
	        }elseif($debitReplay->debitId != $this->debitId){
	            $this->addError($attribute, "debitRepayId和debitId不匹配!");
	        }
	    }
	}
	/**
	 * 验证debitFinancingId方法(checkDebitFinancingId)
	 * 仅当$type为1,2,6需要,否则为null
	 */
	public function checkDebitFinancingId($attribute,$param){
	    if(!$this->hasErrors()){
	        if(!in_array($this->type,array(1,2,6))){
	            $this->debitFinancingId = null;
	        }elseif(!$this->debitFinancingId){
	            $this->addError($attribute, '"'.self::$typeArray[$this->type].'"类型时必需填写debitFinancingId!');
	        }elseif(!($debitFinancing = DebitFinancingRecord::model()->findByPk($this->debitFinancingId))){
	            $this->addError($attribute, "debitRepayId({$this->debitRepayId})不存在!");
	        }elseif($debitFinancing->debitId != $this->debitId){
	            $this->addError($attribute, "debitFinancingId和debitId不匹配!");
	        }
	    }
	}
	/**
	 * 验证orderNo和orderSource
	 * 仅当$type为0,5时需要,否则为null
	 */
	public function checkOrderNoAndOrderSource($attribute,$param){
	    if(!$this->hasErrors()){
	        if(!in_array($this->type,array(0,5))){
	            $this->orderNo = $this->orderSource = null;
	        }elseif(!$this->orderNo){
	            $this->addError($attribute, '"'.self::$typeArray[$this->type].'"类型时必需填写orderNo!');
	        }elseif(!$this->orderSource){
	            $this->addError($attribute, '"'.self::$typeArray[$this->type].'"类型时必需填写orderSource!');
	        }
        }
	}
	/**
	 * 验证提现申请单id(checkWithdrawApplyId)
	 * 仅当$type为5需要,否则为null
	 */
    public function checkWithdrawApplyId($attribute,$param){
        if(!$this->hasErrors()){
            if(!in_array($this->type,array(5))){
                $this->withdrawApplyId = null;
            }elseif(!$this->withdrawApplyId){
                $this->addError($attribute, '"'.self::$typeArray[$this->type].'"类型时必需填写withdrawApplyId!');
            }elseif(!($this->_withdrawApply = WithdrawApply::model()->findByPk($this->withdrawApplyId))){
                $this->addError($attribute, "withdrawApplyId({$this->withdrawApplyId})不存在!");
            }elseif($this->_withdrawApply->status != 0){
                $this->addError($attribute, "提现申请状态有误(非申请中状态)!");
            }elseif($this->_withdrawApply->userId != $this->userId){
                $this->addError($attribute, "提现申请单用户id与当前收支用户Id不匹配!");
            }else{
                $this->money = $this->_withdrawApply->money-$this->_withdrawApply->fee;//提现情况下金额由提现申请单金额决定
            }
        }
    }
	
	/**
	 * 设置标签
	 */
	public function attributeLabels() {
	    return array(
	        'category'=>'收支类型'
	    );
	}
	
	public function loadInit($params = array()){}
	
	public function beforeSave(){
		if (parent::beforeSave())
		{
			if ($this->isNewRecord)
			{
				$this->ctime = date('Y-m-d H:i:s');
			}
			!isset(Yii::app()->session['token']['uid']) || $this->operatorId =  Yii::app()->session['token']['uid'];
			return true;
		}
		else
		{
			return false;
		}
	}
	
}
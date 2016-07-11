<?php
/**
 * 提现申请单
 * @author lizheng
 * @property BankCard $bankCard
 * @property User $applyUser
 * @property BlockMoneyDetail $blockMoneyDetail
 */
class WithdrawApply extends BaseModel
{
    /**
     * 提现类型(银行卡,支付宝...)
     * @var array
     */
    public static $typeArray = array(
            0 => '银行卡',
            1 => '支付宝'
    );
    
    public static $statusArray = array(
            0 => '待处理',
            1 => '已完成',
            -1 => '申请失败'
    );
    
    public function tableName() {
    	return '{{withdraw_apply}}';
    }
    
    public function relations(){
        return array(
                'blockMoneyDetail'     => array(self::HAS_ONE,'BlockMoneyDetail','withdrawApplyId','on'=>'blockMoneyDetail.category=1'),
                'bankCard'             => array(self::BELONGS_TO,'BankCard','bankCardId','on'=>'t.type = 0'),
                'applyUser'            => array(self::BELONGS_TO,'User','userId')
        );
    }
    
    public function rules(){
        return array(
                array('money,bankCardId,type','required','message' => '{attribute}不能为空！','on'=>'add'),
                array('money','numerical','integerOnly'=>true,'message'=>'请输入一个整数！','min'=>100,'tooSmall'=>'提现金额不得少于低于100元','on'=>'add'),
                array('money','checkCashAccount','on'=>'add'),
                array('userId,account','safe','on'=>'add')
        );
    }
    
    /**
     * 检验账户余额是否满足提现要求
     */
    public function checkCashAccount($attribute,$params){
        if(!$this->hasErrors()){
            if(Yii::app()->user->id){
                $user = User::model()->findByPk(Yii::app()->user->id);
                if($this->money > $user->cashMoney){
                    $this->addError($attribute, '提现金额超过账号余额，请重新输入！');
                }
            }else{
                $this->addError($attribute, '请先登录后重试！');
            }
        }
    }
    
    /**
     * 设置标签
     */
    public function attributeLabels() {
        return array(
                'money'=>'提现金额',
                'bankCardId'=>'选择银行卡',
                'type'=>'到账时间'
        );
    }
    
    public function loadInit($params = array()){}
    
    public function beforeSave(){
    	if (parent::beforeSave())
    	{
    		if ($this->isNewRecord)
    		{
    			$this->ctime = date('Y-m-d H:i:s');
    			$this->fee = round($this->money * Yii::app()->params['withdrawFee']/100,2);
    		}
    		!isset(Yii::app()->session['token']['uid']) || $this->operatorId =  Yii::app()->session['token']['uid'];
    		$this->utime = date('Y-m-d H:i:s');
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    }
    /**
     * 获取提现类型描述
     * 支付宝:支付宝
     * 银行卡:银行名称+'银行卡'
     */
    public function getTypeName(){
        if($this->isNewRecord)
            return '';
        if($this->type == 0){
            return BankCard::$bankList[$this->bankCard->type].'银行卡';
        }elseif($this->type == 1){
            return self::$typeArray[$this->type];
        }else{
            return '';
        }
    }
    
    public function save($runValidation=true,$attributes=null){
        if($this->isNewRecord){
            $tran = $this->dbConnection->beginTransaction();
            try {
                //保存提现记录
                if(parent::save($runValidation,$attributes)){
                    //扣除现金金额,加上冻结金额
                    User::model()->updateByPk($this->userId,array('cashMoney'=>new CDbExpression("cashMoney-{$this->money}"),'blockMoney'=>new CDbExpression("blockMoney+{$this->money}")));
                    //生成冻结记录
                    $blockMoney = new BlockMoneyDetail();
                    $blockMoney->attributes = array('userId'=>$this->userId,'money'=>$this->money,'withdrawApplyId'=>$this->id,'category'=>1);
                    $blockMoney->save(false);
                    $tran->commit();
                    return true;
                }else{
                    $tran->rollback();
                    return false;
                }
            }catch(Exception $e){
                $tran->rollback();
                return false;
            }
        }else{
            return parent::save($runValidation,$attributes);
        }
    }
    
    /**
     * 提现成功流程
     * 1数据校验2更新提现用户冻结金额状态同时扣除冻结金额3更新平台账号余额(收入手续费)4提现用户支出手续费5保存用户提现支出6更新提现申请状态
     * @param array $param
     */
    public function finishWithdraw($orderNo = 'ceshino1234567890',$orderSource = '测试银行'){
        if(!$orderNo || !$orderSource){
            return false;
        }
        if($this->isNewRecord || ($this->status != 0)){
            return false;
        }
        if(!($blockRecord = $this->blockMoneyDetail)){
            return false;
        }
        if(!($user = $this->applyUser)){
            return false;
        }
        $tran = $this->dbConnection->beginTransaction();
        try {
            //4提现用户支出手续费
            $user->blockMoney -= $this->fee;
            $user->update(array('blockMoney'));
            $userPayFee = new IncomePayoutDetail();
            $userPayFee->attributes = array('nowCashMoney'=>$user->cashMoney,'nowBlockMoney'=>$user->blockMoney,'userId'=>$this->userId,'category'=>1,'type'=>8,'withdrawApplyId'=>$this->id,'money'=>$this->fee);
            $userPayFee->save(false);
            //3更新平台账号收入
            User::model()->updateByPk(Yii::app()->params['adminId'],array('cashMoney'=>new CDbExpression("cashMoney+{$this->fee}")));
            $adminIncome = new IncomePayoutDetail();
            $adminIncome->attributes = array('nowCashMoney'=>new CDbExpression("(select cashMoney from p2p_user where id=".Yii::app()->params['adminId'].")"),'nowBlockMoney'=>new CDbExpression("(select blockMoney from p2p_user where id=".Yii::app()->params['adminId'].")"),'userId'=>Yii::app()->params['adminId'],'category'=>0,'type'=>7,'withdrawApplyId'=>$this->id,'money'=>$this->fee);
            $adminIncome->save(false);
            //5保存用户提现支出
            $user->blockMoney -= ($this->money-$this->fee);
            $user->update(array('blockMoney'));
            $userPayWithdraw = new IncomePayoutDetail();
            $userPayWithdraw->attributes = array('nowCashMoney'=>$user->cashMoney,'nowBlockMoney'=>$user->blockMoney,'userId'=>$this->userId,'category'=>1,'type'=>5,'money'=>$this->money-$this->fee,'withdrawApplyId'=>$this->id,'orderNo'=>$orderNo,'orderSource'=>$orderSource);
            $userPayWithdraw->save(false);
            //2更新提现用户冻结金额
            $blockRecord->status = 1;//冻结结束
            $blockRecord->endTime = date('Y-m-d H:i:s');//冻结结束时间
            $blockRecord->save(false);
            //6更新申请单状态
            $this->status = 1;
            $this->save(false);
            $tran->commit();
            return true;
        }catch (Exception $e){
            var_dump($e);
            $tran->rollback();
            return false;
        }
    }
    
    /**
     * 提现审核不通过流程
     * 1提现申请状态修改2冻结金额返还
     * @param string $comment 拒绝理由
     */
    public function refuseWithdraw($comment = ''){
        if(!($comment = trim($comment))){
            return false;
        }
        if($this->isNewRecord || ($this->status != 0)){
            return false;
        }
        if(!($blockRecord = $this->blockMoneyDetail)){
            return false;
        }
        if(!($user = $this->applyUser)){
            return false;
        }
        $tran = $this->dbConnection->beginTransaction();
        try {
            //冻结金额返还
            $user->cashMoney += $this->money;
            $user->blockMoney -= $this->money;
            $user->update(array('cashMoney','blockMoney'));
            $blockRecord->status = 1;//冻结结束
            $blockRecord->endTime = date('Y-m-d H:i:s');//冻结结束时间
            $blockRecord->save(false);
            //提现申请状态修改,添加失败原因
            $this->status = -1;
            $this->comment = $comment;
            $this->save(false);
            $tran->commit();
            return true;
        } catch (Exception $e) {
            $tran->rollback();
            return false;
        }
    }
    
}
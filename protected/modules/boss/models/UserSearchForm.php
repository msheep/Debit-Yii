<?php
/**
 * 用户列表查询form
* @author lizheng
*/
class UserSearchForm extends CFormModel
{
    public static $typeArray = array(
            'id' => '会员Id',
            'userName' => '用户名'
    );
    
    public static $moneyTypeArray = array(
            'cashMoney' => '可用余额',
            'blockMoney' => '冻结金额',
            'refundMoney' => '待还金额',
            'incomingMoney' => '待收金额'
    );
    
    public static $moneyTypeOpArray = array(
            '>' => '大于',
            '=' => '等于',
            '<' => '小于'
    );
    
    /**
     * 查询类型
     * @var int
     */
    public $type;
    
    /**
     * 待查询值
     * @var string
     */
    public $condition;
    /**
     * 注册开始时间
     */
    public $fromTime;
    /**
     * 注册结束时间
     */
    public $toTime;
    /**
     * 审核未通过条件标志
     * @var bool
     */
    public $noPass = false;
    /**
     * 欠款中查询标志
     * @var bool
     */
    public $hasRefund = false;
    /**
     * 无欠款查询标志
     * @var bool
     */
    public $noRefund = false;
    /**
     * 账号冻结查询标志
     * @var bool
     */
    public $isBlock = false;
    
    /**
     * 待查询金额类型
     * @var string
     */
    public $moneyType;
    /**
     * 待查询金额比较符
     * @var string
     */
    public $moneyTypeOp;
    /**
     * 待查询金额数目
     * @var double
     */
    public $moneyTypeValue;
    
    public function rules()
    {
        return array(
                array('condition','required','message'=>'请输入查询条件!','on'=>'userView'),//会员详情页查询
                
                array('noPass','safe','on'=>'userAuthList'),//会员资料审核列表
                
                array('hasRefund,noRefund,isBlock','safe','on'=>'userList'),//会员列表
                
                array('moneyTypeValue','numerical','allowEmpty' => true ,'on'=>'userReportList'),//会员报表
                array('moneyTypeOp,moneyType','safe','on'=>'userReportList'),//会员报表
                
                array('fromTime,toTime','safe','on'=>'userList,userAuthList,userReportList'),
                
                array('type,condition','safe'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
                
        );
    }
    
    /**
     * 后台会员列表
     */
    public function searchUserList(){
        $criteria=new CDbCriteria();
        
        if($this->fromTime){
            $criteria->compare('t.ctime',">= {$this->fromTime} 00:00:00");//开始时间
        }
        if($this->toTime){
            $criteria->compare('t.ctime',"<= {$this->toTime} 23:59:59");//结束时间
        }
        
        if(isset(self::$typeArray[$this->type]) && ($this->condition = trim($this->condition))){
            switch ($this->type){
                case 'id' : {
                    $criteria->compare('t.id', $this->condition);
                    break;
                }
                case 'userName' : {
                    $criteria->compare('t.userName', $this->condition, true);
                    break;
                }
            }
        }else{
            $this->condition = '';
        }
        
        //设置欠款中,无欠款,冻结标志(or关系)
        $cols = array();
        !$this->noRefund || $cols[] = 't.refundMoney = 0';
        !$this->hasRefund || $cols[] = 't.refundMoney > 0';
        !$this->isBlock || $cols[] = 't.status = 0';
        if($cols){
            $criteria->addCondition(implode(' OR ', $cols));
        }

        return new CActiveDataProvider('User', array(
                'criteria'=>$criteria,
                'sort'=>array(
                        'defaultOrder'=>'t.ctime DESC',
                ),
        ));
    }
    
    /**
     * 后台会员资料审核列表
     */
    public function searchUserAuthList(){
        $criteria=new CDbCriteria();
    
        if($this->fromTime){
            $criteria->compare('t.ctime',">= {$this->fromTime} 00:00:00");//开始时间
        }
        if($this->toTime){
            $criteria->compare('t.ctime',"<= {$this->toTime} 23:59:59");//结束时间
        }
    
        if(isset(self::$typeArray[$this->type]) && ($this->condition = trim($this->condition))){
            switch ($this->type){
                case 'id' : {
                    $criteria->compare('t.id', $this->condition);
                    break;
                }
                case 'userName' : {
                    $criteria->compare('t.userName', $this->condition, true);
                    break;
                }
            }
        }else{
            $this->condition = '';
        }
        
        if($this->noPass){
            $criteria->addColumnCondition(array('t.mobileAuth'=>3,'t.identityAuth'=>3,'t.residentAuth'=>3,'t.educationAuth'=>3,'t.videoAuth'=>3),'OR');
        }
        
        return new CActiveDataProvider('User', array(
                'criteria'=>$criteria,
                'sort'=>array(
                        'defaultOrder'=>'t.ctime DESC',
                ),
        ));
    }
    
    /**
     * 会员详情页单用户检索
     */
    public function searchUserView(){
        if(isset(self::$typeArray[$this->type]) && ($this->condition = trim($this->condition))){
            $criteria=new CDbCriteria();
            $criteria->compare("t.{$this->type}", $this->condition);
            return User::model()->find($criteria);
        }else{
            return null;
        }
    }
    
    /**
     * 用户报表页检索
     */
    public function searchUserReportList(){
        $criteria=new CDbCriteria();
        
        if($this->fromTime){
            $criteria->compare('t.ctime',">= {$this->fromTime} 00:00:00");//开始时间
        }
        if($this->toTime){
            $criteria->compare('t.ctime',"<= {$this->toTime} 23:59:59");//结束时间
        }
        
        if(isset(self::$typeArray[$this->type]) && ($this->condition = trim($this->condition))){
            switch ($this->type){
                case 'id' : {
                    $criteria->compare('t.id', $this->condition);
                    break;
                }
                case 'userName' : {
                    $criteria->compare('t.userName', $this->condition, true);
                    break;
                }
            }
        }else{
            $this->condition = '';
        }
        
        //金额比较
        if($this->moneyType && $this->moneyTypeOp && ($this->moneyTypeValue != '') && is_numeric($this->moneyTypeValue) && isset(self::$moneyTypeArray[$this->moneyType]) && isset(self::$moneyTypeOpArray[$this->moneyTypeOp])){
            $criteria->compare("t.{$this->moneyType}", "{$this->moneyTypeOp} {$this->moneyTypeValue}");
        }
        
        return new CActiveDataProvider('User', array(
                'criteria'=>$criteria,
                'sort'=>array(
                        'defaultOrder'=>'t.ctime DESC',
                ),
        ));
        
    }
    
    /**
     * 用户报表导出
     * @return CDbDataReader the reader object for fetching the query result
     */
    public function exportUserReportList(){
        $command = Yii::app()->db->createCommand();
        //设置from
        $command->from('{{user}} t');
        //设置join
        //设置查询条件
        if($this->fromTime){
            $command->andWhere('t.ctime >= :from',array(':from'=>"{$this->fromTime} 00:00:00"));
        }
        if($this->toTime){
            $command->andWhere('t.ctime <= :to',array(':to'=>"{$this->toTime} 23:59:59"));
        }
        if(isset(self::$typeArray[$this->type]) && ($this->condition = trim($this->condition))){
            switch ($this->type){
                case 'id' : {
                    $command->andWhere('t.id = :id',array(':id'=>$this->condition));
                    break;
                }
                case 'userName' : {
                    $command->andWhere('t.userName like :userName',array(':userName'=>"%{$this->condition}%"));
                    break;
                }
            }
        }
        //金额比较
        if($this->moneyType && $this->moneyTypeOp && is_numeric($this->moneyTypeValue) && isset(self::$moneyTypeArray[$this->moneyType]) && isset(self::$moneyTypeOpArray[$this->moneyTypeOp])){
            $command->andWhere("t.{$this->moneyType} {$this->moneyTypeOp} :{$this->moneyType}",array(":{$this->moneyType}" => $this->moneyTypeValue));
        }
        //设置select
        $command->select('t.id,t.userName,t.cashMoney,t.blockMoney,t.refundMoney,t.incomingMoney,t.identityAuth,t.mobileAuth,t.residentAuth,t.educationAuth,t.videoAuth,t.ctime,t.utime,t.lastLoginTime,t.mobile,t.status,t.creditRating,t.rank');
        return $command->query();
    }
}


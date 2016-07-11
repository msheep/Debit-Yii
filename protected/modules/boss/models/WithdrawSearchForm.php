<?php
/**
 * 提现列表查询form
* @author lizheng
*/
class WithdrawSearchForm extends CFormModel
{
    public static $typeArray = array(
            'id' => '提现流水号',
            'userId' => '会员id',
            'userName' => '用户名'
    );
    
    /**
     * 查询类型
     * @var string
     */
    public $type;
    /**
     * 待查询值
     * @var string
     */
    public $condition;
    /**
     * 申请提交开始时间
     */
    public $fromTime;
    /**
     * 申请提交结束时间
     */
    public $toTime;
    /**
     * 审核未通过条件标志
     * @var bool
     */
    public $refuse = false;
    /**
     * 完成条件标志
     * @var bool
     */
    public $pass = false;
    /**
     * 待处理条件标志
     * @var bool
     */
    public $pending = false;
    /**
     * 账号冻结查询标志
     * @var bool
     */
    public $isBlock = false;
    
    public function rules()
    {
        return array(
                
                array('refuse,pass,pending,fromTime,toTime','safe','on'=>'withdrawList'),//提现列表
                
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
     * 后台提现列表
     */
    public function searchWithdrawList(){
        $criteria=new CDbCriteria();
        //获取user详情,银行卡详情
        $criteria->with = array('applyUser','bankCard');
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
                    $criteria->compare('applyUser.userName', $this->condition, true);
                    break;
                }
                case 'userId' : {
                    $criteria->compare('t.userId', $this->condition);
                    break;
                }
            }
        }else{
            $this->condition = '';
        }
        
        //设置欠款中,无欠款,冻结标志(or关系)
        $cols = array();
        !$this->pass || $cols[] = 't.status = 1';
        !$this->refuse || $cols[] = 't.status = -1';
        !$this->pending || $cols[] = 't.status = 0';
        if($cols){
            $criteria->addCondition(implode(' OR ', $cols));
        }

        return new CActiveDataProvider('WithdrawApply', array(
                'criteria'=>$criteria,
                'sort'=>array(
                        'defaultOrder'=>'t.ctime DESC',
                ),
        ));
    }
    
    /**
     * 后台提现列表导出
     * @return CDbDataReader the reader object for fetching the query result
     */
    public function exportWithdrawList(){
        $command = Yii::app()->db->createCommand();
        //设置from
        $command->from('{{withdraw_apply}} w');
        //设置join(用户,银行卡)
        $command->leftJoin('{{user}} u','w.userId = u.id');
        $command->leftJoin('{{bank_card}} b','w.bankCardId = b.id and w.type = 0');
        //设置查询条件
        if($this->fromTime){
            $command->andWhere('w.ctime >= :from',array(':from'=>"{$this->fromTime} 00:00:00"));
        }
        if($this->toTime){
            $command->andWhere('w.ctime <= :to',array(':to'=>"{$this->toTime} 23:59:59"));
        }
        if(isset(self::$typeArray[$this->type]) && ($this->condition = trim($this->condition))){
            switch ($this->type){
                case 'id' : {
                    $command->andWhere('w.id = :id',array(':id'=>$this->condition));
                    break;
                }
                case 'userName' : {
                    $command->andWhere('u.userName like :userName',array(':userName'=>"%{$this->condition}%"));
                    break;
                }
                case 'userId' : {
                    $command->andWhere('w.userId = :userId',array(':userId'=>$this->condition));
                    break;
                }
            }
        }
        //设置欠款中,无欠款,冻结标志(or关系)
        $cols = array();
        !$this->pass || $cols[] = 'w.status = 1';
        !$this->refuse || $cols[] = 'w.status = -1';
        !$this->pending || $cols[] = 'w.status = 0';
        if($cols){
            array_unshift($cols, 'OR');
            $command->andWhere($cols);
        }
        //设置select
        $command->select('w.id,w.userId,w.money,w.fee,w.ctime,w.utime,w.type,w.status,w.account,b.card,b.type as bankType,b.bankName,w.comment,u.userName');
        return $command->query();
    }
}
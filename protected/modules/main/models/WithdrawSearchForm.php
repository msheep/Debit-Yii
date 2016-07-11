<?php
/**
 * 提现列表查询form
* @author lizheng
*/
class WithdrawSearchForm extends CFormModel
{
    /**
     * 提现状态
     * @var int
     */
    public $status;
    /**
     * 开始时间
     */
    public $fromTime;
    /**
     * 结束时间
     */
    public $toTime;
    
    public function rules()
    {
        return array(
                array('status,fromTime,toTime','safe')
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

    public function search(){
        $criteria=new CDbCriteria(array('with'=>array('bankCard')));
        
        if($this->fromTime){
            $criteria->compare('t.ctime',">= {$this->fromTime} 00:00:00");//开始时间
        }
        if($this->toTime){
            $criteria->compare('t.ctime',"<= {$this->toTime} 23:59:59");//结束时间
        }
        $criteria->compare('status',$this->status);//状态

        return new CActiveDataProvider('WithdrawApply', array(
                'criteria'=>$criteria,
                'sort'=>array(
                        'defaultOrder'=>'t.ctime DESC',
                ),
        ));
    }

}


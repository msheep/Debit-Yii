<?php 
/**
 * 冻结资金表
 * @author lizheng
 *
 */
class BlockMoneyDetail extends BaseModel{
    
    public function tableName(){
        return '{{block_money_detail}}';
    }
    
    public function rules(){
        return array(
                array('userId,money,debitId,debitFinancingId,endTime,category,withdrawApplyId,status','safe','on'=>'insert')
        );
    }
    
    public function loadInit($params = array()){
    }
    
    public function beforeSave(){
        if (parent::beforeSave())
        {
            if ($this->isNewRecord)
            {
                $this->startTime = date('Y-m-d H:i:s');
            }
            return true;
        }
        else
        {
            return false;
        }
        
    }
    
}
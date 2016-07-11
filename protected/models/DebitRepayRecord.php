<?php
class DebitRepayRecord extends BaseModel {
    public static $status = array(
        0=>'待还款',
        1=>'提前还款',
        2=>'准时还款',
        3=>'逾期还款',
        4=>'欠款',
    );
    
	public function tableName() {
		return '{{debit_repay_record}}';
	}
	
    public function loadInit($params = array()){}
	
    public function rules() {
		return array(
			
		);
	}
	
    public function relations(){
        return array(
        	'debitInfo'=> array(self::BELONGS_TO,'Debit','debitId'),
        );
    }
	
    public function attributeLabels(){
		return array(
				
		);
	}
	
    public function beforeSave() {
		if (parent::beforeSave()) {
			if ($this->isNewRecord){
			    $this->status = 0;
				$this->ctime = date('Y-m-d H:i:s');
			}else{
			    $this->utime = date('Y-m-d H:i:s');
			}
			return true;
		}else{
			return false;
		}
	}
	
	public static function getHaveRepayMoney($debitId, $money){
	    $sql = 'SELECT count(id) 
				FROM p2p_debit_repay_record
				WHERE debitId = :debitId AND status = 1';
        $command = Yii::app()->db->createCommand($sql);
        $command -> bindParam(':debitId', $debitId, PDO::PARAM_INT);
		$number = $command->queryScalar();
		if(!$number){
		    $results = 0;
		}else{
		    $debitModel = Debit::model()->findByPk($debitId);
		    $monthMoney = Debit::getMonthRepay($debitModel->debitMoney, $debitModel->debitDeadline, $debitModel->debitRate, $money);
		    $results = $number * $monthMoney;
		}
	    return $results;
	}

}
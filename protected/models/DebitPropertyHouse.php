<?php
class DebitPropertyHouse extends BaseModel {
    public function tableName() {
		return '{{debit_product_property_house}}';
	}
	
    public function loadInit($params = array()){}
    
    public function rules() {
		return array(
			array('roomNum','required','message'=>'请填写房源户型'),
			array('roomNum', 'numerical','message' => '请填写1-10间的数字'),
			array('hallNum,toiletNum', 'numerical','message' => '请填写0-10间的数字'),
			array('roomNum','compare','compareValue' => '0','operator'=>'>','message'=>'请填写1-10间的数字'),
			array('hallNum,toiletNum', 'compare','compareValue' => '0','operator'=>'>=','message'=>'请填写0-10间的数字'),
			array('houseBasementArea,houseGiveArea', 'compare','compareValue' => '0','operator'=>'>=','message'=>'请填写大于0的数字'),
			array('roomNum,hallNum,toiletNum', 'numerical','integerOnly'=>'true','message' => '请填写0-10间的数字'),
		);
	}
    public function attributeLabels(){
		return array(
			'roomNum'=>'室',
			'hallNum'=>'厅',
			'toiletNum'=>'卫 ',
		    
		);
	}
	
    public function beforeSave() {
		if (parent::beforeSave()) {
			if($this->isNewRecord){
				$this->ctime = date('Y-m-d H:i:s');
				$this->userId = Yii::app()->user->id;
			}
			return true;
		}else{
			return false;
		}
	}
}
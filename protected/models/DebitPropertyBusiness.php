<?php
class DebitPropertyBusiness extends BaseModel {
    public function tableName() {
		return '{{debit_product_property_business}}';
	}
	
    public function loadInit($params = array()){}
    
    public function rules() {
		return array(
			array('housesName','required','message'=>'请填写房源户型'),
		);
	}
    public function attributeLabels(){
		return array(
			'housesName'=>'楼盘名称',
		    
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
<?php
class DebitFinancingRecord extends BaseModel {
    
	public function tableName() {
		return '{{debit_financing_record}}';
	}
	
    public function loadInit($params = array()){}
	
    public function rules() {
		return array(
			
		);
	}
    public function attributeLabels(){
		return array(
				
		);
	}
	
    public function beforeSave() {
		if (parent::beforeSave()) {
			if ($this->isNewRecord){
				$this->ctime = date('Y-m-d H:i:s');
			}
			return true;
		}else{
			return false;
		}
	}

}
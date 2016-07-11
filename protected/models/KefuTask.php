<?php
class KefuTask extends BaseModel {
    
	public function tableName() {
		return '{{kefu_task}}';
	}
	
    public function loadInit($params = array()){}
	
	public function beforeSave() {
		if (parent::beforeSave()) {
			if($this->isNewRecord){
				$this->ctime = date('Y-m-d H:i:s');
				$this->content = mysql_escape_string($this->content);
			}
			return true;
		}else{
			return false;
		}
	}
}
<?php
class UserNotify extends BaseModel {
    
	public function tableName() {
		return '{{user_notify}}';
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
	
	public function relations(){
	    return array(
	            'fromAdmin' => array(self::BELONGS_TO,'OauthAdmin','fromId','on'=>'`t`.`type`=0'),
	            'fromUser' => array(self::BELONGS_TO,'User','fromId','on'=>'`t`.`type`=1')
	    );
	}
	
	
    public function beforeSave() {
		if (parent::beforeSave()) {
			if ($this->isNewRecord){
				$this->ctime = date('Y-m-d H:i:s');
			}else{
			    $this->utime = date('Y-m-d H:i:s');
			}
			return true;
		}else{
			return false;
		}
	}

}
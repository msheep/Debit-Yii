<?php
class OauthToken extends BaseModel {
    
	public function tableName() {
		return '{{oauth_token}}';
	}
	
    public function loadInit($params = array()){}

}
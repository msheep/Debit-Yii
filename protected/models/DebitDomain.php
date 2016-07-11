<?php
class DebitDomain extends BaseModel {
    
    public static $suffix = array(
        '1' => '.com',
        '2' => '.net',
        '3' => '.cn',
    	'4' => '.cc',
        '5' => '.org',
    	'6' => '.com.cn',
        '7' => '.net.cn',
    	'8' => '.org.cn',
        '9' => '.gov.cn',
        '10' => '.mobi',
    	'11' => '.asia',
        '12' => '.me',
        '13' => '.hk',
    	'14' => '.tv',
        '15' => '.name',
    	'16' => '.biz',
        '17' => '.info',
    	'18' => '.co',
        '19' => '.so',
        '20' => '.pw',
        '21' => '.中国',
    	'22' => '.省份域名',
    	'22' => '其他',
    );
    
    public static $worth = array(
        '1' => '关注群体极高的行业名词、常用名词类',
        '2' => '全球知名、有影响力的企业、网站的相关域名',
        '3' => '全球范围内认知度比较高的明星、名人',
    	'4' => '地名、文物',
        '5' => '普通的行业名词、物品名词用语类、知名度有限的企业、网站域名',
    	'6' => '简短、容易记忆且有一定的意义',
        '7' => '有意义但不简短',
    	'8' => '其他',
    );
    
	public function tableName() {
		return '{{debit_product_domain}}';
	}
	
    public function loadInit($params = array()){}
	
    public function rules() {
		return array(
			array('owner,serviceProvider,register,deadLine,registrationDate,lastPayDate,deadLine','required','message'=>'{attribute}不能为空'),
		);
	}
    public function attributeLabels(){
		return array(
				'owner' => '域名所有者',
				'serviceProvider' => '服务提供商',
				'deadLine' => '域名到期时间',
		);
	}
	
	public function beforeSave() {
		if (parent::beforeSave()) {
			if($this->isNewRecord){
				$this->ctime = date('Y-m-d H:i:s');
			}
			return true;
		}else{
			return false;
		}
	}
}
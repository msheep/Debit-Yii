<?php
class DebitCar extends BaseModel {
    
    //变速箱
    public static $gearBox = array(
     	'0'=>'手动档',
     	'1'=>'自动档'
    );
     
    //排量
    public static $output = array(
     	'1'=>'0.8L',
     	'2'=>'1.0L',
    	'3'=>'1.3L',
    	'4'=>'1.6L',
    	'5'=>'1.8L',
     	'6'=>'2.0L',
    	'7'=>'2.4L',
    	'8'=>'2.8L',
    	'9'=>'3.0L',
    	'10'=>'4.0L',
    );
    
    //事故情况
    public static $accident = array(
     	'1'=>'无重大事故，仅有小碰擦',
     	'2'=>'中等事故',
    	'3'=>'有重大事故',
    );
    
    //车牌号码
    public static $carBelong = array(
     	"11"=>'京',
        "12"=>'津',
        "13"=>'冀',
        "14"=>'晋',
        "15"=>'蒙',
        "21"=>'辽',
        "22"=>'吉',
        "23"=>'黑',
        "31"=>'沪',
        "32"=>'苏',
        "33"=>'浙',
        "34"=>'皖',
        "35"=>'闽',
        "36"=>'赣',
        "37"=>'鲁',
        "41"=>'豫',
        "42"=>'鄂',
        "43"=>'湘',
        "44"=>'粤',
        "45"=>'桂',
        "46"=>'琼',
        "50"=>'渝',
        "51"=>'川',
        "52"=>'贵',
        "53"=>'云',
        "54"=>'藏',
        "61"=>'陕',
        "62"=>'甘',
        "63"=>'青',
        "64"=>'宁',
        "65"=>'新',
        "71"=>'台',
    );
    
    //车型
    public static $carType = array(
     	"01"=>'大型汽车',
        "02"=>'小型汽车',
        "03"=>'使馆汽车',
        "04"=>'领馆汽车',
        "05"=>'境外汽车',
        "06"=>'外籍汽车',
//        "07"=>'两三轮摩托车',
//        "08"=>'轻便摩托车',
//        "09"=>'使馆摩托车',
//        "10"=>'领馆摩托车',
//        "11"=>'境外摩托车',
//        "12"=>'外籍摩托车',
//        "13"=>'农用运输',
//        "14"=>'拖拉机',
//        "15"=>'挂车',
        "16"=>'教练汽车',
//        "17"=>'教练摩托车',
        "18"=>'试验汽车',
//        "19"=>'试验摩托车',
        "20"=>'临时入境汽车',
//        "21"=>'临时入境摩托车',
        "22"=>'临时行驶车',
        "23"=>'公安警车',
        "99"=>'其它',
    );
    
    //证件状态
    public static $status = array(
     	'1'=>'有',
     	'2'=>'补办中',
    	'3'=>'丢失',
    );
    
	public function tableName() {
		return '{{debit_product_car}}';
	}
	
    public function loadInit($params = array()){}
    
    public function relations(){
	    return array(
	    	'registrImage' => array(self::HAS_ONE,'File','relateId','condition'=>'category=5'),
	    	'driveImage' => array(self::HAS_ONE,'File','relateId','condition'=>'category=6'),
	        'receiptImage' => array(self::HAS_ONE,'File','relateId','condition'=>'category=7'),
	        'carImage' => array(self::HAS_MANY,'File','relateId','condition'=>'category=8'),
	    );
	}
	
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
			if($this->isNewRecord){
				$this->ctime = date('Y-m-d H:i:s');
			}
			return true;
		}else{
			return false;
		}
	}

}
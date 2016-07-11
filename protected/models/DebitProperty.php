<?php
class DebitProperty extends BaseModel {
    //房产类型
    public static $propertyType = array(
        '1' => '住宅',
    	'2' => '商业用房',
        '3' => '商住两用',
    );
    //房屋产权性质
    public static $propertyCharacter = array(
        '1' => '商品房产权',
    	'2' => '经济适用住房产权',
        '3' => '已购公房(房改房)产权',
    );
    //住宅房屋类型
    public static $houseProperty = array(
        '1' => '普通商品房',
    	'2' => '集资房',
        '3' => '房改房',
    	'4' => '安置房',
    	'5' => '平房/四合院',
    	'6' => '危旧房',
    	'7' => '改造回迁房',
    	'8' => '公房',
        '9' => '别墅',
    	'10' => '其他',
    );
    //房屋房型
    public static $buildingType = array(
    	'1' => '高层',
    	'2' => '小高层',
        '3' => '公寓',
    	'4' => '跃层',
    	'5' => '复式',
    	'6' => '错层',
    	'7' => '洋房',
    	'8' => '其他',
    );
    //房屋朝向
    public static $towards = array(
        '1' => '东',
        '2' => '南',
    	'3' => '西',
    	'4' => '北',
        '5' => '东南',
    	'6' => '东北',
    	'7' => '西南',
        '8' => '西北',
		'9' => '南北',
    	'10' => '东西',        
    );
    //房屋采光通风
    public static $houseLight = array(
        '1'=>'无暗房/南北通风',
        '2'=>'南北通风',
        '3'=>'通风一般',
        '4'=>'通风差'
    );
    //房屋景观
    public static $houseLandscape = array(
        '1'=>'景观房',
        '2'=>'侧景观房',
        '3'=>'景观一般',
        '4'=>'有遮挡'
    );
    //房屋小区位置
    public static $houseLocation = array(
        '1'=>'离小区出口近',
        '2'=>'离小区出口远',
        '3'=>'离小区出口距离适中',
    );
    //房屋噪音
    public static $houseNoise = array(
    	'1'=>'无噪音',
    	'2'=>'噪音较小',
    	'3'=>'噪音较大'
    );
    //房屋厌恶设施
    public static $hateFacility = array(
    	'1'=>'很近',
    	'2'=>'较近',
    	'3'=>'无'
    );
    //房屋厌恶因素
    public static $hateFactor = array(
    	'1'=>'变电站',
    	'2'=>'垃圾场',
    	'3'=>'发射塔'
    );
    //房屋装修程度
    public static $fitment = array(
        '1' => '简单装修',
    	'2' => '精装修',
    	'3' => '中等装修',
        '4' => '豪华装修',
    	'5' => '毛坯',
    );
    //小区配套设施
    public static $houseSupport = array(
        '1' => '健身房',
    	'2' => '会所',
        '3' => '网球场',
    	'4' => '棋牌室',
    	'5' => '停车场',
    	'6' => '活动中心',
    );
    //住宅状态
    public static $houseStatus = array(
        '1' => '居住中',
        '2' => '空房',
    );
    //商业用房类型
    public static $businessType = array(
        '1' => '商铺',
    	'2' => '写字楼',
    	'3' => '厂房/仓库/土地',
    );
    //商铺类型
    public static $shopType = array(
        '1' => '商业街商铺',
    	'2' => '社区住宅底商',
        '3' => '写字楼配套',
    	'4' => '宾馆酒店',
    	'5' => '旅游点商铺',
    	'6' => '主题卖场',
    	'7' => '百货/购物中心',
    	'8' => '其他',
    );
    //铺面类型
    public static $shopFaceType = array(
        '1'=>'店铺',
        '2'=>'摊位',
        '3'=>'柜台'
    );
    //写字楼类型
    public static $officeType = array(
        '1' => '纯写字楼',
    	'2' => '商住楼',
        '3' => '商业综合体楼',
    	'4' => '商务中心',
    	'5' => '商业综合体楼',
    );
    //厂房/仓库/土地类型
    public static $plantType = array(
        '1' => '仓库',
    	'2' => '车库',
        '3' => '土地',
    	'4' => '厂房',
    	'5' => '其他',
    );
    //商业用房配套设施
    public static $bussinesSupport = array(
        '1' => '客梯',
    	'2' => '货梯',
        '3' => '停车位',
    	'4' => '暖气',
    	'5' => '空调',
    	'6' => '网络',
    );
    //商铺可经营类别
    public static $manageType = array(
        '1' => '餐饮美食',
    	'2' => '服饰鞋包',
        '3' => '休闲娱乐',
    	'4' => '美容美发',
    	'5' => '生活服务',
    	'6' => '百货超市',
        '7' => '酒店宾馆',
        '8' => '家居建材',
		'9' => '其他',
    );
    //商铺状态
    public static $shopStatus = array(
        '1' => '营业中',
    	'2' => '空铺',
        '3' => '新铺',
    );
    //产权年限
    public static $useTime = array(
        '70' => '70年',
        '50' => '50年',
    	'40' => '40年',
    );
    //贷款类型
    public static $loanType = array(
        '1' => '公积金贷款',
        '2' => '商业贷款',
    	'3' => '组合贷款',
    );
    
    //是否拥有
    public static $ifhave = array(
     	'0'=>'无',
     	'1'=>'有'
     );
     //是否
     public static $if = array(
     	'0'=>'否',
     	'1'=>'是'
     );
     public static $rank = array(
     	'1'=>'甲级',
     	'2'=>'乙级',
     	'3'=>'丙级'
     );
     public static $relation  = array(
         '1'=>'夫妻',
         '2'=>'子女',
         '3'=>'父母'
     );
     public static $fitmentTime = array(
     	'1'=>'一年以内',
     	'2'=>'二年以内',
     	'3'=>'三年以内',
     	'4'=>'四年以内',
     	'5'=>'五年以内',
     	'6'=>'六年以内',
     	'7'=>'七年以内',
     	'8'=>'八年以内',
     	'9'=>'九年以内',
     	'10'=>'十年以内',
     	'11'=>'十年以上',
     );
    
	public function tableName() {
		return '{{debit_product_property}}';
	}
	
    public function relations(){
	    return array(
	        'business' => array(self::HAS_ONE,'DebitPropertyBusiness','relatedProductId'),
	    	'house' => array(self::HAS_ONE,'DebitPropertyHouse','relatedProductId'),
	    	'image' => array(self::HAS_MANY,'File','relateId','condition'=>'category=4')
	    );
	}
	
    public function loadInit($params = array()){}
	
    public function rules() {
		return array(
			array('address,totalArea,useArea,perCost,cleanFee,floor,allFloor,year,landCertificateId,landOwner,houseCertificateId,houseOwner','required','message'=>'{attribute}不可为空'),
			array('totalArea,useArea,cleanFee,floor,allFloor', 'numerical','message' => '{attribute}填写错误'),
			array('totalArea,useArea,allFloor', 'compare','compareValue' => '0','operator'=>'>','message'=>'{attribute}必须大于0'),
			array('cleanFee', 'compare','compareValue' => '0','operator'=>'>=','message'=>'{attribute}必须大于不能小于0'),
			array('year', 'numerical','integerOnly'=>'true','message' => '{attribute}填写错误'),
            array('cleanFee,rentMoney', 'match','pattern' => '/^\d+(\.\d{1,1})?$/','message'=>'{attribute}最多保留小数点后一位'),
            array('allFloor', 'compare','compareAttribute' => 'floor','operator'=>'>=','message'=>'所在楼层不能大于总楼层'),
		);
	}
    public function attributeLabels(){
		return array(
			'provinceId'=>'省',	
    		'cityId'=>'市',	
    		'areaId'=>'区',	
			'address'=>'地址详情',	
		    'totalArea'=>'建筑面积',	
			'useArea'=>'使用面积',
		    'perCost'=>'购买单价',
			'cleanFee'=>'物业费',
			'year'=>'建筑年代',
			'allFloor'=>'总楼层',
			'floor'=>'所在楼层',
		
		    'landCertificateId'=>'土地证号',
			'landOwner'=>'土地证所有人',
			'houseCertificateId'=>'房屋所有权证号',
			'houseOwner'=>'房屋所有权证所有人',
			'rentMoney'=>'月租金',
		    
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
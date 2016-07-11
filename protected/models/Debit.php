<?php
class Debit extends BaseModel {
    
    public static $status = array(
        '0' => '待审核',
    	'1' => '审核通过',
    	'2' => '招标中',
        '3' => '还款中',
        '4' => '交易成功',
        '-1' => '流标',
        '-2' => '审核未通过',
        '-3' => '用户驳回',
    	'-4' => '终止借贷',
    );
    public static $catArr = array(
        1=>'域名',
        2=>'房产',
        3=>'车辆'
    );
    public static $catEnglish = array(
        1=>'domain',
        2=>'property',
        3=>'car'
    );
    public static $monthArr = array("一","二","三","四","五","六","七","八","九","十","十一","十二");
    
	public function tableName() {
		return '{{debit}}';
	}
	
    public function relations(){
        return array(
        	'domainInfo'=> array(self::HAS_ONE,'DebitDomain','debitId'),
        	'carInfo'=> array(self::HAS_ONE,'DebitCar','debitId'),
            'propertyInfo'=> array(self::HAS_ONE,'DebitProperty','debitId'),
            'debitFinancingRecord'=> array(self::HAS_MANY,'DebitFinancingRecord','debitId','order'=>'ctime DESC'),
        	'debitRepayRecord'=> array(self::HAS_MANY,'DebitRepayRecord','debitId','order'=>'repayDate DESC'),
            'agreeImage' => array(self::HAS_MANY,'File','relateId','condition'=>'category=10'),
        	'userInfo' => array(self::BELONGS_TO,'User','borrowerId'),
        );
    }
	
    public function loadInit($params = array()){}
	
    public function rules() {
		return array(
			array('cat,title,debitMoney,debitRate,invitDeadline,debitPurpose','required','message'=>'{attribute}不能为空','on' => 'create'),
			array('title', 'length','max' => '50','message' => '借款标题不能超过50字','on' => 'create'),
			array('debitRate', 'numerical','message'=>'借款利率填写错误','max' => 25,'min' => 0,'tooBig' => '借款利率不能超过25%','tooSmall' => '借款利率不能小于0','on' => 'create'),
            array('debitRate', 'compare','compareValue' => '0','operator'=>'>','message'=>'借款利率必须大于0','on' => 'create'),
            array('debitRate', 'match','pattern' => '/^\d+(\.\d{1,2})?$/','message'=>'借款利率最多保留小数点后两位','on' => 'create'),
			array('invitDeadline', 'numerical', 'integerOnly'=>true,'message'=>'招标时限填写错误', 'min' => 1,'max' => 30,'tooSmall' => '招标时限不能低于1天','tooBig' => '招标时限不能超过30天','on' => 'create'),
			array('debitPurpose', 'length','max' => '300','tooLong' => '贷款用途不能超过300字','on' => 'create'),
		);
	}

    public function attributeLabels()
	{
		return array(
				'cat' => '借款类型',
				'title' => '借款标题',
				'debitMoney' => '借款金额',
				'debitRate' => '借款利率',
				'invitDeadline' => '招标时限',
				'debitPurpose' => '贷款用途'
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
	
    public function checkProduct($product,$type){
        if($type == 'domain'){
            $table = 'p2p_debit_product_domain';
            $column = 'domain';
        }else if($type == 'propertyLand'){
            $table = 'p2p_debit_product_property';
            $column = 'landCertificateId';
        }else if($type == 'propertyHouse'){
            $table = 'p2p_debit_product_property';
            $column = 'houseCertificateId';
        }else if($type == 'car'){
            $table = 'p2p_debit_product_car';
            $column = 'engineNumber';
        }
        if($table && $column){
    		$sql = 'SELECT a.id 
    				FROM p2p_debit a
    				INNER JOIN '.$table.' b
    			    ON a.productId = b.id
    			    WHERE b.'.$column.' = :product AND a.status NOT in(-1,3)';
            $command = Yii::app()->db->createCommand($sql);
    		$command -> bindParam(':product', $product, PDO::PARAM_STR);
    		$results = $command->queryColumn();
        }else{
            $results = array();
        }
		return $results;
	}
	
	/*
	 * 获取月还款额
	 * @param $money 借款金额
	 * @param $month 借款时间
	 * @param $rate 借款年利率
	 * @param $invest 投资人投资金额，传值后返回该投资人月获得金额
	 * @return float 每月还款额（总额/针对单个人）
	 */
	public static function getMonthRepay($money,$month,$rate,$invest=0){
	    $rate = $rate / 100 / 12;
	    $monthRepay = $money * $rate * pow(1 + $rate, $month) / ( pow(1 + $rate, $month) - 1);
	    if($invest == 0){
	        return sprintf("%.2f", $monthRepay);
	    }else{
	        return sprintf("%.2f", $monthRepay * ($invest / $money));
	    }
	    
	}
	
	/*
	 * 获取借款进度
	 * @param $money 已筹金额
	 * @param $totalMoney 借款金额
	 */
	public static function getProgressPercent($money,$totalMoney){
	    if($totalMoney == 0 || $money == 0){
	        return '0%';
	    }else{
	        $progress = $money / $totalMoney;
            return sprintf("%.2f", $progress * 100)."%";
	    }
	}
	
	/*
	 * 获取截止日期
	 * @param date $startTime 开始时间
	 * @param int  $deadline 截止天数
	 */
    public static function getDeadline($startTime,$deadline){
	    $debitTime = date('Y-m-d',strtotime($startTime));
        $pastTime = date('Y-m-d',strtotime('+'.intval($deadline).' days',strtotime($debitTime.' 00:00:00')));
        return $pastTime;
	}
	
	/*
	 * 获取借贷手续费(手续费为5%)
	 * @param $money 
	 */
    public static function getDebitFee($money,$debitDeadline){
	    $fee = $money * $debitDeadline * 0.5 / 100;
        return sprintf("%.2f", $fee);
	}
	
	/*
	 * 据年月日计算几个月后，或则几个月前的日期
	 * @param date $date 某天日期
	 * $param $addMonths 月数，正数表示几个月后，负数表示几个月前
	 */
    public static function getMonthDay($date, $addMonths) {
         $year = date('Y',strtotime($date));   
         $month = date('m',strtotime($date));   
         $day = date('d',strtotime($date));   
         $month = $month + $addMonths;
         $endMonthDay = mktime(0, 0, 0, $month + 1, 0, $year);
         $endMonthDay = date("d", $endMonthDay);
         if($day > $endMonthDay) {
             $day = $endMonthDay;
         }
         $monthDay = mktime(0, 0, 0, $month, $day, $year);
         return date("Y-m-d", $monthDay);
    }
	
}
<?php
class ResidentAuth extends BaseModel
{    
	public function tableName() {
		return '{{resident_auth}}';
	}
	
	public function rules(){
	    return array(
	            array('maritalStatus,hoursing,cars,monthIncome,email,telphone,liveAddress,liveProvinceId,liveCityId,liveAreaId,householdAddress,householdProvinceId,householdCityId,householdAreaId', 'required','message' => '{attribute}不能为空！','on'=>'add,update'),
	            array('email','email','message'=>'邮箱的格式有误！','on'=>'add,update'),
	            array('telphone','match','pattern'=>'/^(0[0-9]{2,3}\-)?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/','message'=>'固定电话格式有误！','on'=>'add,update'),
	            array('birthday','safe','on'=>'add,update'),
	            array('userId','safe','on'=>'add')
	    );
	}
	
	/**
	 * 设置标签
	 */
	public function attributeLabels() {
	    return array(
	            'maritalStatus'=>'婚姻状况',
	            'telphone'=>'固定电话',
	            'email'=>'常用邮箱',
	            'hoursing'=>'住房',
	            'cars'=>'车辆',
	            'monthIncome'=>'月平均存款',
	            'age'=>'年龄',
	            'householdAddress'=>'户口所在地',
	            'liveAddress'=>'居住地址'
	    );
	}
	
	public function loadInit($params = array()){}
	
	public function beforeSave(){
		if (parent::beforeSave())
		{
			if ($this->isNewRecord)
			{
				$this->ctime = date('Y-m-d H:i:s');
				$this->status = 1;
			}elseif($this->scenario == 'update'){
			    $this->status = 1;
			}
			$this->operatorId = Yii::app()->user->id;
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * 根据生日获取年龄
	 */
	public function getAge(){
	    if($birthday = (int)substr($this->birthday, 0,4)){
	        return date('Y')-$birthday+1;
	    }
	    return 0;
	}
	/**
	 * 获取完整居住地或户口所在地
	 * @var $type 类型live为居住地,household为户口
	 */
	public function getFullAddress($type = 'live'){
	    if(!$this->isNewRecord && in_array($type,array('live','household'))){
	        $p = "{$type}ProvinceId";
	        $c = "{$type}CityId";
	        $a = "{$type}AreaId";
	        $address = "{$type}Address";
	        $res = Province::getFullAreaName($this->$p,$this->$c,$this->$a);
	        if($res){
	            return $res['province'].$res['city'].$res['area'].$this->$address;
	        }
	    }
	    return null;
	}
	
	/**
	 * 户籍验证通过/不通过
	 * @param string $pass
	 * @param string $comment
	 */
	public function auth($pass,$comment = ''){
	    if ($this->isNewRecord || $this->status != 1){
	        return false;
	    }elseif($pass == 'pass' || ($pass == 'nopass' && ($comment = trim($comment)))){
	        $tran = $this->dbConnection->beginTransaction();
	        try {
	            //更新自身status
	            $this->status = $pass == 'pass' ? 2 : 3;
	            $this->comment = $comment;
	            if($this->update(array('status','comment')) && User::model()->updateByPk($this->userId, array('residentAuth'=>$this->status))){
	                $tran->commit();
	                return true;
	            }else{
	                $tran->rollback();
	                return false;
	            }
	        }catch (Exception $e){
	            $tran->rollback();
	            return false;
	        }
	    }else{
	        return false;
	    }
	}
	
	public static $maritalStatusArray = array(0=>'未婚',1=>'已婚');//婚姻状况
	public static $hoursingArray = array(0=>'租房',1=>'自购商品房');//住房状况
	public static $carsArray = array(0=>'无',1=>'1辆',2=>'2辆',3=>'2辆以上');//车辆状况
	public static $monthIncomeArray = array(0=>'无',1=>'500',2=>'500-1000',3=>'1000-2000',4=>'2000-4000',5=>'5000以上');
	
}
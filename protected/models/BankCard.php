<?php
class BankCard extends BaseModel
{
    public static $bankList = array(
        1 => '中国工商银行',
        2 => '中国银行',
        3 => '中国建设银行',
        4 => '中国招商银行',
        5 => '中国农业银行'
    );
    
	public function tableName() {
		return '{{bank_card}}';
	}
	
	public function rules(){
	    return array(
	            array('ownerName,type,card,bankName','required','message' => '{attribute}不能为空！','on'=>'add'),
	            array('ownerName','length','min' => 2,'tooShort'=>'持卡人名字过短！','max'=>20,'tooLong'=>'持卡人名字过长！','on'=>'add'),
	            array('card','match','pattern'=>'/^\d{16}(\d{3})?$/','message'=>'卡号位数有误！','on'=>'add'),
	            array('card','unique','message'=>'该银行卡已经存在,请换一个重试','on'=>'add'),
	            array('bankName','length','max'=>50,'tooLong'=>'开户支行名称过长！','on'=>'add'),
	            array('userId','safe','on'=>'add')
	    );
	}
	
	/**
	 * 设置标签
	 */
	public function attributeLabels() {
	    return array(
	            'ownerName'=>'持卡人',
	            'type'=>'开户银行',
	            'card'=>'账号',
	            'bankName'=>'开户支行'
	    );
	}
	
	public function loadInit($params = array()){}
	
	public function beforeSave(){
		if (parent::beforeSave())
		{
			if ($this->isNewRecord)
			{
				$this->ctime = date('Y-m-d H:i:s');
			}
			return true;
		}
		else
		{
			return false;
		}
	}
}
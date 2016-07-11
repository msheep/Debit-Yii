<?php
/**
 * 手机实名验证model
 * @author lizheng
 * 所有状态:1待审核,2审核通过,3驳回
 */
class MobileAuth extends BaseModel
{
	public function tableName() {
		return '{{mobile_auth}}';
	}
	
	public function rules(){
	    return array(
	            array('realName', 'required','message' => '{attribute}不能为空！','on'=>'add,update'),
	            array('realName', 'length', 'min' => 2,'max'=>20,'tooLong'=>'真实姓名不得超过20个字', 'tooShort' => '真实姓名过短！','on'=>'add,update'),
	            array('userId,mobile','safe','on'=>'add')
	    );
	}
	
	/**
	 * 设置标签
	 */
	public function attributeLabels() {
	    return array(
	            'mobile' => '手机号',
	            'realName' => '机主姓名'
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
			}elseif ($this->scenario == 'update'){
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
	 * 手机实名验证通过/不通过
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
	            if($this->update(array('status','comment')) && User::model()->updateByPk($this->userId, array('mobileAuth'=>$this->status))){
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
}
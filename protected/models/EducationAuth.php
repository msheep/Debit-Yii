<?php
class EducationAuth extends BaseModel
{
	public function tableName() {
		return '{{education_auth}}';
	}
	
	public function rules(){
	    return array(
	            array('degree,school,degreeNo,schoolStartTime,schoolEndTime', 'required','message' => '{attribute}不能为空！','on'=>'add,update'),
	            array('school', 'length', 'min' => 2, 'tooShort' => '毕业学校名字过短！','max'=>100,'tooLong'=>'毕业学校名字过长！','on'=>'add,update'),
	            array('schoolStartTime,schoolEndTime', 'date','format'=>'yyyy-MM-dd','on'=>'add,update'),
	            array('schoolEndTime', 'compare','compareAttribute'=>'schoolStartTime','operator'=>'>','message'=>'毕业时间必须大于入学时间','on'=>'add,update'),
	            array('degreeNo','length','max'=>100,'tooLong'=>'学位证书编号过长！','on'=>'add,update'),
	            array('userId','safe','on'=>'add')
	    );
	}
	
	/**
	 * 设置标签
	 */
	public function attributeLabels() {
	    return array(
	            'degree' => '最高学历',
	            'school' => '毕业学校',
	            'schoolStartTime' => '入学时间',
	            'schoolEndTime' => '毕业时间',
	            'degreeNo' => '学位证书编号'
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
	 * 学历验证通过/不通过
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
	            if($this->update(array('status','comment')) && User::model()->updateByPk($this->userId, array('educationAuth'=>$this->status))){
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
	
	public static $degreeArray = array(0=>'自学',1=>'小学',2=>'初中',3=>'中专',4=>'高中',5=>'大专',6=>'本科',7=>'硕士',8=>'博士');//学历状况
	
}
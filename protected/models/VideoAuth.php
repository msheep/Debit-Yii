<?php
/**
 * 视频认证model
 * @author lizheng
 * @property File $videoFile
 */
class VideoAuth extends BaseModel
{
    public function tableName() {
		return '{{video_auth}}';
	}
	/**
	 * 设置关系
	 */
	public function relations(){
	    return array(
	        'videoFile' => array(self::HAS_ONE,'File','relateId','on'=>'category = 3 and delFlag = 0')
	    );
	}
	
	public function rules(){
	    return array(
	            array('userId,video','safe','on'=>'add')
	    );
	}
	
	/**
	 * 设置标签
	 */
	public function attributeLabels() {
	    return array(
	            'vfile'=>'上传录制好的视频文件'
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
	            if($this->update(array('status','comment')) && User::model()->updateByPk($this->userId, array('videoAuth'=>$this->status))){
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
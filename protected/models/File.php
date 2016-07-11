<?php
class File extends BaseModel
{
    public $fileAttribute = 'file';
    
    /**
     * 对应的上传文件
     * @var CUploadedFile
     */
    public $uploadFile;
    
	public function tableName() {
		return '{{file}}';
	}
	
	public function rules(){
	    return array(
	            //视频认证场景
	            array('uploadFile','required','message' => '上传文件不能为空！','on'=>'videoAuth'),
	            array('uploadFile','file','maxSize'=>20*1024*1024,'tooLarge'=>'视频文件不能超过5M!','types'=>'avi,mpeg,rm,rmvb,mpg,3gp,mp4,mov,mtv,wmv,amv','wrongType'=>'上传的文件类型有误!','mimeTypes'=>'audio/x-ms-wmv,audio/x-pn-realaudio,audio/x-pn-realaudio,video/mp4,video/x-msvideo,video/mpeg,video/quicktime,video/3gpp','wrongMimeType'=>'上传的文件格式有误！','on'=>'videoAuth'),
	            //全局有效
	            array('category,host,relateId','safe')
	    );
	}
	
	/**
	 * 设置标签
	 */
	public function attributeLabels() {
	    return array(
	            'path'=>'文件路径',
	            'category'=>'文件类别'
	    );
	}
	
	public function loadInit($params = array()){}
	
	public function beforeSave(){
		if (parent::beforeSave())
		{
			if ($this->isNewRecord)
			{
				$this->ctime = date('Y-m-d H:i:s');
				$this->userId = Yii::app()->user->id;
			}
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function save($runValidation = true,$attributes = null){
	    if(!$runValidation || $this->validate($attributes)){
	        if($this->isNewRecord){
	            //保存文件(如果需要)
	            if($this->uploadFile){
	                $webRoot = Yii::getPathOfAlias('webroot');
    	            $fileName = date('YmdHis').'_'.$this->relateId.'_'.rand().'.'.$this->uploadFile->extensionName;
    	            $fileSavePath = Yii::app()->params['uploadPath'].'/'.date('Y').'/'.date('m').'/'.date('d').'/';
    	            if(is_dir($webRoot.$fileSavePath) || @mkdir($webRoot.$fileSavePath,'0777',true)){
    	                if($this->uploadFile->saveAs($webRoot.$fileSavePath.$fileName)){
    	                    $this->path = $fileSavePath.$fileName;
    	                    $this->fileName = $this->uploadFile->name;
    	                }else{
    	                    $this->addError('uploadFile', '上传文件失败!');
    	                    return false;
    	                }
    	            }else{
    	                $this->addError('uploadFile', '上传目录创建失败!');
    	                return false;
    	            }
	            }
	            return $this->insert($attributes);
	        }else{
	            return $this->update($attributes);
	        }
	    }
	    else
	        return false;
	}
	
	/**
	 * 删除文件方法(记录标记删除,同时尝试删除文件)
	 */
	public function delete(){
	    if(!$this->isNewRecord){
	        $this->delFlag = 1;
	        if($this->update(array('delFlag'))){
	            $webRoot = Yii::getPathOfAlias('webroot');
	            @unlink($webRoot.$this->path);//可能无法保证执行失败后的恢复
	            return true;
	        }else{
	            $this->addError('uploadFile', '更新文件记录失败:数据库执行失败!');
	            return false;
	        }
	    }
	    $this->addError('uploadFile', '更新文件记录失败:不存在的文件记录!');
	    return false;
	}
	
    public function batchSave(){
	    $file = CUploadedFile::getInstances($this, $this->fileAttribute);
	    foreach($file as $k=>$v){
	        if(is_object($v) && get_class($v) === 'CUploadedFile'){
	            $fileName = date('YmdHis').'_'.$this->userId.'_'.rand().'.'.$v->extensionName;    
	            $fileSavePath = dirname(Yii::app()->basePath).'/upload/'.date('Y').'/'.date('m').'/'.date('d');
	            if(is_dir($fileSavePath) || @mkdir($fileSavePath,'0777',true)){
	                $fileModel = new File('add');
	                $v->saveAs($fileSavePath.'/'.$fileName);
	                $fileModel->path = '/upload/'.date('Y').'/'.date('m').'/'.date('d').'/'.$fileName;
	                $fileModel->attributes = $this->attributes;
	                $fileModel->save();
	            }
	        }
	    }
	}
	
	/**
	 * 批量保存文件方法,包装文件保存,记录生成
	 */
	public static function saveFile($files){
	    
	    
	}
	
}

<?php
/**
 * 安全问题
 * @author lizheng
 */
class SecurityQuestion extends BaseModel{
    
    public $type;//问题类型
    
    public function tableName(){
        return '{{security_question}}';
    }
    
    public function rules(){
        return array(
                array('question,answer,type','required','message' => '{attribute}不能为空！','on'=>'insert,update'),
                array('question','length','max'=>50,'tooLong'=>'问题长度不得超过50个字!','on'=>'insert,update'),
                array('answer','length','max'=>50,'tooLong'=>'回答长度不得超过50个字!','on'=>'insert,update'),
                array('userId','safe','on'=>'insert')
        );
    }
    
    public function loadInit($params){}
    
    public function attributeLabels()
    {
        return array(
                'question' => '问题',
                'answer' => '答案'
        );
    }
    
    public function beforeSave(){
        if(parent::beforeSave()){
            if($this->isNewRecord){
                $this->ctime = date('Y-m-d H:i:s');
            }
            $this->utime = date('Y-m-d H:i:s');
            return true;
        }else {
            return false;
        }
    }
    
}
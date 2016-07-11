<?php
/**
 * 验证安全保护问题form
 * @author lizheng
 */
class SecurityQuestionForm extends CFormModel
{
    public $answer;
    
    /**
     * @var SecurityQuestion $question
     */
    public $question;
    
    /**
     * @param SecurityQuestion $question
     * @param string $scenario
     */
    public function __construct($question,$scenario = ''){
        $this->question = $question;
        parent::__construct($scenario);
    }
    
    public function rules()
    {
        return array(
                array('answer','required','message'=>'{attribute}不能为空'),
                array('answer', 'length', 'max'=>50, 'tooLong'=>'回答不得超过50个字！'),
                array('answer', 'authenticate')
        );
    }
    
    public function attributeLabels()
    {
        return array(
                'question' => '问题',
                'answer' => '答案'
        );
    }
    
    public function authenticate($attribute,$param){
        if(!$this->hasErrors()){
            if($this->question->answer !== $this->answer){
                $this->addError($attribute, '回答有误,请重新输入!');
            }
        }
    }
}
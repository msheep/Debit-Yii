<?php
/**
 * 充值提现控制器
 * @author lizheng
 */
class TradingController extends Controller
{
    private $_user;
    
    protected function beforeAction($action)
    {
        $this->_user = User::model()->findByPk(Yii::app()->user->id);
        
        return true;
    }
    
    //设置访问权限过滤
    public function filters(){
        return array(
                'accessControl',
        );
    }
    
    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
                array('allow', // allow authenticated users to access all actions
                        'users'=>array('@'),
                ),
                array('deny',  // deny all users
                        'users'=>array('*'),
                ),
        );
    }
    
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else $this->render('error', $error);
		}
	}
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			    'maxLength'=>5,
			    'minLength'=>5
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			)   
		    
		);
	}
	
	/**
	 * 绑定银行卡
	 */
	public function actionBindCard(){
	    $cards = $this->_user->cards;
	    $model = new BankCard('add');
	    if(isset($_POST['BankCard'])){
	        $this->validateCsrfToken();
	        $_POST['BankCard']['userId'] = $this->_user->id;
	        $model->attributes = $_POST['BankCard'];
	        if($model->save()){
	            $model->addError('bankName', '保存成功！');
	        }
	    }
	    $this->render('bindCard',array('model'=>$model,'cards'=>$cards));
	}
	
	/**
	 * 提现申请
	 * @todo 前提:设置过支付密码,已经绑定至少一张银行卡
	 */
	public function actionApplyWithdraw(){
	    $action = 1;//申请步骤第一步申请，第二步验证密码，第三步成功
        $model = new WithdrawApply('add');
        $confirmForm = new ConfirmPayPasswordForm();
        $cards = $this->_user->cards;
        $cardOptions = array();
        foreach ($cards as $v){
            $cardOptions[$v->id] = BankCard::$bankList[$v->type].'(尾号'.substr($v->card, -4).')';
        }
        //初始化model
        $model->bankCardId = $cards ? reset($cards)->id : 0;
        $model->money = '';
	    if(isset($_POST['WithdrawApply'])){
	        $this->validateCsrfToken();
	        $_POST['WithdrawApply']['userId'] = $this->_user->id;
	        $model->attributes = $_POST['WithdrawApply'];
	        if($model->validate()){
	            $_POST['WithdrawApply']['account'] = $cards[$model->bankCardId]->bankName.'(尾号'.substr($cards[$model->bankCardId]->card,-4).')';
	            Yii::app()->session['applyWithdrawData'] = $_POST['WithdrawApply'];
	            $action = 2;
	        }
	    }
	    if(isset($_POST['ConfirmPayPasswordForm']) && ($apply = Yii::app()->session['applyWithdrawData'])){
	        $action = 2;
	        $this->validateCsrfToken();
	        $confirmForm->attributes = $_POST['ConfirmPayPasswordForm'];
	        if($confirmForm->validate()){
	            //入库
	            $model->attributes = $apply;
	            if($model->save()){
	                unset(Yii::app()->session['applyWithdrawData']);
	                $action = 3;
	            }else{
	                $action = 1;
	            }
	        }
	    }
	    $this->render('applyWithdraw',array('action'=>$action,'confirmForm'=>$confirmForm,'model'=>$model,'cardOptions'=>$cardOptions,'user'=>$this->_user));
	    
	}
	
}
<?php
/**
 * 提现申请
 * @author lizheng
 *
 */
class WithdrawController extends Controller{
    /**
     * 当前登录用户
     * @var User
     */
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
     * 所有action仅支持登录用户
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
     * 提现记录
     */
    public function actionIndex(){
        $model = new WithdrawSearchForm();
        if(isset($_GET['WithdrawSearchForm'])){
            $model->attributes = $_GET['WithdrawSearchForm'];
        }
        $money = $this->_user->getRelated('withdrawMoneyCount',true,array('condition'=>'status = 1'));
        $this->render('index',array('model'=>$model,'money'=>$money));
    }
    
    
    
}
<?php
/**
 * 用户消息中心
 * @author lizheng
 *
 */
class UserNotifyController extends Controller
{
    /**
     * 当前登录用户
     * @var User
     */
    private $_user;
    /**
     * 当前操作的消息model
     * @var UserNotify
     */
    private $_model;
    
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
     * 消息列表页
     */
    public function actionIndex(){
        $dataProvider=new CActiveDataProvider('UserNotify', array(
                'criteria'=>array(
                        'condition' => 'delFlag = 0 and userId='.$this->_user->id,
                        'order' => 't.ctime DESC',
                        'with' => array('fromAdmin','fromUser')
                ),
                'pagination'=>array(
                        'pageSize'=>20,
                ),
        ));
        //$notifys = $this->_user->notifys;
        $notifys = array();
        $this->render('index',array('notifys'=>$notifys,'dataProvider'=>$dataProvider));
    }
    
    /**
     * 删除消息
     */
    public function actionDelete()
    {
        if(Yii::app()->request->isPostRequest)
        {
            if(isset($_POST['del']) && is_array($_POST['del'])){
                $ids = $_POST['del'];
                $conditon = new CDbCriteria(array('condition' => 'userId=:uid','params'=>array(':uid'=>$this->_user->id)));
                $conditon->addInCondition('id', $ids);
                UserNotify::model()->updateAll(array('delFlag'=>1,'utime'=>date('Y-m-d H:i:s')),$conditon);
            }elseif(isset($_GET['id'])){
                $notify = $this->loadModel();
                $notify->delFlag = 1;
                $notify->save(false,array('delFlag'));
            }else{
                throw new CHttpException(400,'无效参数!');
            }
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(array('index'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }
    
    /**
     * 查看消息
     */
    public function actionView()
    {
        $notify = $this->loadModel();
        
        $this->render('view',array('notify'=>$notify));
    }
    
    /**
     * 设置当前消息实例model
     * @throws CHttpException
     */
    public function loadModel()
    {
        if($this->_model===null)
        {
            if(isset($_GET['id']))
            {
                $condition="userId={$this->_user->id} and delFlag=0";
                $this->_model=UserNotify::model()->findByPk($_GET['id'], $condition);
            }
            if($this->_model===null)
                throw new CHttpException(404,'请求参数有误,请稍后重试!');
        }
        return $this->_model;
    }
    
}
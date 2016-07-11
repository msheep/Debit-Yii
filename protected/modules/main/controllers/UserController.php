<?php

class UserController extends Controller
{
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
            ),
            'selectArea' => array(
                'class'=>'SelectAreaAction'      
            ),    
            
        );
    }
    
    /**
     * 用户中心主页
     */
    public function actionIndex()
    {
        $this->render('index',array('user'=>$this->_user));
    }

    /**
     * 用户详情信息页
     */
    public function actionProfile()
    {
        
        $user = User::model()->find();
        $this->render('profile');
    }
    
    /**
     * 用户手机实名认证
     */
    public function actionMobileAuth()
    {
        $mobileAuth = $this->_user->mobileAuthItem;
        if(!$mobileAuth){
            $mobileAuth = new MobileAuth('add');
            $mobileAuth->attributes = array('mobile'=>$this->_user->mobile);
        }elseif($mobileAuth->status == 3){//驳回待修改
            $mobileAuth->scenario = 'update';
        }elseif($mobileAuth->status == 1){
            $mobileAuth->scenario = 'view';
        }else{
            $this->redirect('/user/index');
        }
        if(isset($_POST['MobileAuth']) && in_array($mobileAuth->scenario, array('add','update'))){
            $this->validateCsrfToken();
            $_POST['MobileAuth']['userId'] = $this->_user->id;
            $mobileAuth->attributes = $_POST['MobileAuth'];
            $tran = $mobileAuth->dbConnection->beginTransaction();
            try {
                if($mobileAuth->save() && User::model()->updateByPk($mobileAuth->userId, array('mobileAuth'=>1))){
                    $mobileAuth->addError('realName', '保存成功！');
                    $tran->commit();
                }else{
                    $tran->rollback();
                }
            } catch (Exception $e) {
                $mobileAuth->addError('realName', '保存失败:数据库操作失败！');
                $tran->rollback();
            }
        }
        
        $this->render('mobile_auth',array('model' => $mobileAuth));
    }
    
    /**
     * 用户身份认证页
     */
    public function actionIdentityAuth()
    {
        $this->checkAuthProcess();
        
        $identityAuth = $this->_user->identityAuthItem;
        if(!$identityAuth){
            $identityAuth = new IdentityAuth('add');
        }elseif($identityAuth->status == 3){
            $identityAuth->scenario = 'update';//@todo 多余
        }elseif($identityAuth->status == 1){
            $identityAuth->scenario = 'view';
        }else{
            $this->redirect('/user/index');
        }
        if(isset($_POST['IdentityAuth'])  && in_array($identityAuth->scenario, array('add','update'))){
            $this->validateCsrfToken();
            $_POST['IdentityAuth']['userId'] = $this->_user->id;
            $identityAuth->attributes = $_POST['IdentityAuth'];
            $tran = $identityAuth->dbConnection->beginTransaction();
            try {
                if($identityAuth->save() && User::model()->updateByPk($identityAuth->userId, array('identityAuth'=>1))){
                    $identityAuth->addError('identityNo', '保存成功！');
                    $tran->commit();
                }else{
                    $tran->rollback();
                }
            } catch (Exception $e) {
                $identityAuth->addError('identityNo', '保存失败:数据库操作失败！');
                $tran->rollback();
            }
        }
        
        $this->render('identity_auth',array('model' => $identityAuth));
    }
    
    /**
     * 用户户籍认证页
     */
    public function actionResidentAuth()
    {
        $this->checkAuthProcess();
        
        $residentAuthItem = $this->_user->residentAuthItem;
        if(!$residentAuthItem){
            $birthday = $this->_user->identityAuthItem->birthday;
            $model = new ResidentAuth('add');
            //初始化属性
            $model->attributes = array('birthday'=>date('Y-m-d',$birthday),'maritalStatus'=>'','hoursing'=>'','cars'=>'','monthIncome'=>'');
        }elseif($residentAuthItem->status == 3){
            $model = $residentAuthItem;
        }elseif($residentAuthItem->status == 1){
            $model = $residentAuthItem;
            $model->scenario = 'view';
        }else{
            $this->redirect('/user/index');
        }
        if(isset($_POST['ResidentAuth']) && in_array($model->scenario, array('add','update'))){
            $this->validateCsrfToken();
            $_POST['ResidentAuth']['userId'] = $this->_user->id;
            $model->attributes = $_POST['ResidentAuth'];
            
            $tran = $model->dbConnection->beginTransaction();
            try {
                if($model->save() && User::model()->updateByPk($model->userId, array('residentAuth'=>1))){
                    $model->addError('email', '保存成功！');
                    $tran->commit();
                }else{
                    $tran->rollback();
                }
            } catch (Exception $e) {
                $model->addError('email', '保存失败:数据库操作失败！');
                $tran->rollback();
            }
        }
    
        $this->render('resident_auth',array('model' => $model,'user'=>$this->_user));
    }
    
    /**
     * 用户学历认证页
     */
    public function actionEducationAuth()
    {
        $this->checkAuthProcess();
        
        $educationAuthItem = $this->_user->educationAuthItem;
        if(!$educationAuthItem){
            $model = new EducationAuth('add');
            $model->attributes = array('degree'=>'','schoolStartTime'=>'','schoolEndTime'=>'');
        }elseif($educationAuthItem->status == 3){
            $model = $educationAuthItem;
        }elseif($educationAuthItem->status == 1){
            $model = $educationAuthItem;
            $model->scenario = 'view';
        }else{
            $this->redirect('/user/index');
        }
        if(isset($_POST['EducationAuth']) && in_array($model->scenario,array('add','update'))){
            $this->validateCsrfToken();
            $_POST['EducationAuth']['userId'] = $this->_user->id;
            $model->attributes = $_POST['EducationAuth'];
            
            $tran = $model->dbConnection->beginTransaction();
            try {
                if($model->save() && User::model()->updateByPk($model->userId, array('educationAuth'=>1))){
                    $model->addError('degreeNo', '保存成功！');
                    $tran->commit();
                }else{
                    $tran->rollback();
                }
            } catch (Exception $e) {
                $model->addError('degreeNo', '保存失败:数据库操作失败！');
                $tran->rollback();
            }
        }
        
        $this->render('edu_auth',array('model' => $model));
    }
    
    /**
     * 用户视频认证页
     */
    public function actionVideoAuth()
    {
        $this->checkAuthProcess();
    
        $videoAuthItem = $this->_user->videoAuthItem;
        
        if(!$videoAuthItem){
            $model = new VideoAuth('add');
            $file = new File('videoAuth');
        }elseif($videoAuthItem->status == 3){
            $model = $videoAuthItem;
            $file = new File('videoAuth');
        }elseif($videoAuthItem->status == 1){
            $model = $videoAuthItem;
            $model->scenario = 'view';
            $file = $model->videoFile;
        }else{
            $this->redirect('/user/index');
        }
        if(isset($_POST['File']) && in_array($model->scenario,array('add','update'))){
            $file->uploadFile = CUploadedFile::getInstance($file, 'uploadFile');
            if($file->validate()){
                $model->attributes = array('userId'=>$this->_user->id);
                $tran =$model->dbConnection->beginTransaction();
                try{
                    if($model->save() && User::model()->updateByPk($model->userId, array('videoAuth'=>1))){
                        $file->attributes = array('relateId'=>$model->userId,'category'=>3);
                        if($model->videoFile && !$model->videoFile->delete()){
                            $file->addError('uploadFile', $model->videoFile->getError('uploadFile'));
                            $tran->rollback();
                        }else{
                            if($file->save(false)){
                                $tran->commit();
                            }else{
                                $tran->rollback();
                            }
                        }
                    }else{
                        $tran->rollback();
                    }
                }catch (Exception $e) {
                    $file->addError('updloadFile', '文件上传异常!');
                    $tran->rollback();
                }
            }
        }
        $this->render('video_auth',array('model' => $model,'file'=>$file));
        
    }
    
    /**
     * 根据验证流程填写验证信息
     */
    private function checkAuthProcess(){
        $action = strtolower($this->action->id);
        if(!$this->_user->mobileAuth){
            $this->redirect('/user/mobileAuth');
        }elseif(!$this->_user->identityAuth || $action == 'identityauth'){
            $action == 'identityauth' || $this->redirect('/user/identityAuth');
        }elseif(!$this->_user->residentAuth || $action == 'residentauth'){
            $action == 'residentauth' || $this->redirect('/user/residentAuth');
        }elseif(!$this->_user->educationAuth || $action == 'educationauth'){
            $action == 'educationauth' || $this->redirect('/user/educationAuth');
        }elseif(!$this->_user->videoAuth || $action == 'videoauth'){
            $action == 'videoauth' || $this->redirect('/user/videoAuth');
        }
    }
    
    /**
     * 安全问题设置页
     */
    public function actionSecurityQuestion(){
        $sq = $this->_user->securityQuestion;
        //设置安全问题
        if(isset($_POST['SecurityQuestion']) && (!$sq || Yii::app()->session['securityQuestionValid'])){
            $this->validateCsrfToken();
            if(!$sq){
                $sq = new SecurityQuestion();
                $sq->userId = $this->_user->id;
            }
            !isset(Yii::app()->params['securityQuestions'][$_POST['SecurityQuestion']['type']]) || $_POST['SecurityQuestion']['question'] = Yii::app()->params['securityQuestions'][$_POST['SecurityQuestion']['type']];
            $sq->attributes = $_POST['SecurityQuestion'];
            if($sq->save()){
                unset(Yii::app()->session['securityQuestionValid']);
                echo '成功!';return;
            }else{
                $model = $sq;
            }
        }elseif(!$sq){
            //从未设置过安全问题,直接设置
            $model = new SecurityQuestion();
        }else{
            //先验证后设置
            $model = new SecurityQuestionForm($sq);
        }
        //验证安全问题
        if(isset($_POST['SecurityQuestionForm'])){
            $this->validateCsrfToken();
            $model->attributes = $_POST['SecurityQuestionForm'];
            if($model->validate()){
                Yii::app()->session['securityQuestionValid'] = 1;
                $model = new SecurityQuestion();
            }
        }
        $this->render('security_question',array('model' => $model));
    }
    
    /**
     * 用户中心重设登录密码
     */
    public function actionEditLoginPassword(){
        $model = new SetPasswordForm($this->_user,'editLoginPassword');
        if(isset($_POST['SetPasswordForm'])){
            $this->validateCsrfToken();
            $model->attributes = $_POST['SetPasswordForm'];
            if($model->validate() && $model->savePassword()){
                echo 'chenggong!';
            }
        }
        
        $this->render('set_password',array('model'=>$model));
    }
    
    /**
     * 用户中心设置支付密码
     */
    public function actionSetPayPassword(){
        $scenario = $this->_user->payPassword ? 'editPayPassword' : 'setPayPassword';
        $model = new SetPasswordForm($this->_user,$scenario);
        if(isset($_POST['SetPasswordForm'])){
            $this->validateCsrfToken();
            $model->attributes = $_POST['SetPasswordForm'];
            if($model->validate() && $model->savePassword()){
                echo 'chenggong!';
            }
        }
    
        $this->render('set_password',array('model'=>$model));
    }
    
}
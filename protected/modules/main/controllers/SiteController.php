<?php

class SiteController extends Controller
{
	CONST MOBILE_HAS_REGISTED = 101; 			//手机号码已注册
	CONST SEND_MOBILECODE_FAILED = 102; 		//发送手机注册码失败
	CONST SEND_MOBILECODE_BLOCKED = 103; 		//发送手机Block
	CONST MOBILE_NO_REGSTED=104;// 手机号码未注册
	
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
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$this->render('index');
	}
	
	/**
	 * Displays the contact page
	 */
	public function actionRegister()
	{
		if (Yii::app()->user->id) $this->redirect(array('/user/profile'));
		if(Yii::app()->request->isAjaxRequest && isset($_POST['source'])){
			 $verifyCode = trim($_POST['verifyCode']);
			 $captcha=Yii::app()->session->get(RegisterForm::SESSION_MOBILE_CODE);
			 if($verifyCode && $verifyCode==$captcha) echo self::AJAXSUCCESS;
			 else echo self::FAILED;
			 Yii::app()->end();
		}
		$this->validateCsrfToken();
		$model=new RegisterForm;
		$model->scenario = 'register';
		
		$username = $mobile = array();
		
		if(isset($_POST['ajax']) && $_POST['ajax']==='register-form')
		{
// 			$_POST['RegisterForm']['username']=trim($_POST['RegisterForm']['username']);
// 			$_POST['RegisterForm']['email']=trim($_POST['RegisterForm']['email']);
// 			$_POST['RegisterForm']['mobile']=trim($_POST['RegisterForm']['mobile']);
// 			if(isset($_POST['RegisterForm']['username']) && !empty($_POST['RegisterForm']['username'])){
// 				$sql = "SELECT id FROM v1_user WHERE username = :username";
// 				$command = Yii::app()->db->createCommand($sql);
// 				$command->bindValue(":username", $_POST['RegisterForm']['username'], PDO::PARAM_STR);
// 				$result = $command->queryRow();
// 				if($result) {
// 					$username = array('RegisterForm_username'=>array('此用户名已注册'));
// 				}
// 			} 
// 			if(isset($_POST['RegisterForm']['email']) && !empty($_POST['RegisterForm']['email'])){
// 				$sql = "SELECT id FROM v1_user WHERE email = :email";
// 				$command = Yii::app()->db->createCommand($sql);
// 				$command->bindValue(":email", $_POST['RegisterForm']['email'], PDO::PARAM_STR);
// 				$result = $command->queryRow();
// 				if($result) {
// 					$email = array('RegisterForm_email'=>array('此邮箱已注册'));
// 				}
// 			}
// 			if(isset($_POST['RegisterForm']['mobile']) && !empty($_POST['RegisterForm']['mobile'])){
// 				$sql = "SELECT id FROM v1_user WHERE mobile = :mobile";
// 				$command = Yii::app()->db->createCommand($sql);
// 				$command->bindValue(":mobile", $_POST['RegisterForm']['mobile'], PDO::PARAM_STR);
// 				$result = $command->queryRow();
// 				if($result) {
// 					$mobile = array('RegisterForm_mobile'=>array('此手机已注册'));
// 				}
// 			} 
// 			echo json_encode(array_merge($username, $email, $mobile, json_decode(CActiveForm::validate($model), true)));
		    $model->attributes = $_POST['RegisterForm'];
		    echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		
		if(isset($_POST['RegisterForm']))
		{
		    $model->attributes = $_POST['RegisterForm'];
			if($model->validate()) {
			    $user = new User('register');
			    $user->attributes = $_POST['RegisterForm'];
			    if($user->save())
			    {
			        Yii::app()->session->remove(RegisterForm::SESSION_MOBILE_CODE);
			        Yii::app()->user->setFlash('RegisterSuccess', '注册成功, 请登录!');
			        $this->redirect(array('/site/login'));
			    }
			}
		}
		$this->render('register', array(
					'model' => $model
				));
	}
	
	public function actionCheckRegisted(){
	    if(isset($_POST['moblie'])) {
	        if($this->_checkRegisted($_POST['moblie'])) echo 1; 
	        else echo 0;
	        Yii::app()->end();
	    }
	    echo 0;
	}
	
	protected function _checkRegisted($mobile=NULL, $username=NULL, $email=NULL) {
	    if(!is_numeric($mobile)) throw new CHttpException(400, '非法手机号码: '.$mobile);   
// 		$sql = "SELECT id,mobile,username,email FROM v1_user WHERE mobile = :mobile OR username = :username OR email=:email";
// 		$command = Yii::app()->db->createCommand($sql);
// 		$command->bindValue(":mobile", $mobile, PDO::PARAM_STR);
// 		$command->bindValue(":username", $username, PDO::PARAM_STR);
// 		$command->bindValue(":email", $email, PDO::PARAM_STR);
// 		$result = $command->queryRow();
	    $result = User::model()->find('mobile = :mobile OR userName = :username',array(':mobile'=>$mobile,':username'=>$username));
		return $result;
	 }
	
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if(isset($_GET['callUrl']))  Yii::app()->session['callUrl'] = $_GET['callUrl'];
		if (Yii::app()->user->id) {
			if(Yii::app()->session['callUrl'])  $this->redirect(array(Yii::app()->session['callUrl']));
			else $this->redirect(array('/user/profile'));
		}
		$this->validateCsrfToken();
		$model=new LoginForm;
		// if it is ajax validation request
// 		$username = array();
// 		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')//配合ajax验证(取消ajax验证)
// 		{
// 		    $model->attributes = $_POST['LoginForm'];
// 			echo CActiveForm::validate($model);
// 			Yii::app()->end();
// 		}
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()) {
				if(Yii::app()->session['callUrl']) { 
				    $callUrl = Yii::app()->session['callUrl'];
				    unset(Yii::app()->session['callUrl']);
					$this->redirect(array($callUrl)); 
				}elseif($return = Yii::app()->user->returnUrl){
				    $this->redirect($return);
				} else{
				    $this->redirect('/user/index'); 
				}
			} 
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}
	

	public function actionSendMobileCode() {
		if(Yii::app()->request->isAjaxRequest && isset($_POST['source'])){
			$mobile = $_POST['mobile'];
			if (!$this->checkRegisted($mobile)) {
				echo json_encode(array('status' => self::MOBILE_NO_REGSTED));
				Yii::app()->end();
			}
			$code = mt_rand(1,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
			if($_POST['source']=='dynamicPassword'){
				Yii::app()->session->add(RegisterForm::SESSION_DYNAMICPWD_CODE, $code);
				$param['mobile'] = $mobile;
				$param['typeInfo']['code'] = $code;
				$ret = SendSMS::SendText($param,'danymic');
				$status = $ret['status'] == 'success' ? 1 : self::SEND_MOBILECODE_FAILED;
				echo $status;
			}elseif($_POST['source']=='resetpwd'){
				$this->validateCsrfToken();
				Yii::app()->session->add(RegisterForm::SESSION_RESETPWD_CODE, $code);
				$param['mobile'] = $mobile;
				$param['typeInfo']['code'] = $code;
				$ret = @SendSMS::SendText($param,'mmyzm');
				$status = $ret['status'] == 'success' ? 1 : self::SEND_MOBILECODE_FAILED;
				echo json_encode(array('status'=>$status));
			}
		}else if (Yii::app()->request->isAjaxRequest) {
			$blockMoblies = array('13421826153'=>true, '13236350089'=>true, '18658780065'=>true, '15010376135'=>true);
			$mobile = $_POST['mobile'];
			if(isset($blockMoblies[$mobile])) { 
				echo json_encode(array('status' => self::SEND_MOBILECODE_BLOCKED));
				Yii::app()->end();
			}
	    	$this->validateCsrfToken();
			if ($this->_checkRegisted($mobile)) {
				echo json_encode(array('status' => self::MOBILE_HAS_REGISTED));
				Yii::app()->end();
			}
			//5位数字验证码
			$code = mt_rand(10000,99999);
			//$code = 9;
			Yii::app()->session->add(RegisterForm::SESSION_MOBILE_CODE, "$mobile:$code");
			$param['mobile'] = $mobile;
			$param['typeInfo']['code'] = $code;
			$ret = @SendSMS::SendText($param,'zczm');
			$status = (isset($ret['status']) && $ret['status'] == 'success') ? 1 : self::SEND_MOBILECODE_FAILED;
			echo json_encode(array('status'=>$status)); 
		}
	}
	
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	
	public function actionCheckVerifyCode(){
		if (Yii::app()->request->isPostRequest) {
			$this->validateCsrfToken();
			if($_POST['verifyCode']==""){
				echo 1;
				exit;
			}else if(!is_numeric($_POST['verifyCode'])||Yii::app()->session[RegisterForm::SESSION_RESETPWD_CODE]!=$_POST['verifyCode']){
				echo 2;
				exit;
			}
			echo 3;
		}
	}
	
	// 判断手机有没有注册过
	public function actionIsMobile(){
		if(isset($_POST['mobile'])){
			$mobile=$_POST['mobile'];
			$user=User::model()->find('mobile=?', array($mobile));
			if($user){
				echo self::AJAXSUCCESS;
			}else{
				echo self::FAILED;
			}
		}
	}
}
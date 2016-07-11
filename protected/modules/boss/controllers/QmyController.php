<?php
class QmyController extends Controller{
     public function actionIndex(){
        $accessToken = Yii::app()->session['token']['access_token'];
        $uid = Yii::app()->session['token']['uid'];
        if(!$accessToken || !$accessToken){
            $this->actionGetCode();
        }else{
            $tokenInfo = OauthToken::model()->findByPk($accessToken);
            if(time() > $tokenInfo->expires){
                $this->actionGetCode();
            }else{
                $this->redirect('/site/index');
            }
        }      
    }
    
    //获取code码
    public function actionGetCode(){
        $code = isset($_REQUEST['code']) ? $_REQUEST['code'] : '';
        if (empty($code)){
        	// state参数用于防止跨站请求伪造（CSRF）攻击，成功授权后回调时会原样带回
        	Yii::app()->session['state'] = md5 ( uniqid (rand (), TRUE ) );
        	// 拼接URL
        	$qmyOauthV2 = new qmyOauthV2( WB_AKEY , WB_SKEY );
        	$code_url = $qmyOauthV2->getAuthorizeURL(WB_CALLBACK_URL,'code',Yii::app()->session['state']);
        	$this->redirect($code_url);
        }  
    }
    
    //获取token
    public function actionCallback(){
        $qmyOauthV2 = new qmyOauthV2( WB_AKEY , WB_SKEY );
        $token = array();
        if(isset($_REQUEST['state']) && $_REQUEST['state'] == Yii::app()->session['state']){
        	if ($_REQUEST['code']) {
        		$keys = array();
        		$keys['code'] = Yii::app()->request->getParam('code');
        		$keys['redirect_uri'] = WB_CALLBACK_URL;
        		try {
        			$token = $qmyOauthV2->getAccessToken( 'code', $keys ) ;
        		} catch (OAuthException $e) {
        		}
        		if(!empty($token) && isset($token['access_token'])){
        		    //设置token的session
            		Yii::app()->session['token'] = $token;
            		//保存token信息
            		$oauthTokenModel = new OauthToken();
            		$oauthTokenModel->oauthToken = $token['access_token'];
            		$oauthTokenModel->expires = $token['expires_in'];
            		$oauthTokenModel->uid = $token['uid'];
            		$oauthTokenModel->save();
            		$this->getOauthAdmin();
            		$this->redirect('/site/index');
        		}else{
        			unset($_SESSION['token']); 
        			print_r($token);
        			echo '授权失败！';
        			Yii::app()->end();
        		}
        	}else{
        	    print_r($_REQUEST);
        		echo '授权失败！';
        		Yii::app()->end();
        	}
        }else{
            echo 'state不匹配，你有可能被跨站请求伪造（CSRF）攻击！';
            Yii::app()->end();
        }
    }
    
    //判断当前boss登录者是在该系统有信息，无则新增
    public function getOauthAdmin(){
        $oauthInfo = OauthAdmin::model()->findByPk($_SESSION['token']['uid']);
        if(!$oauthInfo || !isset($oauthInfo)){
            $qmyClientV2 = new qmyClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
            $userInfo = $qmyClientV2->get_user_info($_SESSION['token']['uid']);
            if(!empty($userInfo) && $userInfo['success'] == 'true'){
                $oauthAdminModel = new OauthAdmin();
                $oauthAdminModel->id = $userInfo['data']['uid'];
                $oauthAdminModel->name = $userInfo['data']['name'];
                $oauthAdminModel->userName = $userInfo['data']['realname'];
                $oauthAdminModel->save();
            }else{
                echo '读取资源失败！';
                Yii::app()->end();
            }
        }
    }
}
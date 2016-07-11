<?php 
class BackCWebUser extends CWebUser{
    //重写CWebUser的login方法
    public function login($identity,$duration=0){
         $uid = Yii::app()->session['token']['uid'];
         if(!uid){
             $this->getCode();
         }
    }
    
    public function getCode(){
        $code = Yii::app()->request->getParam('code') ? Yii::app()->request->getParam('code') : '';
        if (empty($code)){
        	// state参数用于防止跨站请求伪造（CSRF）攻击，成功授权后回调时会原样带回
        	Yii::app()->session['state'] = md5 ( uniqid (rand (), TRUE ) );
        	// 拼接URL
        	$o = new qmyOauthV2( WB_AKEY , WB_SKEY );
        	$code_url = $o->getAuthorizeURL(WB_CALLBACK_URL,'code',Yii::app()->session['state']);
        	$this->redirect($code_url);
        }
    }
    
    public function callback(){
        $o = new qmyOauthV2( WB_AKEY , WB_SKEY );
        $token = array();
        if(Yii::app()->request->getParam('state') && Yii::app()->request->getParam('state') == Yii::app()->session['state']){
        	if (Yii::app()->request->getParam('code')) {
        		$keys = array();
        		$keys['code'] = Yii::app()->request->getParam('code');
        		$keys['redirect_uri'] = WB_CALLBACK_URL;
        		try {
        			$token = $o->getAccessToken( 'code', $keys ) ;
        		} catch (OAuthException $e) {
        		}
        		if(!empty($token) && isset($token['access_token'])){
            		Yii::app()->session['token'] = $token;
        		    $c = new qmyClientV2( WB_AKEY , WB_SKEY , Yii::app()->session['token']['access_token'] );
                    $ms  = $c->get_user_info(Yii::app()->session['token']['uid']);
                    if($ms['success'] == 'true'){
                    	$_SESSION['token']['userName'] = $ms['data']['realname'];
                    	$this->redirect('/site/index');
                    }else{
                    	print_r($ms);
                    	throw new CHttpException(200, '读取资源失败！');
                    }
        		}else{
        			unset($_SESSION['token']); 
        			print_r($token);
        			throw new CHttpException(200, '授权失败！');
        		}
        	}else{
        	    print_r(Yii::app()->request);
        		throw new CHttpException(200, '授权失败！');
        	}
        }else{
            throw new CHttpException(200, 'state不匹配，你有可能被跨站请求伪造（CSRF）攻击！');
        }
    }
}
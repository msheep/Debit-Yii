<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class BackEndController extends CController
{
    private $_pageTitle;
	public $keyword= '';
	public $des = '';
	public $layout='/layouts/mainLayout';
	
    public $uid;
   
    public $monthArr = array("一","二","三","四","五","六","七","八","九","十","十一","十二");

    public function init(){
        parent::init();
        @header("Content-type:text/html;charset=utf-8");
		$this->useGlobalCss();
		$this->useCommonJs();
		//uploadify 因为使用flash,需要重新设置sessionid才能读取到对应session来验证登录
	    $sessionName = Yii::app()->session->sessionName;
	    if ($sessionId = Yii::app()->request->getPost($sessionName)) {
	        $session = Yii::app()->getSession();
            $session->close();
            $session->sessionID = $sessionId;
            $session->open();
	    }
	    //end
//		if(Yii::app()->session['token']['uid'] && Yii::app()->session['token']['access_token']){
//		    $this->uid = Yii::app()->session['token']['uid'];
//		}else{
//			$this->redirect('/qmy/getCode');
//		}
		$this->uid = 3;
	}
	
	public function getPageTitle()
	{
	    if($this->_pageTitle!==null)
	        return $this->_pageTitle;
	    else
	    {
	        $name=ucfirst(basename($this->getId()));
	        if($this->getAction()!==null && strcasecmp($this->getAction()->getId(),$this->defaultAction))
	            return $this->_pageTitle=ucfirst($this->getAction()->getId()).' '.$name.' - '.Yii::app()->name;
	        else
	            return $this->_pageTitle=$name.'-'.Yii::app()->name;
	    }
	}
	
	public function setPageTitle($value, $index=false)
	{
	    if($index==false) $this->_pageTitle = $value.'-'.Yii::app()->name;
	    else $this->_pageTitle = Yii::app()->name.'-'.$value;
	}
	
	//验证用户的菜单权限
    public function auth($menu = NULL){
        if(!$menu){
            Yii::app()->end();
        }
		$qmyClientV2 = new qmyClientV2( WB_AKEY , WB_SKEY , Yii::app()->session['token']['access_token'] );
        $menuInfo = $qmyClientV2->get_user_menu_permisson($this->uid,$menu);
        return $menuInfo;
	}
	
    function useGlobalCss() {
		Yii::app()->getClientScript()->registerCssFile("/css/boss/list.css");
		Yii::app()->getClientScript()->registerCssFile("/css/boss/bootstrap.min.css");
		Yii::app()->getClientScript()->registerCssFile("/css/boss/bootstrap-responsive.min.css");
//		Yii::app()->getClientScript()->registerCssFile("/css/boss/font-awesome.min.css");
	}
	
	function useCommonJs() {
	    Yii::app()->clientScript->registerCoreScript('jquery');
		Yii::app()->clientScript->registerScriptFile('/js/common.js', CClientScript::POS_END);
		Yii::app()->clientScript->registerScriptFile('/js/boss/bootstrap.min.js', CClientScript::POS_END);
	}
	
	public function getXingQi($date){
		if($date==1){
			return  "一";
		}else if($date==2){
			return "二";
		}else if($date==3){
			return  "三";
		}else if($date==4){
			return  "四";
		}else if($date==5){
			return  "五";
		}else if($date==6){
			return "六";
		}else if($date==0){
			return "日";
		}else{
			return "";
		}
	}

	public function getUserName($userId){
	    $user = User::model()->findByPk($userId);
        if($user && $user->userName!=''){
            return $user->userName;
        }else{
            return $userId;
        }
	}
	
    public function getAdminUserName($adminId){
	    $admin = OauthAdmin::model()->findByPk($adminId);
        if($admin && $admin->userName!=''){
            return $admin->userName;
        }else{
            return $adminId;
        }
	}
	
}
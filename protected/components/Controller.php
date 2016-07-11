<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	const AJAXSUCCESS = 1; // AJAX请求成功
	const SUCCESS = 2; //执行成功
	const FAILED = 3; // 执行失败
	const ILLEGALITY = 4; // 非法请求
	const DBERROR = 5; // 数据库错误
	const NOTEXISTUSER = 6; // 用户不存在
	const PLEASELOGIN = 8; // 请登录
	const INACITVE = 7; // 未激活
	const NOPERMISSION = 9; // 无权限
	const TOKENERROR = 10; // 令牌错误
	const BAIXIAOFILED=11;
	
	// seo des
	private $_pageTitle;
	public $keyword= '';
	public $des = '';
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='/layouts/mainLayout';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	
	// @todo 管理员
	public function isAdmin(){
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

	public function validateCsrfToken()
	{
		$request=Yii::app()->request;
		if($request->getIsPostRequest())
		{
			// only validate POST requests
			$cookies=$request->getCookies();
			if($cookies->contains($request->csrfTokenName) && isset($_POST[$request->csrfTokenName]))
			{
				$tokenFromCookie=$cookies->itemAt($request->csrfTokenName)->value;
				$tokenFromPost=$_POST[$request->csrfTokenName];
				$valid=$tokenFromCookie===$tokenFromPost;
			}
			else
				$valid=false;
			if(!$valid){
				if (Yii::app()->request->isAjaxRequest) {
					echo Controller::TOKENERROR;
					Yii::app()->end();
				} else throw new CHttpException('403', '令牌错误, 非法请求! -'.json_encode($_POST));
			}
		}
	}
	
	function useGlobalCss() {
		Yii::app()->getClientScript()->registerCssFile("/css/styles.css?0819");
	}
	
	function useCommonJs() {
	    Yii::app()->clientScript->registerCoreScript('jquery');
		Yii::app()->clientScript->registerScriptFile('/js/common.js?0903', CClientScript::POS_END);
	}

	function useCsrfToken(){
	    $request=Yii::app()->request;
	    echo CHtml::hiddenField($request->csrfTokenName,$request->getCsrfToken(),array('id'=>'tokenId'));
	}
}
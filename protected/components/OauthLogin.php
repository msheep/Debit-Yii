<?php
class oauthLogin extends CWidget{
	public $qmy_code_url;
	public $back_url;
	public $itemView = 'small_login';
	/**
	* Initialize the widget
	*/
	public function init(){
		$this->qmyLogin();
	}

	/**
     * qmyLogin
     */
    public function qmyLogin()
    {
        $state = md5(rand(5, 10));
        Yii::app()->session->add('sina_state',$state);
        $qmyService = new SaeTOAuthV2(WB_AKEY,WB_SKEY);
        $this->qmy_code_url = $weiboService->getAuthorizeURL(WB_CALLBACK_URL,'code',$state);
		Yii::app()->session->add('back_url',$this->back_url.'?state='.$state);
    }

    /**
	* Run the widget
	*/
	public function run()
	{
		parent::run();
		$this->getViewFile($this->itemView);
		$this->render($this->itemView,array('data',$this->data)); 	
	}
}
?>
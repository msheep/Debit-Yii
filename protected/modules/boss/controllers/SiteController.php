<?php 
class SiteController extends BackEndController{
    
    public function actionError(){
		if($error = Yii::app()->errorHandler->error){
			if(Yii::app()->request->isAjaxRequest){
			    echo $error['message'];
			}else{
			    $this->render('error', $error);
			}
		}	
	}
	
    public function actionIndex(){
       $this->render('index');
    }
    
	
}
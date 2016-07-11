<?php
class UserController extends BackEndController{
    /**
     * 会员列表
     */
    public function actionUserList(){
        $model = new UserSearchForm('userList');
        
        if(isset($_GET['UserSearchForm'])){
            $model->attributes = $_GET['UserSearchForm'];
        }
        
        $this->render('userList',array('model'=>$model));
    }
    
    /**
     * 会员列表-资料审核
     */
    public function actionUserAuthList(){
    
        $model = new UserSearchForm('userAuthList');
    
        if(isset($_GET['UserSearchForm'])){
            $model->attributes = $_GET['UserSearchForm'];
        }
    
        $this->render('userAuthList',array('model'=>$model));
    }
    
    /**
     * 查看会员资料
     */
    public function actionView($id = 0){
        
        $model = new UserSearchForm('userView');
        
        if(isset($_GET['UserSearchForm']) && Yii::app()->request->isAjaxRequest){
            $model->attributes = $_GET['UserSearchForm'];
            if($user = $model->searchUserView()){
                echo $this->renderPartial('_view',array('user'=>$user));
            }
            return;
        }
        if(!$id || !($user = User::model()->with('educationAuthItem','videoAuthItem.videoFile','mobileAuthItem','identityAuthItem','residentAuthItem')->findByPk($id))){
            $this->redirect('/user/userList');
        }
        
        $this->render('view',array('model'=>$model,'user'=>$user));
        
    }
    
    /**
     * 查看审核详情
     */
    public function actionAuthView($id = 0){
    
        $model = new UserSearchForm('userView');
    
        if(isset($_GET['UserSearchForm']) && Yii::app()->request->isAjaxRequest){
            $model->attributes = $_GET['UserSearchForm'];
            if($user = $model->searchUserView()){
                echo $this->renderPartial('_authView',array('user'=>$user));
            }
            return;
        }
    
        if(!$id || !($user = User::model()->with('educationAuthItem','videoAuthItem.videoFile','mobileAuthItem','identityAuthItem','residentAuthItem')->findByPk($id))){
            $this->redirect('/user/userAuthList');
        }
        
        $this->render('view',array('model'=>$model,'user'=>$user));
    
    }
    
    /**
     * 信息审核请求(ajax)
     * @param string $type
     * @param int $id
     */
    public function actionAuth($type,$id){
        $res['code'] = 0;
        $res['msg'] = '';
        $res['reload'] = false;
        if(in_array($type,array_keys(User::$authTypeArray)) && ($user = User::model()->with("{$type}AuthItem")->findByPk($id))){
            $authName = $type."AuthItem";
            if(!$user->$authName){
                $res['msg'] = '当前用户未提交'.User::$authTypeArray[$type].'!';
                $res['reload'] = true;
            }elseif($user->$authName->status != 1){
                $res['msg'] = '当前用户'.User::$authTypeArray[$type].'状态有误!';
                $res['reload'] = true;
            }elseif(isset($_POST['op']) && in_array($_POST['op'],array('pass','nopass'))){//审核通过或者不通过
                $user->$authName->scenario = 'auth';//设置场景
                
                $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
                
                if($user->$authName->auth($_POST['op'],$comment)){
                    $res['code'] = 1;
                    $res['reload'] = true;
                }else{
                    $res['msg'] = '操作有误,请稍后重试!';
                }
            }else{
                $res['msg'] = $this->renderPartial('_authForm',array('data'=>$user->$authName),true);
                $res['code'] = 1;
            }
            echo json_encode($res);
        }else{
            throw new CHttpException('400');
        }
    }
}
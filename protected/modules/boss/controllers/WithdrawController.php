<?php
/**
 * 提现控制器
 * @author lizheng
 *
 */
class WithdrawController extends BackEndController{
    /**
     * 提现申请列表
     */
    public function actionIndex(){
        $model = new WithdrawSearchForm('withdrawList');
    
        if(isset($_GET['WithdrawSearchForm'])){
            $model->attributes = $_GET['WithdrawSearchForm'];
        }
        
        $this->render('index',array('model'=>$model));
    }
    
    /**
     * 提现审核
     * @param string $id 申请id
     */
    public function actionCheckApply($id = 0){
        $res['code'] = 0;
        $res['msg'] = '';
        $res['reload'] = false;
        $pass = isset($_POST['pass']) && in_array($_POST['pass'], array('yes','no')) ? $_POST['pass'] : null;
        $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
        $orderNo = isset($_POST['orderNo']) ? trim($_POST['orderNo']) : '';
        if(!$id || !$pass || ($pass === 'no' && !$comment) || ($pass === 'yes' && !$orderNo)){
            $res['msg'] = '参数有误,请稍后重试!';
        }elseif(!($withdraw = WithdrawApply::model()->with('applyUser','blockMoneyDetail')->findByPk($id))){
            $res['msg'] = '不存在的申请记录!';
        }elseif($withdraw->status != 0){
            $res['msg'] = '提现申请记录状态有误!';
            $res['reload'] = true;
        }elseif($pass === 'yes' && !($result = $withdraw->finishWithdraw($orderNo))){
            $res['msg'] = '审核通过操作失败:数据库操作有误!';//@todo 以后可考虑使用model的addError实现错误的详细化
        }elseif($pass === 'no' && !($result = $withdraw->refuseWithdraw($comment))){
            $res['msg'] = '审核不通过操作失败:数据库操作有误!';
        }
        !(isset($result) && $result) || $res['code'] = 1;
        echo json_encode($res);
    }
}

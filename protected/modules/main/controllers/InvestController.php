<?php
/**
 * 我要理财控制器
 *
 */
class InvestController extends Controller {
    
    public function init(){
        $this->layout = '/layouts/investLayout';
    }
    
    public function actionDebitDetail(){
        $debitId = Yii::app()->request->getParam('debitId');
        if(!$debitId){
            throw new CHttpException(200, '未找到相关借贷申请!');
            Yii::app()->end();
        }else{
            $debitModel = Debit::model()->findByPk($debitId);
            if(!$debitModel || !isset($debitModel) || $debitModel->status == 0 || $debitModel->status == 1){
                throw new CHttpException(200, '该借贷申请不存在!');
                Yii::app()->end();
            }
            //借贷失败的产品只有参与该借贷的人可以查看
            $lender = array();
            if($debitModel->status < 0){
                if($debitModel->debitFinancingRecord){
                    foreach($debitModel->debitFinancingRecord as $record){
                        $lender[] = $record->lenderId;
                    }
                }
                if((!empty($lender) && !in_array(Yii::app()->user->id, $lender)) || $debitModel->borrowerId != Yii::app()->user->id){
                    throw new CHttpException(200, '该借贷申请不存在!');
                    Yii::app()->end();
                }
            }
            $canDebit = 1;
        }
        $this->render('debitDetail',array('debit'=>$debitModel));
    }
    
    public function actionFinance(){
        //用户未登录
        $uid = Yii::app()->user->id;
        if(!$uid){
		    $this->redirect('/site/login');
		    Yii::app()->end();
		}
		$userModel = User::model()->findByPk($uid);
		//未通过理财认证
		if($userModel->getInvestStatus() != '已通过'){
		    echo -1;
		    Yii::app()->end();
		}
		//借贷资金或者接待人不存在
		$money = Yii::app()->request->getParam('money');
		$debitId = Yii::app()->request->getParam('debitId');
		if(!$money || !$debitId){
		    echo -1;
		    Yii::app()->end();
		}
		//借贷人余额不足
        if($userModel->cashMoney < $money){
		    echo -1;
		    Yii::app()->end();
		}
		//借贷信息不存在或者状态不对
		$debitModel = Debit::model()->findByPk($debitId);
        if(!$debitModel || !isset($debitModel) || $debitModel->status != 2){
            echo -2;
            Yii::app()->end();
        }
        //投资时间过期了
        $debitTime = date('Y-m-d',strtotime($debitModel->vtime));
        $pastTime = date('Y-m-d',strtotime('+'.intval($debitModel->invitDeadline).' days',strtotime($debitTime.' 00:00:00')));
        $pastTime = strtotime($pastTime.'23:59:59');
        if($pastTime < time()){
            echo -3;
            Yii::app()->end();
        }
        //投资已经满了
        $progress = intval($debitModel->debitProgress);
        if($debitModel->debitMoney == $progress){
            echo -4;
            Yii::app()->end();
        }
        //投资超过上限
        $progress += $money;
        if($progress > $debitModel->debitMoney){
            echo -4;
            Yii::app()->end();
        }
        $transaction = Yii::app()->db->beginTransaction();
        try{
            //插入融资记录
            $debitFinancingRecordModel = new DebitFinancingRecord();
            $debitFinancingRecordModel->debitId = $debitId;
            $debitFinancingRecordModel->debitMoney = $money;
            $debitFinancingRecordModel->lenderId = $uid;
            $debitFinancingRecordModel->save();
            $debitFinancingId = $debitFinancingRecordModel->attributes['id'];
            //更新借贷进度
            $debitModel->debitProgress = $progress;
            $debitModel->update();
            //插入冻结记录表
            $blockMoneyDetailModel = new BlockMoneyDetail();
            $blockMoneyDetailModel->status = 0;
            $blockMoneyDetailModel->money = $money;
            $blockMoneyDetailModel->debitId = $debitId;
            $blockMoneyDetailModel->debitFinancingId = $debitFinancingId;
            $blockMoneyDetailModel->userId = $uid;
            $blockMoneyDetailModel->category = 0;
            $blockMoneyDetailModel->save();
            //若投资达到上限，通知客服处理
            if($debitModel->debitMoney == $progress){
                $kefuTaskModel = new KefuTask();
                $kefuTaskModel->content = $debitId.'投资满额';
                $kefuTaskModel->save();
            }
            //更新用户帐户信息
            $userModel = User::model()->findByPk($uid);
            $userModel->blockMoney += $money;
            $userModel->cashMoney -= $money;
            $userModel->update();
            $transaction->commit();
            echo 1;
        }catch (Exception $e) {
            $transaction->rollback(); 
            echo 0;
        }
        
    }
    
    /**
     * 我要理财-资费说明
     */
    public function actionFeeIntro(){
        $this->breadcrumbs = array(
                '我要理财'=>array('invest/index'),
                '资费说明',
        );
        $this->render('feeIntro');
    }
    
    /**
     * 我要理财-借贷说明
     */
    public function actionDebitIntro(){
        $this->breadcrumbs = array(
                '我要理财'=>array('invest/index'),
                '借贷说明',
        );
        $this->render('debitIntro');
    }
    
    /**
     * 我要理财-首页
     */
    public function actionIndex(){
        $this->breadcrumbs = array(
                '我要理财'
        );
        $this->render('index');
    }
    
    /**
     * 我要理财-借款列表
     */
    public function actionDebitList(){
        $this->breadcrumbs = array(
                '我要理财'=>array('invest/index'),
                '借款列表',
        );
        $this->render('debitList');
    }
    
}
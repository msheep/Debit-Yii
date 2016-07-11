<?php
/*
 * 我的账户相关
 */
class AccountCenterController extends Controller {
    public $uid;
    public function init(){
		$this->uid = Yii::app()->user->id;
		if(!$this->uid){
		    $this->redirect('/site/login');
		}
	}
	
	//列表
	public function actionDebitList(){
	    $listType = isset($_GET['list']) ? trim($_GET['list']) : 0;
	    $criteria = new CDbCriteria;
	    //投资中的列表
	    if($listType == 0){
	        $criteria->select = 't.id , t2.id as debitId';
	        $criteria->join = 'JOIN p2p_debit t2 ON t2.id = t.debitId';
	        $criteria->addInCondition('t2.status',array(2,3));
	        $criteria->addCondition('t.lenderId = :uid');
	        $criteria->params[':uid'] = $this->uid;
	        $criteria->order = 't.ctime DESC';
	        $data = DebitFinancingRecord::model()->findAll($criteria);
	    //借款中的列表
	    }else if($listType == 1){
	        $criteria->addCondition('borrowerId = :uid');
	        $criteria->params[':uid'] = $this->uid;
	        $criteria->addInCondition('status',array(0,1,2,3));
	        $criteria->order = 'ctime DESC';
	        $data = Debit::model()->findAll($criteria);
	    //近期完成的列表
	    }else if($listType == 2){
	        $nowDate = date('Y-m-d',time());
	        $lastDate = date('Y-m-d',strtotime('-3 months'));
	        $listType = isset($_GET['type']) ? trim($_GET['type']) : 0;
	        $history = !isset($_GET['history']) || trim($_GET['history']) == 0 ? 0 : 1;
	        if($listType == 0){
	            if($history == 1){
	                $sql = '(SELECT debitProgress as debitMoney,title,debitRate,ctime,CONCAT("debit") as type,id
                		 	 FROM p2p_debit 
                			 WHERE borrowerId = :uid AND ctime < :lastDate AND status IN (4,-1,-2,-3,-4)) 
							UNION ALL 
							(SELECT a.debitMoney,b.title,b.debitRate,a.ctime,CONCAT("invest") as type,a.id
                			 FROM p2p_debit_financing_record a
	                		 JOIN p2p_debit b 
	                		 ON a.debitId = b.id 
	                		 WHERE a.lenderId = :uid AND a.ctime < :lastDate AND b.status = 4) 
	                		order by ctime DESC';
	            }else{
	                $sql = '(SELECT debitProgress as debitMoney,title,debitRate,ctime,CONCAT("debit") as type,id
                			 FROM p2p_debit 
                			 WHERE borrowerId = :uid AND ctime >= :lastDate AND status IN (4,-1,-2,-3,-4)) 
	                		UNION ALL 
	                		(SELECT a.debitMoney,b.title,b.debitRate,a.ctime,CONCAT("invest") as type,a.id 
	                		 FROM p2p_debit_financing_record a
	                		 JOIN p2p_debit b ON a.debitId = b.id 
	                		 WHERE a.lenderId = :uid AND a.ctime >= :lastDate AND b.status = 4) 
	                		order by ctime DESC';
	            } 
	        }else if($listType == 1){
	            if($history == 1){
	                $sql = 'SELECT a.debitMoney,b.title,b.debitRate,a.ctime,CONCAT("invest") as type,a.id
	                		FROM p2p_debit_financing_record a
	                		JOIN p2p_debit b ON a.debitId = b.id 
	                		WHERE a.lenderId = :uid AND a.ctime < :lastDate AND b.status = 4 order by a.ctime DESC';
	            }else{
	                $sql = 'SELECT a.debitMoney,b.title,b.debitRate,a.ctime,CONCAT("invest") as type,a.id 
	                		FROM p2p_debit_financing_record a
	                		JOIN p2p_debit b ON b.id = a.debitId 
	                		WHERE a.lenderId = :uid AND a.ctime >= :lastDate AND b.status = 4 order by a.ctime DESC';
	            }
	        }else if($listType == 2){
	            if($history == 1){
    	            $sql = 'SELECT debitProgress as debitMoney,title,debitRate,ctime,CONCAT("debit") as type,id 
    	            		FROM p2p_debit 
    	            		WHERE borrowerId = :uid AND ctime < :lastDate AND status IN (4,-1,-2,-3,-4) order by ctime DESC';
	            }else{
	                $sql = 'SELECT debitProgress as debitMoney,title,debitRate,ctime,CONCAT("debit") as type,id 
    	            		FROM p2p_debit 
    	            		WHERE borrowerId = :uid AND ctime >= :lastDate AND status IN (4,-1,-2,-3,-4) order by ctime DESC';
	            }
	        }
	        $command = Yii::app()->db->createCommand($sql);
            $command -> bindParam(':uid', $this->uid, PDO::PARAM_INT);
            $command -> bindParam(':lastDate', $lastDate, PDO::PARAM_STR);
    		$data = $command->queryAll();
	    //账户变动记录的列表
	    }else if($listType == 3){
	        $listType = isset($_GET['type']) ? trim($_GET['type']) : 0;
	        $data  = IncomePayoutDetail::model()->findAll('userId = :uid',array(':uid'=>$this->uid));
	    }
	    $dp = new CArrayDataProvider($data, array(
    		'pagination' => array(
				'pageSize' => 10,
			),
		));
		if (Yii::app()->request->isAjaxRequest) {
			$this->renderPartial('_debitList', array('dp' => $dp));
			Yii::app()->end();
		}else{
		    $this->render('debitList',array('dp'=>$dp));
		}
	}
	
	public function actionAgreePublish(){
	    $id = Yii::app()->request->getParam('id');
	    $money = Yii::app()->request->getParam('money');
	    $result = Yii::app()->request->getParam('result');
	    $debitModel = Debit::model()->findbyPk($id);
	    if($debitModel->realDebitMoney != $money){
	        echo -1;
	        Yii::app()->end();
	    }
	    switch ($result){
	        //拒绝发布
	        case 0:
	            $reason = trim(Yii::app()->request->getParam('reason'));
	            $debitModel->status = '-3';
	            $debitModel->vtime = date('Y-m-d H:i:s');
	            $debitModel->userRejectInfo = mysql_escape_string($reason);
        	    if($debitModel->update()){
        	        echo 1;
        	    }else{
        	        echo 0;
        	    }
	            break;
	        //同意发布     
	        case 1:
	            $debitModel->status = 2;
	            $debitModel->vtime = date('Y-m-d H:i:s');
        	    if($debitModel->update()){
        	        echo 1;
        	    }else{
        	        echo 0;
        	    }
	            break;
	    }
	}
	
	public function actionDebitDetail(){
	    $debitId = Yii::app()->request->getParam('debitId');
	    $debitModel = Debit::model()->findByPk($debitId);
	    if(!$debitModel){
	        throw new CHttpException(200, '该借贷申请不存在!');
            Yii::app()->end();
	    }
	    $this->render('debitDetail',array('debit'=>$debitModel));
	}
}
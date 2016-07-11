<?php
/*
 * 借贷相关控制器
 */
class DebitController extends BackEndController{
     //借贷产品审核列表
     public function actionProductList(){
		$criteria = new CDbCriteria;
        $criteria->order = 't.ctime DESC';
        $criteria->condition = 'productId > 0';
        $dateBegin = date('Y-m-d'); 
        $dateEnd = date('Y-m-d');
        $checkStatus = '';
        $condition = '';
        $searchCondition = '';
        $params = $_GET;
        if(isset($_GET['search'])){
            if(isset($_GET['dateBegin'])){
                $dateBegin = $_GET['dateBegin'];
            }
            if(isset($_GET['dateEnd'])){
                $dateEnd = $_GET['dateEnd'];
            }
            if(isset($_GET['checkStatus']) && !empty($_GET['checkStatus'])){
                $checkStatus = array();
                foreach($_GET['checkStatus'] as $s){
                    if($s == '1'){
                        $checkStatus[] = 't.status=1';
                        $checkStatus[] = 't.status=3';
                        $checkStatus[] = 't.status=4';
                        $checkStatus[] = 't.status=-1';
                        $checkStatus[] = 't.status=-3';
                        $checkStatus[] = 't.status=-4';
                    }else if($s == '2'){
                        $checkStatus[] = 't.status=2';
                        $checkStatus[] = 't.status=-1';
                        $checkStatus[] = 't.status=3';
                        $checkStatus[] = 't.status=4';
                    }else{
                        $checkStatus[] = 't.status='.$s;
                    }
                }
                $checkStatus = array_unique($checkStatus);
                $status = implode(' OR ', $checkStatus);
                $criteria->condition.=" AND (".$status.")";
            } 
            if(isset($_GET['checkCat']) && !empty($_GET['checkCat'])){
                $checkCat = array();
                foreach($_GET['checkCat'] as $c){
                    $checkCat[] = 't.cat='.$c;
                }
                $cat = implode(' OR ', $checkCat);
                $criteria->condition.=" AND (".$cat.")";
            }
            if(isset($_GET['searchCondition']) && trim($_GET['searchCondition'])!=''){
                $searchCondition = trim($_GET['searchCondition']);
                $condition = $_GET['condition'];
                if($condition == 1){
                    $criteria->condition.=' AND t.borrowerId = :searchCondition';                
                }else if($condition == 2){
                    $searchCondition = 0;
                    $user = User::model()->find('userName=:name',array('name'=>$searchCondition));
                    if($user){
                        $searchCondition = $user->id;
                    }
                    $criteria->condition.=' AND t.borrowerId = :searchCondition';        
                }else if($condition == 3){
                    $criteria->condition.=' AND t.title like :searchCondition';                   
                }else if($condition == 4){
                    $searchCondition = intval($searchCondition);
                    $criteria->condition.=' AND t.title like :searchCondition';                   
                }
                $criteria->params += array(':searchCondition' => $searchCondition);         
            }
        } 
        $criteria->condition.=" AND t.ctime >= :dateBegin";
        $criteria->condition.=' AND t.ctime <= :dateEnd';
        $criteria->params += array(':dateBegin' => $dateBegin.' 00:00:00');
        $criteria->params += array(':dateEnd' => $dateEnd.' 23:59:59');
        
        $dataProvider = new CActiveDataProvider('Debit', array(
            'pagination'=>array(
            	'pageSize'=>'2',
            ),
            'criteria'=>$criteria,
        ));
       if(Yii::app()->request->isAjaxRequest){ 
            $this->renderPartial('_ajaxListView',array(
				'dataProvider' => $dataProvider,
            	'itemView'=>'_productList',
		    ));
        }else{
            $this->render('productList',array(
            	'dataProvider' => $dataProvider,
            	'data'=>$params ,
            	'dateBegin' => $dateBegin,
            	'dateEnd' => $dateEnd
            ));
       }  
	}
	
	//借贷产品详情页
	public function actionDetail(){
	    $debitId = Yii::app()->request->getParam('debitId');
	    $debitModel = Debit::model()->findByPk($debitId);
	    if(!$debitModel){
	        throw new CHttpException(200, '该借贷申请不存在!');
            Yii::app()->end();
	    }
	    $this->render('detail',array('data'=>$debitModel));
	}
	
	//还款记录列表
    public function actionRepayList(){
		$criteria = new CDbCriteria;
        $criteria->order = 't.ctime DESC';
        $criteria->condition = '1';
        $dateBegin = date('Y-m-d'); 
        $dateEnd = date('Y-m-d');
        $checkStatus = '';
        $condition = '';
        $searchCondition = '';
        $params = $_GET;
        extract($_GET);
        if(isset($_GET['search'])){
            if(isset($_GET['dateBegin'])){
                $dateBegin = $_GET['dateBegin'];
            }
            if(isset($_GET['dateEnd'])){
                $dateEnd = $_GET['dateEnd'];
            }
            if(isset($_GET['checkStatus']) && !empty($_GET['checkStatus'])){
                $checkStatus = array();
                foreach($_GET['checkStatus'] as $s){
                    $checkStatus[] = 't.status='.$s;
                }
                $status = implode(' OR ', $checkStatus);
                $criteria->condition.=" AND (".$status.")";
            } 
            if(isset($_GET['checkCat']) && !empty($_GET['checkCat'])){
                $checkCat = array();
                foreach($_GET['checkCat'] as $c){
                    $checkCat[] = 't.cat='.$c;
                }
                $cat = implode(' OR ', $checkCat);
                $criteria->condition.=" AND (".$cat.")";
            }
            
        }
      
        $criteria->condition.=" AND t.ctime >= :dateBegin";
        $criteria->condition.=' AND t.ctime <= :dateEnd';
        $criteria->params += array(':dateBegin' => $dateBegin.' 00:00:00');
        $criteria->params += array(':dateEnd' => $dateEnd.' 23:59:59');
        
        $dataProvider = new CActiveDataProvider('DebitRepayRecord', array(
            'pagination'=>array(
            	'pageSize'=>'2',
            ),
            'criteria'=>$criteria,
        ));
        if(Yii::app()->request->isAjaxRequest){ 
            $this->renderPartial('_ajaxListView',array(
				'dataProvider' => $dataProvider,
            	'itemView'=>'_repayList',
		    ));
        }else{
            $this->render('repayList',array(
            	'dataProvider' => $dataProvider,
            	'data'=>$params ,
            	'dateBegin' => $dateBegin,
            	'dateEnd' => $dateEnd
            ));
        }  
	}
	
	//多图片上传
	public function actionUploadImage(){
	    if(!empty($_FILES)){
	        $file = new CUploadedFile($_FILES['Filedata']['name'],$_FILES['Filedata']['tmp_name'],$_FILES['Filedata']['type'],$_FILES['Filedata']['size'],$_FILES['Filedata']['error']);
	        $fileName = date('YmdHis').'_'.$this->uid.'_'.rand().'.'.$file->extensionName;    
            $fileSavePath = dirname(Yii::app()->basePath).'/upload/'.date('Y').'/'.date('m').'/'.date('d');
            if(is_dir($fileSavePath) || @mkdir($fileSavePath,'0777',true)){
                if($file->saveAs($fileSavePath.'/'.$fileName)){
                    echo json_encode(array('path'=>'/upload/'.date('Y').'/'.date('m').'/'.date('d'),'filename'=>$fileName,'realName'=>$_FILES['Filedata']['name']));
                }else{
                    echo 0;
                }
            }
	    }
	} 
	
	//审核借贷
    public function actionExamineDebit(){
        if(empty($_POST)){
            echo -1;
            Yii::app()->end();
        }
        switch($_POST['pass']){
            case 'yes':
                $transaction = Yii::app()->db->beginTransaction();
	            try{
	                //更新借贷表
                    $debitModel = Debit::model()->findByPk($_POST['debitId']);
                    $debitModel->verify = 1;
                    $debitModel->operatorId = $this->uid;
                    $debitModel->realDebitMoney = $_POST['realDebitMoney'];
                    $debitModel->realMinDebitMoney = $_POST['realMinDebitMoney'];
                    $debitModel->productPrice = $_POST['productPrice'];
                    $debitModel->kefuInfo = $_POST['kefuInfo'];
                    $debitModel->status = 1;
                    $debitModel->kvtime = date('Y-m-d H:i:s');
                    $debitModel->update();
                    //保存图片
                    if(isset($_POST['file'])){
                        foreach($_POST['file'] as $file){
                           $image = array();
                           $image = json_decode($file,true);
                           if($image){
                               $fileModel = new File();
                               $fileModel->userId = $this->uid; 
                               $fileModel->category = 10; 
                               $fileModel->relateId = $_POST['debitId']; 
                               $fileModel->fileName = $image['realName']; 
                               $fileModel->path = $image['path'].'/'.$image['filename'];
                               $fileModel->save();
                           }         
                        }
                    }
                    //给用户发送信息
                    $userNotifyModel = new UserNotify();
	                $userNotifyModel->userId = $debitModel->borrowerId;
	                $userNotifyModel->title = '审核通过';
	                $userNotifyModel->msg = '审核结果:抵押品估值'.$_POST['realDebitMoney'].'万元；核准贷款金额'.$_POST['productPrice'].'。<br/>'.$_POST['kefuInfo'];
	                $userNotifyModel->isRead = 0;
	                $userNotifyModel->save();
                    $transaction->commit();
	                echo 1;
	            }catch (Exception $e) {
                    $transaction->rollback(); 
                    echo 0;
                }
                break;
            case 'no':
                $transaction = Yii::app()->db->beginTransaction();
	            try{
                    $debitModel = Debit::model()->findByPk($_POST['debitId']);
                    $debitModel->operatorId = $this->uid;
                    $debitModel->kefuInfo = $_POST['kefuInfo'];
                    $debitModel->status = -2;
                    $debitModel->kvtime = date('Y-m-d H:i:s');
	                $debitModel->save();
	                $userNotifyModel = new UserNotify();
	                $userNotifyModel->userId = $debitModel->borrowerId;
	                $userNotifyModel->title = '审核不通过';
	                $userNotifyModel->msg = $_POST['kefuInfo'];
	                $userNotifyModel->isRead = 0;
	                $userNotifyModel->save();
	                $transaction->commit();
	                echo 1;
                }catch (Exception $e) {
                    $transaction->rollback(); 
                    echo 0;
                }
                break;
        }
    }
    
    //终止借贷
    public function actionStopDebit(){
        $debitId = Yii::app()->request->getParam('id');
        $reason = Yii::app()->request->getParam('reason');
        $debitModel = Debit::model()->findByPk($debitId);
        //订单状态不正确
        if($debitModel->status != 2){
            echo -1;
            Yii::app()->end();
        }
        if($debitModel->debitProgress > 0){
            try{
                foreach($debitModel->debitFinancingRecord as $financingRecord){
                    //更新出借人账户信息
                    $userModel = User::model()->findByPk($financingRecord->lenderId);
                    $userModel->blockMoney -= $financingRecord->debitMoney;
                    $userModel->update();
                    //更新出借人block记录
                    $blockMoneyModel = BlockMoneyDetail::model()->updateAll(array('endTime'=>date('Y-m-d H:i:s',time())),
                                   'debitId = :debitId AND debitFinancingId = :debitFinancingId AND userId = :userId',
                                   array(':debitId'=>$debitId,':debitFinancingId'=>$financingRecord->id,':userId'=>$financingRecord->lenderId));
                    //更新借贷状态
                    $debitModel->status = '-4';
                    $debitModel->kefuStopInfo = mysql_escape_string($reason);
                    $debitModel->stopId = $this->uid;
                    $debitModel->stime = date('Y-m-d H:i:s');
                    $debitModel->update();
                    $transaction->commit();
                    echo 1;
                }
            }catch (Exception $e) {
                $transaction->rollback(); 
                echo 0;
            }
        }else{
            $debitModel->status = '-4';
            $debitModel->kefuStopInfo = mysql_escape_string($reason);
            $debitModel->stopId = $this->uid;
            if($debitModel->update()){
                echo 1;
            }else{
                echo 0;
            }
        }
    }
    
    public function actionShowFinanceRecord(){
    	$debitId = Yii::app()->request->getParam('id');
    	$debitModel = Debit::model()->findByPk($debitId);
    	$html = '';
    	if($debitModel->debitFinancingRecord){
    		$html .= '<table class="tableStatic"><thead><tr>';
    		$html .= '<td>ID</td>';
    		$html .= '<td>投资人</td>';
    		$html .= '<td>投资金额</td>';
    		$html .= '<td>占总投资比</td>';
    		$html .= '<td>时间</td>';
    		$html .= '<td>状态</td>';
    		$html .= '</tr></thead><tbody>';
    		foreach($debitModel->debitFinancingRecord as $record){
    			$html .= '<tr>';
    			$html .= '<td>'.$record->id.'</td>';
    			$html .= '<td>'.$this->getUserName($record->lenderId).'</td>';
    			$html .= '<td>¥'.$record->debitMoney.'</td>';
    			$html .= '<td>'.(sprintf("%.4f",$record->debitMoney / $debitModel->debitProgress) * 100).'%</td>';
    			$html .= '<td>'.$record->ctime.'</td>';
    			$html .= '<td>'.Debit::$status[$debitModel->status].'</td>';
    			$html .= '</tr>';
    		}
    		$html .= '</tbody></table>';
    	}else{
    		$html = '暂时无借款信息';
    	}
    	echo $html;
    }
    
	public function actionShowRepayRecord(){
    	$debitId = Yii::app()->request->getParam('id');
    	$debitModel = Debit::model()->findByPk($debitId);
    	$html = '';
    	if($debitModel->debitRepayRecord){
    		$html .= '<table class="tableStatic"><thead><tr>';
    		$html .= '<td>ID</td>';
    		$html .= '<td>规定还款时间</td>';
    		$html .= '<td>实际还款时间</td>';
    		$html .= '<td>还款金额</td>';
    		$html .= '<td>收款人</td>';
    		$html .= '<td>状态</td>';
    		$html .= '</tr></thead><tbody>';
    		foreach($debitModel->debitRepayRecord as $record){
    			foreach($debitModel->debitFinancingRecord as $finance){
    				$html .= '<tr>';
	    			$html .= '<td>'.$record->id.'</td>';
	    			$html .= '<td>'.$record->repayDate.'</td>';
	    			$html .= '<td>'.($record->realRepayDate == '0000-00-00' ? '—' : $record->realRepayDate).'</td>';
	    			$html .= '<td>¥'.Debit::getMonthRepay($debitModel->debitMoney, $debitModel->debitDeadline, $debitModel->debitRate, $finance->debitMoney).'</td>';
	    			$html .= '<td>'.$this->getUserName($finance->lenderId).'</td>';
	    			$html .= '<td>'.DebitRepayRecord::$status[$record->status].'</td>';
	    			$html .= '</tr>';
    			}
    		}
    		$html .= '</tbody></table>';
    	}else{
    		$html = '暂时无借款信息';
    	}
    	echo $html;
    }
 
    //放款
    public function actionLoanDebitMoney(){
        $debitId = Yii::app()->request->getParam('id');
        $debitModel = Debit::model()->findByPk($debitId);
        //订单状态不正确
        if($debitModel->status != 2){
            echo -1;
            Yii::app()->end();
        }
        $financingMoney = 0;
        $investUser = array();
        foreach($debitModel->debitFinancingRecord as $financingRecord){
            $financingMoney += $financingRecord->debitMoney;
        }
        //满额放款
        if($financingMoney == $debitModel->realDebitMoney){
            if($financingMoney == $debitModel->debitProgress){
                $type = 0;
            }else{
                //不满足放款条件
                echo -2;
                Yii::app()->end();
            }
        }else{
            $deadline = Debit::getDeadline($debitModel->vtime, $debitModel->invitDeadline + 1);
            //最低限额放款
            if( ($financingMoney < $debitModel->realDebitMoney) && ($financingMoney >= $debitModel->realMinDebitMoney) && (time() > strtotime($deadline)) ){
                $type = 1;
            }else{
                //不满足放款条件
                echo -2;
                Yii::app()->end();
            }
        }
        $transaction = Yii::app()->db->beginTransaction();
        try{
            foreach($debitModel->debitFinancingRecord as $financingRecord){
                $monthIncomingMoney = Debit::getMonthRepay($financingRecord->debitMoney, $debitModel->debitDeadline, $debitModel->debitRate,$financingRecord->debitMoney);
                $incomingMoney = $monthIncomingMoney * $debitModel->debitDeadline;
                //更新出借人账户信息
                $userModel = User::model()->findByPk($financingRecord->lenderId);
                $userModel->blockMoney -= $financingRecord->debitMoney;
                $userModel->incomingMoney += $incomingMoney;
                $userModel->update();
                //更新出借人block记录
                $blockMoneyModel = BlockMoneyDetail::model()->updateAll(array('endTime'=>date('Y-m-d H:i:s',time())),
                                   'debitId = :debitId AND debitFinancingId = :debitFinancingId AND userId = :userId',
                                   array(':debitId'=>$debitId,':debitFinancingId'=>$financingRecord->id,':userId'=>$financingRecord->lenderId));
                //出借人支出记录
                $incomePayoutDetailModel = new IncomePayoutDetail();
                $incomePayoutDetailModel->type = '6';
                $incomePayoutDetailModel->money = $financingRecord->debitMoney;
                $incomePayoutDetailModel->userId = $userModel->id;
                $incomePayoutDetailModel->category = '1';
                $incomePayoutDetailModel->relatedId = $financingRecord->debitId;
                $incomePayoutDetailModel->relatedTable = 'DebitFinancingRecord';
                $incomePayoutDetailModel->nowCashMoney = $userModel->cashMoney;
                $incomePayoutDetailModel->nowBlockMoney = $userModel->blockMoney;
                $incomePayoutDetailModel->save(false); 
                //给用户发送信息
                $userNotifyModel = new UserNotify();
                $userNotifyModel->userId = $debitModel->borrowerId;
                $userNotifyModel->fromId = $this->uid;
                $userNotifyModel->relatedId = $financingRecord->id;
                $userNotifyModel->title = '投标成功';
                $userNotifyModel->msg = '您在ID为'.$financingRecord->debitId.'招标成功，于'.date('Y-m-d H:i:h').'打款给了招标人，您将在未来'.$debitModel->debitDeadline.'个月内，每月将收到'.$monthIncomingMoney.'元';
                $userNotifyModel->isRead = 0;
                $userNotifyModel->save();
            }
            $fee = Debit::getDebitFee($financingMoney, $debitModel->debitDeadline);
            $repayMoney = Debit::getMonthRepay($financingMoney, $debitModel->debitDeadline, $debitModel->debitRate);
            //更新借贷人账户信息
            $userModel = User::model()->findByPk($debitModel->borrowerId);
            $userModel->cashMoney += $financingMoney;
            $userModel->refundMoney += $repayMoney * $debitModel->debitDeadline;
            $userModel->update();
            //借贷人的收入记录
            $incomePayoutDetailModel = new IncomePayoutDetail();
            $incomePayoutDetailModel->type = '3';
            $incomePayoutDetailModel->money = $debitModel->debitMoney;
            $incomePayoutDetailModel->userId = $debitModel->borrowerId;
            $incomePayoutDetailModel->category = '0';
            $incomePayoutDetailModel->relatedId = $debitModel->id;
            $incomePayoutDetailModel->relatedTable = 'Debit';
            $incomePayoutDetailModel->nowCashMoney = $userModel->cashMoney;
            $incomePayoutDetailModel->nowBlockMoney = $userModel->blockMoney;
            $incomePayoutDetailModel->save(false);
             //更新借贷人账户信息
            $userModel = User::model()->findByPk($debitModel->borrowerId);
            $userModel->cashMoney -= $fee;
            $userModel->update();
            //借贷人的手续费支出记录
            $incomePayoutDetailModel = new IncomePayoutDetail();
            $incomePayoutDetailModel->type = '10';
            $incomePayoutDetailModel->money = $fee;
            $incomePayoutDetailModel->userId = $debitModel->borrowerId;
            $incomePayoutDetailModel->category = '1';
            $incomePayoutDetailModel->relatedId = $debitModel->id;
            $incomePayoutDetailModel->relatedTable = 'Debit';
            $incomePayoutDetailModel->nowCashMoney = $userModel->cashMoney;
            $incomePayoutDetailModel->nowBlockMoney = $userModel->blockMoney;
            $incomePayoutDetailModel->save(false);
            //更新平台账户信息
            $userModel = User::model()->findByPk(Yii::app()->params['adminId']);
            $userModel->cashMoney += $fee;
            $userModel->update();
            //平台账户手续费收入记录
            $incomePayoutDetailModel = new IncomePayoutDetail();
            $incomePayoutDetailModel->type = '9';
            $incomePayoutDetailModel->money = $fee;
            $incomePayoutDetailModel->userId = $userModel->id;
            $incomePayoutDetailModel->category = '0';
            $incomePayoutDetailModel->relatedId = $debitModel->id;
            $incomePayoutDetailModel->relatedTable = 'Debit';
            $incomePayoutDetailModel->nowCashMoney = $userModel->cashMoney;
            $incomePayoutDetailModel->nowBlockMoney = $userModel->blockMoney;
            $incomePayoutDetailModel->save(false);
            //更新借贷人的借贷记录状态和管理费
            $debitModel->status = 3;
            $debitModel->fee = $fee;
            $debitModel->repayDate = date('d',time());
            $debitModel->update();
            //插入待还款记录
            $repayRecordModel = new DebitRepayRecord();
            $repayRecordModel->debitId = $debitModel->id;
            $repayRecordModel->repayMoney = $repayMoney;
            $repayRecordModel->repayDate = Debit::getMonthDay(date('Y-m-d', time()), 1);
            $repayRecordModel->save();
            //给用户发送信息
            $userNotifyModel = new UserNotify();
            $userNotifyModel->userId = $debitModel->borrowerId;
            $userNotifyModel->title = '达标放款';
            $userNotifyModel->relatedId = $debitModel->id;
            $userNotifyModel->msg = '您在ID为'.$financingRecord->debitId.'招标成功，于'.date('Y-m-d H:i:h').'放款，支付平台手续费'.$fee.'元';
            $userNotifyModel->isRead = 0;
            $userNotifyModel->save();
            //$userModel = User::model()->updateByPk($financingRecord->lenderId,array('blockMoney'=>new CDbExpression('blockMoney - '.$financingRecord->debitMoney)));
            $transaction->commit();
            echo 1;
        }catch (Exception $e) {var_dump($e);
            $transaction->rollback(); 
            echo 0;
        } 
    }
    
    //每月还款脚本（每日凌晨跑脚本）
    public function actionRepayMonth(){
        $startTime = time();
        
        $overTimeRepay = array();
        $onTimeRepay = array();
        $mailNotify = array();
        $smsNotify = array();
        //今日还款和逾期者账户余额不足发送催款提醒
        $urgeNotify = array();
        $errorData = array();
        $timeAround = date('Y-m-d',strtotime('+3 days'));
        $allNeedActionDebit = DebitRepayRecord::model()->findAll('repayDate <= :time AND status = 0', array(':time' => $timeAround));
        if($allNeedActionDebit){
            $result = TRUE;
            foreach($allNeedActionDebit as $debits){
                $repayTimes = DebitRepayRecord::model()->count('debitId = :id AND status = 1', array(':id'=>$debits->debitId));
                //异常数据
                if($repayTimes != $debits->debitInfo->debitDeadline || $debits->debitInfo->status != 3){
                     $errorData[] = $debits->id;
                     continue;
                }
                $param = array();
                $debits->repayDate = $debits->repayDate;
                if(strtotime($debits->repayDate) > time()){
                    $beginDate = strtotime('+24 hours');
                    $endDate = strtotime('+72 hours');
                    //提前三天发送邮件还款提醒
                    if(strtotime($debits->repayDate) <= $endDate && strtotime($debits->repayDate) > $beginDate){
                        $mailNotify[$debits->id]['debitId'] = $debits->debitId;
                        $mailNotify[$debits->id]['repayDate'] = $debits->repayDate;
                        $mailNotify[$debits->id]['repayMoney'] = $debits->repayMoney;
                    //提前一天发送短信还款提醒
                    }else if(strtotime($debits->repayDate) <= $beginDate){
                        $smsNotify[$debits->id]['debitId'] = $debits->debitId;
                        $smsNotify[$debits->id]['repayDate'] = $debits->repayDate;
                        $smsNotify[$debits->id]['repayMoney'] = $debits->repayMoney;
                    }
                }else{
                    $cashMoney = $debits->debitInfo->userInfo->cashMoney;
                    //余额充足扣款
                    if($cashMoney >= $debits->repayMoney){
                        if(date('Y-m-d') == $debits->repayDate){
                            //取出该借贷所有借贷人的借款信息
                            $allFinance = $debits->debitInfo->debitFinancingRecord;
                            $totalRepayMoney = 0;
                            $transaction = Yii::app()->db->beginTransaction();
                            try{
                                if($allFinance){
                                    foreach($allFinance as $finance){
                                        //计算每位出借人的应收还款
                                        $repayMoney = Debit::getMonthRepay($debits->debitInfo->debitProgress, $debits->debitInfo->debitDeadline, $debits->debitInfo->debitRate, $finance->debitMoney);
                                        $totalRepayMoney += $repayMoney;
                                        //更新出借人帐户
                                        $userModel = User::model()->findByPk($finance->lenderId);
                                        $userModel->incomingMoney -= $repayMoney;
                                        $userModel->cashMoney += $repayMoney;
                                        $userModel->update();
                                        //出借人收入记录
                                        $incomePayoutDetailModel = new IncomePayoutDetail();
                                        $incomePayoutDetailModel->type = '1';
                                        $incomePayoutDetailModel->money = $repayMoney;
                                        $incomePayoutDetailModel->userId = $finance->lenderId;
                                        $incomePayoutDetailModel->category = '0';
                                        $incomePayoutDetailModel->debitId = $debits->debitId;
                                        $incomePayoutDetailModel->debitFinancingId = $finance->id;
                                        $incomePayoutDetailModel->debitRepayRecordId = $debits->id;
                                        $incomePayoutDetailModel->nowCashMoney = $userModel->cashMoney;
                                        $incomePayoutDetailModel->nowBlockMoney = $userModel->blockMoney;
                                        $incomePayoutDetailModel->save(false); 
                                    }
                                    //更新借贷人账户
                                    $userModel = User::model()->findByPk($debits->debitInfo->borrowerId);
                                    $userModel->refundMoney -= $debits->repayMoney;
                                    $userModel->cashMoney -= $debits->repayMoney;
                                    $userModel->update();
                                    //借贷人支出记录
                                    $incomePayoutDetailModel = new IncomePayoutDetail();
                                    $incomePayoutDetailModel->type = '4';
                                    $incomePayoutDetailModel->money = $repayMoney;
                                    $incomePayoutDetailModel->userId = $finance->lenderId;
                                    $incomePayoutDetailModel->category = '1';
                                    $incomePayoutDetailModel->debitId = $debits->debitId;
                                    $incomePayoutDetailModel->debitRepayRecordId = $debits->id;
                                    $incomePayoutDetailModel->nowCashMoney = $userModel->cashMoney;
                                    $incomePayoutDetailModel->nowBlockMoney = $userModel->blockMoney;
                                    $incomePayoutDetailModel->save(false); 
                                   
                                    $moneyDiff = 0;
                                    if($totalRepayMoney > $debits->repayMoney){
                                        //平台四舍五入支出账户
                                        $moneyDiff = $totalRepayMoney - $debits->repayMoney;
                                        //更新平台账户信息
                                        $userModel = User::model()->findByPk(Yii::app()->params['adminId']);
                                        $userModel->cashMoney -= $moneyDiff;
                                        $userModel->update();
                                        //平台账户手续费支出记录
                                        $incomePayoutDetailModel = new IncomePayoutDetail();
                                        $incomePayoutDetailModel->type = '12';
                                        $incomePayoutDetailModel->money = $moneyDiff;
                                        $incomePayoutDetailModel->userId = $userModel->id;
                                        $incomePayoutDetailModel->category = '1';
                                        $incomePayoutDetailModel->debitId = $debits->debitId;
                                        $incomePayoutDetailModel->debitRepayRecordId = $debits->id;
                                        $incomePayoutDetailModel->nowCashMoney = $userModel->cashMoney;
                                        $incomePayoutDetailModel->nowBlockMoney = $userModel->blockMoney;
                                        $incomePayoutDetailModel->save(false);
                                    }else if($totalRepayMoney < $debits->repayMoney){
                                        //平台四舍五入收益账户
                                        $moneyDiff = $debits->repayMoney - $totalRepayMoney;
                                        //更新平台账户信息
                                        $userModel = User::model()->findByPk(Yii::app()->params['adminId']);
                                        $userModel->cashMoney += $moneyDiff;
                                        $userModel->update();
                                        //平台账户手续费收益记录
                                        $incomePayoutDetailModel = new IncomePayoutDetail();
                                        $incomePayoutDetailModel->type = '11';
                                        $incomePayoutDetailModel->money = $moneyDiff;
                                        $incomePayoutDetailModel->userId = $userModel->id;
                                        $incomePayoutDetailModel->category = '0';
                                        $incomePayoutDetailModel->debitId = $debits->debitId;
                                        $incomePayoutDetailModel->debitRepayRecordId = $debits->id;
                                        $incomePayoutDetailModel->nowCashMoney = $userModel->cashMoney;
                                        $incomePayoutDetailModel->nowBlockMoney = $userModel->blockMoney;
                                        $incomePayoutDetailModel->save(false);
                                    }
                                    
                                    //更新还款记录状态
                                    $debits->status = 1;
                                    $debits->realRepayDate = date('Y-m-d');
                                    $debits->update();
                                    //统计所有的还款记录
                                    $repayTimes = DebitRepayRecord::model()->count('debitId = :id', array(':id'=>$debits->debitId));
                                    //未还完插入还款记录
                                    if($repayTimes < $debits->debitInfo->debitDeadline){
                                        $repayModel = new DebitRepayRecord();
                                        $repayModel->debitId = $debits->debitId;
                                        $repayModel->repayMoney = $debits->repayMoney;
                                        $repayModel->repayDate = Debit::getMonthDay($debits->repayDate, 1);
                                        $repayModel->save();
                                    }else{
                                        //全部更新借贷状态
                                        $debits->debitInfo->status = 4;
                                        $debits->debitInfo->save();
                                    } 
                                }
                                $transaction->commit();
                            }catch (Exception $e) {
                                $transaction->rollback(); 
                                $result = FALSE;
                            } 
                        }
                    //余额不足发送短信催款    
                    }else{
                        //发送短信
                        $param['content'] = '尊敬的'.$userModel->userName.'用户：您好！'.date('d',strtotime($debits->repayDate)).'日为您的本月还款日，由于您的现金账户不足，本月您在尊贷网的还款未能成功扣除，请您尽快充值还款。';
                        if($debits->repayDate < date('Y-m-d')){
                            $timeDiff = (strtotime(date('Y-m-d')) - strtotime($debits->repayDate)) / (3600*24); 
                            $param['content'] .= '您已逾期'.$timeDiff.'天';
                        }
                        $param['mobile'] = $userModel->mobile;
                        $return = SendSMS::SendText($param);
                        if($return['status'] == 'fail'){
                            $result = FALSE;
                        }
                    }
                   
                }
             }
             print_r($mailNotify);
             print_r($smsNotify);
             print_r($urgeNotify);
        }else{
            echo '*******************'.date('Y-m-d').'无需要还款的记录！*****************************';
        }
    }

}
<?php
/**
 * 报表控制器
 * @author lizheng
 *
 */
class ReportController extends BackEndController{
    /**
     * 提现报表
     */
    public function actionWithdraw($export = false){
        $model = new WithdrawSearchForm('withdrawList');
        
        if(isset($_GET['WithdrawSearchForm'])){
            $model->attributes = $_GET['WithdrawSearchForm'];
        }
        
        if($export){
            $this->exportWithdraw($model->exportWithdrawList());
            Yii::app()->end();
        }
        
        $_GET['export'] = true;
        $exportUrl = $this->createAbsoluteUrl('report/withdraw',$_GET);
        unset($_GET['export']);
        $this->render('withdraw',array('model'=>$model,'exportUrl'=>$exportUrl));
        
    }
    
    /**
     * 会员信息报表
     */
    public function actionUser($export = false){
        $model = new UserSearchForm('userReportList');
    
        if(isset($_GET['UserSearchForm'])){
            $model->attributes = $_GET['UserSearchForm'];
        }
        
        if($export){
            $this->exportUser($model->exportUserReportList());
            Yii::app()->end();
        }
    
        $_GET['export'] = true;
        $exportUrl = $this->createAbsoluteUrl('report/user',$_GET);
        unset($_GET['export']);
        $this->render('user',array('model'=>$model,'exportUrl'=>$exportUrl));
    
    }
    
    /**
     * 提现报表导出
     * @param CDbDataReader $resultSet the reader object for fetching the query result
     */
    private function exportWithdraw($resultSet){
        Yii::$enableIncludePath = false;
        Yii::import('application.components.phpexcel.PHPExcel', true);
        $objPHPExcel = new PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        ->setLastModifiedBy("Maarten Balliauw")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");
        // Add some data
        $tk = 'A';
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '提现申请ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '用户ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '用户名');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '申请金额');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '手续费');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '提现状态');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '提现方式');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '账号');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '申请时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '最后更新时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '失败原因');
    
        $index = 2;
        foreach ($resultSet as $value) {
            $tk = 'A';
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$value['id']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$value['userId']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$value['userName']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$value['money']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$value['fee']);
            //状态
            $status = isset(WithdrawApply::$statusArray[$value['status']]) ? WithdrawApply::$statusArray[$value['status']] : '';
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$status);
            //提现方式
            $type = isset(WithdrawApply::$typeArray[$value['type']]) ? WithdrawApply::$typeArray[$value['type']] : '';
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$type);
            //账号
            $account = $value['type'] == 1 ? $value['account'] : (BankCard::$bankList[$value['bankType']].$value['bankName']."({$value['card']})");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, $account);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$value['ctime']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$value['utime']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$value['comment']);
            $index++;
        }
    
        // Redirect output to a client’s web browser (Excel2007)
        $name = 'withdraw-'.date('YmdHis');
        header("Pragma: public");header("Expires: 0");
        header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition: attachment;filename='.$name.'.xlsx');
        header("Content-Transfer-Encoding:binary");
    
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    
    }
    
    /**
     * 会员报表导出
     * @param CDbDataReader $resultSet the reader object for fetching the query result
     */
    private function exportUser($resultSet){
        Yii::$enableIncludePath = false;  
        Yii::import('application.components.phpexcel.PHPExcel', true);
        $objPHPExcel = new PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        ->setLastModifiedBy("Maarten Balliauw")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");
        // Add some data
        $tk = 'A';
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', 'ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '用户名');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '手机号码');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '手机实名认证');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '身份认证');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '户籍认证');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '学历认证');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '视频认证');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '可用金额');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '冻结金额');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '待还金额');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '待收金额');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '注册时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '最后更新时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '上次登录时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '信用等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '会员等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '用户状态');
        
        $index = 2;
        foreach ($resultSet as $value) {
            $tk = 'A';
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$value['id']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$value['userName']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$value['mobile']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.(isset(User::$authStatusArray[$value['mobileAuth']]) ? User::$authStatusArray[$value['mobileAuth']] : ''));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.(isset(User::$authStatusArray[$value['identityAuth']]) ? User::$authStatusArray[$value['identityAuth']] : ''));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.(isset(User::$authStatusArray[$value['residentAuth']]) ? User::$authStatusArray[$value['residentAuth']] : ''));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.(isset(User::$authStatusArray[$value['educationAuth']]) ? User::$authStatusArray[$value['educationAuth']] : ''));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.(isset(User::$authStatusArray[$value['videoAuth']]) ? User::$authStatusArray[$value['videoAuth']] : ''));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$value['cashMoney']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$value['blockMoney']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$value['refundMoney']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$value['incomingMoney']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$value['ctime']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$value['utime']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$value['lastLoginTime']);
            //信用等级
            $cred = isset(User::$creditRatingArray[$value['creditRating']]) ? User::$creditRatingArray[$value['creditRating']] : '';
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$cred);
            //会员等级
            $rank = isset(User::$rankArray[$value['rank']]) ? User::$rankArray[$value['rank']] : '';
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$rank);
            //状态
            $status = isset(User::$statusArray[$value['status']]) ? User::$statusArray[$value['status']] : '';
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$status);
            $index++;
        }
        
        // Redirect output to a client’s web browser (Excel2007)
        $name = 'user-'.date('YmdHis');
        header("Pragma: public");header("Expires: 0");
        header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition: attachment;filename='.$name.'.xlsx');
        header("Content-Transfer-Encoding:binary");
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
        
    }
    
    
    
}
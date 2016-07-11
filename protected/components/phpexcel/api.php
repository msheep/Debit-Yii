<?php
    	
    	
    	//define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
    	
    	/** Include PHPExcel */
    	require_once 'PHPExcel.php';
    	
		
    	function orderToXml($data) {
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
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '订购额');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '状态');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '订单号');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '订单类型');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '地区代码');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '车次');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '渠道');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '下单时间');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '是否是保险');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '是否需要发票');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '保险名称，保单类型');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '保险数量');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '保险单价');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '保险总金额');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '是否需要保险发票');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '出发站');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '到达站');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '车票时间');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '票面价格');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '数量');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '成人数量');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '儿童数量');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '首选坐席');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '可接受坐席');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '证件信息');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '用户id');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '收件人姓名');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '固定电话');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '用户登录帐号');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '用户备注');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '用户手机号码');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '保单收件人姓名');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '保单收件人电话');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '保单收件人地址');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '保单收件人邮编');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '配送地址');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '配送类型Id');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '物流价格');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '是否可周末进行配送');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '终端类型');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', 'H  有票 E  空 A  数字');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '配送地址');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '代理商ID');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '保险公司编号');
    	   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '产品编号');
    	   
    	   $index = 2;
    	   foreach($data as $k => $v) {
    			$tk = 'A';
    			foreach($v  as $kk=> $vv) {
    				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.$index, $vv);
    			}
    			$index++;
    		}
    		
    		// Redirect output to a client’s web browser (Excel2007)
    		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    		header('Content-Disposition: attachment;filename="order.xlsx"');
    		header('Cache-Control: max-age=0');
    		
    		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    		$objWriter->save('php://output');
    		exit;
    	}
    	function baoxianToXml($data,$action=NULL) {
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
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '订单号');
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '保单号');
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '单证号');
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '保单状态');
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '投保日期');
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '方案名称');
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '金额');
    		$index = 2;
    		foreach($data as $k => $v) {
    			$tk = 'A';
    			foreach($v  as $kk=> $vv) {
    				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.$index, $vv);
    			}
    			$index++;
    		}
    		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    		header('Content-Disposition: attachment;filename='.$action.'.xlsx');
    		header('Cache-Control: max-age=0');
    	
    		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    		$objWriter->save('php://output');
    		exit;
    	}
    	
    	function bxSumToXml($data,$sumData,$action=NULL) {
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
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '订单号');
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '保单号');
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '单证号');
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '保单状态');
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '投保日期');
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '方案名称');
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.'1', '金额');
    		$index = 2;
    		foreach($data as $k => $v) {
    			$tk = 'A';
    			foreach($v  as $kk=> $vv) {
    				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++.$index, $vv);
    			}
    			$index++;
    		}
    		$sumIndex=$index+1;
    		$sumTk='A';
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($sumTk++.$sumIndex, '正常件数');
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($sumTk++.$sumIndex, '正常保费');
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($sumTk++.$sumIndex, '退单件数');
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($sumTk++.$sumIndex, '退单保费');
    		
    		$sumIndex=$sumIndex+1;
    		$sumTk='A';
    		foreach($sumData as $k => $v) {
    				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($sumTk++.$sumIndex, $v);
    		}
    		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    		header('Content-Disposition: attachment;filename='.$action.'.xlsx');
    		header('Cache-Control: max-age=0');
    	
    		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    		$objWriter->save('php://output');
    		exit;
    	}
    	
    	
    //	spl_autoload_unregister(array('PHPExcel_Autoloader', 'Load'));
        //差额退款导出Excel
        function marginRefundToXml($data) {
            //组装$data
            $orderData = array();
            foreach ($data as $key => $value) {
                $orderNumber = orderIDResolve($value);
                if ($orderNumber) {
                    $sql = 'SELECT a.OrderNumber,a.ctime,
                            b.TrainNumber,b.FromStationName,b.TostationName,b.TicketTime,b.TicketPrice,b.AuditTicketCount,b.childTicketCount,
                            b.SeatName,b.UserMobile,b.UserName,b.passport,a.OrderPrice,a.realPrice,a.bankOrderNumber,c.refundFee
                            FROM v1_order_status a 
                            LEFT JOIN  v1_order b
                            ON a.OrderNumber=b.OrderNumber
                            LEFT JOIN v1_refund_mark c
                            ON a.OrderNumber=c.orderNumber
                            WHERE a.OrderNumber=:ordernumber
                            AND c.type=1';
                    $command =Yii::app()->db->createCommand($sql);
                    $command->bindParam(':ordernumber', $orderNumber);
                    $result = $command->queryAll();
                    if(!empty($result)){
                        $orderData[$key] = $result[0];
                    }
                }
            }
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
            $style_obj = new PHPExcel_Style();
            $style_array = array(
                'borders' => array(
                    'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                    'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                    'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'wrap' => true
                )
            );
            $style_obj->applyFromArray($style_array);
            $count = count($orderData)+1;
            $objPHPExcel->setActiveSheetIndex(0)->setSharedStyle($style_obj, "A1:Q$count");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '订单号');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '订单时间');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '车次');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '出发站');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '到达站');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '出发日期');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '支付单价');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '成人数');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '儿童数');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '坐席');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '联系人电话');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '联系人姓名');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '乘客信息');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '订单金额');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '购票价');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '招行支付订单号');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '差额退款金额');
           

            $index = 2;
            foreach ($orderData as $k => $v) {
                $tk = 'A';
                foreach ($v as $kk => $vv) {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$vv);
                }
                $index++;
            }

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="orderRefundMargin.xlsx"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        }
        
        //退票退款导出Excel
        function noTicketRefundToXml($data) {
            //组装$data
            $orderData = array();
            foreach ($data as $key => $value) {
                $orderNumber = orderIDResolve($value);
                if ($orderNumber) {
                    $sql = 'SELECT a.OrderNumber,a.ctime,
                            b.TrainNumber,b.FromStationName,b.TostationName,b.TicketTime,b.TicketPrice,b.AuditTicketCount,b.childTicketCount,
                            b.SeatName,b.UserMobile,b.UserName,b.passport,a.OrderPrice,c.refundFee
                            FROM v1_order_status a 
                            LEFT JOIN  v1_order b
                            ON a.OrderNumber=b.OrderNumber
                            LEFT JOIN v1_refund_mark c
                            ON a.OrderNumber=c.orderNumber
                            WHERE a.OrderNumber=:ordernumber
                            AND c.type=0';
                    $command =Yii::app()->db->createCommand($sql);
                    $command->bindParam(':ordernumber', $orderNumber);
                    $result = $command->queryAll();
                    if(!empty($result)){
                        $orderData[$key] = $result[0];
                    }
                }
            }
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
            $style_obj = new PHPExcel_Style();
            $style_array = array(
                'borders' => array(
                    'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                    'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                    'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'wrap' => true
                )
            );
            $style_obj->applyFromArray($style_array);
            $count = count($orderData)+1;
            $objPHPExcel->setActiveSheetIndex(0)->setSharedStyle($style_obj, "A1:O$count");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '订单号');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '订单时间');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '车次');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '出发站');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '到达站');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '出发日期');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '支付单价');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '成人数');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '儿童数');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '坐席');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '联系人电话');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '联系人姓名');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '乘客信息');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '订单金额');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '无票退款金额');
           

            $index = 2;
            foreach ($orderData as $k => $v) {
                $tk = 'A';
                foreach ($v as $kk => $vv) {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, ' '.$vv);
                }
                $index++;
            }

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="noTicketRefund.xlsx"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        }
        
        /*
         * 客服排班表导出为EXCEL
         * @author zhangyi 2013-08-19
         */
        function KefuSchedulToXml($beginDate,$endDate){
            //找出所有客服
            $sql = "SELECT t.adminID,t1.realname
                    FROM v1_admin_role t
                    LEFT JOIN v1_admin t1 on t1.id=t.adminID
                    WHERE t.roleIDs like '%16%' AND t1.id IS NOT NULL";
            $allKefu = Yii::app()->db->createCommand($sql)->queryAll();
            //开始PHP 模板
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
            $style_obj = new PHPExcel_Style();
            $style_array = array(
                'borders' => array(
                    'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                    'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                    'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'wrap' => true
                )
            );
            $style_obj->applyFromArray($style_array);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', '时间');
            $begin = strtotime($beginDate);
            $end = strtotime($endDate);
            //计数，采用样式
            $count = count($allKefu)+1;
            $rowCount = intval(($end - $begin)/24/3600)+1;
            $numArray = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
            if($rowCount <= 26){
                $num = $numArray[$rowCount].$count;
            }if($rowCount > 26){
                $num = 'A'.$numArray[$rowCount-26].$count;
            }
            //统一样式
            $objPHPExcel->setActiveSheetIndex(0)->setSharedStyle($style_obj, "A1:$num");
            //打印第一行
            for ($i = $begin; $i <= $end; $i+=(24 * 3600)) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . '1', date("Y-m-d", $i));
            }
            //打印以后行
            $index = 2;
            //找出所有客服排班
            $sql = "SELECT kefu_id,schedul_date,schedul_type
                    FROM v1_kefu_ticket_schedul
                    WHERE schedul_date>=:beginDate and schedul_date<=:endDate
                    ORDER BY kefu_id";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(':beginDate', $beginDate, PDO::PARAM_STR);
            $command->bindParam(':endDate', $endDate, PDO::PARAM_STR);
            $schedul = $command->queryAll();
            //重新组装数据
            $kefuSchedul = array();
            foreach ($schedul as $schedulVal){
                $kefuSchedul[$schedulVal['kefu_id']][$schedulVal['schedul_date']] = $schedulVal;
            }
            //获取所有的班次名称
            $allSchedulType = array('0'=>'休');
            $sql = "SELECT id,schedulType
                        FROM v1_kefu_ticket_type";
            $allType = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($allType as $value) {
                $allSchedulType[$value['id']] = $value['schedulType'];
            }
            //打印剩余的列
            foreach ($allKefu as $k => $v) {
                $tk = 'A';
                $realname = $v['realname'];
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, $realname);
                $begin = strtotime($beginDate);
                $end = strtotime($endDate);
                for ($i = $begin; $i <= $end; $i+=(24 * 3600)) {
                    if(isset($kefuSchedul[$v['adminID']][date("Y-m-d", $i)]['schedul_type'])){
                        if(!empty($allSchedulType[$kefuSchedul[$v['adminID']][date("Y-m-d", $i)]['schedul_type']])){
                            $type = $allSchedulType[$kefuSchedul[$v['adminID']][date("Y-m-d", $i)]['schedul_type']];
                        }  else {
                            $type = '班号'.$kefuSchedul[$v['adminID']][date("Y-m-d", $i)]['schedul_type'];
                        }
                    }else{
                        $type = '无';
                    }
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tk++ . $index, $type);
                }
                $index++;
            }

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="schedul.xlsx"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        }
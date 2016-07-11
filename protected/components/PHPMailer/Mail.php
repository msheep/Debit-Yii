<?php 
/*
 * $param array $sendInfo = array(
 * 					'CharSet' => 'utf-8',		//可传，设置编码，默认utf-8
 * 					'Port' => '25',				//可传，设置SMTP主机端口号，默认25
 * 					'Host' => '25',				//可传，设置SMTP主机服务器，默认25
 * 					'Username' => '******@qq.com',		//可传，设置 SMTP服务器用户名（填写完整的Email地址）
 * 					'Password' => '******',				//可传，设置 SMTP服务器用户名
 * 					'From' => '******@qq.com',			//可传，设置发件人地址，默认用户名地址（填写完整的Email地址）
 * 					'FromName' => 'test',		//可传，设置发件人名称，默认为发件人地址@前半部分，或者用户名@前半部分
 * 					'To' => array(
 * 							[0]=>array('email'=>'onemail@qq.com','name'=>'onename'),
 * 							[1]=>array('email'=>'twomail@qq.com','name'=>'twoname'),
 * 							[2]=>array('email'=>'twomail@qq.com','name'=>'twoname'),
 * 						)					//可传，设置收件人邮箱和地址，二维数组形式，email必填，name可选
 * 					'AddBCC' => array(
 * 							[0]=>array('email'=>'onemail@qq.com','name'=>'onename'),
 * 							[1]=>array('email'=>'twomail@qq.com','name'=>'twoname'),
 * 							[2]=>array('email'=>'twomail@qq.com','name'=>'twoname'),
 * 						)					//可传，设置密送邮箱和地址，二维数组形式，email必填，name可选
 * 					'AddCC' => array(
 * 							[0]=>array('email'=>'onemail@qq.com','name'=>'onename'),
 * 							[1]=>array('email'=>'twomail@qq.com','name'=>'twoname'),
 * 							[2]=>array('email'=>'twomail@qq.com','name'=>'twoname'),
 * 						)					//可传，设置抄送邮箱和地址，二维数组形式，email必填，name可选
 * 					'AddAttachment' => array(
 * 									[0]=>array('attach'=>'D:/www/theone/protected/components/PHPMailer/360log.png','name'=>'360log.png'),
 * 								)		//可传，设置附件和附件名称，二维数组形式，attach必填，name可选
 * 					'Subject' => 'Test Mail',	//可传，设置邮件主题
 * 					'Body' => 'This is a test mail!',	//可传，设置邮件的内容
 * 					'AddReplyTo' => 'xxx@sina.com','xxxx',	//可传，设置回复地址
 * 					'typeInfo' => array(),	//可传，设置模板参数
 * 			)
 * $param string $type   设置邮件模板('cpl':十分便民火车票出票量日报表 )
 * return array $mailResult array('status'=>'success/fail','msg'=>'')
 * 调用方法：
 *  //模板版本
 *  Yii::import ('application.components.PHPMailer.Mail',true);
 *	$cpl['typeInfo']['totalOrderNum'] = 1 ;
 *	$cpl['typeInfo']['successOrderNum'] = 1 ;
 *	$cpl['typeInfo']['successRefundFee'] = 1 ;
 *	$cpl['typeInfo']['successTicketCount'] = 1 ;
 *	$cpl['typeInfo']['totalOrderPrice'] = 1 ;
 *	$cpl['typeInfo']['successRealPrice'] = 1 ;
 *	$cpl['typeInfo']['successTotalAmount'] = 1 ;
 *	$cpl['typeInfo']['successOrderPrice'] = 1 ;
 *	$cpl['typeInfo']['backTicketCount'] = 1 ;
 *	$cpl['typeInfo']['backOrderPrice'] = 1 ;
 *	$cpl['typeInfo']['backRefundFee'] = 1 ;
 *	$cpl['typeInfo']['backInsureFee'] = 1 ;
 *	$cpl['typeInfo']['backFee'] = 1 ;
 *	$cpl['To'][0]['email'] = 'yangjing@meiti.com' ;
 *	Mail::sendMail($cpl,'cpl');
 *	//非模板版本
 *  Yii::import ('application.components.PHPMailer.Mail',true);
 *	$cpl['Subject'] = 'Test' ;
 *	$cpl['Body'] = 'This Is An Test Mail' ;
 *	$cpl['To'][0]['email'] = 'yangjing@meiti.com' ;
 *	$cpl['To'][1]['email'] = 'ningyangjing@126.com';
 *	Mail::sendMail($cpl); 
 */

require_once 'class.phpmailer.php';
class Mail{
	 public static function sendMail($sendInfo=array(),$type=''){ 
	 	if($type == ''){
	 		if(!isset($sendInfo['Subject']) || !isset($sendInfo['Body'])){
	 			$mailResult['status'] = 'fail';
				$mailResult['msg'] = "您未使用模板发送，请填写邮件主题和内容再发送！";
	 			return $mailResult;
	 			exit();
	 		}
	 	}else{
	 		if(!isset($sendInfo['typeInfo']) || empty($sendInfo['typeInfo'])){
	 			$mailResult['status'] = 'fail';
				$mailResult['msg'] = "您使用模板发送邮件，请输入参数！";
	 			return $mailResult;
	 			exit();
	 		}else{
	 			$result = Mail::getSubjectAndBody($type,$sendInfo['typeInfo']);
	 			if(!empty($result)){
	 				$sendInfo['Subject'] = $result['Subject'];
	 				$sendInfo['Body'] = $result['Body'];
	 			}else{
	 				$mailResult['status'] = 'fail';
					$mailResult['msg'] = "调用模板失败，请输入完整的参数！";
		 			return $mailResult;
	 				exit();
	 			}
	 		}
	 	}
	 	//实例化
		$mail = new PHPMailer();
		//赋予变量默认值
		if(!isset($sendInfo['Username'])){
			$sendInfo['Username'] = 'noreply@qumaiya.com';
		}
		$fromName = array();
		$fromName = explode("@",$sendInfo['Username']);
	 	if(!isset($sendInfo['Password'])){
			$sendInfo['Password'] = 'no2013';
		}
		if(!isset($sendInfo['CharSet'])){
			$sendInfo['CharSet'] = 'utf-8';
		}
		if(!isset($sendInfo['Port'])){
			$sendInfo['Port'] = 25;
		}
	 	if(!isset($sendInfo['Host'])){
			$host = trim($fromName[1]);
			if($host == 'meiti.com'){
				$host = 'qq.com';
			}else if($host == 'qumaiya.com'){
				$host = 'exmail.qq.com';
			}
			$sendInfo['Host'] = "smtp.".$host;
		}
		if(!isset($sendInfo['From'])){
			$sendInfo['From'] = $sendInfo['Username'];
		}
		if(!isset($sendInfo['FromName'])){
			$fromMail = array();
			$fromMail = explode("@",$sendInfo['From']);
			$sendInfo['FromName'] = $fromMail[0];
			
		}
		
		//是否通过SMTP协议发送
		$mail -> ISSMTP();
		//SMTP服务器是否需要验证(验证为true 不验证为false)
		$mail -> SMTPAuth = true;
		//设置用户名
		$mail -> Username = $sendInfo['Username'];
		//设置密码
		$mail -> Password = $sendInfo['Password'];
		//设置编码
		$mail -> CharSet = $sendInfo['CharSet'];
		//设置端口
		$mail -> Port = $sendInfo['Port'];
		//设置主机服务器
		$mail -> Host = $sendInfo['Host'];	
		//发件人地址
		$mail -> From = $sendInfo['From'];
		//发件人
		$mail -> FromName = $sendInfo['Username'];
		//是否以HTML格式发送
		$mail -> IsHTML(true);
		//主题
		$mail -> Subject = $sendInfo['Subject'];
		//内容
		$mail -> Body = $sendInfo['Body'];
	 	//添加附件
		if(isset($sendInfo['AddAttachment'])){
			if(is_array($sendInfo['AddAttachment'])){
				foreach($sendInfo['AddAttachment'] as $attach){
					if(isset($attach['name'])){
						$mail -> AddAttachment($attach['attach'],$attach['name']);
					}else{
						$mail -> AddAttachment($attach['attach']);
					}
				}
			}
		}
		 //调用回复方法,添加回复对象
		if(isset($sendInfo['AddReplyTo'])){
			$mail -> AddReplyTo($sendInfo['AddReplyTo']); 
		}
		//添加收件人，支持群发
		$success = true;
		if(isset($sendInfo['To'])){
			foreach($sendInfo['To'] as $addr){
				if(isset($addr['name'])){
					$mail -> AddAddress($addr['email'],$addr['name']);
				}else{
					$email = array();
					$email = explode("@",$addr['email']);
					$mail -> AddAddress($addr['email'],$email[0]);
				}
			}
		}
		//密送
		if(isset($sendInfo['AddBCC'])){
			foreach($sendInfo['AddBCC'] as $addrB){
				if(isset($addrB['name'])){
					$mail -> AddBCC($addrB['email'],$addrB['name']);
				}else{
					$email = array();
					$email = explode("@",$addrB['email']);
					$mail -> AddBCC($addrB['email'],$email[0]);
				}
			}
		}
		//抄送
		if(isset($sendInfo['AddCC'])){ 
			foreach($sendInfo['AddCC'] as $addrC){
				if(isset($addrC['name'])){
					$mail -> AddCC($addrC['email'],$addrC['name']);
				}else{
					$email = array();
					$email = explode("@",$addrC['email']);
					$mail -> AddCC($addrC['email'],$email[0]);
				}
			}

		}
		$error = array();
		if (!$mail->Send()){
			$success = false;
			$error[] = "发送失败，原因：".$mail->ErrorInfo;
        }
		$mail -> ClearAddresses();
		if(!isset($sendInfo['To']) && !isset($sendInfo['AddCC']) && !isset($sendInfo['AddBCC']) ){ 
			$mail -> AddAddress('tanweiwei@meiti.com','tanweiwei');
			if (!$mail->Send()){
				$success = false;
				$error[] = "tanweiwei@meiti.com发送失败，原因：".$mail->ErrorInfo;
            }
		}
		$mailResult = array();
		if($success){
			$mailResult['status'] = 'success';
			$mailResult['msg'] = '发送邮件成功！';
		}else{
			$mailResult['status'] = 'fail';
			if(!empty($error)){
				$mailResult['msg'] = $error;
			}else{
				$mailResult['msg'] = '发送邮件失败！';
			}
			
		}
		return $mailResult;

	}
	
	//配置不同模板的邮件
	public static function getSubjectAndBody($type,$content){
		if(!empty($content) && isset($type)){
			switch($type){
				case 'cpl':
					if(!isset($content['totalOrderNum']) || !isset($content['successOrderNum']) || !isset($content['successRefundFee'])
					    || !isset($content['successTicketCount']) || !isset($content['successRealPrice']) || !isset($content['successTotalAmount']) 
					    || !isset($content['successOrderPrice']) || !isset($content['backTicketCount']) || !isset($content['backOrderPrice']) 
					    || !isset($content['backRefundFee']) || !isset($content['backInsureFee']) || !isset($content['backFee'])){
							return array();
							exit();
					}
					if(!isset($content['subject'])){
						$content['subject'] = '十分便民火车票出票量日报表';
					}
					if(!isset($content['date'])){
						$content['date'] = date('Y-m-d',time());
					}
					if(!isset($content['connectEmail'])){
						$content['connectEmail'] = 'tanweiwei@meiti.com';
					}
					$weekArray = array("日","一","二","三","四","五","六");
					$result['Subject'] = $content['subject'];
					$body = '';
					$body .= '<html><div style="width:1000px;line-height:35px;text-align:center;font-family:Arial,宋体;font-size:12px;">';
					$body .= '<div >';
					$body .= '<div style="margin-bottom:10px"><font style="text-align:center;color:black;font-size:23px;font-weight:bold">'.$result['Subject'].'</font>';
					$body .= '<span style="font-size:12px;margin-left:20px;color:rgb(51, 51, 51)">时间：'.$content['date'].'&nbsp;&nbsp;&nbsp;星期'.$weekArray[date('w',strtotime($content['date']))].'</span></div>';
					$body .= '<div><table border="0" cellpadding="0" cellspacing="0" width="1000" style="text-align:center;font-size:12px"><tbody>';
					$body .= '<tr style="margin:0px;height:35px" bgcolor="#F2F2F2">';
					$body .= '<td style="width:200px;"><strong>日出票统计</strong></td>';
					$body .= '<td style="width:100px;">订单总量</td>';
					$body .= '<td style="width:100px;">成功订单总量</td>';
					$body .= '<td style="width:100px;">出票量</td>';
					$body .= '<td style="width:100px;">购票总金额</td>';
					$body .= '<td style="width:100px;">保险总金额</td>';
					$body .= '<td style="width:100px;">差额退款</td>';
					$body .= '<td style="width:100px;">订单总金额</td>';
					$body .= '</tr>';
					$body .= '<tr style="margin:0px;width:200px;height:35px" bgcolor="#FAFAFA">';
					$body .= '<td></td>';
					$body .= '<td>'.$content['totalOrderNum'].'</td>';
					$body .= '<td>'.$content['successOrderNum'].'</td>';
					$body .= '<td>'.$content['successTicketCount'].'</td>';
					$body .= '<td>'.$content['successRealPrice'].'</td>';
					$body .= '<td>'.$content['successTotalAmount'].'</td>';
					$body .= '<td>'.$content['successRefundFee'].'</td>';
					$body .= '<td>'.$content['successOrderPrice'].'</td>';
					$body .= '</tr>';
					$body .= '<tr style="margin:0px;width:200px;height:35px" bgcolor="#F2F2F2">';
					$body .= '<td><strong>日退票统计</strong></td>';
					$body .= '<td colspan="2"></td>';
					$body .= '<td>退票量</td>';
					$body .= '<td>票款总金额</td>';
					$body .= '<td>退票总金额</td>';
					$body .= '<td>退保险总金额</td>';
					$body .= '<td>退款合计</td>';
					$body .= '</tr>';
					$body .= '<tr style="margin:0px;width:200px;height:35px" bgcolor="#FAFAFA">';
					$body .= '<td colspan="3"></td>';
					$body .= '<td>'.$content['backTicketCount'].'</td>';
					$body .= '<td>'.$content['backOrderPrice'].'</td>';
					$body .= '<td>'.$content['backRefundFee'].'</td>';
					$body .= '<td>'.$content['backInsureFee'].'</td>';
					$body .= '<td>'.$content['backFee'].'</td>';
					$body .= '</tr>';
					$body .= '</tbody></table></div>';
					$body .= '<p style="text-align:left;color:rgb(51, 51, 51)">十分便民BOSS报表中心自动发送，任何问题请联系<a href="mailto:'.$content['connectEmail'].'">'.$content['connectEmail'].'</a></p>';
					$body .= '</div>';
					$body .= '</div></html>';
					$result['Body'] = $body;
					return $result;
					break;
				case 'sms':
					if(!isset($content['connectEmail'])){
						$content['connectEmail'] = 'tanweiwei@meiti.com';
					}
					if(!isset($content['success'])){
						$content['success'] = '0';
					}
					if(!isset($content['subject'])){
						if($content['success'] == '0'){
							$content['subject'] = '注意：短信发送失败！';
						}else{
							$content['subject'] = '短信发送成功！';
						}
					}
					$result['Subject'] = $content['subject'];
					$body = '';
					$body .= '<html><div style="font-family:Arial,宋体;font-size:12px;">';
					$body .= '<div>';
					if($content['success']){
						$body .= '<p>短信发送成功，共发送'.$content['totalNum'].'个号码'.$content['num'].'条短信，短信余额为'.$content['money'].'！发送内容如下：</p>';
					}else{
						$body .= '<p>由于<span style="color:red">'.$content['reason'].'</span>原因，'.$content['totalNum'].'个号码'.$content['num'].'条短信发送失败！发送内容如下：</p>';
					}
					$body .= '<p>'.$content['cont'].'</p>';
					$body .= '<p>发送手机号码如下：</p>';
					if(is_array($content['mobile'])){
						foreach($content['mobile'] as $mobile){
							$body .= '<p>'.$mobile.'</p>';
						}	
					}else{
						$body .= '<p>'.$content['mobile'].'</p>';
					}
					
					$body .= '<p style="color:rgb(51, 51, 51);text-align:right">十分便民BOSS报表中心自动发送，任何问题请联系<a href="mailto:'.$content['connectEmail'].'">'.$content['connectEmail'].'</a></p>';
					$body .= '</div>';
					$body .= '</div></html>';
					$result['Body'] = $body;
					return $result;
					break;
			}
		}else{
			return array();
		}
	}
	
}
?>
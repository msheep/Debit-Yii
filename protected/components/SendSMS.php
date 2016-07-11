<?php
/**
 * 发送短信独立模块 API 2.0  支持模板的发送和群发
 * @param array $param = array(
 * 							'localSend' => 'true/false',			//默认不支持本地站点调用该接口
 * 							'content' => 'this is the text',		//不调用模板发送，必传，短信内容70字以内按一条计算，超出将按67个字符每条计算
 * 							'limit' => '20',						//限制每日发送短信最高条数，默认20
 * 							'mobile' => array('110','112'),			//一个号码可以直接字符串，多个数组形式
 * 							'sign' => '【去买呀 www.qumaiya.com】',	//短信的签名，默认为【去买呀 www.qumaiya.com】
 * 							'typeInfo' => array(),					//模板中的传值，数组形式
 * 						 )
 * @param string $type 模板名称，默认没有模板，发送内容自动加签名
 * @return array $result = array(
 * 								'status' = > 'success/fail',											//发送成功或失败
 * 								'error' = > 111,														//发送结果代码
 * 								'msg' = > '发送手机号码错误或者超出上限，均不符合要求',					//发送结果详情
 * 								'number' = > array(['limit']=>,['wrong']=>,['fail']=>,['success']=>),												//发送的所有手机号码的发送情况
 * 							)
 * 调用方法：
 * 		//模板版本
 * 		$param['typeInfo']['Code'] = 'E1234';
 * 		$param['mobile'] = array('15996253401');
 * 		$res = SendSMS::SendText($param,'yzm');	
 * 		//非模板版本
 * 		$param['content'] = '111';
 * 		$param['mobile'] = array('15996253401');
 * 		$res = SendSMS::SendText($param);	
 */
Yii::import ('application.components.PHPMailer.Mail',true);
Class SendSMS{
	const SMS_URL = 'http://118.244.212.86:89/';
	const SMS_NAME = 'shifenbianmin';
	const SMS_PWD = 'qumaiya520';
	const SMS_MAX = 50;			//批量发送最大人数
	const ENCODE = 'UTF8';		//短信编码
	static $error = array(
				//短信接口返回代码
				//新版
				'101' => '用户名或密码不能为空',
				'201' => '接收手机号码或内容不能为空',
				'301' => '用户名或密码输入错误',
				'401' => '余额不足',
				'501' => '该账号被禁用，请与上级代理商联系',
				'601' => '短信内容未加签名，请加签名，如【签名】，中括号内不能为空',
				//旧版
				'-1' => '接收手机号码或内容不能为空',
				'-2' => '通道不存在',
				'-4' => '接收号码无效',
				'-7' => '当前账户余额不足',
				'-8' => '网关发送短信时出现异常',
				'-11' => '密码错误',
				'-12' => '账户锁定',
				'-14' => '验证用户时执行异常',
				//自定义代码
				'111' => '短信发送成功',
				'222' => '本地站点不支持发送短信',
				'701' => '您未选择模板发送短信，请填写短信内容',
				'702' => '您选择了模板发送短信，请输入模板参数',
				'703' => '模板参数传入不全，或者模板不存在',
				'704' => '发送手机号码错误或者超出上限，均不符合要求',
				'705' => '发送号码不能为空',
				'706' => '发送超过上限',
				'707' => '发送号码错误',
			);
	static $template = array(
				'common'=>'无模板',
		        'zc'=>'注册',
    		    'ydxx'=>'预订成功,提醒支付',
    		    'mmyzm'=>'密码重置验证码',
    		    'zczm'=>'注册验证码',
    		    'yzm'=>'验证码',
		    	'newpwd'=>'新密码',
    		    'ydcg'=>'订购成功,提醒取票',
    		    'kydcg'=>'客户端预订成功',
    		    'cetk'=>'差额退款',
	            'zztk'=>'正在退票',
    		    'wptk'=>'预订失败',
		    	'zftk'=>'预订失败返款',
    		    'bxtk'=>'保险退款',
				'fcgh'=>'发车关怀',
                'txcg'=>'提现成功',
		    );
	public static function SendText($param,$type='common'){
 		//默认本地站点不调用短信接口
		if(!isset($param['localSend'])){
			$param['localSend'] = false;
		}
		if(defined('LOCALHOST') && !$param['localSend']){
			return SendSMS::getReturn('success',222);
		} 
		
		//判断是否调用模板
		if($type == 'common'){
			if(!isset($param['content']) || trim($param['content']) == ''){
	 			return SendSMS::getReturn('fail',701);
	 			exit();
			}
		}else{
			if(!isset($param['typeInfo'])){
				return SendSMS::getReturn('fail',702);
	 			exit();
			}else{
				$result = SendSMS::getAllTypeSMSCont($type, $param['typeInfo']);
				if($result === 0){
					return SendSMS::getReturn('fail',703);
		 			exit();
				}else{
					$param['content'] = $result['content'];
					if(!isset($param['limit']) && isset($result['limit'])){
						$param['limit'] = $result['limit'];
					}
				}
			}
		}
		
		//每日每个号码最多发送短信条数，默认20条
		if(!isset($param['limit'])){
			$param['limit'] = 20;
		}
		$otherInfo = array();
	    if(!isset($param['typeInfo']['OrderNumber'])){
	         if(isset($param['typeInfo']['ENumber'])){
	              $ENumbers = explode('(',$param['typeInfo']['ENumber']);
		          $orderInfoO = OrderTicket::model()->find("ElectronicOrderNumber=:ENumber",array(':ENumber'=>$ENumbers[0]));
		          if($orderInfoO){
		              $otherInfo['orderNumber'] = $orderInfoO->orderNumber;
		          }
		     }
		}else{
		      $otherInfo['orderNumber'] = $param['typeInfo']['OrderNumber'];
		   
		}
		
	    if(!isset($param['typeInfo']['ENumber'])){
	        if(isset($param['typeInfo']['OrderNumber'])){
		          $orderInfoE = OrderTicket::model()->find("OrderNumber=".trim($param['typeInfo']['OrderNumber']));
		          if($orderInfoE){
		              $ENumber = explode('(',$orderInfoE->ElectronicOrderNumber);
		              $otherInfo['ENumber'] = $ENumber[0];
		          }
		     }
		}else{
		     $ENumber = explode('(',$param['typeInfo']['ENumber']);
		     $otherInfo['ENumber'] = $ENumber[0];
		    
		}
		
		$allNumbers = array();
		//验证手机号码，分为单个和批量的
		$wrongMobile = array();
		$sendMobile = array();
		$limitMobile = array();
		$insertValue = array();
		$num = 1;
		$day = intval(date('Ymd'));
		if(isset($param['mobile'])){
			if(is_array($param['mobile'])){
				$param['mobile'] = array_unique($param['mobile']);
				$num = count($param['mobile']);
				foreach($param['mobile'] as $k=>$mobile){
					//过滤不正确的手机号码
					if(SendSMS::checkMobile($mobile) < 1){
						$wrongMobile[] = $mobile;
						$insertValue[] = "(".$mobile.",'".mysql_escape_string($param['content'])."',' ',707,'".$type."','".json_encode($otherInfo)."','".date('Y-m-d H:i:s',time())."')";	
						unset($param['mobile'][$k]);
					}else{
						//过滤超出限制的手机号码
					    if(!defined('LOCALHOST')){
    						$key = sprintf("%u", crc32($day.$mobile.$type));
    	    				$value = Yii::app()->countCache->get($key); 
    	    				if(!empty($value)){ 
    		    				if($value < $param['limit']){
    		    					$sendMobile[] = $mobile;
    			    			}else{
    			    				$limitMobile[] = $mobile;
    			    				$insertValue[] = "(".$mobile.",'".mysql_escape_string($param['content'])."',' ',706,'".$type."','".json_encode($otherInfo)."','".date('Y-m-d H:i:s',time())."')";	
    			    				unset($param['mobile'][$k]);
    			    			}
    	    				}else{
    	    				   $sendMobile[] = $mobile; 
    	    				}
						}else{
		    				$sendMobile[] = $mobile;
		    			}
					}
				}
			}else{
				//过滤不正确的手机号码
				if(SendSMS::checkMobile($param['mobile']) < 1){
					$wrongMobile[] = $param['mobile'];
					$insertValue[] = "(".$param['mobile'].",'".mysql_escape_string($param['content'])."',' ',707,'".$type."','".json_encode($otherInfo)."','".date('Y-m-d H:i:s',time())."')";	
				}else{
					//过滤超出限制的手机号码
				    if(!defined('LOCALHOST')){
    					$key = sprintf("%u", crc32($day.$param['mobile'].$type));
    					$value = Yii::app()->countCache->get($key);
    					if(!empty($value)){
    	    				if($value <= $param['limit']){
    		    				$sendMobile[] = $param['mobile'];
    		    			}else{
    		    				$limitMobile[] = $param['mobile'];
    		    				$insertValue[] = "(".$param['mobile'].",'".mysql_escape_string($param['content'])."',' ',706,'".$type."','".json_encode($otherInfo)."','".date('Y-m-d H:i:s',time())."')";		
    		    			}
    					}else{
    	    				$sendMobile[] = $param['mobile'];
    	    			}
				    }else{
	    				$sendMobile[] = $param['mobile'];
	    			}
				}
			}
			$allNumbers['wrong'] = $wrongMobile;
			$allNumbers['limit'] = $limitMobile;
			if(!empty($wrongMobile)){
				$wrongMobiles = implode(',',$wrongMobile);
				@YiiLog(array('mobile'=>$wrongMobiles, 'content'=>$param['content'], 'WrongNumber'=>true), 'SendSMS Wrong Phone');
				if(empty($limitMobile) && empty($sendMobile)){
					self::InsertSendLog($insertValue);
					return SendSMS::getReturn('fail',707,'',array_merge($wrongMobile,$limitMobile));
		 			exit();
				}
			}
			if(!empty($limitMobile)){
				$limitMobiles = implode(',',$limitMobile);
				@YiiLog(array('mobile'=>$limitMobiles, 'content'=>$param['content'], 'Spite'=>true,'Times'=>$value), 'SendSMS Too Much');
				SendSMS::sendMail(706,'短信发送超过上限'.$param['limit'].'条（已经发送了'.$value.'条）',$param['content'],$limitMobile);
				if(empty($wrongMobile) && empty($sendMobile)){
					self::InsertSendLog($insertValue);
					return SendSMS::getReturn('fail',706,'',array_merge($wrongMobile,$limitMobile));
		 			exit();
				}
			}
			if(empty($sendMobile)){
				self::InsertSendLog($insertValue);
				return SendSMS::getReturn('fail',704,'',array_merge($wrongMobile,$limitMobile));
		 		exit();
			}
		}else{
			return SendSMS::getReturn('fail', 705);
 			exit();
		}
		
		//整理出发送的手机号码，目前一次最多发送50个
		if(!empty($sendMobile)){
			$allGroup = array();
			$allGroup = array_chunk($sendMobile,self::SMS_MAX,true);
		}else{
			return SendSMS::getReturn('fail', 704 );
		 	exit();
		}
		
		//发送短信内容带有签名，且短信为url编码
		if(!isset($param['sign'])){
			$param['sign'] = '【去买呀专业票务平台】';
		}
		$content = $param['content'].$param['sign'];
		if(self::ENCODE == 'GBK'){
			$urlContent = urlencode(iconv('UTF-8', 'GBK', $content));
		}else{
			$urlContent = urlencode($content);
		}

		$sendSuccess = array();
		$sendFail = array();
		foreach($allGroup as $group){
			$mobiles = '';
			$mobiles = implode(',',$group);
			$file_contents = 'NoContents'; 
            if(mb_strlen($content,'UTF8')<66){
                $url='http://gateway.woxp.cn:6630/utf8/web_api/?x_eid=11401&x_uid=newqumaiya&x_pwd_md5=3d003426c8984ca4ed43231d73ef9f90&x_ac=10&x_target_no='.$mobiles.'&x_memo='.$urlContent.'&x_gate_id=300';
            }else{ 
                $url='http://gateway.woxp.cn:6630/utf8/web_api/?x_eid=11401&x_uid=newqumaiya&x_pwd_md5=3d003426c8984ca4ed43231d73ef9f90&x_ac=12&x_target_no='.$mobiles.'&x_memo='.$urlContent.'&x_gate_id=300';	
            }			
			//$url = self::SMS_URL.'sendsms.asp?name='.self::SMS_NAME.'&password='.self::SMS_PWD.'&mobile='.$mobiles.'&message='.$urlContent;
			if(function_exists('file_get_contents')){
				$ctx = stream_context_create(array('http' => array('timeout' => 5)));
				$file_contents = file_get_contents($url,0,$ctx);
			}else{
				$ch = curl_init();
				$timeout = 5;
				curl_setopt ($ch, CURLOPT_URL, $url);
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				$file_contents = curl_exec($ch);
				curl_close($ch);
			}
			//新的短信
//			$sendResult = '';
//			$sendResult = explode(',',$file_contents);
//			if($sendResult[0] == 'succ'){
//				//发送成功设置一次缓存
//				foreach($group as $number){
//					$key = sprintf("%u", crc32($day.$number));
//					$value = Yii::app()->countCache->incr($key);
//					$sendSuccess[] = $number;
//					$insertValue[] = "(".$number.",'".mysql_escape_string($param['content'])."',' ',111,'".$type."','".json_encode($otherInfo)."','".date('Y-m-d H:i:s',time())."')";		
//				}
//				$leftMoney = $sendResult[1];
//			}else if($sendResult[0] == 'err'){
//				foreach($group as $number){
//					$sendFail[] = $number;
//					$insertValue[] = "(".$number.",'".mysql_escape_string($param['content'])."',' ','".$file_contents."','".$type."','".json_encode($otherInfo)."','".date('Y-m-d H:i:s',time())."')";	
//				}
//				$errorCode[] = $sendResult[1];
//			}
			//旧的短信调用
			if($file_contents > 0){
				//发送成功设置一次缓存
				foreach($group as $number){
				    if(!defined('LOCALHOST')){
    				    $key = sprintf("%u", crc32($day.$number.$type));
        				$value = Yii::app()->countCache->get($key);
        				if($value){
        				    $value = Yii::app()->countCache->incr($key);
        				}else{
        				    $value = Yii::app()->countCache->set($key, 1, 2*24*3600);
        				}
				    }
					$sendSuccess[] = $number;
					$insertValue[] = "(".$number.",'".mysql_escape_string($param['content'])."',' ',111,'".$type."','".json_encode($otherInfo)."','".date('Y-m-d H:i:s',time())."')";		
				}
				$allSendNumber = $file_contents;
			}else{
				foreach($group as $number){
				    if(!defined('LOCALHOST')){
    				    $key = sprintf("%u", crc32($day.$number.$type));
        				$value = Yii::app()->countCache->get($key);
        				if($value){
        				    $value = Yii::app()->countCache->incr($key);
        				}else{
        				    $value = Yii::app()->countCache->set($key, 1, 2*24*3600);
        				}
				    }
					$sendFail[] = $number;
					$insertValue[] = "(".$number.",'".mysql_escape_string($param['content'])."',' ','".$file_contents."','".$type."','".json_encode($otherInfo)."','".date('Y-m-d H:i:s',time())."')";	
				}
				$errorCode[] = $file_contents;
			}
		}
		
		//统计发送条数
		$allSendNum = 0;
		if(mb_strlen($content,self::ENCODE) < 70){
			$eachNum = 1;
		}else{
			$eachNum = ceil((mb_strlen($content,self::ENCODE) - 70)/67); 
			$eachNum += 1;
		}
		$allSendNum = $eachNum * count($sendSuccess);
		
		//记录到数据库

		self::InsertSendLog($insertValue);
		$allNumbers['fail'] = $sendFail;
		$allNumbers['success'] = $sendSuccess;
		if(!empty($sendFail)){
			$sendFailNumber = implode(',',$sendFail);
			$errorCode = array_unique($errorCode);
			foreach($errorCode as $ec){
			    if(isset(self::$error[$ec])){
			        $error[] = self::$error[$ec];
			    }else{
			        $error[] = $ec;
			    }
				
			}
			$errors = implode('、',$error);
			$errorCodes = implode('、',$errorCode);
			Yii::log($sendFailNumber.':'.$url.'-'.var_export($file_contents, true), CLogger::LEVEL_WARNING, 'SendSMS Gateway Error');
			SendSMS::sendMail($errorCodes,$errors,$param['content'],$sendFail);
			return SendSMS::getReturn('fail',$errorCodes,$errors,$allNumbers);
		}else{
			$sendSuccessNumber = implode(',',$sendSuccess);
			@YiiLog(array('mobile'=>$sendSuccessNumber,'result'=>$file_contents, 'content'=>$param['content'].' 【去买呀 www.qumaiya.com 】 '), '.SendSMS');
//			return SendSMS::getReturn('success',111,self::$error[111].'，已经发送'.count($sendSuccess).'个号码'.$allSendNum.'条短信，余额为：'.$leftMoney,$allNumbers);
			return SendSMS::getReturn('success',111,self::$error[111].'，已经发送'.count($sendSuccess).'个号码'.$allSendNum.'条短信',$allNumbers);
		}
	}
	
	//设置短信模板
	public static function getAllTypeSMSCont($type,$param){
		if(!empty($param) && isset($type)){
			switch($type){
			    //注册发送短信
				case 'zc':
					if(!isset($param['mobile']) || !isset($param['pwd'])){
						return 0;	
					}
					$result['content'] = '欢迎成为去买呀会员！用户名：'.$param['mobile'].'，初始密码：'.$param['pwd'].'，请及时登陆网站并修改密码，祝您一路顺风！';
					$result['limit'] = 10;
					return $result;
					break;	
				//预订成功短信，提醒支付				
				case 'ydxx':
					if(!isset($param['UserName']) || !isset($param['TrainNumber']) || !isset($param['FromStationName']) 
						|| !isset($param['ToStationName']) || !isset($param['SeatName']) || !isset($param['TicketTime']) ){
						return 0;	
					}
					$param['TicketTime'] = date('Y年m月d日',strtotime($param['TicketTime']));
					$result['content'] = '尊敬的'.$param['UserName'].'先生/女士，您已成功预订车票：'.$param['TicketTime'].$param['TrainNumber'].'次'.$param['FromStationName'].'至'.$param['ToStationName'].$param['SeatName'].'。为了您顺利购票，请在30分钟内完成支付，超时订单将被取消。';
					$result['limit'] = 10;
					return $result;
					break;
				//密码重置验证码短信
				case 'mmyzm':
					if(!isset($param['code'])){
						return 0;	
					}
					$result['content'] = '尊敬的去买呀用户您好！您重置密码的验证码为：'.$param['code'];
					$result['limit'] = 4;
					return $result;
					break;
				case 'danymic':
					if(!isset($param['code'])){
						return 0;
					}
					$result['content'] = '尊敬的去买呀用户您好！您此次的动态密码为：'.$param['code'];
					$result['limit'] = 4;
					return $result;
					break;
				//注册验证码短信
				case 'zczm':
					if(!isset($param['code'])){
						return 0;	
					}
					$result['content'] = '尊敬的去买呀用户您好！欢迎成为去买呀会员，您的验证码为：'.$param['code'];
					$result['limit'] = 4;
					return $result;
					break;
				//验证码短信
				case 'yzm':
					if(!isset($param['code'])){
						return 0;	
					}
					$result['content'] = '尊敬的去买呀用户您好！您的验证码为：'.$param['code'];
					$result['limit'] = 4;
					return $result;
					break;
				//新密码短信
				case 'newpwd':
					if(!isset($param['passWord'])){
						return 0;	
					}
					$result['content'] = '尊敬的去买呀用户您好！您的新密码为：'.$param['passWord'].',请妥善保管！';
					$result['limit'] = 3;
					return $result;
					break;
				//订购成功短信，提醒取票
				case 'ydcg':
					if(!isset($param['ENumber'])){
						return 0;	
					}
					$TicketTime = date('Y年m月d日',strtotime($param['TicketTime']));
					$startTime = date('H:i',strtotime($param['TicketTime']));
					$result['content'] = '您已购'.$TicketTime.$param['TrainNumber'].'次'.$param['FromStationName'].'至'.$param['ToStationName'].$param['SeatName'].$startTime.'开，电子订单号'.$param['ENumber'].'。由于火车站排队人员较多，请携带订票使用的身份证提前至火车站售票窗口或者自助售票机领取车票。祝您一路顺风！';
					return $result;
					break;
				//客户端预订成功短信
				case 'kydcg':
					if(!isset($param['ENumber'])){
						return 0;	
					}
					$result['content'] = '尊敬的去买呀用户:您好,您的'.$param['ENumber'].' 车票购票成功,请携带您的身份证去车站领取车票,详情见网上订单!';
					return $result;
					break;
				//差额退款短信
				case 'cetk':
					if(!isset($param['OrderNumber'])){
						return 0;	
					}
					if($param['refund']){
					    $refund = $param['refund'].'元';
					}
					$result['content'] = '您的订单'.$param['OrderNumber'].'已订票成功，'.$param['reason'].'购票差额'.$refund.'已经返还至您在去买呀的现金账户，请注意查收。感谢您使用去买呀！';
					return $result;
					break;
				//保险退款短信（目前暂时取消发送）
				case 'bxtk':
					if(!isset($param['OrderNumber'])){
						return 0;	
					}
					$result['content'] = '尊敬的用户:您订单号为:'.$param['OrderNumber'].' 的订单的已经对保险款项进行返还,大约在3-5个工作日之内到账!';
					return $result;
					break;
				//正在退票短信
				case 'zztk':
					if(!isset($param['OrderNumber'])){
						return 0;	
					}
					$result['content'] = '您的订单'.$param['OrderNumber'].'正在退票，退票成功后订票款项将返还您的支付账户，届时将有短信通知。感谢您使用去买呀！';
					return $result;
					break;
				//预订失败短信
				case 'wptk':
					if(!isset($param['OrderNumber'])){
						return 0;	
					}
					$result['content'] = '很遗憾的通知您订单号'.$param['OrderNumber'].'预定的车票已售完，订票时支付的款项将退还您的支付账户，退款成功后将有短信通知，感谢您使用去买呀！';
					return $result;
					break;
				//预订失败返款短信
				case 'zftk':
					if(!isset($param['OrderNumber'])){
						return 0;	
					}
					$result['content'] = '您订单号'.$param['OrderNumber'].'支付的款项将在5个工作日之内返还您的支付账户，请注意查收。感谢您使用去买呀！';
					return $result;
					break;
				//发车关怀短信
				case 'fcgh':
					$tianQi = '';
					if(!empty($param['tianQi']) && isset($param['tianQi'])){
					    $tianQi = $param['ToStationName'].date('d',strtotime($param['TicketTime'])).'日天气:'.$param['tianQi']['stateDetailed'].$param['tianQi']['tem1'].'-'.$param['tianQi']['tem2'].'度';
					}	
					$TicketTime = date('Y年m月d日',strtotime($param['TicketTime']));
					$startTime = date('H:i',strtotime($param['TicketTime']));			
					$result['content'] = '【出行贴士】您预订的'.$TicketTime.$startTime.'开'.$param['TrainNumber'].'次'.$param['FromStationName'].'至'.$param['ToStationName'].$param['SeatName'].'，列车将于'.$param['DiffTime'].'小时后发车小时后发车，请安排好您的出行计划。'.$tianQi.'如需帮助请致电025-66113670，去买呀竭诚为您服务！';
					return $result;
					break;
                //提现成功
                case 'txcg':
                    if(!isset($param['type'])){
                        return 0;
                    }
                    if($param['type'] == 0){
                        $result['content'] = '您的提现申请已经提交支付宝处理，提现金额约在5个工作日内到账，请注意查收！';
                    }else if($param['type'] == 1){
                        $result['content'] = '您的提现申请已经提交银行处理，提现金额约在5个工作日内到账，请注意查收！';
                    }
                    return $result;
                    break;
				default:
					return 0;
					break;
			}
		}else{
			return 0;
		}
	}
	
	public static function getReturn($success,$err,$msg='',$mobile=''){
		$SMSResult['status'] = $success;
		$SMSResult['error'] = $err;
		if($msg == ''){
			if(isset(self::$error[$err])){
				$SMSResult['msg'] = self::$error[$err];
			}else{
				$SMSResult['msg'] = $err;
			}
			
		}else{
			$SMSResult['msg'] = $msg;
		}
		if($mobile!=''){
			$SMSResult['number'] = $mobile;
		}
		return $SMSResult;
	}
	
	//发送记录插入数据库
	public static function InsertSendLog($insertValue){
		$insertValues = implode(',',$insertValue);
		$sql = 'INSERT INTO v1_send_sms_log(mobile,content,result,error_code,template,other_info,insert_time) VALUES '.$insertValues;
		$command = Yii::app()->db->createCommand($sql)->execute();
		return $command;
	}
	
	//短信发送结果发邮件提示(备用)
	public static function sendMail($errorCode, $reason='',$cont,$mobile,$success = '0',$money = 0){
	    if(defined('LOCALHOST')){
	        $day = intval(date('Ymd'));
	        $key = sprintf("%u", crc32($day.'smsMail'.$errorCode));
	        $value = Yii::app()->countCache->get($key);
	        if(!empty($value)){ 
	            if($value < 8){
            		$sendMail['To'][0]['email'] = 'yangjing@meiti.com';
            		$sendMail['To'][1]['email'] = 'zhangyi01@meiti.com';
            		$sendMail['typeInfo']['reason'] = $reason;
            		$sendMail['typeInfo']['cont'] = $cont;
            		$sendMail['typeInfo']['mobile'] = $mobile;
            		$num = count($mobile);
            		if(mb_strlen($cont,self::ENCODE) < 70){
            			$eachNum = 1;
            		}else{
            			$eachNum = ceil((mb_strlen($cont,self::ENCODE) - 70)/67); 
            			$eachNum += 1;
            		}
            		$num = count($mobile);
            		$sendMail['typeInfo']['num'] = $eachNum * $num;
            		$sendMail['typeInfo']['totalNum'] = $num;
            		$sendMail['typeInfo']['money'] = $money;
            		$sendMail['typeInfo']['success'] = $success;
            		$res =  Mail::sendMail($sendMail,'sms');
            		return $res;
	            }else{
	                $value = Yii::app()->countCache->incr($key);
	            }
	        }else{
	            $value = Yii::app()->countCache->set($key, 1, 2*24*3600);
	        }
			
	    }
	}
	
	//验证手机号码
	public static function checkMobile($number){
		if(is_numeric($number) || strlen($number)== 11){
			if(preg_match("/^(13[0-9]|15[0-9]|18[0-9]|14[57])[0-9]{8}$/",$number)){
				return  1;
			}else{
				return 0;
			} 
		}else{
			return 0;
		}
	}
}

?>

<?php 
    Yii::app()->clientScript->registerCssFile('/css/idialog/default.css');
    Yii::app()->clientScript->registerScriptFile('/js/artDialog.min.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile('/js/jquery.debit.js', CClientScript::POS_END);
    
    $uid = Yii::app()->user->id; 
    $userModel = User::model()->findByPk($uid);
?>
<p>标题：<span id="debit_title"><?php echo $debit->title;?></span></p>
<?php if($debit->status != -2 && $debit->status != -3){?>
	<p>借款金额：<span id="debit_money"><?php echo $debit->realDebitMoney;?></span></p>
	<?php if($debit->realMinDebitMoney != $debit->realDebitMoney){?>
		<p>此标已设最低下限：<?php echo $debit->realMinDebitMoney;?></p>
	<?php }?>
<?php }else{?>
	<p>借款金额：<span id="debit_money"><?php echo $debit->debitMoney;?></span></p>
	<?php if($debit->debitMoney != $debit->debitMinMoney){?>
		<p>此标已设最低下限：<?php echo $debit->debitMoney;?></p>
	<?php }?>
<?php }?>
<?php if($debit->status == 3 || $debit->status == 4){?>
	<p>成标价：<?php echo $debit->debitProgress;?></p>
<?php }?>
<p>借款期限：<span id="debit_deadline"><?php echo $debit->debitDeadline;?>个月</span></p>
<p>年利率：<span id="debit_rate"><?php echo floatval($debit->debitRate);?></span>%</p>
<?php if($debit->status != -2 && $debit->status != -3){?>
	<p>招标进度：<span id="debit_progress"><?php echo $debit->debitProgress;?></span></p>
	<p>截止时间：<span id="debit_deadline"><?php echo Debit::getDeadline($debit->vtime, $debit->invitDeadline).' 23:59:59';?></span></p>
<?php }?>
<?php if($debit->debitMoney == $debit->debitProgress || $debit->status == 3 || $debit->status == 4){?>
	<button>招标成功</button>
<?php }else{?>
    <?php if($debit->status == 2 && $debit->realDebitMoney > $debit->debitProgress){?>
        <?php if(!$uid){?>
        	<a href="/site/login">我要投标</a>
        <?php }else{?>
            <?php if($userModel->getInvestStatus() == '已通过'){?>
            	<?php if($debit->borrowerId != $uid){?>
            		<button onclick="debit('<?php echo $_GET['debitId'];?>','<?php echo User::model()->findByPk($uid)->cashMoney;?>')">我要投标</button>
            	<?php }else{?>
            		<button>查看投标</button>
        		<?php }?>
        	<?php }else{?>
        		<button>去认证</button>
        	<?php }?>
    	<?php }?>
	<?php }else if($debit->status == 2 && $debit->realDebitMoney == $debit->debitProgress){?>
		<button>已满标</button>
	<?php }else if($debit->status < 0){?>
		<button><?php echo Debit::$status[$debit->status]?></button>
	<?php }?>
<?php }?>


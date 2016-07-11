<?php 
    $uid = Yii::app()->user->id; 
?>
<p>借贷详情：</p>
<p>标题：<span id="debit_title"><?php echo $debit->title;?></span></p>
<p>申请借款金额：
<?php 
    if($debit->debitMinMoney != $debit->debitMoney && $debit->debitMinMoney != 0){
        echo '¥'.$debit->debitMinMoney.' - ¥'.$debit->debitMoney;
    }else{
        echo '¥'.$debit->debitMoney;
    }
?>
</p>
<?php if($debit->status != -2){?>
	<p>审核借款金额：
    <?php 
        if($debit->realDebitMoney != $debit->realMinDebitMoney && $debit->realMinDebitMoney != 0){
            echo '¥'.$debit->realMinDebitMoney.' - ¥'.$debit->realDebitMoney;
        }else{
            echo '¥'.$debit->realDebitMoney;
        }
    ?>
<?php }?>
</p>
<p>借款期限：<span><?php echo $debit->debitDeadline;?>个月</span></p>
<p>年利率：<span><?php echo floatval($debit->debitRate);?></span>%</p>
<p>还款方式：等额本息</p>
<p>状态：<?php echo $debit->status == 2 && $debit->realDebitMoney == $debit->debitProgress ? '已满标' : Debit::$status[$debit->status];?></p>
<?php if($debit->status == 2){?>
	<p>招标进度：<?php echo Debit::getProgressPercent($debit->debitProgress, $debit->realDebitMoney);?></p>
<?php }else if($debit->status == 3 || $debit->status == 4){?>
	<p>实际借款金额：<span id="debit_money"><?php echo $debit->debitProgress;?></span></p>
	<p>招标进度：100%</p>
	<p>月还款金额：<?php echo Debit::getMonthRepay($debit->debitProgress, $debit->debitDeadline, $debit->debitRate);?></p>
	<p>月还款日：<?php echo $debit->repayDate;?></p>
<?php }?>
<?php if($debit->kvtime != '0000-00-00 00:00:00'){?>
	<p>客服审核时间：<?php echo $debit->kvtime;?></p>
	<?php if(trim($debit->kefuInfo) != ''){
	    echo '<p>客服备注：'.$debit->kefuInfo.'</p>';
    }?>
<?php }?>
<?php if($debit->status != 0 && $debit->status != 1 && $debit->status != -2){?>
	<p>招标截止时间：<span id="debit_deadline"><?php echo Debit::getDeadline($debit->vtime, $debit->invitDeadline).' 23:59:59';?></span></p>
	<?php if($debit->status == -3){?>
		<p>用户驳回时间：<?php echo $debit->vtime;?></p>
		<p>驳回原因：<?php echo $debit->userRejectInfo;?></p>
	<?php }else{?>
		<p>发布时间：<?php echo $debit->vtime;?></p>
	<?php }?>
	<p>投标明细：</p>
    <table style="width:500px">
    	<thead>
    		<th>投标人</th>
    		<th>投标金额</th>
    		<th>投标时间</th>
    	</thead>
    	<tbody>
    	<?php if($debit->debitFinancingRecord){?>
    		<?php foreach($debit->debitFinancingRecord as $finacingRecord){?>
    			<tr>
    			    <td><?php $name = User::model()->findByPk($finacingRecord->lenderId)->userName;
    			        echo mb_substr($name,0,1,'UTF-8').str_repeat('*',mb_strlen($name, 'UTF-8')-1);?></td>
        			<td><?php echo $finacingRecord->debitMoney?></td>
        			<td><?php echo $finacingRecord->ctime?></td>
    			</tr>
    		<?php }?>
    	<?php }?>
    	<tr>
    		<td>合计</td>
    		<td><?php echo $debit->debitProgress;?></td>
    		<?php if($debit->status == 2){?>
        		<?php if($debit->realDebitMoney > $debit->debitProgress){?>
        			<td>还缺<?php echo $debit->realDebitMoney - $debit->debitProgress;?></td>
        		<?php }else if($debit->realDebitMoney <= $debit->debitProgress){?>
        			<td>筹款完成，客服正在帮你打款！</td>
        		<?php }else if(($debit->realMinDebitMoney <= $debit->debitProgress) && (strtotime(Debit::getDeadline($debit->vtime, $debit->invitDeadline + 1)) < time())){?>
        			<td>筹款完成，客服正在帮你打款！</td>
        		<?php }?>		
    		<?php }else if($debit->status == 3 || $debit->status == 4){?>
    			<td>已打款！</td>
    		<?php }else{?>
    			<td><?php echo Debit::$status[$debit->status];?></td>
    		<?php }?>
    	</tr>
    <?php }?>
	</tbody>
</table>
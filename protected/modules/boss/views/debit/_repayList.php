<?php if($index == '0') {?>	
<thead>
	<tr>
		<td>项目编号</td>
		<td>借款时间</td>
		<td>用户名</td>
		<td>标题</td>
		<td>金额</td>
		<td>利率</td>
		<td>期限</td>
		<td>月还款额</td>
		<td>规定还款时间</td>
		<td>实际还款时间</td>
		<td>还款状态</td>
		<td>是否需要提醒</td>
		<td>催款次数</td>
		<td>操作</td>
	</tr>
</thead>
<tbody>
<?php }?>
	<tr>
		<td><?php echo str_pad($data->id,6,'0',STR_PAD_LEFT);?></td>
	    <td><?php echo date('Y-m-d H:i',strtotime($data->ctime));?></td>
	    <td><?php echo $this->getUserName($data->debitInfo->borrowerId);?></td>
		<td><?php echo $data->debitInfo->title;?></td>
		<td>¥<?php echo $data->debitInfo->debitMoney;?></td>
		<td><?php echo floatval($data->debitInfo->debitRate);?>%</td>
		<td><?php echo $this->monthArr[$data->debitInfo->debitDeadline-1];?>个月</td>
		<td>¥<?php echo $data->repayMoney;?></td>
		<td><?php echo $data->repayDate;?></td>
		<td><?php echo $data->realRepayDate == '0000-00-00' ? '—' : $data->realRepayDate;?></td>
		<td><?php echo DebitRepayRecord::$status[$data->status];?></td>
		<td><?php echo $data->ifNotify == 0 ? '是' : '否';?></td>
		<td><?php echo $data->notifyTime;?></td>
		<td>
			<?php 
				$financeCount = count($data->debitInfo->debitFinancingRecord);
				$repayCount = count($data->debitInfo->debitRepayRecord);
			?>
			<button class="btn" onclick="showFinanceRecord(<?php echo $data->debitId?>)">出借记录(<?php echo $financeCount;?>)</button>
			<button class="btn" onclick="showRepayRecord(<?php echo $data->debitId?>)">还款记录(<?php echo $repayCount;?>)</button>
		</td>
	</tr>
<?php if ($index == $widget->dataProvider->getItemCount()) {?>
</tbody>
<?php }?>
<script>
function showFinanceRecord(id){
	$.ajax({
		type:'post',
		url:'/debit/showFinanceRecord',
		data:'id='+id,
		success:function(data){
			art.dialog({
				title:'投资列表',
			    content: data,
			    okValue:'确定',
			    ok:function(){
			    },
			    fixed:true,
			    drag: false,
			    resize: false,
			    lock:true,
			    opacity: 0.50
			});
		}
	 })
}
function showRepayRecord(id){
	$.ajax({
		type:'post',
		url:'/debit/showRepayRecord',
		data:'id='+id,
		success:function(data){
			art.dialog({
				title:'还款列表',
			    content: data,
			    okValue:'确定',
			    ok:function(){
			    },
			    fixed:true,
			    drag: false,
			    resize: false,
			    lock:true,
			    opacity: 0.50
			});
		}
	 })
}
</script>

<?php if($index == '0') {?>	
<thead>
	<tr>
		<td>项目编号</td>
		<td>用户名</td>
		<td>抵押产品</td>
		<td>金额</td>
		<td>期限</td>
		<td>利率</td>
		<td>项目状态</td>
		<td>审核人</td>
		<td>操作</td>
	</tr>
</thead>
<tbody>
<?php }?>
	<tr>
		<td><?php echo str_pad($data->id,6,'0',STR_PAD_LEFT);?></td>
	    <td><?php echo $this->getUserName($data->borrowerId);?></td>
	    <td><?php echo Debit::$catArr[$data->cat];?></td>
		<td>¥<?php echo $data->debitMoney;?></td>
		<td><?php echo $this->monthArr[$data->debitDeadline-1];?>个月</td>
		<td><?php echo floatval($data->debitRate);?>%</td>
		<td id="status_<?php echo $data->id;?>"><?php echo Debit::$status[$data->status];?></td>
		<td id="kefu_<?php echo $data->id;?>"><?php echo $data->operatorId==0?'—':$this->getAdminUserName($data->operatorId);?></td>
		<td>
			<?php if($data->status == 0){?>
		      	<a href="/debit/detail/<?php echo $data->id;?>" class="btn btn-danger">审核</a>
		    <?php }else{?>
        		<a href="/debit/detail/<?php echo $data->id;?>" class="btn">查看</a>
			<?php }?>
		</td>
	</tr>
<?php if ($index == $widget->dataProvider->getItemCount()) {?>
</tbody>
<?php }?>

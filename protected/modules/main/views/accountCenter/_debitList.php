<!-- 投资中的列表 -->
<?php if(!isset($_GET['list'])){?>
	<?php if($index == '0') {?>	
    <thead>
    	<tr>
    		<th>项目编号</th>
    		<th>项目名称</th>
    		<th>利率</th>
    		<th>投资金额</th>
    		<th>预期收益金额</th>
    		<th>收益率</th>
    		<th>已收金额</th>
    		<th>待收金额</th>
    		<th>操作</th>
    	</tr>
    </thead>
    <tbody>
    <?php }?>
    	<tr>
    		<?php 
    		    $debitModel = Debit::model()->findByPk($data['debitId']);
    		    $FinancingModel = DebitFinancingRecord::model()->findByPk($data['id']);
    		?>
    	    <td><?php echo str_pad($debitModel->id,6,'0',STR_PAD_LEFT);?></td>
    	    <td><?php echo $debitModel->title;?></td>
    	    <td><?php echo floatval($debitModel->debitRate);?>%</td>
    	    <td>¥<?php echo $FinancingModel->debitMoney;?></td>
    	    <?php if($debitModel->status == 3 || $debitModel->status == 4){
    	        $totalGetMoney = Debit::getMonthRepay($debitModel->debitProgress, $debitModel->debitDeadline, $debitModel->debitRate, $FinancingModel->debitMoney) * $debitModel->debitDeadline;
	        ?>
        	    <td>¥<?php echo $totalGetMoney;?></td>
        		<td><?php echo sprintf("%.2f", ($totalGetMoney - $FinancingModel->debitMoney)/$FinancingModel->debitMoney * 100)."%";?></td>
        		<td>¥<?php $haveGetMoney = DebitRepayRecord::getHaveRepayMoney($debitModel->id, $FinancingModel->debitMoney);echo $haveGetMoney;?></td>
        		<td>¥<?php echo $totalGetMoney - $haveGetMoney;?></td>
    	    <?php }else{?>
    	    	<td>—</td>
    	    	<td>—</td>
    	    	<td>—</td>
    	    	<td>—</td>
    	    <?php }?>
    	    <td><a href='/invest/<?php echo $debitModel->id;?>'>查看</a></td>
	    </tr>
    <?php if ($index == $widget->dataProvider->getItemCount()) {?>
    </tbody>
    <?php }?>
<!-- 借款中的列表 -->
<?php }else if(trim($_GET['list']) == 1){?>
    <?php if($index == '0') {?>	
    <thead>
    	<tr>
    		<th>项目编号</th>
    		<th>项目名称</th>
    		<th>借款期限</th>
    		<th>申请借款金额</th>
    		<th>核准借款金额</th>
    		<th>实际达标金额</th>
    		<th>借款利率</th>
    		<th>借款状态</th>
    		<th>抵押产品</th>
    		<th>操作</th>
    	</tr>
    </thead>
    <tbody>
    <?php }?> 
    	<tr>
    		<td><?php echo str_pad($data['id'],6,'0',STR_PAD_LEFT);?></td>
    		<td><?php echo $data['title'];?></td>
    		<td><?php echo Debit::$monthArr[$data['debitDeadline']-1];?>个月</td>
    		<td>
    		    <?php 
    		        if($data['debitMinMoney'] != $data['debitMoney'] && $data['debitMinMoney'] != 0){
    		            echo '¥'.$data['debitMinMoney'].' - ¥'.$data['debitMoney'];
    		        }else{
    		            echo '¥'.$data['debitMoney'];
    		        }
		        ?>
		    </td>
    		<td>
    			<?php 
    			    if($data['status'] == 0){
        		        echo '—';
    			    }else{
    			        if($data['realDebitMoney'] != $data['realMinDebitMoney'] && $data['realMinDebitMoney'] != 0){
        		            echo '¥'.$data['realMinDebitMoney'].' - ¥'.$data['realDebitMoney'];
        		        }else{
        		            echo '¥'.$data['realDebitMoney'];
        		        }
    			    }
		        ?>
	        </td>
	        <td>
	        	<?php if($data['status'] == 3 || $data['status'] == 4){
	        		 echo '¥'.$data['debitProgress'];
	        	}else{
	        	    echo '—';
	        	}?>
	        </td>	
    		<td><?php echo floatval($data['debitRate']);?>%</td>
    		<td id="status_<?php echo $data['id'];?>">
    			<?php 
    			    if($data['status'] == 2){
			            echo Debit::getProgressPercent($data->debitProgress, $data->realDebitMoney);
    			    }else{
    			        if($data['productId'] > 0){
    			            echo Debit::$status[$data['status']];
    			        }else{
    			            echo '抵押资产未认证';
    			        }
    			    }
    			?>
    		</td>
    		<td><?php echo Debit::$catArr[$data['cat']];?></td>
    		<td id="operate_<?php echo $data['id'];?>">
    			<?php 
    			    if($data['status'] == 1){
    			        echo "<a class='agree_btn' href='javascript:void(0)' onclick='agreePublish(".'"'.$data['id'].'","'.$data['realDebitMoney'].'","1"'.")'>同意发布</a>&nbsp;&nbsp;<a class='reject_btn' href='javascript:void(0)' onclick='rejectPublish(".'"'.$data['id'].'","'.$data['realDebitMoney'].'","0"'.")'>拒绝发布</a>&nbsp;&nbsp;";
    			    }else if($data['status'] == 3){
    			        echo "<a href=''>提现</a>";
    			    }
    			    if($data['productId'] > 0){
    			        echo "<a target='_blank' href='/accountCenter/debitDetail/".$data['id']."'>查看</a>";
    			    }else{
    			        echo "<a target='_blank' href='/debit/".Debit::$catEnglish[$data['cat']]."/".$data['id']."'>资产认证</a>";
    			    }
    			?>
    		</td>
    	</tr>
    <?php if ($index == $widget->dataProvider->getItemCount()) {?>
    </tbody>
    <?php }?>
<!-- 近期完成的列表 -->
<?php }else if(trim($_GET['list']) == 2){?>
	<?php if($index == '0') {?>	
   	<thead>
    	<tr>
    		<th>ID</th>
    		<th>时间</th>
    		<th>交易类型</th>
    		<th>项目标题</th>
    		<th>借/贷金额</th>
    		<th>年利率</th>
    		<th>操作</th>
    	</tr>
    </thead>
    <tbody>
    <?php }?>
    	<tr>
    		<td><?php echo $data['id'];?></td>
    		<td><?php echo $data['ctime'];?></td>
    		<td><?php echo $data['type'] == 'invest' ? '投资' : '贷款';?></td>
    		<td><?php echo $data['title'];?></td>
    		<td>
    			<?php 
    			    if($data['type'] == 'invest'){
    			        echo '¥'.$data['debitMoney'];
    			    }else{
    			        $debitModel = Debit::model()->findByPk($data['id']);
    			        if($debitModel['status'] == 3 || $debitModel['status'] == 4){
    			            echo '¥'.$data['debitMoney'];
    			        }else{
        		            if($debitModel['status'] != -2){
        		                if($debitModel['realDebitMoney'] != $debitModel['realMinDebitMoney'] && $debitModel['realMinDebitMoney'] != 0){
                		            echo '¥'.$debitModel['realMinDebitMoney'].' - ¥'.$debitModel['realDebitMoney'];
                		        }else{
        		                    echo '¥'.$debitModel['realDebitMoney'];
                		        }
        		            }else{
        		                if($debitModel['debitMinMoney'] != $debitModel['debitMoney'] && $debitModel['debitMinMoney'] != 0){
                		            echo '¥'.$debitModel['debitMinMoney'].' - ¥'.$debitModel['debitMoney'];
                		        }else{
        		                    echo '¥'.$debitModel['debitMoney'];
                		        }
        		            }
    			        }
    			    }
    			?>
    		</td>
    		<td><?php echo floatval($data['debitRate']);?>%</td>
    		<td><a href='<?php echo $data['id'] == '投资' ? '/invest/'.substr($data['id'], 1) :'/accountCenter/debitDetail/'.$data['id'];?>'>查看</a></td>
	    </tr>
    <?php if ($index == $widget->dataProvider->getItemCount()) {?>
    </tbody>
    <?php }?>
<!-- 账户变动记录的列表 -->
<?php }else if(trim($_GET['list']) == 3){?>
	<?php if($index == '0') {?>	
    <thead>
    	<tr>
    		<th>流水号</th>
    		<th>交易类型</th>
    		<th>金额</th>
    		<th>余额</th>
    		<th>时间</th>
    	</tr>
    </thead>
    <tbody>
    <?php }?>
    	<tr>
    		<td><?php echo $data['id'];?></td>
    		<td><?php echo IncomePayoutDetail::$categoryArray[$data['category']] .'：'. IncomePayoutDetail::$typeArray[$data['type']];?></td>
    		<td><?php echo $data['category'] == 1 ? '-¥'.$data['money'] : ''.$data['money'];?></td>
    		<td>¥<?php echo $data['nowCashMoney'] + $data['nowBlockMoney'];?></td>
    		<td><?php echo $data['ctime'];?></td>
	    </tr>
    <?php if ($index == $widget->dataProvider->getItemCount()) {?>
    </tbody>
    <?php }?>
<?php }?>
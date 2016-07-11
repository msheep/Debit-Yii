<div>成功提现金额:<?php echo $money;?></div>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'withdraw-search-form',
    'method' => 'get',
    'action' => array('index')
));
?>
<div class="rows">
    	<?php $this->widget('ext.my97DatePicker.JMy97DatePicker',array(
                'model'=>$model,
                'attribute'=>'fromTime',
                'options'=>array('dateFmt'=>'yyyy-MM-dd','readOnly'=>true,'maxDate'=>'#F{$dp.$D(\'WithdrawSearchForm_toTime\')}'),
            ));?>
-
    	<?php $this->widget('ext.my97DatePicker.JMy97DatePicker',array(
                'model'=>$model,
                'attribute'=>'toTime',
                'options'=>array('dateFmt'=>'yyyy-MM-dd','readOnly'=>true,'minDate'=>'#F{$dp.$D(\'WithdrawSearchForm_fromTime\')}'),
            ));?>
</div>
<div class="rows">
    <?php echo $form->dropDownList($model,'status',array_merge(array(''=>'所有状态记录'),WithdrawApply::$statusArray));?>
</div>
<input type="submit" class="btn" value="搜索" />
<?php $this->endWidget();?>
<?php $cg = $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search(),
    'selectableRows' => 0,
	'columns'=>array(
	    array(
	        'name' => 'id',
	        'value' => '$data->id'
	    ),
        array(
            'name'=>'ctime'
        ),
        'money',
	    'fee',
	    array(
	            'name'=>'bankCardId',
	            'value' => 'BankCard::$bankList[$data->bankCard->type]."(尾号:".substr($data->bankCard->card, -4).")"'
	    ),
        array(
            'name' => 'status',
            'value' => 'WithdrawApply::$statusArray[$data->status]'
        )
	),
)); ?>

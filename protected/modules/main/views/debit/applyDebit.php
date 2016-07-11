<?php 
Yii::app()->clientScript->registerCssFile('/css/idialog/default.css');
Yii::app()->clientScript->registerScriptFile('/js/artDialog.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('/js/jquery.debit.js', CClientScript::POS_END);
$form = $this->beginWidget('CActiveForm',array(
    'id'=>'apply-debit-form', 
    'enableClientValidation'=>true,              
    'clientOptions' => array(
    	'validateOnSubmit' => true,
    ),
));
?>
     <div>
         <?php echo $form->label($model,'请选择借款产品：',array('for'=>'cat')); ?>
         <?php echo $form->dropDownList($model ,'cat',array('1'=>'域名贷','2'=>'房产贷','3'=>'车辆贷')); ?>
     </div>
     <div>
         <?php echo $form->label($model,'借款标题：',array('for'=>'title')); ?>
         <?php echo $form->textField($model, 'title')?>
         <?php echo $form->error($model,'title'); ?>
     </div>
     <div>
        <label for="debitMoney">借款金额：</label>
        <a href="javascript:void(0)" onclick="getMoney('Debit_debitMoney','10000','down')">向下</a>
        <input id="Debit_debitMoney" type="text" name="Debit[debitMoney]" value="10000" onblur="checkDebitMoney();">元
        <a href="javascript:void(0)" onclick="getMoney('Debit_debitMoney','10000','up')">向上</a>
     </div>
     
     <div id="minDebit" style="display:none">
        <label for="debitMinMoney">最低借款金额：</label>
        <input id="Debit_debitMinMoney" type="text" name="Debit[debitMinMoney]" value="10000" onblur="checkMinMoney('Debit_debitMinMoney',10000,'Debit_debitMoney');">元
     </div>
   
     <div>
         <?php echo $form->label($model,'借款利率：',array('for'=>'debitRate')); ?>
         <?php echo $form->textField($model, 'debitRate',array('value'=>''))?>%
         <?php echo $form->error($model,'debitRate'); ?>
     </div>
     <div>
         <?php echo $form->label($model,'借款期限：',array('for'=>'debitDeadline')); ?>
         <?php 
             $debitRate = array();
             for($i=1; $i<13; $i++){
                 $debitRate[$i] = $i.'个月';
             }
             echo $form->dropDownList($model ,'debitDeadline',$debitRate); 
         ?>        
     </div>
     <div>
         <?php echo $form->label($model,'招标时限：',array('for'=>'invitDeadline')); ?>
         <?php echo $form->textField($model, 'invitDeadline',array('value'=>''))?>天
         <?php echo $form->error($model,'invitDeadline'); ?>
     </div>
     <div>
	<label>还款方式：</label>
    	<span>等额本息</span>
    </div>
    <div>
	<label>网站收费：</label>
    	<span>0.5%每月（一次收取）</span>
    </div>
     <!--
     <div>
         <?php echo $form->label($model,'月还款日：',array('for'=>'repayDate')); ?>
         <?php 
             $repayDate = array();
             for($i=1; $i<21; $i++){
                 $repayDate[$i] = $i.'日';
             }
             echo $form->dropDownList($model, 'repayDate', $repayDate)
         ?>
     </div>
     -->
     <div>
         <?php echo $form->label($model,'贷款用途：',array('for'=>'debitPurpose')); ?>
         <?php echo $form->textArea($model, 'debitPurpose')?>
         <?php echo $form->error($model,'debitPurpose'); ?>
     </div>
     <input type='hidden' name='Debit[fee]' id='Debit_fee' value=''>
     <?php echo CHtml::button('立即借款',array('onclick'=>'applyDebit()')); ?>
<?php $this->endWidget(); ?>
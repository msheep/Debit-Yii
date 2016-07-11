<?php if($action == 2){
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'confirm-paypassword-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	)
));
$this->useCsrfToken();
?>
<div>确认提现信息</div>
<div>银行卡信息：<?php echo $cardOptions[$model->bankCardId];?></div>
<div>提现金额：<?php echo $model->money;?></div>
<div>到账时间:提现成功后3-5个工作日</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($confirmForm,'password'); ?></div>
    <div class="rt">
        <?php echo $form->passwordField($confirmForm,'password');?>
    	<?php echo $form->error($confirmForm,'password'); ?>
    </div>
</div>
<input type="submit" class="btn" value="提交" />
<?php $this->endWidget();?>
<?php }elseif($action == 1){?>
<?php 
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'apply-withdraw-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	)
)); 
$this->useCsrfToken();
?>
<div>账户余额:<?php echo $user->cashMoney;?></div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'bankCardId'); ?></div>
    <div class="rt">
        <?php echo $form->radioButtonList($model,'bankCardId',$cardOptions);?>
    	<?php echo $form->error($model,'bankCardId'); ?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'money'); ?></div>
    <div class="rt">
        <?php echo $form->textField($model,'money');?>
    	<?php echo $form->error($model,'money'); ?>
    </div>
</div>
<input type="submit" class="btn" value="提交" />
<?php $this->endWidget();?>
<?php }elseif($action == 3){?>
<div>提现申请已提交，请等待银行处理！<br />
预计提现成功后3-5个工作日</div>
<?php }?>
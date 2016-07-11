<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'identity-auth-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); 
$this->useCsrfToken();
?>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->labelEx($model,'realName'); ?></div>
    <div class="rt">
    	<?php echo $form->textField($model,'realName'); ?>
    	<?php echo $form->error($model,'realName',array(),false); ?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->labelEx($model,'identityNo'); ?></div>
    <div class="rt">
    	<?php echo $form->textField($model,'identityNo'); ?>
    	<?php echo $form->error($model,'identityNo',array(),false); ?>
    </div>
</div>
<input type="submit" class="btn" value="提交" />

<?php $this->endWidget(); ?>
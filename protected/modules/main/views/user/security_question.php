<?php if($model instanceof SecurityQuestion){?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'security-question-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); 
$this->useCsrfToken();
?>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'question'); ?></div>
    <div class="rt">
        <span><?php echo $form->dropdownList($model,'type',array_merge(array(''=>'请选择问题',0=>'自定义问题'),Yii::app()->params['securityQuestions']),array('onchange'=>'selectType(this)'));?></span>
    	<span><?php echo $form->textField($model,'question',array('style'=>'display:none')); ?></span>
    	<span><?php echo $form->error($model,'type',array('inputContainer'=>'span')); ?></span>
    	<span><?php echo $form->error($model,'question',array('inputContainer'=>'span','clientValidation'=>'if($("#SecurityQuestion_type").val() !== "0"){return;}'));?></span>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->labelEx($model,'answer'); ?></div>
    <div class="rt">
    	<?php echo $form->textField($model,'answer'); ?>
    	<?php echo $form->error($model,'answer'); ?>
    </div>
</div>
<input type="submit" class="btn" value="提交" />
<script>
function selectType(obj){
    if(obj.value === '0'){
        $("#SecurityQuestion_question").show();
    }else{
    	$("#SecurityQuestion_question,#SecurityQuestion_question_em_").hide();
    }
    //@todo 重置quesiton的验证状态:$.fn.yiiactiveform.getSettings
}
</script>
<?php $this->endWidget(); ?>
<?php }elseif($model instanceof SecurityQuestionForm){?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'security-question-auth-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); 
$this->useCsrfToken();
?>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'question'); ?></div>
    <div class="rt">
    	<?php echo $model->question->question;?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'answer'); ?></div>
    <div class="rt">
    	<?php echo $form->textField($model,'answer'); ?>
    	<?php echo $form->error($model,'answer'); ?>
    </div>
</div>
<input type="submit" class="btn" value="提交" />
<?php $this->endWidget(); ?>
<?php }?>
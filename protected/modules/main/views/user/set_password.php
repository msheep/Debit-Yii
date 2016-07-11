<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'set-password-form',
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); 
$this->useCsrfToken();
?>
<div><?php echo SetPasswordForm::$scenarioArray[$model->scenario];?></div>
<?php if($model->scenario != 'setPayPassword'){?>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'oldPassword'); ?></div>
    <div class="rt">
        <?php echo $form->passwordField($model,'oldPassword'); ?>
        <?php echo $form->error($model,'oldPassword');?>
    </div>
</div>
<?php }?>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'password'); ?></div>
    <div class="rt">
        <?php echo $form->passwordField($model,'password'); ?>
        <?php echo $form->error($model,'password');?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'confirmPassword'); ?></div>
    <div class="rt">
        <?php echo $form->passwordField($model,'confirmPassword'); ?>
        <?php echo $form->error($model,'confirmPassword');?>
    </div>
</div>
<input type="submit" class="btn" value="提交" />
<?php $this->endWidget();?>
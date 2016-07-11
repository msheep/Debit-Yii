<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'mobile-auth-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
                'validateOnSubmit'=>true,
        ),
));
$this->useCsrfToken();
?>
<div class="rows">
<div class="lab"><span class="red">*</span><?php echo $form->label($model,'mobile'); ?></div>
<div class="rt">
<?php echo $model->mobile;?>
</div>
</div>
<div class="rows">
<div class="lab"><span class="red">*</span><?php echo $form->labelEx($model,'realName'); ?></div>
<div class="rt">
<?php echo $form->textField($model,'realName'); ?>
<?php echo $form->error($model,'realName'); ?>
</div>
</div>
<input type="submit" class="btn" value="提交" />

<?php $this->endWidget(); ?>
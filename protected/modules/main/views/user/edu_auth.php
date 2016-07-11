<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'edu-auth-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); 
$this->useCsrfToken();
?>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'degree'); ?></div>
    <div class="rt">
    	<?php echo $form->dropDownList($model,'degree',array_merge(array(''=>'请选择'),$model::$degreeArray)); ?>
    	<?php echo $form->error($model,'degree'); ?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'school'); ?></div>
    <div class="rt">
    	<?php echo $form->textField($model,'school'); ?>
    	<?php echo $form->error($model,'school'); ?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'schoolStartTime'); ?></div>
    <div class="rt">
    	<?php $this->widget('ext.my97DatePicker.JMy97DatePicker',array(
                'model'=>$model,
                'attribute'=>'schoolStartTime',
                'options'=>array('dateFmt'=>'yyyy-MM-dd'),
            ));?>
    	<?php echo $form->error($model,'schoolStartTime'); ?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'schoolEndTime'); ?></div>
    <div class="rt">
        <?php $this->widget('ext.my97DatePicker.JMy97DatePicker',array(
                'model'=>$model,
                'attribute'=>'schoolEndTime',
                'options'=>array('dateFmt'=>'yyyy-MM-dd'),
            ));?>
    	<?php echo $form->error($model,'schoolEndTime'); ?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'degreeNo'); ?></div>
    <div class="rt">
    	<?php echo $form->textField($model,'degreeNo'); ?>
    	<?php echo $form->error($model,'degreeNo'); ?>
    </div>
</div>
<input type="submit" class="btn" value="提交" />

<?php $this->endWidget(); ?>
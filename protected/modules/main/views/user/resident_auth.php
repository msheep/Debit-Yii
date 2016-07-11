<?php
Yii::app()->clientScript->registerCssFile('/css/idialog/default.css');
Yii::app()->clientScript->registerScriptFile('/js/artDialog.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('/js/resident_auth.js', CClientScript::POS_END);
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'resident-auth-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); 
$this->useCsrfToken();
?>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($user->identityAuthItem,'realName'); ?></div>
    <div class="rt">
        <?php echo $user->identityAuthItem->realName;?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($user->identityAuthItem,'identityNo'); ?></div>
    <div class="rt">
        <?php echo $user->identityAuthItem->identityNo;?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($user,'mobile'); ?></div>
    <div class="rt">
    	<?php echo $user->mobile;?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'age'); ?></div>
    <div class="rt">
        <?php echo $model->age;?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'maritalStatus'); ?></div>
    <div class="rt">
    	<?php echo $form->dropDownList($model,'maritalStatus',array_merge(array(''=>'请选择'),$model::$maritalStatusArray)); ?>
        <?php echo $form->error($model,'maritalStatus'); ?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'liveAddress'); ?></div>
    <div class="rt">
        <?php echo $form->dropDownList($model, 'liveProvinceId', array('---请选择省---'));?>
        <?php echo $form->dropDownList($model, 'liveCityId', array('---请选择市---'));?>
        <?php echo $form->dropDownList($model, 'liveAreaId', array('---请选择区----'));?>
    	<?php echo $form->textField($model,'liveAddress'); ?>
        <?php echo $form->error($model,'liveAddress'); ?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'householdAddress'); ?></div>
    <div class="rt">
        <?php echo $form->dropDownList($model, 'householdProvinceId', array('---请选择省---'));?>
        <?php echo $form->dropDownList($model, 'householdCityId', array('---请选择市---'));?>
        <?php echo $form->dropDownList($model, 'householdAreaId', array('---请选择区----'));?>
    	<?php echo $form->textField($model,'householdAddress'); ?>
        <?php echo $form->error($model,'householdAddress'); ?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'hoursing'); ?></div>
    <div class="rt">
    	<?php echo $form->dropDownList($model,'hoursing',array_merge(array(''=>'请选择'),$model::$hoursingArray)); ?>
        <?php echo $form->error($model,'hoursing'); ?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'cars'); ?></div>
    <div class="rt">
    	<?php echo $form->dropDownList($model,'cars',array_merge(array(''=>'请选择'),$model::$carsArray)); ?>
        <?php echo $form->error($model,'cars'); ?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'monthIncome'); ?></div>
    <div class="rt">
    	<?php echo $form->dropDownList($model,'monthIncome',array_merge(array(''=>'请选择'),$model::$monthIncomeArray)); ?>
        <?php echo $form->error($model,'monthIncome'); ?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'telphone'); ?></div>
    <div class="rt">
    	<?php echo $form->textField($model,'telphone'); ?>
        <?php echo $form->error($model,'telphone'); ?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'email'); ?></div>
    <div class="rt">
    	<?php echo $form->textField($model,'email'); ?>
        <?php echo $form->error($model,'email'); ?>
    </div>
</div>
<input type="submit" class="btn" value="提交" />

<?php $this->endWidget(); ?>
<?php 
Yii::app()->clientScript->registerCssFile('/css/idialog/default.css');
Yii::app()->clientScript->registerScriptFile('/js/artDialog.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('/js/jquery.debit.js', CClientScript::POS_END);
$form = $this->beginWidget('CActiveForm',array(
    'id'=>'debit-domain-form',  
    'enableClientValidation'=>true,      
    'clientOptions' => array(
    	'validateOnSubmit' => true,
    ),
));
?>
<div>
	<label for="cat">抵押资产类型：</label>
	<span id="cat">域名贷</span>
</div>
<div>
    <?php echo $form->label($model,'请输入需要抵押的域名：',array('for'=>'domain')); ?>
    www.<?php echo $form->textField($model, 'domain',array('value'=>'', 'onblur'=>'checkProduct("domain")'))?>
    <?php echo $form->error($model,'domain'); ?>
</div>
<div>
    <?php echo $form->label($model,'域名后缀：',array('for'=>'suffix')); ?>
    <?php echo $form->dropDownList($model, 'suffix',DebitDomain::$suffix)?>
    <?php echo $form->error($model,'suffix'); ?>
</div>
<div>
    <?php echo $form->label($model,'域名所有人：',array('for'=>'owner')); ?>
    <?php echo $form->textField($model, 'owner',array('value'=>''))?>
    <?php echo $form->error($model,'owner'); ?>
</div>
<div>
    <?php echo $form->label($model,'域名注册人：',array('for'=>'register')); ?>
    <?php echo $form->textField($model, 'register',array('value'=>''))?>
    <?php echo $form->error($model,'register'); ?>
</div>
<div>
    <?php echo $form->label($model,'服务提供商：',array('for'=>'serviceProvider')); ?>
    <?php echo $form->textField($model, 'serviceProvider',array('value'=>''))?>
    <?php echo $form->error($model,'serviceProvider'); ?>
</div>
<div>
    <?php echo $form->label($model,'注册日期：',array('for'=>'registrationDate')); ?>
    <?php $this->widget('ext.my97DatePicker.JMy97DatePicker',array(
        'model'=>$model,
        'attribute'=>'registrationDate',
        'options'=>array('isShowClear'=>false,'readOnly'=>true,'dateFmt'=>'yyyy-MM-dd','maxDate'=>'%y-%M-%d'),
    ));?>
    <?php echo $form->error($model,'registrationDate'); ?>
</div>
<div>
    <?php echo $form->label($model,'最近一次续费日：',array('for'=>'lastPayDate')); ?>
    <?php $this->widget('ext.my97DatePicker.JMy97DatePicker',array(
        'model'=>$model,
        'attribute'=>'lastPayDate',
        'options'=>array('isShowClear'=>false,'readOnly'=>true,'dateFmt'=>'yyyy-MM-dd','maxDate'=>'%y-%M-%d'),
    ));?>
    <?php echo $form->error($model,'deadLine'); ?>
</div>
<div>
    <?php echo $form->label($model,'到期日期：',array('for'=>'deadLine')); ?>
    <?php $this->widget('ext.my97DatePicker.JMy97DatePicker',array(
        'model'=>$model,
        'attribute'=>'deadLine',
        'options'=>array('isShowClear'=>false,'readOnly'=>true,'dateFmt'=>'yyyy-MM-dd'),
    ));?>
    <?php echo $form->error($model,'deadLine'); ?>
</div>
<input type='hidden' name='debitId' id='debitId' value='<?php echo $_GET['debitId']?>'>
<?php echo CHtml::submitButton('提交'); ?>
<?php $this->endWidget(); ?>
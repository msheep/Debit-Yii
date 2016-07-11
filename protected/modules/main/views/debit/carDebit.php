<?php 
Yii::app()->clientScript->registerCssFile('/css/idialog/default.css');
Yii::app()->clientScript->registerScriptFile('/js/artDialog.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('/js/jquery.debit.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('/js/jquery.car.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('/js/jquery.province.js', CClientScript::POS_END);
$form = $this->beginWidget('CActiveForm',array(
    'id'=>'debit-car-form',  
    'enableClientValidation'=>true,      
    'clientOptions' => array(
    	'validateOnSubmit' => true,
    ),
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
));
?>
<div>
	<label for="cat">抵押资产类型：</label>
	<span id="cat">车辆贷</span>
</div>

<div id="selectCar">
    <?php echo $form->label($model,'车型：'); ?>
	<span id="carDiv"></span>
	找不到您的车？<a href="javascript:void(0)" onClick="addCar()">填写一个</a>
</div>

<div id="addCar" style="display:none">
    <?php echo $form->label($model,'车型：',array('for'=>'addCar')); ?>
    <?php echo $form->textField($model, 'addCar',array('value'=>''))?>
    <?php echo $form->error($model,'addCar'); ?>
    <a href="javascript:void(0)" onClick="selectCar()">选择车型</a>
</div>

<div>
    <?php echo $form->label($model,'颜色：',array('for'=>'color')); ?>
    <?php echo $form->textField($model, 'color',array('value'=>''))?>
    <?php echo $form->error($model,'color'); ?>
</div>

<div>
    <?php echo $form->label($model,'变速箱：',array('for'=>'gearBox')); ?>
    <?php echo $form->radioButtonList($model, 'gearBox',DebitCar::$gearBox, array('template'=>'<span class="radio">{input}{label}</span> ','separator'=>''))?>
    <?php echo $form->error($model,'gearBox'); ?>
</div>

<div>
    <?php echo $form->label($model,'排量：',array('for'=>'output')); ?>
    <?php echo $form->dropDownList($model, 'output',DebitCar::$output)?>
    <?php echo $form->error($model,'output'); ?>
</div>

<div>
    <?php echo $form->label($model,'行驶里程：',array('for'=>'mileage')); ?>
     <?php echo $form->textField($model, 'mileage',array('value'=>''))?>公里
    <?php echo $form->error($model,'mileage'); ?>
</div>

<div>
    <?php echo $form->label($model,'车辆所在地：'); ?>
    <span id="carAreaDiv"></span>
</div>

<div>
    <?php echo $form->label($model,'购置价格：',array('for'=>'cost')); ?>
     <?php echo $form->textField($model, 'cost',array('value'=>''))?>万元
    <?php echo $form->error($model,'cost'); ?>
</div>

<div>
    <?php echo $form->label($model,'购车时间：'); ?>
    <select id="DebitCar_buyYear" name="DebitCar[buyYear]">
        <?php for($i=date('Y',time()); $i>=1991; $i--){?>
        	<option value="<?php echo $i;?>"><?php echo $i;?></option>
        <?php }?>
        <option value="-1">90年以及前</option>
    </select>
    <select id="DebitCar_buyMonth" name="DebitCar[buyMonth]">
        <?php for($i=1; $i<13; $i++){?>
        	<option value="<?php echo str_pad($i,2,'0',STR_PAD_LEFT);?>"><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
        <?php }?>
    </select>
</div>

<div>
    <?php echo $form->label($model,'首次上牌日期：'); ?>
    <select id="DebitCar_registYear" name="DebitCar[registYear]">
        <?php for($i=date('Y',time()); $i>=1991; $i--){?>
        	<option value="<?php echo $i;?>"><?php echo $i;?></option>
        <?php }?>
        <option value="-1">90年以及前</option>
    </select>
    <select id="DebitCar_registMonth" name="DebitCar[registMonth]">
        <?php for($i=1; $i<13; $i++){?>
        	<option value="<?php echo str_pad($i,2,'0',STR_PAD_LEFT);?>"><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
        <?php }?>
    </select>
</div>

<div>
    <?php echo $form->label($model,'下次验车日期：'); ?>
    <select id="DebitCar_auditYear" name="DebitCar[auditYear]">
        <?php for($i=date('Y',time())+2; $i>=1991; $i--){?>
        	<option value="<?php echo $i;?>"><?php echo $i;?></option>
        <?php }?>
        <option value="-1">90年以及前</option>
    </select>
    <select id="DebitCar_auditMonth" name="DebitCar[auditMonth]">
        <?php for($i=1; $i<13; $i++){?>
        	<option value="<?php echo str_pad($i,2,'0',STR_PAD_LEFT);?>"><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
        <?php }?>
    </select>
</div>

<div>
    <?php echo $form->label($model,'交强险截止日期：'); ?>
    <select id="DebitCar_insuranceYear" name="DebitCar[insuranceYear]">
        <?php for($i=date('Y',time())+1; $i>=1991; $i--){?>
        	<option value="<?php echo $i;?>"><?php echo $i;?></option>
        <?php }?>
        <option value="-1">90年以及前</option>
    </select>
    <select id="DebitCar_insuranceMonth" name="DebitCar[insuranceMonth]">
        <?php for($i=1; $i<13; $i++){?>
        	<option value="<?php echo $i;?>"><?php echo $i;?></option>
        <?php }?>
    </select>
</div>

<div>
    <?php echo $form->label($model,'车船使用税有效期：',array('for'=>'taxDate')); ?>
    <select id="DebitCar_taxDate" name="DebitCar[taxDate]">
        <?php for($i=date('Y',time())+1; $i>=date('Y',time()); $i--){?>
        	<option value="<?php echo $i;?>"><?php echo $i;?></option>
        <?php }?>
    	<option value="-1">已过期</option>
    </select>
</div>

<div>
    <?php echo $form->label($model,'车辆出厂时间：'); ?>
    <select id="DebitCar_productYear" name="DebitCar[productYear]">
        <?php for($i=date('Y',time()); $i>=1991; $i--){?>
        	<option value="<?php echo $i;?>"><?php echo $i;?></option>
        <?php }?>
        <option value="-1">90年以及前</option>
    </select>
    <select id="DebitCar_productMonth" name="DebitCar[productMonth]">
        <?php for($i=1; $i<13; $i++){?>
        	<option value="<?php echo $i;?>"><?php echo $i;?></option>
        <?php }?>
    </select>
</div>

<div>
    <?php echo $form->label($model,'发动机号：',array('for'=>'engineNumber')); ?>
    <?php echo $form->textField($model, 'engineNumber',array('value'=>''))?>
    <?php echo $form->error($model,'engineNumber'); ?>
</div>

<div>
    <?php echo $form->label($model,'车牌类型：',array('for'=>'carType')); ?>
    <?php echo $form->dropDownList($model, 'carType',DebitCar::$carType)?>
    <?php echo $form->error($model,'carType'); ?>
</div>

<div>
    <?php echo $form->label($model,'车牌号码：',array('for'=>'plates')); ?>
    <?php echo $form->dropDownList($model, 'platesBelong',DebitCar::$carBelong)?>
    <?php echo $form->textField($model, 'plates',array('value'=>''))?>
    <?php echo $form->error($model,'plates'); ?>
</div>

<div>
    <?php echo $form->label($model,'事故情况：',array('for'=>'accident')); ?>
    <?php echo $form->dropDownList($model, 'accident',DebitCar::$accident)?>
    <?php echo $form->error($model,'accident'); ?>
</div>

<div>
    <?php echo $form->label($model,'车主姓名：',array('for'=>'carOwner')); ?>
    <?php echo $form->textField($model, 'carOwner',array('value'=>''))?>
    <?php echo $form->error($model,'carOwner'); ?>
</div>

<div>	
	<?php echo $form->label($file,'登记证：',array('for'=>'registration')); ?>
	<?php echo $form->radioButtonList($model, 'registration',DebitCar::$status, array('template'=>'<span class="radio">{input}{label}</span> ','separator'=>'','onclick'=>'checkStatus("DebitCar_registration_0")'))?>
	<div class="addPhoto"><?php echo CHtml::activeFileField($file, 'registr',array('value'=>'','class'=>'file_path'))?></div>
	<?php echo $form->error($file,'registration'); ?>
</div>

<div>	
	<?php echo $form->label($file,'行驶证：',array('for'=>'drivingPermit')); ?>
	<?php echo $form->radioButtonList($model, 'drivingPermit',DebitCar::$status, array('template'=>'<span class="radio">{input}{label}</span> ','separator'=>'','onclick'=>'checkStatus("DebitCar_drivingPermit_0")'))?>
	<div class="addPhoto"><?php echo CHtml::activeFileField($file, 'drive',array('value'=>'','class'=>'file_path'))?></div>
	<?php echo $form->error($file,'drivingPermit'); ?>
</div>

<div>	
	<?php echo $form->label($file,'购车发票：',array('for'=>'receipt')); ?>
	<?php echo $form->radioButtonList($model, 'receipt',DebitCar::$status, array('template'=>'<span class="radio">{input}{label}</span> ','separator'=>'','onclick'=>'checkStatus("DebitCar_receipt_0")'))?>
	<div class="addPhoto"><?php echo CHtml::activeFileField($file, 'receipt',array('value'=>'','class'=>'file_path'))?></div>
	<?php echo $form->error($file,'receipt'); ?>
</div>

<div>	
	<?php echo $form->label($file,'上传车辆照片：',array('for'=>'receipt')); ?>
	正面车头照<?php echo CHtml::activeFileField($file, 'car[]',array('value'=>'','class'=>'file_path'))?><br>
	背面车尾照<?php echo CHtml::activeFileField($file, 'car[]',array('value'=>'','class'=>'file_path'))?><br>
	车厢内部照<?php echo CHtml::activeFileField($file, 'car[]',array('value'=>'','class'=>'file_path'))?><br>
	<?php echo $form->error($file,'receipt'); ?>
</div>

<input type='hidden' name='debitId' id='debitId' value='<?php echo $_GET['debitId']?>'>
<?php echo CHtml::submitButton('提交'); ?>
<?php $this->endWidget(); ?>
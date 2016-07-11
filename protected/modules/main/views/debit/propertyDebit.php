<?php 
Yii::app()->clientScript->registerCssFile('/css/idialog/default.css');
Yii::app()->clientScript->registerScriptFile('/js/artDialog.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('/js/jquery.debit.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('/js/jquery.province.js', CClientScript::POS_END);
$form = $this->beginWidget('CActiveForm',array(
    'id'=>'debit-domain-form',  
    'enableClientValidation'=>true,      
    'clientOptions' => array(
    	'validateOnSubmit' => true,
    ),
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
));
?>

<div>
	<label for="cat">抵押资产类型：</label>
	<span id="cat">房产贷</span>
</div>
<div>
    <?php echo $form->label($model,'物业类型：',array('for'=>'type')); ?>
    <?php echo $form->dropDownList($model, 'type', DebitProperty::$propertyType, array('onChange'=>'changePropertyType()'));?>
</div>

<div class="house house_type"> 	
 	<?php echo $form->label($house,'房屋产权性质：',array('for'=>'housePropertyType')); ?>
	<?php echo $form->dropDownList($house, 'housePropertyType', DebitProperty::$propertyCharacter);?>
</div>
<div class="house house_type">
 	<?php echo $form->label($house,'类型：',array('for'=>'houseType')); ?>
	<?php echo $form->dropDownList($house, 'houseType', DebitProperty::$houseProperty);?>
</div>


<div class="business"> 	
 	<?php echo $form->label($business,'商业用房类型：',array('for'=>'businessType')); ?>
	<?php echo $form->dropDownList($business, 'businessType', DebitProperty::$businessType, array('onChange'=>'changeCommercailType()'));?>
</div>
<div class="business shop"> 	
 	<?php echo $form->label($business,'类型：',array('for'=>'shopType')); ?>
	<?php echo $form->dropDownList($business, 'shopType', DebitProperty::$shopType);?>
	<?php echo $form->dropDownList($business, 'shopFaceType', DebitProperty::$shopFaceType);?>
</div>	
<div class="business office">
 	<?php echo $form->label($business,'类型/级别：',array('for'=>'officeType')); ?>
	<?php echo $form->dropDownList($business, 'officeType', DebitProperty::$officeType);?>
	<?php echo $form->dropDownList($business, 'officeRank', DebitProperty::$rank);?>
</div>
<div class="business plant"> 	
 	<?php echo $form->label($business,'类型：',array('for'=>'plantType')); ?>
	<?php echo $form->dropDownList($business, 'plantType', DebitProperty::$plantType);?>
</div>
<div class="business">
    <?php echo $form->label($business,'楼盘名称：',array('for'=>'housesName')); ?>
    <?php echo $form->textField($business, 'housesName',array('value'=>''))?>
    <?php echo $form->error($business,'housesName',array('clientValidation'=>'if($("#DebitProperty_type").val() != 2){return ;}')); ?>
</div>

<div>
	<?php echo $form->label($model,'房产所在地：'); ?>
	<span id="areaDiv"></span>
	<?php echo $form->textField($model, 'address',array('value'=>''))?>
 	<?php echo $form->error($model,'address'); ?>
</div>
<div>
    <?php echo $form->label($model,'建筑面积：',array('for'=>'totalArea')); ?>
    <?php echo $form->textField($model, 'totalArea',array('value'=>''))?>平方米
    <?php echo $form->error($model,'totalArea'); ?>
</div>
<div>
    <?php echo $form->label($model,'使用面积：',array('for'=>'useArea')); ?>
    <?php echo $form->textField($model, 'useArea',array('value'=>''))?>平方米
    <?php echo $form->error($model,'useArea'); ?>
</div>
<div>
    <?php echo $form->label($model,'购置年月：',array('for'=>'ageLimit')); ?>
    <?php $this->widget('ext.my97DatePicker.JMy97DatePicker',array(
        'model'=>$model,
        'attribute'=>'buyDate',
        'options'=>array('isShowClear'=>false,'readOnly'=>true,'dateFmt'=>'yyyy-MM','maxDate'=>'%y-%M-%d'),
    ));?>
</div>
<div>
    <?php echo $form->label($model,'购买单价：',array('for'=>'perCost')); ?>
    <?php echo $form->textField($model, 'perCost',array('value'=>''))?>元/平方米
    <?php echo $form->error($model,'perCost'); ?>
</div>
<div>
    <?php echo $form->label($model,'物业费：',array('for'=>'cleanFee')); ?>
    <?php echo $form->textField($model, 'cleanFee',array('value'=>''))?>元/平米·月 
    <?php echo $form->error($model,'cleanFee'); ?>
</div>
<div>
    <?php echo $form->label($model,'楼层：',array('for'=>'floor')); ?>
          第<?php echo $form->textField($model, 'floor',array('value'=>''))?>层/共<?php echo $form->textField($model, 'allFloor',array('value'=>''))?>层
    <?php echo $form->error($model,'floor'); ?>
    <?php echo $form->error($model,'allFloor'); ?>
</div>
<div>
    <?php echo $form->label($model,'建筑年代：',array('for'=>'year')); ?>
    <?php echo $form->textField($model, 'year',array('value'=>''))?>年
    <?php echo $form->error($model,'year'); ?>
</div>
<div>
    <?php echo $form->label($model,'产权年限：',array('for'=>'ageLimit')); ?>
    <?php echo $form->dropDownList($model, 'ageLimit', DebitProperty::$useTime);?>
</div>
<div>
	<?php echo $form->label($model,'土地证号：',array('for'=>'landCertificateId')); ?>
	<?php echo $form->textField($model, 'landCertificateId',array('value'=>''))?>
 	<?php echo $form->error($model,'landCertificateId'); ?>
</div>
<div id="landBothOwner_0">
	<?php echo $form->label($model,'所有人（土地证号）：',array('for'=>'landOwner')); ?>
	<?php echo $form->textField($model, 'landOwner',array('value'=>'','class'=>'land_owner','name'=>'DebitProperty[landOwner]'))?>
 	<?php echo $form->error($model,'landOwner'); ?>
 	<a id="landBothOwner" href="javascript:void(0)" onclick="addowner('landBothOwner')">添加共有人</a>
</div>
<div>
	<?php echo $form->label($model,'房屋所有权证号：',array('for'=>'houseCertificateId')); ?>
	<?php echo $form->textField($model, 'houseCertificateId',array('value'=>''))?>
 	<?php echo $form->error($model,'houseCertificateId'); ?>
</div>
<div id="houseBothOwner_0">
	<?php echo $form->label($model,'所有人（房屋所有权证号）：',array('for'=>'houseOwner')); ?>
	<?php echo $form->textField($model, 'houseOwner',array('value'=>'','class'=>'house_owner','name'=>'DebitProperty[houseOwner]'))?>
 	<?php echo $form->error($model,'houseOwner'); ?>
 	<a id="houseBothOwner" href="javascript:void(0)" onclick="addowner('houseBothOwner')">添加共有人</a>
</div>
<div class="house"> 	
 	<?php echo $form->label($house,'房屋状态：',array('for'=>'status')); ?>
	<?php echo $form->dropDownList($house, 'status', DebitProperty::$houseStatus);?>
</div>
<div class="house"> 	
 	<?php echo $form->label($house,'装修情况：',array('for'=>'fitment')); ?>
	<?php echo $form->dropDownList($house, 'fitment', DebitProperty::$fitment, array('onChange'=>'changeFitment()'));?>
</div>
<div class="house fitment"> 	
 	<?php echo $form->label($house,'装修完成时间：',array('for'=>'fitmentTime')); ?>
	<?php echo $form->dropDownList($house, 'fitmentTime',DebitProperty::$fitmentTime)?>
</div>
<div class="house fitment"> 	
 	<?php echo $form->label($house,'装修金额：',array('for'=>'fitmentMoney')); ?>
	<?php echo $form->textField($house, 'fitmentMoney',array('value'=>''))?>元
</div>
<div class="house"> 	
 	<?php echo $form->label($house,'赠送面积：',array('for'=>'houseGiveArea')); ?>
	<?php echo $form->textField($house, 'houseGiveArea',array('value'=>''))?>平方米
</div>
<div class="house"> 	
 	<?php echo $form->label($house,'地下室：',array('for'=>'houseBasementArea')); ?>
	<?php echo $form->textField($house, 'houseBasementArea',array('value'=>''))?>平方米
</div>
<div class="house"> 	
 	<?php echo $form->label($house,'朝向：',array('for'=>'houseToward')); ?>
	<?php echo $form->dropDownList($house, 'houseToward', DebitProperty::$towards);?>
	<?php echo $form->dropDownList($house, 'isLift', DebitProperty::$ifhave)?>电梯
</div>
<div class="house"> 	
 	<?php echo $form->label($house,'户型：'); ?>
	<?php echo $form->textField($house, 'roomNum',array('value'=>''))?>室
	<?php echo $form->textField($house, 'hallNum',array('value'=>''))?>厅
	<?php echo $form->textField($house, 'toiletNum',array('value'=>''))?>卫
	<?php echo $form->error($house,'roomNum',array('clientValidation'=>'if($("#DebitProperty_type").val() == 2){return ;}')); ?>
	<?php echo $form->error($house,'hallNum',array('clientValidation'=>'if($("#DebitProperty_type").val() == 2){return ;}')); ?>
	<?php echo $form->error($house,'toiletNum',array('clientValidation'=>'if($("#DebitProperty_type").val() == 2){return ;}')); ?>
</div>

<div>
	<?php echo $form->label($model,'是否有贷款：',array('for'=>'isLoan')); ?>
	<?php echo $form->radioButtonList($model, 'isLoan', array('0'=>'否','1'=>'是'),array('template'=>'<span class="radio">{input}{label}</span> ','separator'=>'','onClick'=>'changeLoan()'));?>
	<?php echo $form->error($model,'isLoan'); ?>
</div>

<div class="isloan"> 	
 	<?php echo $form->label($model,'贷款类型：',array('for'=>'loanType')); ?>
	<?php echo $form->dropDownList($model, 'loanType', DebitProperty::$loanType);?>
	<?php echo $form->error($model,'loanType'); ?>
</div>
<div class="isloan">	
	<?php echo $form->label($model,'贷款年限：'); ?>
	<?php $this->widget('ext.my97DatePicker.JMy97DatePicker',array(
        'model'=>$model,
        'attribute'=>'loanBegin',
        'options'=>array('isShowClear'=>false,'readOnly'=>true,'dateFmt'=>'yyyy-MM','maxDate'=>'#F{$dp.$D(\"DebitProperty_loanEnd\")||\"%y-%M-%d\"}'),
    ));?>起 -
    <?php $this->widget('ext.my97DatePicker.JMy97DatePicker',array(
        'model'=>$model,
        'attribute'=>'loanEnd',
        'options'=>array('isShowClear'=>false,'readOnly'=>true,'dateFmt'=>'yyyy-MM','minDate'=>'#F{$dp.$D(\"DebitProperty_loanBegin\")}'),
    ));?>止
</div>
<div class="isloan">	
	<?php echo $form->label($model,'按揭成数：',array('for'=>'loanPercent')); ?>
	<?php echo $form->dropDownList($model, 'loanPercent', array(1=>'1成',2=>'2成',3=>'3成',4=>'4成',5=>'5成',6=>'6成',7=>'7成',8=>'8成',9=>'9成'));?>
</div>

<div>
	<?php echo $form->label($model,'是否正在出租：',array('for'=>'isRent')); ?>
	<?php echo $form->radioButtonList($model, 'isRent', array('0'=>'否','1'=>'是'),array('template'=>'<span class="radio">{input}{label}</span> ','separator'=>'','onClick'=>'changeRent()'));?>
</div>

<div class="isrent">	
	<?php echo $form->label($model,'起止时间：'); ?>
	<?php $this->widget('ext.my97DatePicker.JMy97DatePicker',array(
        'model'=>$model,
        'attribute'=>'rentBegin',
        'options'=>array('isShowClear'=>false,'readOnly'=>true,'dateFmt'=>'yyyy-MM-dd','maxDate'=>'#F{$dp.$D(\"DebitProperty_rentEnd\")||\"%y-%M-%d\"}'),
    ));?>起 -
	<?php $this->widget('ext.my97DatePicker.JMy97DatePicker',array(
        'model'=>$model,
        'attribute'=>'rentEnd',
        'options'=>array('isShowClear'=>false,'readOnly'=>true,'dateFmt'=>'yyyy-MM-dd','minDate'=>'#F{$dp.$D(\"DebitProperty_rentBegin\")}'),
    ));?>止
</div>
<div class="isrent">	
	<?php echo $form->label($model,'月租金：',array('for'=>'rentMoney')); ?>
	<?php echo $form->textField($model, 'rentMoney',array('value'=>''))?>元
	<?php echo $form->error($model,'rentMoney'); ?>
</div>

<div class="business shop"> 	
 	<?php echo $form->label($business,'状态：',array('for'=>'status')); ?>
	<?php echo $form->dropDownList($business, 'status', DebitProperty::$shopStatus);?>
</div>
<div class="business shop"> 	
 	<?php echo $form->label($business,'可经营类别：',array('for'=>'manageType')); ?>
	<?php echo $form->checkBoxList($business, 'manageType', DebitProperty::$manageType, array('template'=>'<span class="check">{input}{label}</span>','separator'=>' '));?>
</div>

<div class="business"> 	
 	<?php echo $form->label($business,'配套设施：',array('for'=>'support')); ?>
	<?php echo $form->checkBoxList($business, 'support', DebitProperty::$bussinesSupport, array('template'=>'<span class="check">{input}{label}</span>','separator'=>' '));?>
</div>

<div class="house"> 	
 	<?php echo $form->label($house,'是否二手房：',array('for'=>'isSecondHand')); ?>
	<?php echo $form->radioButtonList($house, 'isSecondHand', DebitProperty::$if,array('template'=>'<span class="radio">{input}{label}</span> ','separator'=>''));?>
</div>
<div class="house"> 	
 	<?php echo $form->label($house,'房产购置满5年：',array('for'=>'isFiveYear')); ?>
	<?php echo $form->radioButtonList($house, 'isFiveYear', DebitProperty::$if,array('template'=>'<span class="radio">{input}{label}</span> ','separator'=>''));?>
</div>
<div class="house"> 	
 	<?php echo $form->label($house,'买房家庭首次购房：',array('for'=>'isFirstBuy')); ?>
	<?php echo $form->radioButtonList($house, 'isFirstBuy', DebitProperty::$if,array('template'=>'<span class="radio">{input}{label}</span> ','separator'=>''));?>
</div>
<div class="house"> 	
 	<?php echo $form->label($house,'采光通风：',array('for'=>'houseLight')); ?>
	<?php echo $form->radioButtonList($house, 'houseLight', DebitProperty::$houseLight,array('template'=>'<span class="radio">{input}{label}</span> ','separator'=>''));?>
</div>
<div class="house"> 	
 	<?php echo $form->label($house,'景观情况：',array('for'=>'houseLandscape')); ?>
	<?php echo $form->radioButtonList($house, 'houseLandscape', DebitProperty::$houseLandscape,array('template'=>'<span class="radio">{input}{label}</span> ','separator'=>''));?>
</div>
<div class="house"> 	
 	<?php echo $form->label($house,'小区位置：',array('for'=>'houseLocation')); ?>
	<?php echo $form->radioButtonList($house, 'houseLocation', DebitProperty::$houseLocation,array('template'=>'<span class="radio">{input}{label}</span> ','separator'=>''));?>
</div>
<div class="house"> 	
 	<?php echo $form->label($house,'噪音影响：',array('for'=>'houseNoise')); ?>
	<?php echo $form->radioButtonList($house, 'houseNoise', DebitProperty::$houseNoise,array('template'=>'<span class="radio">{input}{label}</span> ','separator'=>''));?>
</div>
<div class="house"> 	
 	<?php echo $form->label($house,'自有车位：',array('for'=>'carport')); ?>
	<?php echo $form->radioButtonList($house, 'carport', DebitProperty::$ifhave,array('template'=>'<span class="radio">{input}{label}</span> ','separator'=>''));?>
</div>
<div class="house"> 	
 	<?php echo $form->label($house,'厌恶设施：',array('for'=>'hateFacility')); ?>
	<?php echo $form->radioButtonList($house, 'hateFacility', DebitProperty::$hateFacility,array('template'=>'<span class="radio">{input}{label}</span> ','separator'=>'','onClick'=>'changeHateFacility()'));?>
</div>
<div class="house hate"> 	
 	<?php echo $form->label($house,'厌恶因素：',array('for'=>'hateFactor')); ?>
	<?php echo $form->checkBoxList($house, 'hateFactor', DebitProperty::$hateFactor,array('template'=>'<span class="check">{input}{label}</span>','separator'=>' '));?>
</div>
<div class="house"> 	
 	<?php echo $form->label($house,'小区配套：',array('for'=>'support')); ?>
	<?php echo $form->checkBoxList($house, 'support', DebitProperty::$houseSupport,array('template'=>'<span class="check">{input}{label}</span>','separator'=>' '));?>
</div>

<div> 	
 	<?php echo $form->label($model,'交通状况：',array('for'=>'transport')); ?>
	<?php echo $form->textArea($model, 'transport');?>
</div>
<div> 	
 	<?php echo $form->label($model,'周边配套：',array('for'=>'aroundSupport')); ?>
	<?php echo $form->textArea($model, 'aroundSupport');?>
</div>
<div> 	
 	<?php echo $form->label($model,'建筑描述：',array('for'=>'buildingDesc')); ?>
	<?php echo $form->textArea($model, 'buildingDesc');?>
</div>
<div>	
	<?php echo $form->label($model,'补充说明：',array('for'=>'moreInfo')); ?>
	<?php echo $form->textArea($model, 'moreInfo',array('value'=>''))?>
</div>
<div id="path">	
	<?php echo $form->label($file,'上传房产照片：',array('for'=>'path')); ?>
	<?php echo CHtml::activeFileField($file, 'path[]',array('value'=>'','class'=>'file_path'))?>
	<a href="javascript:void(0)" onClick="addFile('path')">添加照片</a>
	<?php echo $form->error($file,'path'); ?>
</div>

<input type='hidden' name='debitId' id='debitId' value='<?php echo $_GET['debitId']?>'>

<?php echo CHtml::submitButton('提交'); ?>
<?php $this->endWidget(); ?>


<style>
.business, .isloan, .isrent{
	display: none;
}
</style>
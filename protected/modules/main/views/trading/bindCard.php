<?php 
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'bind-card-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	)
)); 
$this->useCsrfToken();
?>
<div>
<?php if($cards){?>
<ul>
<?php foreach($cards as $v){?>
<li>卡号：<?php echo $v->card;?></li>
<?php }?>
</ul>
<?php }?>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'ownerName'); ?></div>
    <div class="rt">
        <?php echo $form->textField($model,'ownerName');?>
    	<?php echo $form->error($model,'ownerName'); ?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'type'); ?></div>
    <div class="rt">
        <?php echo $form->dropDownList($model,'type',BankCard::$bankList);?>
    	<?php echo $form->error($model,'type'); ?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'card'); ?></div>
    <div class="rt">
        <?php echo $form->textField($model,'card');?>
    	<?php echo $form->error($model,'card'); ?>
    </div>
</div>
<div class="rows">
    <div class="lab"><span class="red">*</span><?php echo $form->label($model,'bankName'); ?></div>
    <div class="rt">
        <?php echo $form->textField($model,'bankName');?>
    	<?php echo $form->error($model,'bankName'); ?>
    </div>
</div>
<input type="submit" class="btn" value="提交" />
<?php $this->endWidget();?>
<div class="container">
<?php 
Yii::app()->clientScript->registerScriptFile('/js/boss/user_view.js', CClientScript::POS_END);
?>
<ul class="breadcrumb">
    <li><?php if(strtolower($this->action->id) == 'view'){?>查看会员资料<?php }else{?>查看审核详情<?php }?></li>
</ul>
<?php $form=$this->beginWidget('CActiveForm', array(
   'id'=>'userview-search-form',
    'method' => 'get',
    'action' => array($this->action->id),
    'htmlOptions' => array('class'=>'form-inline')
));
?>
    <?php echo $form->dropDownList($model,'type',UserSearchForm::$typeArray);?>
    <?php echo $form->textField($model,'condition',array('placeholder'=>'请输入查询条件'));?>
<input type="submit" class="btn" value="查询" />
<?php $this->endWidget();?>
<div id="info">
<?php echo $this->renderPartial("_{$this->action->id}", array('user'=>$user)); ?>
</div>
</div>
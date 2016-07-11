<?php if($model->status == 1){?>
<div class="rows">
    <div class="lab">已上传视频：</div>
    <div class="rt">
    	<a target="_blank" href="<?php echo $this->createUrl($file->path);?>"><?php echo $file->fileName;?></a>
    </div>
</div>
<?php }else{
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'video-auth-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
    'htmlOptions'=>array(
        'enctype'=>'multipart/form-data'
    )
)); 
$this->useCsrfToken();
?>
<div class="rows">
    <?php if($model->status == 3){?>
    <div>驳回原因:<?php echo $model->comment;?></div>
    <?php }?>
    <div class="lab"><span class="red">*</span><?php echo $form->label($file,'上传的视频'); ?></div>
    <div class="rt">
    	<?php echo $form->fileField($file,'uploadFile'); ?>
    	<?php echo $form->error($file,'uploadFile'); ?>
    </div>
</div>

<input type="submit" class="btn" value="提交" />
<?php $this->endWidget();?>
<?php }?>
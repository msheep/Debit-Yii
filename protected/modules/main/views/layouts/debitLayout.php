<!-- 前台我要借款布局文件 -->
<?php $this->beginContent('/layouts/main'); ?>
<div class="main">
	<?php 
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'htmlOptions' =>array('class'=>'breadcrumb'),
        'inactiveLinkTemplate'=>'{label}',
        'separator' => '<span>&gt;</span>',
        'homeLink' => '当前位置：<a href="'.$this->createUrl('site/index').'">首页</a>',
        'links'=>$this->breadcrumbs
    ));
    ?>
	<div class="left">
		<ul class="sidebar">
			<li><a href="<?php echo $this->createUrl('debit/index');?>">我要借款<span>&gt;&gt;</span></a></li>
			<li><a href="<?php echo $this->createUrl('debit/applydebit');?>">开始借款<span>&gt;&gt;</span></a></li>
			<li><a href="<?php echo $this->createUrl('debit/condition');?>">借款资格<span>&gt;&gt;</span></a></li>
			<li><a href="<?php echo $this->createUrl('assist/calculator');?>">利率说明<span>&gt;&gt;</span></a></li>
			<li><a href="<?php echo $this->createUrl('debit/feeintro');?>">资费说明<span>&gt;&gt;</span></a></li>
		</ul>
	</div><!--/left-->
	<?php echo $content;?>
</div>
<?php $this->endContent(); ?>

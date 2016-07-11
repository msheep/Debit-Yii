<!-- 前台我要理财布局文件 -->
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
			<li <?php if(strtolower($this->action->id) == 'index'){?>class="on"<?php }?>><a href="<?php echo $this->createUrl('invest/index');?>">我要理财<span>&gt;&gt;</span></a></li>
			<li <?php if(strtolower($this->action->id) == 'debitlist'){?>class="on"<?php }?>><a href="<?php echo $this->createUrl('invest/debitlist');?>">借款列表<span>&gt;&gt;</span></a></li>
			<li <?php if(strtolower($this->action->id) == 'debitintro'){?>class="on"<?php }?>><a href="<?php echo $this->createUrl('invest/debitintro');?>">借贷说明<span>&gt;&gt;</span></a></li>
			<li <?php if(strtolower($this->action->id) == 'feeintro'){?>class="on"<?php }?>><a href="<?php echo $this->createUrl('invest/feeintro');?>">资费说明<span>&gt;&gt;</span></a></li>
		</ul>
	</div><!--/left-->
	<?php echo $content;?>
</div>
<?php $this->endContent(); ?>
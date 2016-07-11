<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<?php 
	$this->useGlobalCss();
	 Yii::app()->clientScript->registerCoreScript('jquery');
	$this->useCommonJs();
	?>
     <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <?php if($this->keyword!='') echo '<meta name="keywords" content="'.$this->keyword.'" />';?>
    <?php if($this->des!='') echo '<meta name="description" content="'.$this->des.'" />';?>
</head>

<body>
<?php echo $content;?>
</body>
</html>

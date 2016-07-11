<?php if(isset($notify)){?>
<div>标题:</div>
<div><?php echo $notify->title;?></div>
<div>内容:</div>
<div><?php echo $notify->msg;?></div>
<div>时间:</div>
<div><?php echo $notify->ctime;?></div>
<?php }?>
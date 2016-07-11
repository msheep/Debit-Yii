<?php
    //$message CHtml::encode($message);
    if(isset($code)&&$code!='200') { $this->pageTitle='错误'; }
    $msg = isset($message)?$message:$error['message'];
    if(isset($code)&&$code==500) $msg = '服务器忙...';
    //$msg = urlencode('发生错误: '.htmlspecialchars($msg));
?>
<div class="main">
    <div style="color: #F1851C;font-family: 微软雅黑;font-size: 21px;font-weight: bolder;text-align:center;margin:90px auto">抱歉: <?php echo $msg;?></div>
</div>

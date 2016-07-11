<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
    $this->useGlobalCss();
    $this->useCommonJs();
?>
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<?php if($this->keyword!='') echo '<meta name="keywords" content="'.$this->keyword.'" />';?>
<?php if($this->des!='') echo '<meta name="description" content="'.$this->des.'" />';?>
</head>

<body>
<div class="site_nav">
	<div class="inner">
		<div class="login">
		    <?php if(Yii::app()->user->isGuest){?>
		    <a href="<?php echo Yii::app()->user->loginUrl;?>">登录</a>
			<span class="sp">|</span>
			<a href="<?php echo $this->createUrl('site/register')?>">注册</a>
		    <?php }else{?>
			<span class="user">您好，<a href="<?php echo $this->createUrl('user/profile')?>"><?php echo Yii::app()->user->name;?></a>！</span>
			<a href="<?php echo $this->createUrl('site/logout')?>">【安全退出】</a>
			<span class="sp">|</span>
			<a href="<?php echo $this->createUrl('user/index')?>">我的账户</a>
			<span class="sp">|</span>
			<a href="<?php echo $this->createUrl('usernotify/index')?>">系统消息(0)</a>
			<?php }?>
		</div>
		<p class="collect">
			<a href="#">+ 收藏</a>
			<span class="sp">|</span>
			<a href="#">设为首页</a>
			<a href="#" class="wb">+ 我们微博</a>
			<a href="#">+ 我们微信</a>
		</p>
		<div class="clr"></div>
	</div>
</div><!--/site_nav-->
<div class="header">
	<div class="ntop">
		<div class="logo">
			<a href="<?php echo $this->createUrl('site/index');?>"><img src="/images/logo_03.gif" /></a>
			<span><img src="/images/logo_02.gif" /></span>
		</div>
		<div class="contact">
			<p>传真：0571-56638596</p>
			<p>邮件：support@goldenname.com</p>
			<p>电话：<span>400-6624-724</span></p>
		</div>
		<div class="clr"></div>
	</div><!--/ntop-->
	<div class="navi">
		<s class="l"></s>
		<s class="r"></s>
		<ul id="nav_tabs" class="menu">
			<li class="sub"><a href="<?php echo $this->createUrl('site/index');?>">首页</a></li>
			<li class="sub">
				<a href="<?php echo $this->createUrl('invest/index');?>">我要理财</a>
				<ul class="second">
					<li><a href="<?php echo $this->createUrl('invest/debitList');?>">借款列表</a></li>
					<li><a href="<?php echo $this->createUrl('invest/debitintro');?>">借贷说明</a></li>
					<li><a href="<?php echo $this->createUrl('invest/feeintro');?>">资费说明</a></li>
				</ul>
			</li>
			<li class="sub">
			    <a href="<?php echo $this->createUrl('debit/index');?>">我要借款</a>
			    <ul class="second">
					<li><a href="<?php echo $this->createUrl('debit/applydebit');?>">开始借款</a></li>
					<li><a href="<?php echo $this->createUrl('debit/condition');?>">借款资格</a></li>
					<li><a href="<?php echo $this->createUrl('assist/calculator');?>">利率说明</a></li>
					<li><a href="<?php echo $this->createUrl('debit/feeintro');?>">资费说明</a></li>
				</ul>
			</li>
			<li class="sub"><a href="<?php echo $this->createUrl('user/index')?>">我的账户</a></li>
		</ul>
	</div><!--/navi-->
</div><!--/header-->
<?php echo $content;?>
<div class="footer">
	<div class="help">
		<dl>
			<dt><i class="a"></i>新手入门</dt>
			<dd><a href="#">免费注册</a></dd>
			<dd><a href="#">新手上路</a></dd>
			<dd><a href="#">邀请好友</a></dd>
			<dd><a href="#">如何投资</a></dd>
			<dd><a href="#">如何借款</a></dd>
		</dl>
		<dl>
			<dt><i class="b"></i>我要投资</dt>
			<dd><a href="#">借款列表</a></dd>
			<dd><a href="#">自动投标</a></dd>
			<dd><a href="#">成为VIP</a></dd>
			<dd><a href="#">投资资费</a></dd>
		</dl>
		<dl>
			<dt><i class="c"></i>我要借款</dt>
			<dd><a href="#">实名认证</a></dd>
			<dd><a href="#">成为VIP</a></dd>
			<dd><a href="#">发布借款</a></dd>
			<dd><a href="#">利息计算器</a></dd>
			<dd><a href="#">借款资费</a></dd>
		</dl>
		<dl>
			<dt><i class="d"></i>诚信保障</dt>
			<dd><a href="#">本金保障</a></dd>
			<dd><a href="#">法律政策</a></dd>
			<dd><a href="#">资费说明</a></dd>
		</dl>
		<dl>
			<dt><i class="e"></i>关于借贷宝</dt>
			<dd><a href="#">关于我们</a></dd>
			<dd><a href="#">团队介绍</a></dd>
			<dd><a href="#">联系我们</a></dd>
			<dd><a href="#">招贤纳士</a></dd>
		</dl>
		<div class="clr"></div>
	</div><!--/help-->
	<p class="about">
		<a href="/site/register" target="_blank">免费注册</a>
		<span>|</span>
		<a href="/help/aboutus" target="_blank">关于我们</a>
		<span>|</span>
		<a href="/help/aboutus#exception" target="_blank">免责声明</a>
		<span>|</span>
		<a href="/help/aboutus#service" target="_blank">服务说明</a>
		<span>|</span>
		<a href="/help/aboutus#hr" target="_blank">诚聘英才</a>
		<span>|</span>
		<a href="/help/aboutus#contactus" target="_blank">联系我们</a>
	</p>
	<p class="space">
		苏ICP备13016850号-2 Copyright &copy; 2013 借贷宝！ 十分便民票务淮安有限公司
	</p>
	<div class="serverinfo">
		<a class="a" href="#"></a>
		<a class="b" href="#"></a>
		<a class="c" href="#"></a>
	</div>
</div><!--/footer-->
<script type="text/javascript">
var nav_second = $("#nav_tabs .sub .second");
nav_second.each(function(i){
	$(this).width($(this).width());
});
nav_second.hide();
$("#nav_tabs .sub").unbind().hover(function(){
	$(".second", $(this)).fadeIn(150);
},function(){
	$(".second", $(this)).hide();
})
</script>
</body>
</html>
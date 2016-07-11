<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle='登陆';
$this->keyword=Yii::app()->params['keyword'];
?>
<div class="main">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'login-form',
			'enableAjaxValidation'=>false,
			'enableClientValidation'=>true,
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
		)); 
		$this->useCsrfToken();
		?>
			<div class="log_box">
			<div class="cont">
			<div class="tt">会员登录</div>
			<ul>
				<li class="lab">手机号码/用户名</li>
				<li><?php echo $form->textField($model,'mobile'); ?><span class="red">&#42;</span></li>
				<li><?php echo $form->error($model,'mobile'); ?></li>
			
				<li class="lab">密码</li>
				<li><?php echo $form->passwordField($model,'password'); ?><span class="red">&#42;</span></li>
				<li><?php echo $form->error($model,'password'); ?></li>
				<li class="lab">验证码</li>
				<li>
				    <?php echo $form->textField($model,'captcha'); ?>
<!-- 					<input name="LoginForm[captcha]" id="LoginForm_captcha" type="text" class="ver"> -->
					<span class="pic"><?php $this->widget('CCaptcha',array(
							'id' => 'captcha',
							'buttonLabel'=>'看不清?'
					))?></span>
				</li>
				<li><?php echo $form->error($model,'captcha'); ?></li>
				<li class="forget">
					<p class="red"><a href="/site/resetpwd" class="gray">忘记密码?</a></p>
					<div>
						<input type="submit" name="yt0" value="登&nbsp;&nbsp;&nbsp;&nbsp;录" class="btn">
					</div>
				</li>
	        </ul>	
			</div>
		</div>
			<?php $this->endWidget(); ?>	
	<div class="legal_rights">
		<div class="tt"><img src="/images/legal.jpg" width="300" height="36" /></div>
		<dl>
			<dd>专业的票务平台为您提供安全可靠的订票服务！</dd>
			<dd>尊享去买呀完善贴心的信息关怀服务！</dd>
			<dd>支持火车站预售期前订票，确保第一时间订票！</dd>
		</dl>
		<div>
			<a class="btn" href="/site/register">立即注册</a>
		</div>
	</div>
	<div class="clr"></div>
</div><!--/main-->

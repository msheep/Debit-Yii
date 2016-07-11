<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle='注册';
$this->useGlobalCss();
?>
	<div class="main register">
		<?php $form=$this->beginWidget('CActiveForm', array(
		    	'id'=>'register-form',
		    	'enableAjaxValidation'=>true,
		    	'enableClientValidation'=>true,
		        'clientOptions'=>array(
		        	'validateOnSubmit'=>true
		        ),
		)); 
		$this->useCsrfToken();
		?>
		
		    <div class="title">
		    	<span class="man">注册会员</span>
		    	<span class="r">已经是注册用户了？<a href="/site/login">直接登录</a></span>
		    </div>
				<div class="rows">
					<div class="lab"><span class="red">*</span><?php echo $form->labelEx($model,'userName'); ?></div>
					<div class="rt">
						<?php echo $form->textField($model,'userName'); ?>
						<?php echo $form->error($model,'userName',array()); ?>
					</div>
				</div>
				
				<div class="rows">
					<div class="lab"><span class="red">*</span><?php echo $form->labelEx($model,'password'); ?></div>
					<div class="rt">
						<?php echo $form->passwordField($model,'password'); ?>
						<?php echo $form->error($model,'password',array(),false); ?>
					</div>
				</div>
				
				<div class="rows">
					<div class="lab"><span class="red">*</span><?php echo $form->labelEx($model,'rpassword'); ?></div>
					<div class="rt">
						<?php echo $form->passwordField($model,'rpassword'); ?>
						<?php echo $form->error($model,'rpassword',array(),false); ?>
					</div>
				</div>
				
				<div class="rows">
					<div class="lab"><span class="red">*</span><?php echo $form->labelEx($model,'mobile'); ?></div>
					<div class="rt">
						<?php echo $form->textField($model,'mobile'); ?>
						<?php echo $form->error($model,'mobile',array()); ?>
					</div>
				</div>
				
				<div class="rows">
					<div class="lab"><span class="red">*</span><?php echo $form->labelEx($model,'verifyCode'); ?></div>
					<div class="rt">
						<?php echo $form->textField($model,'verifyCode'); ?>
						<?php echo $form->error($model,'verifyCode',array()); ?>
						<input id="sendsms" type="button" value="获取验证码" class="verify"/>
					</div>
				</div>
				<!--<div class="gray">我们已经向您的手机发送验证短信，如果在一分钟内没有收到短信 ，请重新注册</div>-->
		
				<div class="rows">
					<div class="lab">&nbsp;</div>
					<div class="rt">
						<div class="agree">				
							<div class="hd">去买呀 服务协议</div>
							<p class="b">一、本站服务条款的确认和接纳</p>
							<p>本站的各项电子服务的所有权和运作权归本站。本站提供的网上代购火车票，配送火车票的服务将完全按照其发布的服务条款和操作规则严格执行。用户必须完全同意所有服务条款并完成注册程序，才能成为本站的正式用户。 用户确认：本协议条款是处理双方权利义务的当然约定依据，除非违反国家强制性法律，否则始终有效。</p>
							<p class="b">二、服务简介</p>
							<p>本站运用自己的操作系统通过国际互联网络为用户提供网络服务。同时，用户必须：</p>
							<p>(1)自行配备上网的所需设备，包括个人电脑、调制解调器或其它必备上网装置。</p>
							<p>(2)自行负担个人上网所支付的与此服务有关的电话费用、网络费用。</p>
							<p>基于本站所提供的网络服务的重要性，用户应同意：</p>
							<p>(1)提供详尽、准确的个人资料。</p>
							<p>(2)不断更新注册资料，符合及时、详尽、准确的要求。</p>
							<p>本站不公开用户的姓名、地址、电子邮箱和笔名， 除以下情况外：</p>
							<p>(1)用户授权本站透露这些信息。</p>
							<p>(2)相应的法律及程序要求本站提供用户的个人资料。</p>
							<p>如果用户提供的资料包含有不正确的信息，本站保留结束用户使用网络服务资格的权利。</p>
							<p class="b">三、价格和费用支付、退款</p>
							<p>去买呀是一个领先的电子商务、专业致力于网上火车票服务平台, 提供全国列车时刻表查询、火车票价格查询、火车票价格、列车票、火车票查询等服务。</p>
							<p class="b">五、服务条款的修改</p>
							<p>本站将可能不定期的修改本用户协议的有关条款，一旦条款及服务内容产生变动，本站将会在重要页面上提示修改内容。 如果不同意本站对条款内容所做的修改，用户可以主动取消获得的网络服务。如果用户继续使用本站的服务，则视为接受服务条款的变动。网站在修改服务条款之前，会在网站的显著位置进行提前告知。</p>
							<p class="b">六、用户隐私制度</p>
							<p>尊重用户个人隐私是本站的一项基本政策。所以，作为对以上第二点人注册资料分析的补充，本站一定不会在未经合法用户授权时公开、编辑或透露其注册资料及保存在本站中的非公开内容，除非有法律许可要求或本站在诚信的基础上认为透露这些信件在以下四种情况是必要的：</p>
							<p>(1)遵守有关法律规定，遵从本站合法服务程序。</p>
							<p>(2)保持维护本站的商标所有权。</p>
							<p>(3)在紧急情况下竭力维护用户个人和社会大众的隐私安全。</p>
							<p>(4)符合其它相关的要求。</p>
							<p class="b">七、用户的帐号，密码和安全性</p>
							<p>用户一旦注册成功，成为本站的合法用户，将得到一个密码和用户名。您可随时根据指示改变您的密码。因用户个人原因导致用户名密码泄露而产生的问题由用户负全部责任。另外，每个用户都要对以其用户名进行的所有活动和事件负全责。用户若发现任何非法使用用户帐号或存在安全漏洞的情况，请立即通告本站。</p>
							<p class="b">八、拒绝提供担保</p>
							<p>用户个人对网络服务的使用承担风险。本站对此不作任何类型的担保，不论是明确的或隐含的，但是不对商业性的隐含担保、特定目的和不违反规定的适当担保作限制。</p>
							<p class="b">九、有限责任</p>
							<p>除法律规定的违约及侵权责任外，本站对任何直接、间接、偶然、特殊及继起的损害不负责任，这些损害可能来自：不正当使用网络服务，在网上购买商品或进行同类型服务，在网上进行交易，非法使用网络服务或用户传送的信息有所变动。这些行为都有可能会导致本站的形象受损，所以本站事先提出这种损害的可能性。</p>
							<p class="b">十、对用户信息的存储和限制</p>
							<p>本站不对用户所发布信息的删除或储存失败负责。本站有判定用户的行为是否符合本站服务条款的要求和精神的保留权利 ，如果用户违背了服务条款的规定，本站有中断对其提供网络服务的权利。此权利以不违反法律规定为限。</p>
							<p class="b">十一、用户管理</p>
							<p>用户单独承担发布内容的责任。用户对服务的使用是根据所有适用于本站的国家法律、地方法律和国际法律标准的。</p>
							<p>用户必须遵循：</p>
							<p>(1)从中国境内向外传输技术性资料时必须符合中国有关法规。</p>
							<p>(2)使用网络服务不作非法用途。</p>
							<p>(3)不干扰或混乱网络服务。</p>
							<p>(4)遵守所有使用网络服务的网络协议、规定、程序和惯例。</p>
							<p>用户须承诺不传输任何非法的、骚扰性的、中伤他人的、辱骂性的、恐性的、伤害性的、庸俗的，淫秽等信息资料。另 外，用户也不能传输何教唆他人构成犯罪行为的资料；不能传输助长国内不利条件和涉及国家安全的资料；不能传输任何 不符合当地法规、国家法律和国际法律的资料。未经许可而非法进入其它电脑系统是禁止的。 若用户的行为不符合以上提 到的服务条款，本站将作出独立判断立即取消用户服务帐号。用户需对自己在网上的行为承担法律责任。用户若在本站上 散布和传播反动、色情或其它违反国家法律的信息，本站的系统记录有可能作为用户违反法律的证据。</p>
							<p class="b">十二、保障用户</p>
							<p>同意保障和维护本站全体成员的利益，负责支付由用户使用超出业务范围引起的律师费用，违反服务条款的损害补偿费用等。</p>
							<p class="b">十三、结束服务</p>
							<p>用户或本站可随时根据实际情况中断一项或多项网络服务。用户对后来的条款修改有异议，或对本站的服务不满，可以行使如下权利：</p>
							<p>(1)停止使用本站的网络服务。</p>
							<p>(2)通告本站停止对该用户的服务。</p>
							<p>(3)结束服务后，用户使用网络服务的权利马上中止。从那时起，用户没有权利，本站也没有义务传送任何未处理的信息或未完成的服务给户或第三方。</p>
							<p class="b">十四、通告</p>
							<p>所有发给用户的通告都可通过重要页面的公告或电子邮件或常规的信件传送。用户协议条款的修改、服务变更、或其它重 要事件的通告都会以此形式进行。</p>
							<p class="b">十五、网络服务内容的所有权</p>
							<p>本站定义的网络服务内容包括：文字、软件、声音、图片、录象、图表、广告中的全部内容；电子邮件的全部内容；本站 为用户提供的其它信息。所有这些内容受版权、商标、标签和其它财产所有权法律的保护。所以，用户只能在本站和广告商授权下才能使用这些内容，而不能擅自复制、再造这些内容、或创造与内容有关的派生产品。本站所有的文章版权归原文作 者和本站共同所有，任何人需要转载本站的文章，必须征得原文作者或本站授权。</p>
							<p class="b">十六、责任限制</p>
							<p>如因不可抗力或其它本站无法控制的原因使本站系统崩溃或无法正常使用导致网上交易无法完成或丢失有关的信息、记录等，本站不承担责任。但是本站会尽可能合理地协助处理善后事宜，并努力使客户免受经济损失。 除了本站的使用条件中规定的其它限制和除外情况之外，在中国法律法规所允许的限度内，对于因交易而引起的或与之有关的任何直接的、间接的、 特殊的、附带的、后果性的或惩罚性的损害，或任何其它性质的损害，本站、本站的董事、管理人员、雇员、代理或其它代表在任何情况下都不承担责任。本站的全部责任，不论是合同、保证、侵权(包括过失)项下的还是其它的责任，均不超过您所购买的与该索赔有关的商品价值额。</p>
							<p class="b">十七、法律管辖和适用</p>
							<p>本协议的订立、执行和解释及争议的解决均应适用中国法律。</p>
							<p>如发生本站服务条款与中国法律相抵触时，则这些条款将完全按法律规定重新解释，而其它合法条款则依旧保持对用户产生法律效力和影响。</p>
							<p>本协议的规定是可分割的，如本协议任何规定被裁定为无效或不可执行，该规定可被删除而其余条款应予以执行。</p>
							<p>如双方就本协议内容或其执行发生任何争议，双方应尽力友好协商解决；协商不成时，任何一方均可向本站所在地的人民法院提起诉讼。</p>
						</div>
						<input type="submit" class="btn" value="同意服务协议并注册" />
					</div>
				</div>
				<?php $this->endWidget(); ?>
	</div>
<script type="text/javascript">
$(document).ready(function(){
	$(".gray").hide();
	/*
	$('#RegisterForm_mobile').blur(function(){
		var mobile = $(this).val();
		var regex = /^(1(([35][0-9])|(47)|[8][012356789]))\d{8}$/;
		$(this).parent().find(".errorMessage").remove();
		if(regex.test(mobile) == false) {
			$(this).parent().append('<div class="errorMessage" id="RegisterForm_mobile_em_" style="">请填写正确的手机号码</div>');
			return false;}
	})
	*/
	$('#sendsms').bind('click', function(){
		var mobile = $.trim($('#RegisterForm_mobile').val());
		if (mobile == '') {alert('手机号码不能为空！');return false;}	
		var regex = /^(1(([35][0-9])|(4[57])|[8][012356789]))\d{8}$/;
		if(regex.test(mobile) == false) {alert('手机号码有误！');return false;}
		var tokenValue = $('#tokenId').val();
		$.ajax({ 
			async:true, 
			cache:false, 
			timeout: 5000, //超时时间：4秒
			dataType: 'json', 
			type:"POST", 
			url:"/site/sendmobilecode", 
		    data:{mobile: mobile, <?php echo Yii::app()->request->csrfTokenName?>: tokenValue},
		    beforeSend: function(XMLHttpRequest){
		    	$('#sendsms').attr('disabled', 'disabled');
			},
		    error:function(jqXHR, textStatus, errorThrown){ 
		        alert("网络错误");
		        $('#sendsms').attr('disabled', false);
		    }, 
		    success:function(response){  
				 var msg;
				 if (response == '10'){
					alert('令牌错误, 非法请求!');
					return;
				} 
				 if (response.status == '1') {
					msg = '发送成功, 请查看手机短信！';
					$(".gray").show();
				}else if (response.status == '102'){
					msg = '发送验证码失败！';
				}else if (response.status == '101'){
					{ msg = '该手机已经注册！'; $('#sendsms').attr('disabled', false); }
				}else{ msg = '发送短信失败！';}
				alert(msg);
		   } 
	    }); 
	});
});
</script>

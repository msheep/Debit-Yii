 //*********** 借贷申请 	start ************//
function applyDebit(){
	if($('.errorMessage:visible').size() > 0){
		return false;
	}
	var cat = $.trim($('#Debit_cat').val());
	var title = $.trim($('#Debit_title').val()); 
	var money = $.trim($('#Debit_debitMoney').val());
	var rate = $.trim($('#Debit_debitRate').val());
	var debitDeadline = $.trim($('#Debit_debitDeadline').val());
	var invitDeadline = $.trim($('#Debit_invitDeadline').val());
	var purpose = $.trim($('#Debit_debitPurpose').val());

	if(title.length == 0){
		this.addError('Debit_title','借款标题不能为空！');
	}
	
	this.checkRealDebitMoney();
	var money = $.trim($('#Debit_debitMoney').val());
	
	if(money >= 20000){
		this.checkMinMoney('Debit_debitMinMoney',10000,'Debit_debitMoney');
	}
	var minMoney = $.trim($('#Debit_debitMinMoney').val());
	
	if(rate.length == 0){
		this.addError('Debit_debitRate','借款利率不能为空！');
	}else{
		if(!$.isNumeric(rate)){
			this.addError('Debit_debitRate','借款利率填写不正确！');
		}else if(rate>25){
			this.addError('Debit_debitRate','借款利率不能超过25');
		}else if(rate<=0){
			this.addError('Debit_debitRate','借款利率必须大于0！');
		}else{
			var reg = /^\d+(\.\d{1,2})?$/;
			if(!reg.test(rate)){
				this.addError('Debit_debitRate','借款利率保留小数点后两位！');
			}
		}
	}

	if(invitDeadline.length == 0){
		this.addError('Debit_invitDeadline','招标时限不能为空！');
	}else{
		if(isNaN(invitDeadline)){
			this.addError('Debit_invitDeadline','招标时限填写不正确！');
		}else if(invitDeadline>30){
			this.addError('Debit_invitDeadline','招标时限不能超过30天！');
		}else if(invitDeadline<1){
			this.addError('Debit_invitDeadline','招标时限至少为1天！');
		}
	}
	
	if(purpose.length == 0){
		this.addError('Debit_debitPurpose','贷款用途不能为空！');
	}

	if($('.errorMessage:visible').size() > 0){
		return false;
	}

	money = parseInt(money);
	rate = parseFloat(rate);
	debitDeadline = parseInt(debitDeadline);
	invitDeadline = parseInt(invitDeadline);

	var catName = '';
	if(cat == 1){
		catName = '域名贷';
	}else if(cat == 2){
		catName = '房产贷';
	}else if(cat == 2){
		catName = '车辆贷';
	}

	//四舍五入求手续费
	var charge = this.round2(money * debitDeadline *0.5 / 100,0);
	var monthRate = rate / 100 / 12;
	var monthRepay = money * monthRate * Math.pow(1 + monthRate, debitDeadline) / (Math.pow(1 + monthRate, debitDeadline) - 1);

	$('#Debit_fee').val(charge);

	var list = '';
	list += '<table style="width:600px">';
	list += '<tr>';
	list += '<th>期数</th>';
	list += '<th>月还款本息</th>';
	list += '<th>月还款本金</th>';
	list += '<th>利息</th>';
	list += '<th>余额</th>';
	list += '</tr>';
	var restMoney = money;
	for(var i=1; i<=debitDeadline; i++){
		list += '<tr>';
		list += '<td>第'+ i +'期</td>';
		list += '<td>¥'+ this.round2(monthRepay,2) +'</td>';
		list += '<td>¥'+ this.round2(monthRepay - restMoney * monthRate,2) +'</td>';
		list += '<td>¥'+ this.round2(restMoney * monthRate,2) +'</td>';
		list += '<td>¥'+ this.round2(monthRepay * (debitDeadline - i),2) +'</td>';
		list += '</tr>';
		restMoney = restMoney - (monthRepay - restMoney * monthRate);
	}
	list += '</table>';

	var totalRepay = this.round2(monthRepay * debitDeadline,2);
	var content = '';
	content += '借款产品：' + catName + '<br>';
	content += '借款金额：' + money + '元<br>';
	if(money >= 20000){
		content += '最低借款金额：' + minMoney + '元<br>';
	}
	content += '借款期限：' + debitDeadline + '个月<br>';
	content += '招标时限：' + invitDeadline + '天<br>';
	content += '手续费：' + charge + '元<br>';
	content += '年利率：' + rate + '%<br>';
	content += '月利率：' + this.round2(monthRate * 100,2) + '%<br>';
	content += '月还款额：' + this.round2(monthRepay,2) + '元<br>';
	content += '还款总额：' + totalRepay + '元<br>';
	content += list;
	content += '<input type="button" value="确认，立即借款" onclick="submitDebit()">';
	art.dialog({
		id:'debit-apply',
		title:"借款申请",
	    content: content,
	    fixed:true,
	    drag: false,
	    resize: false,
	    lock:true,
	    opacity: 0.50
	});
 }

 function submitDebit(){
 	art.dialog({id:'debit-apply'}).close();
 	var cat = $.trim($('#Debit_cat').val());
	var title = $.trim($('#Debit_title').val()); 
	var money = $.trim($('#Debit_debitMoney').val());
	var minMoney = $.trim($('#Debit_debitMinMoney').val());
	var rate = $.trim($('#Debit_debitRate').val());
	var debitDeadline = $.trim($('#Debit_debitDeadline').val());
	var invitDeadline = $.trim($('#Debit_invitDeadline').val());
	var purpose = $.trim($('#Debit_debitPurpose').val());
	var fee = $.trim($('#Debit_fee').val());
	if(cat == 1){
		var url = 'domain';
	}else if(cat == 2){
		var url = 'property';
	}else if(cat == 3){
		var url = 'car';
	}
	if(minMoney.length == 0){
		minMoney = 0;
	}
	$.ajax({
		type:'post',
		url:'/debit/submitDebit',
		data:'cat='+cat+'&title='+title+'&debitMoney='+money+'&debitRate='+rate+
			 '&debitDeadline='+debitDeadline+'&invitDeadline='+invitDeadline+'&debitPurpose='+purpose+'&debitMinMoney='+minMoney+'&fee='+fee,
		success:function(data){
			if(data > 0){
				art.dialog({
					title:"资产认证",
				    content: '借款申请已提交，请进行第三步抵押资产认证！',
				    okValue: '确定',
				    ok:function(){
				    	window.location.href = '/debit/'+url+'/'+data;
				    	return true;
				    },
				    cancelValue:'暂不认证',
				    cancel:function(){
				    	window.location.href = '/debit/applyDebit';
				    	return true;
				    },
				    opacity: 0.50,
				    resize: false,
				    fixed:true,
				    drag: false
				});
				return true;
			}else if(data == -1){
				alert('信息提交不完整或者不合法，请仔细填写后再提交！');
				return false;
			}else if(data == 0 || data == -2){
				alert('网络错误，请稍候再试！');
				return false;
			}
		}
	})
 }
 //*********** 借贷申请 	end ************//
 

 //*********** 房产贷 	start ************//
 function changePropertyType(){
	var type = $.trim($('#DebitProperty_type').val());
	if(type == 1){
		$('.house').show();
		$('.house_type').show();
		$('.business').hide();
	}else if(type == 2){
		$('.house').hide();
		$('.house_type').hide();
		$('.business').show();
		$('.shop').show();
		$('.office').hide();
		$('.plant').hide();
	}else if(type == 3){
		$('.house').show();
		$('.house_type').hide();
		$('.business').hide();
	}
 }
 
 function changeCommercailType(){
	var type = $.trim($('#DebitPropertyBusiness_businessType').val());
	if(type == 1){
		$('.shop').show();
		$('.office').hide();
		$('.plant').hide();
	}else if(type == 2){
		$('.shop').hide();
		$('.office').show();
		$('.plant').hide();
	}else if(type == 3){
		$('.shop').hide();
		$('.office').hide();
		$('.plant').show();
	}
 }
 
 function changeLoan(){
	 if($('#DebitProperty_isLoan_0').attr('checked') == 'checked'){
		 $('.isloan').hide();
	 }else if($('#DebitProperty_isLoan_1').attr('checked') == 'checked'){
		 $('.isloan').show();
	 }
 }
 
 function changeRent(){
	 if($('#DebitProperty_isRent_0').attr('checked') == 'checked'){
		 $('.isrent').hide();
	 }else if($('#DebitProperty_isRent_1').attr('checked') == 'checked'){
		 $('.isrent').show();
	 }
 }
 
 function changeFitment(){
	 var type = $.trim($('#DebitPropertyHouse_fitment').val());
	 if(type != 5){
		 $('.fitment').show();
	 }else{
		 $('.fitment').hide();
	 }
 }
 
 function changeHateFacility(){
	 if($('#DebitPropertyHouse_hateFacility_2').attr('checked') == 'checked'){
		 $('.hate').hide();
	 }else{
		 $('.hate').show();
	 }
 }
 
 function addowner(id){
	 var oldNum = $('.'+id+'_relation').size();
	 num = oldNum + 1;
	 var html = '<div id="'+id+'_'+num+'">';
	 html += '<select name="DebitProperty['+id+']['+num+'][]" class="'+id+'_relation" >';
	 html += '<option value="1">夫妻</option>';
	 html += '<option value="2">子女</option>';
	 html += '<option value="3">父母</option>';
	 html += '</select>';
	 html += '<input name="DebitProperty['+id+']['+num+'][]" type="text" class="'+id+'_name">';
	 html += '<a href="javascript:void(0)" class="delete_owner">删除</a>';
	 html += '</div>';
	 $('#'+id+'_'+oldNum).after(html);
 }
 
 function addFile(id){
	 var html = '<div>';
	 html += '<input name="File['+id+'][]" type="file" class="file_path">';
	 html += '<a href="javascript:void(0)" class="delete_owner">删除</a>';
	 html += '</div>';
	 $('#'+id).after(html);
 }
 
 $('.delete_owner').live('click',function(){
	 $(this).parent().remove();
 })
 //*********** 房产贷 	end **************//
 
 
 
 //*********** 车辆贷 	start ************//
 function addCar(){
	$('#addCar').show();
	$('#selectCar').hide();
 }
 
 function selectCar(){
	$('#addCar').hide();
	$('#selectCar').show();
 }
 
 function checkStatus(id){
	if($('#'+id).attr('checked') == 'checked'){
		$('#'+id).parent('.radio').parent('span').siblings('.addPhoto').show();
	}else{
		$('#'+id).parent('.radio').parent('span').siblings('.addPhoto').hide();
	}
 }
 
 //*********** 车辆贷 	end **************//
 
 
 function checkProduct(type){
	 var product = '';
	 var id = '';
	 //检验域名
	 if(type == 'domain'){
		 product = $.trim($('#DebitDomain_domain').val());
		 id = 'DebitDomain_domain';
		 if(product.length == 0){
			 this.addError(id,'域名不能为空！');
			 return false;
		 }
		 var reg = /^(?:http:\/\/)?(www.)?[0-9a-zA-Z]+_?[0-9a-zA-Z]+.(?:com(?:.cn)?|cn|net|org|info|mobi)$/;
		 if(reg.test(product) == false){
			 this.addError(id,'域名填写错误！');
			 return false;
		 }
	 //检验房产
	 }else if(type == 'property'){
		 product = $.trim($('#DebitProperty_propertyCertificateId').val());
		 id = 'DebitProperty_propertyCertificateId';
		 if(product.length == 0){
			 this.addError(id,'房产证号不能为空！');
			 return false;
		 }
	 }else if(type == 'car'){
		 
	 }
	 $.ajax({
		type:'post',
		url:'/debit/checkProduct',
		data:'type='+type+'&product='+product,
		success:function(data){
			if(data == -1){
				addError(id,'网络异常！');
				return false;
			}else if(data == 1){
				addError(id,'该域名已经存在！');
				return false;
			}else if(data == 0){
				$('#'+id).parent().removeClass('error');
				$('#'+id).siblings('.errorMessage').hide();
				return true;
			}
		}
	 })
 }

 function submitDebitProduct(type){
	var data = '';
	var debitId = $.trim($('#debitId').val());
	if(type == 'domain'){
		var domain = $.trim($('#DebitDomain_domain').val());
		var owner = $.trim($('#DebitDomain_owner').val()); 
		var serviceProvider = $.trim($('#DebitDomain_serviceProvider').val());
		var deadLine = $.trim($('#DebitDomain_deadLine').val());
		this.checkProduct('domain');
		if(owner.length == 0){
			this.addError('DebitDomain_owner','域名所有者不能为空！');
		}
		if(serviceProvider.length == 0){
			this.addError('DebitDomain_serviceProvider','服务提供商不能为空！');
		}
		if(deadLine.length == 0){
			this.addError('DebitDomain_deadLine','域名到期时间不能为空！');
		}
		getData = 'type=domain&domain='+domain+'&owner='+owner+'&serviceProvider='+serviceProvider+'&deadLine='+deadLine+'&debitId='+debitId;
	}else if(type == 'property'){
		var provinceId = $.trim($('#DebitProperty_provinceId').val());
		var cityId = $.trim($('#DebitProperty_cityId').val()); 
		var areaId = $.trim($('#DebitProperty_areaId').val());
		var address = $.trim($('#DebitProperty_address').val());
		var area = $.trim($('#DebitProperty_area').val());
		var year = $.trim($('#DebitProperty_year').val());
		var propertyCertificateId = $.trim($('#DebitProperty_propertyCertificateId').val());
		if(address.length == 0){
			this.addError('DebitProperty_address','地址详情不能为空！');
		}
		if(area.length == 0){
			this.addError('DebitProperty_area','建筑面积不能为空！');
		}else{
			if(!$.isNumeric(area)){
				this.addError('DebitProperty_area','建筑面积不正确！');
			}else if(area <= 0){
				this.addError('DebitProperty_area','建筑面积必须大于0！');
			}else{
				var reg = /^\d+(\.\d{1,2})?$/;
				if(!reg.test(area)){
					this.addError('DebitProperty_area','借款利率保留小数点后两位！');
				}
			}
		}
		this.checkProduct('property');
		getData = 'type=property&provinceId='+provinceId+'&cityId='+cityId+'&areaId='+areaId+'&address='+address+'&area='+area+'&year='+year+'&propertyCertificateId='+propertyCertificateId+'&debitId='+debitId;
	}else if(type == 'car'){
		
	}
	if($('.errorMessage:visible').size() > 0){
		return false;
	}
	$.ajax({
		type:'post',
		url:'/debit/submitProductDebit',
		data: getData,
		success:function(data){
			if(data == -4 || data == 0){
				alert('网络错误，请稍候再试！');
				return false;
			}else if(data == -1){
				alert('您没有权限操作该借贷申请！');
				return false;
			}else if(data == -3 ){
				alert('信息提交不完整或者不合法，请仔细填写后再提交！');
				return false;
			}else if(data == 2){
				alert('该抵押产品已经存在！');
				return false;
			}else if(data == -5){
				alert('您已经提交该借贷申请！');
				return false;
			}else if(data == 1){
				art.dialog({
					title:'',
				    content: '您的抵押申请已提交，请等待系统估价、审核您的贷款申请！',
				    okValue:'确定',
				    ok:function(){
				    	window.location.replace(window.location.href);
				    	return true;
				    },
				    fixed:true,
				    drag: false,
				    resize: false,
				    lock:true,
				    opacity: 0.50
				});
			}
		}
	})
 }

 
 //*********** 借款列表	start ************//
 function rejectPublish(id,money,result){
	 if(result == 0){
		 art.dialog({
			title:'拒绝发布原因',
		    content: '<textarea id="reject-reason"></textarea>',
		    okValue:'确定',
		    ok:function(){
		    	var reason = $.trim($('#reject-reason').val());
		    	if(reason.length == 0){
		    		addError('reject-reason','请填写拒绝发布原因！');
		    		return false;
		    	}else{
		    		agreePublish(id,money,result,reason);
		    		removeError('reject-reason');
		    	}
		    },
		    fixed:true,
		    drag: false,
		    resize: false,
		    lock:true,
		    opacity: 0.50
		});
	 };
 }
 function agreePublish(id,money,result,reason){
 	 $.ajax({
		type:'post',
		url:'/accountCenter/agreePublish',
		data: 'id='+id+'&money='+money+'&result='+result+'&reason='+reason,
		success:function(data){
			if(data == 1 && result == 1){
				$('#operate_'+id).html("<a href=''>查看</a>");
				$('#status_'+id).html('0.00%');
				art.dialog({
					title:'操作成功',
				    content: '您的借贷申请已经成功发布！',
				    okValue:'确定',
				    ok:function(){
				    	return true;
				    },
				    fixed:true,
				    drag: false,
				    resize: false,
				    lock:true,
				    opacity: 0.50
				});
			}else if(data == 1 && result == 0){
				$('#operate_'+id).html("<a href=''>查看</a>");
				$('#status_'+id).html('用户驳回');
				$('#status_'+id).parent().remove();
				art.dialog({
					title:'操作成功',
				    content: '您的借贷申请将不会在网站上显示，具体请到近期完成列表查看！',
				    okValue:'确定',
				    ok:function(){
				    	return true;
				    },
				    fixed:true,
				    drag: false,
				    resize: false,
				    lock:true,
				    opacity: 0.50
				});
			}else if(data == 0){
				art.dialog({
					title:'操作失败',
				    content: '网络错误，请稍候再试！',
				    okValue:'确定',
				    ok:function(){
				    	return true;
				    },
				    fixed:true,
				    drag: false,
				    resize: false,
				    lock:true,
				    opacity: 0.50
				});
			}else if(data == -1){
				art.dialog({
					title:'操作失败',
				    content: '数据传输错误，请稍候再试！',
				    okValue:'确定',
				    ok:function(){
				    	return true;
				    },
				    fixed:true,
				    drag: false,
				    resize: false,
				    lock:true,
				    opacity: 0.50
				});
			}
		}
	 })
 }
 //*********** 借款列表	end ************//
 
 
 //*********** 利率计算器	start ************//
 function calculate(money,time,rate,result){
	 this.checkMoney(money,10000);
	 this.checkRate(rate);
	 if($('.errorMessage:visible').size() > 0){
		 return false;
	 }
	 var money = $.trim($('#'+money).val());
	 var time = $.trim($('#'+time).val());
	 var rate = $.trim($('#'+rate).val());
	 var monthRate = rate / 100 / 12;
	 var monthRepay = money * monthRate * Math.pow(1 + monthRate, time) / (Math.pow(1 + monthRate, time) - 1);
	 var html = '';
	 html += '<p><label>年利率：</label><span>'+ rate +'%</span></p>';
	 html += '<p><label>月利率：</label><span>'+ this.round2(monthRate * 100,2) +'%</span></p>';
	 html += '<p><label>月还款额：</label><span>¥'+ this.round2(monthRepay,2) +'元</span></p>';
	 html += '<p><label>还款总额：</label><span>¥'+ this.round2(monthRepay * time,2) +'元</span></p>';
	 $('#'+result).html(html); 
 }
 //*********** 利率计算器	end ************//
 
 
 //*********** 投标 	start ************//
 //投标
 function debit(debitId,cashMoney){
	var title = $.trim($('#debit_title').text()); 
	var money = $.trim($('#debit_money').text());
	var deadline = $.trim($('#debit_deadline').text());
	var rate = $.trim($('#debit_rate').text());
	var progress = $.trim($('#debit_progress').text());
	var content = '';
	var percent = progress / money * 100;
	content += '<p>' + title + '</p>';
	content += '<p>借款金额：' + money + '</p>';
	content += '<p>借款期限：' + deadline + '</p>';
	content += '<p>年利率：' + rate + '%</p>';
	content += '<p>招标进度：已完成' + this.round2(percent,2) + '%，还需要' + (money - progress) + '元</p>';
	content += '<p>投标金额：<a href="javascript:void(0)" onclick="getMoney(' + "'finance_money', 100 , 'down'" + ')">向下</a>';
	content += '<input type="input" id="finance_money" name="finance_money" value="100" onblur="checkFinanceMoney(' + "'finance_money'," + (money - progress) + ',' + cashMoney + ')">';
	content += '<a href="javascript:void(0)" onclick="getMoney(' + "'finance_money', 100, 'up'" + ')">向上</a></p>';
	art.dialog({
		id:'debit-apply',
		title:"我要借出",
	    content: content,
	    fixed:true,
	    drag: false,
	    resize: false,
	    lock:true,
	    opacity: 0.50,
	    okValue: '确定借出',
	    ok:function(){
	    	checkFinanceMoney('finance_money',(money - progress));
	    	if($('.errorMessage:visible').size() > 0){
		   		return false;
		   	}
	    	var finance = $.trim($('#finance_money').val());
	    	$.ajax({
				type:'post',
				url:'/invest/finance',
				data:'money='+finance+'&debitId='+debitId,
				success:function(data){
					if(data == 1){
						alert('投资成功！');
						window.location.replace(window.location.href);
						return true;
					}else{
						alert('操作失败，请稍候再试！');
						return false;
					}
				}
			 })
	    },
	    cancelValue:'再想想',
	    cancel:function(){
	    }
	});
 }
 
 function checkFinanceMoney(id,debit,cashMoney){
	 var ex = /^\d+$/;
	 var money = $.trim($('#'+id).val());
	 money = Math.ceil((money < 1 ? 1 : money) / 100) * 100;
	 $('#'+id).val(money);
	 if(money > debit){
		 this.addError(id,'投标金额不能大于借款！');
		 return false;
	 }else if(money > cashMoney){
		 this.addError(id,'余额不足，请<a href="">充值</a>！');
		 return false;
	 }else{
		 this.removeError(id);
		 return true;
	 }
	
 }
 //*********** 投标 	end ************//
 
 //*********** 公共 	start ************//
 function checkMoney(id,min){
	 var ex = /^\d+$/;
	 var money = $.trim($('#'+id).val());
	 money = Math.ceil((money < 1 ? 1 : money) / 100) * 100;
	 if(money < min){
		 money = min;
	 }
	 $('#'+id).val(money);
 }
 
 function checkMinMoney(id,min,max){
	 var ex = /^\d+$/;
	 var money = $.trim($('#'+id).val());
	 var maxMoney = $.trim($('#'+max).val());
	 if(money.length > 0){
		 money = Math.ceil((money < 1 ? 1 : money) / 100) * 100;
		 if(money <= maxMoney){
			 if(money >= min){
				 $('#'+id).val(money);
			 }else{
				 $('#'+id).val(min);
			 } 
		 }
		 if(money > maxMoney){
			 this.addError(id,'最低借款金额不能高于借款金额！');
		 }else{
			 this.removeError(id);
		 }
	 }
 }
 
 function checkRealDebitMoney(){
	 var ex = /^\d+$/;
	 var money = $.trim($('#Debit_debitMoney').val());
	 money = Math.ceil((money < 1 ? 1 : money) / 100) * 100;
	 if(money < 10000){
		 money = 10000;
	 }
	 $('#Debit_debitMoney').val(money);
 }
 
 function checkDebitMoney(){
	 var ex = /^\d+$/;
	 var money = $.trim($('#Debit_debitMoney').val());
	 money = Math.ceil((money < 1 ? 1 : money) / 100) * 100;
	 if(money < 10000){
		 money = 10000;
	 }
	 if(money >= 20000){
		 var minMoney = money * 0.5;
		 minMoney = Math.ceil(( minMoney < 1 ? 1 : minMoney) / 100) * 100;
		 $('#Debit_debitMinMoney').val(minMoney);
		 $('#minDebit').show();
	 }else{
		 $('#minDebit').hide();
	 }
	 $('#Debit_debitMoney').val(money);
 }
 
 function getMoney(id,min,kind){
	 var money = $.trim($('#'+id).val());
	 money = Math.ceil((money < 1 ? 1 : money) / 100) * 100;
	 if(kind == 'up'){
		 var now = parseInt(money) + 100;
	 }else{
		 var now = parseInt(money) - 100;
	 }
	 if(now <= min){
		 now = min;
	 }
	 $('#'+id).val(now);
 }
 
 
 function checkRate(id){
	 var rate = $.trim($('#'+id).val());
	 if(rate.length == 0){
		this.addError(id,'借款利率不能为空！');
		return false;
	}else{
		if(!$.isNumeric(rate)){
			this.addError(id,'借款利率填写不正确！');
			return false;
		}else if(rate<=0){
			this.addError(id,'借款利率必须大于0！');
			return false;
		}else{
			var reg = /^\d+(\.\d{1,2})?$/;
			if(!reg.test(rate)){
				this.addError(id,'借款利率保留小数点后两位！');
				return false;
			}else{
				this.removeError(id);
				return true;
			}
		}
	}
 }
 
 //四舍五入保留两位小数
 function round2(number,fractionDigits){  
    with(Math){  
        return round(number*pow(10,fractionDigits))/pow(10,fractionDigits);  
    }  
 } 

 function addError(id,text){
 	$('#'+id).focus();
 	$('#'+id).parent().addClass('error');
 	if($('#'+id).siblings('#'+id+'_em_').size() > 0){
 		$('#'+id).siblings('#'+id+'_em_').html(text);
 		$('#'+id).siblings('#'+id+'_em_').show();
 	}else{
 		var html = '<div id="'+id+'_em_" class="errorMessage"></div>';
 		$('#'+id).after(html);
 		$('#'+id).siblings('.errorMessage').html(text);
 	}
 }
 
 function removeError(id){
	$('#'+id).parent().removeClass('error');
	$('#'+id).siblings('#'+id+'_em_').html('');
	$('#'+id).siblings('#'+id+'_em_').hide();
 }
 //*********** 公共 	end ************//
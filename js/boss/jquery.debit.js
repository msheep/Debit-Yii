function examine(id,formatId,title,kefu,userId){
	var myDialog = art.dialog({
		id: 'examine',
		title:"审核",
		content:title+'（'+formatId+'）审核通过？',
	    okValue: '通过',
	    opacity: 0.50,
	    resize: false,
	    fixed:true,
	    drag: false,
	    ok:function(){
	    	$.ajax({
				type:'post',
				url:'/user/examineDebit',
				data:'id='+id+'&do=yes',
				success:function(data){
					if(data == 1){
						$('#examine_'+id).remove();
						$('#status_'+id).html('招标中');
						$('#kefu_'+id).html(kefu);
						return true;
					}else{
						alert('网络问题！');
						return false;
					}
				}
			 })
	    },
	    cancelValue:'不通过',
	    cancel:function(){
	    	var cont = '';
	    	cont += '<textarea rows="3" name="rejectReason" id="rejectReason"></textarea>';
	    	var dialog = art.dialog.get('examine');
	    	dialog.close();
	    	art.dialog({
	    		id: 'reject',
	    		title:"审核不通过",
	    		content:cont,
	    		okValue: '确认并发送',
	    	    ok:function(){
	    	    	var reason = $.trim($('#rejectReason').val());
	    	    	if(reason.length == 0){
	    	    		alert('请填写审核不通过的原因！');
	    	    		return false;
	    	    	}
	    	    	$.ajax({
	    				type:'post',
	    				url:'/user/examineDebit',
	    				data:'id='+id+'&userId='+userId+'&reason='+reason+'&do=no',
	    				success:function(data){
	    					if(data == 1){
	    						$('#examine_'+id).remove();
	    						$('#status_'+id).html('审核不通过');
	    						$('#kefu_'+id).html(kefu);
	    						return true;
	    					}else if(data == 1){
	    						alert('请填写理由！');
	    						return false;
	    					}else{
	    						alert('网络问题！');
	    						return false;
	    					}
	    				}
	    			 })
	    	    },
	    	    cancelValue:'取消',
	    	    cancel:function(){
	    	    	this.close();
	    	    },
	    	    opacity: 0.50,
	    	    resize: false,
	    	    fixed:true
	    	})
	    	return false;
	    }
	});
}
/*****************借贷详情页 start**********************/
function pass(){
	$('.pass').show();
	$('.nopass').hide();
	$('.pass-result .btn-primary').attr('onClick','submitPass("yes")');
	$('.pass-result .btn-danger').attr('onClick','noPass()');
	$('.pass-result .pass-word').text('提交');
	$('.pass-result .nopass-word').text('不通过');
}

function noPass(){
	$('.pass').hide();
	$('.nopass').show();
	$('.pass-result .btn-primary').attr('onClick','pass()');
	$('.pass-result .btn-danger').attr('onClick','submitPass("no")');
	$('.pass-result .pass-word').text('通过');
	$('.pass-result .nopass-word').text('提交');
}

function submitPass(pass){
	var debitId = $.trim($('#debitId').val());
	var dataInfo = '';
	if(pass == 'yes'){
		var productPrice = $.trim($('#productPrice').val());
		var realDebitMoney = $.trim($('#realDebitMoney').val());
		var realMinDebitMoney = $.trim($('#realMinDebitMoney').val());
		var kefuInfo = $.trim($('#kefuInfo').val());
		var file = $('#kefu-form').data();
		if(productPrice.length == 0){
			this.addError('productPrice','请填写抵押品估值！');
		}
		if(realDebitMoney.length == 0){
			this.addError('realDebitMoney','请填写贷款金额！');
		}
		if(realMinDebitMoney.length == 0){
			this.addError('realDebitMoney','请填写最低贷款金额！');
		}
		if($('.errorMessage:visible').size() > 0){
			return false;
		}
		dataInfo = {productPrice:productPrice,realDebitMoney:realDebitMoney,realMinDebitMoney:realMinDebitMoney,kefuInfo:kefuInfo,file:file,debitId:debitId,file:file,pass:pass};
	}else if(pass == 'no'){
		var noPassInfo = $.trim($('#noPassInfo').val());
		if(noPassInfo.length == 0){
			this.addError('noPassInfo','请填写审核不通过的理由！');
		}
		if($('.errorMessage:visible').size() > 0){
			return false;
		}
		dataInfo = {kefuInfo:noPassInfo,debitId:debitId,pass:pass};
	}
	$.ajax({
		type:'post',
		url:'/debit/examineDebit',
		data:dataInfo,
		success:function(data){
			if(data == 1){
				alert('审核成功！');
				window.location.replace(window.location.href);
			}else{
				alert('请稍候再试！');
			}
		}
	 })
}
//'productPrice='+productPrice+'&realDebitMoney='+realDebitMoney+'&kefuInfo='+kefuInfo+'&file='+file+'&debitId='+debitId,
//var input = document.getElementById("demo_input");
//var result = document.getElementById("demo_result");
//if ( typeof FileReader === 'undefined' ){
//    result.innerHTML = "<p class='warn'>抱歉，你的浏览器不支持 FileReader</p>";
//    input.setAttribute( 'disabled','disabled' );
//} else {
//    input.addEventListener( 'change',readFile,false );
//}
//
//function readFile(){
//    var file = this.files[0];
//    if(!/image\/\w+/.test(file.type)){
//            alert("请确保文件为图像类型");
//            return false;
//    }
//    var reader = new FileReader();
//    reader.readAsDataURL(file);
//    reader.onload = function(e){
//            result.innerHTML = '<img src="'+this.result+'" alt=""/>'
//    }
//}

function loanDebitMoney(id){
	$.ajax({
		type:'post',
		url:'/debit/loanDebitMoney',
		data:'id='+id,
		success:function(data){
			if(data == 1){
				alert('放款成功！');
				window.location.replace(window.location.href);
			}else{
				alert('网络错误，请联系技术人员！');
			}
		}
	 })
}

function stopDebit(id){
	 art.dialog({
		title:'终止借贷原因',
	    content: '<textarea id="stop-reason"></textarea>',
	    okValue:'确定',
	    ok:function(){
	    	var reason = $.trim($('#stop-reason').val());
	    	if(reason.length == 0){
	    		addError('stop-reason','请填写拒绝发布原因！');
	    		return false;
	    	}else{
	    		removeError('stop-reason');
	    		$.ajax({
	    			type:'post',
	    			url:'/debit/stopDebit',
	    			data:'id='+id+'&reason='+reason,
	    			success:function(data){
	    				if(data == 1){
	    					alert('操作成功！');
	    					window.location.replace(window.location.href);
	    				}else{
	    					alert('网络错误，请联系技术人员！');
	    				}
	    			}
	    		 })
	    	}
	    },
	    fixed:true,
	    drag: false,
	    resize: false,
	    lock:true,
	    opacity: 0.50
	});
 };

/*****************借贷详情页 end**********************/


/*****************公用js**********************/
function ajaxSearch(){
	var url=$("[name='searchFrom']").attr('action');
	var data=$("[name='searchFrom']").serialize()+"&search=";
	var id=$(".list-view").attr('id');
	$.fn.yiiListView.update(id,{
			url:url,
			data:data
	})
	return false;
}

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
 		var html = '<span id="'+id+'_em_" class="errorMessage"></span>';
 		$('#'+id).parent().append(html);
 		$('#'+id).siblings('.errorMessage').html(text);
 	}
 }
 
 function removeError(id){
	$('#'+id).parent().removeClass('error');
	$('#'+id).siblings('#'+id+'_em_').html('');
	$('#'+id).siblings('#'+id+'_em_').hide();
 }
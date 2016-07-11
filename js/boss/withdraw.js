(function($){
	$(function(){
		//消息专用
		artDialog.tips = function (content, time,reload) {
		    return artDialog({
		        id: 'Tips',
		        title: false,
		        cancel: false,
		        fixed: true,
		        lock: false,
		        beforeunload : function(){if(reload){location.reload()}}
		    })
		    .content('<div style="padding: 0 1em;">' + content + '</div>')
		    .time(time || 2000);
		};
		$('a[name="withdraw_auth"]').click(function(){
			$this = $(this);
			art.dialog({
				id: 'checkForm',
		        title: false,
		        fixed: true,
		        lock: true,
		        initialize : function(){
		        	$('#withdraw_auth_pass').data('href',$this.attr('href'));
		        	$('#pass').val('yes').change();
		        	$('#orderNo,#comment').val('');
		        },
		        beforeunload : function(){
		        	$('#withdraw_auth_pass').removeData('href');
		        },
		        content : document.getElementById('check_form')
			});
			
			return false;
		});
		$('#pass').change(function(){
			var val = $(this).val();
			$('#check_form .for-'+val).show();
			$('#check_form .for-'+(val == 'yes' ? 'no' : 'yes')).hide();
		});
		$('#withdraw_auth_pass').click(function(){
			var $this = $(this);
			var form = $('#check_form');
			if($this.data('href')){
				var sel = $('#pass',form).val(),orderNo,comment;
				if(sel == 'yes' && !(orderNo = $.trim($('#orderNo',form).val()))){
					artDialog.tips('请输入打款流水号!');
				}else if(sel == 'no' && !(comment = $.trim($('#comment',form).val()))){
					artDialog.tips('请输入不通过原因!');
				}else{
					$.ajax({
						url : $this.data('href'),
						type : 'post',
						data : form.serialize(),
						dataType : 'json',
						success : function(res){
							if(res.code == 0){
								artDialog.tips(res.msg,2000,res.reload);
							}else{
								artDialog.tips('操作成功',2000,true);
							}
						},
						error : function(error){
							artDialog.tips('操作失败,请稍后重试!');
						}
					})
				}
			}else{
				artDialog.tips('改源码是不对的哦!');
			}
			
			return false;
		});
		
		
	})
})(jQuery)
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
		//审核按键事件
		$("a[name='auth_btn']").click(function(){
			var $this = $(this);
			$.ajax({
				url : $this.attr('href'),
				type : 'get',
				dataType : 'json',
				success : function(res){
					if(res.code == 1){
						art.dialog({
							id : 'auth_form_dialog',
							fixed: true,
							lock: true,
							title : '',
							content : res.msg
						});
					}else{
						artDialog.tips(res.msg,2000,res.reload);
					}
				},
				error : function(error){
					artDialog.tips('加载失败,请稍后重试!');
				}
			});
			
			return false;
		});
		//通过审核按钮绑定事件
		$('body').on('click','a[name="auth_btn_pass"]',function(){
			var $this = $(this);
			$.ajax({
				url : $this.attr('href'),
				type : 'post',
				data : {op:'pass'},
				dataType : 'json',
				success : function(res){
					if(res.code == 0){
						artDialog.tips(res.msg,2000,res.reload);
					}else{
						art.dialog.get('auth_form_dialog').close();
						artDialog.tips('操作成功!',2000,res.reload);
					}
				},
				error : function(error){
					artDialog.tips('操作失败,请稍后重试!');
				}
			})
			return false;
		});
		//不通过审核按钮绑定事件
		$('body').on('click','a[name="auth_btn_nopass"]',function(){
			var $this = $(this);
			var noPassDialog = art.dialog({
				fixed: true,
				lock: true,
				title : '驳回原因',
				content : '<textarea rows="3" id="comment" name="comment" class="input-xlarge"></textarea>',
				ok: function () {
					var comment = $.trim($('#comment').val());
					if(comment == ''){
						alert('请输入驳回原因!');
					}else{
						var thisDialog = this;
						$.ajax({
				        	url : $this.attr('href'),
				        	data : {comment:comment,op:'nopass'},
							type : 'post',
							dataType : 'json',
							success : function(res){
								if(res.code == 0){
									artDialog.tips(res.msg,2000,res.reload);
								}else{
									thisDialog.close();
									art.dialog.get('auth_form_dialog').close();
									artDialog.tips('操作成功!',2000,res.reload);
								}
							},
							error : function(error){
								artDialog.tips('操作失败,请稍后重试!');
							}
				        })
					}
			        return false;
			    },
			    okValue : '确定'
			});
			
			return false;
		});
	});
})(jQuery)
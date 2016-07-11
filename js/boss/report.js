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
		//提现查看按键事件
		$('a[name="withdraw_view"]').click(function(){
			var data = eval('('+$(this).attr('data')+')');
			if(data){
				artDialog({
					id : 'detail',
					title : false,
					fixed: true,
			        lock: true,
			        initialize : function(){
			        	$('#detail_view .for-id').text(data.id);
			        	$('#detail_view .for-uid').text(data.userId);
			        	$('#detail_view .for-record').empty().append('<tr><td>'+data.ctime+'</td><td>提现申请</td></tr>');
			        	if(data.status != 0){
			        		$('#detail_view .for-record').append('<tr><td>'+data.utime+'</td><td>'+(data.status == 1 ? '提现成功' : '提现失败')+'</td></tr>');
			        	}
			        },
					content : document.getElementById('detail_view')
				})
			}else{
				artDialog.tips('无效数据!');
			}
			
			
		});
		
		
	})
})(jQuery)
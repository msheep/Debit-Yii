(function($){
	$(function(){
		$.ajax({
			url:'/user/selectArea',
			data:{'type':'province'},
			success:function(res){
				$('#ResidentAuth_liveProvinceId,#ResidentAuth_householdProvinceId').append(res);
			}
		});
		//绑定省，市change事件
		$('#ResidentAuth_liveProvinceId,#ResidentAuth_householdProvinceId').change(function(){
			var pId = $(this).val();
			var city = $(this).next();
			var area = city.next();
			if(pId > 0){
				$.ajax({
					url:'/user/selectArea',
					data:{'type':'city','parentId':pId},
					success:function(res){
						city.html('<option value="0">---请选择市---</option>'+res);
						area.html('<option value="0">---请选择区---</option>');
					}
				})
			}else{
				city.html('<option value="0">---请选择市---</option>');
				area.html('<option value="0">---请选择区---</option>');
			}
		});
		$('#ResidentAuth_liveCityId,#ResidentAuth_householdCityId').change(function(){
			var pId = $(this).val();
			var area = $(this).next();
			if(pId > 0){
				$.ajax({
					url:'/user/selectArea',
					data:{'type':'area','parentId':pId},
					success:function(res){
						area.html('<option value="0">---请选择区---</option>'+res);
					}
				})
			}else{
				area.html('<option value="0">---请选择区---</option>');
			}
		});
	})
})(jQuery)
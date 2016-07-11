(function($){
	$.fn.areaFunc = function(options){
		var defaults = {
			area: true,
			provinceId: 'province',
			provinceName: 'province',
			cityId: 'city',
			cityName: 'city',
			areaId: 'area',
			areaName: 'area',
		}
		var options = $.extend(defaults, options);  
		var province = $('<select />').attr({
			'id':options.provinceId,
			'name':options.provinceName,
			'class':'province'
		}).append('<option value="0">---请选择省---</option>');
		var city = $('<select />').attr({
			'id':options.cityId,
			'name':options.cityName,
			'class':'city'
		}).append('<option value="0">---请选择市---</option>');
		$(this).append(province).append(city);
		//area可以存在/或者不存在
		if(options.area !== false){
			var area = $('<select />').attr({
				'id':options.areaId,
				'name':options.areaName,
				'class':'area'
			}).append('<option value="0">---请选择区---</option>');
			$(this).append(area);
		}
		
		//初始化省
		$.ajax({
			type:'post',
			url:'/debit/selectCity',
			data:'type=province',
			success:function(data){
				if(data != 0){
					province.append(data);
				}
			}
		});
		
		//省
		province.change(function(){
			var provinceId = $.trim(province.val()); 
			if(provinceId != 0){
				if($(this).find('.city option[title='+provinceId+']').size() == 0){
					$.ajax({
						type:'post',
						url:'/debit/selectCity',
						data:'type=city&provinceId='+provinceId,
						success:function(data){
							if(data != 0){
								city.append(data);
							}
						}
					 })
				}
			}
			city.find('option').hide();
			city.find('option[value=0]').show();
			city.find('option[value=0]').attr('selected','selected');
			city.find('option[title='+provinceId+']').show();
			if(area){
				area.find('option').hide();
				area.find('option[value=0]').show();
				area.find('option[value=0]').attr('selected','selected');
			}
		});
		
		//市
		if(area){
			city.change(function(){
				var cityId = $.trim(city.val()); 
				if(cityId != 0){
					if($(this).find('.area option[title='+cityId+']').size() == 0){
						$.ajax({
							type:'post',
							url:'/debit/selectCity',
							data:'type=area&cityId='+cityId,
							success:function(data){
								if(data != 0){
									area.append(data);
								}
							}
						 })
					}
				}
				area.find('option').hide();
				area.find('option[value=0]').show();	
				area.find('option[value=0]').attr('selected','selected');
				area.find('option[title='+cityId+']').show();		
			});
		}
	}
	
})(jQuery);


$(function(){
	$('#areaDiv').areaFunc({
		'provinceId': 'DebitProperty_provinceId',
		'provinceName': 'DebitProperty[provinceId]',
		'cityId': 'DebitProperty_cityId',
		'cityName': 'DebitProperty[cityId]',
		'areaId': 'DebitProperty_areaId',
		'areaName': 'DebitProperty[areaId]',
	});
	
	$('#carAreaDiv').areaFunc({
		'provinceId': 'DebitCar_provinceId',
		'provinceName': 'DebitCar[provinceId]',
		'cityId': 'DebitCar_cityId',
		'cityName': 'DebitCar[cityId]',
		'area': false,
	});
})

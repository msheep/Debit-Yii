(function($){
	$.fn.areaFunc = function(options){
		var province = $('<select />').attr({
			'id':options.provinceId,
			'name':options.provinceName,
			'class':'province'
		}).append('<option value="0">---请选择省---</option>');
		var city = $('<select />').attr({
			'id':options.cityId,
			'name':options.cityIdName,
			'class':'city'
		}).append('<option value="0">---请选择市---</option>');
		$(this).append(province).append(city);
		//area可以存在/或者不存在
		if(options.area != 'false' || !options.area){
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
			area.find('option').hide();
			area.find('option[value=0]').show();
			area.find('option[value=0]').attr('selected','selected');
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
		
		
		
		/*var options = $.extend(defaults, options);
			selectProvince: function(){
				var province = $.trim($('#DebitProperty_provinceId').val()); 
				if(province != 0){
					if($('#DebitProperty_cityId option[title='+province+']').size() == 0){
						$.ajax({
							type:'post',
							url:'/debit/selectCity',
							data:'type=city&provinceId='+province,
							success:function(data){
								if(data != 0){
									$('#DebitProperty_cityId').append(data);
								}
							}
						 })
					}
				}
				$('#DebitProperty_cityId option').hide();
				$('#DebitProperty_cityId option[value=0]').show();
				$('#DebitProperty_cityId option[value=0]').attr('selected','selected');
				$('#DebitProperty_cityId option[title='+province+']').show();
				$('#DebitProperty_areaId option').hide();
				$('#DebitProperty_areaId option[value=0]').show();
				$('#DebitProperty_areaId option[value=0]').attr('selected','selected');
			},
			
			selectCity: function(){
				if($('#DebitProperty_areaId').size() <= 0){
					return true;
				}
				var city = $.trim($('#DebitProperty_cityId').val()); 
				if(city != 0){
					if($('#DebitProperty_areaId option[title='+city+']').size() == 0){
						$.ajax({
							type:'post',
							url:'/debit/selectCity',
							data:'type=area&cityId='+city,
							success:function(data){
								if(data != 0){
									$('#DebitProperty_areaId').append(data);
								}
							}
						 })
					}
				}
				$('#DebitProperty_areaId option').hide();
				$('#DebitProperty_areaId option[value=0]').show();	
				$('#DebitProperty_areaId option[value=0]').attr('selected','selected');
				$('#DebitProperty_areaId option[title='+city+']').show();
			},
			
			selectArea: function(){
				var city = $.trim($('#DebitProperty_cityId').val()); 
				if(city != 0){
					if($('#DebitProperty_areaId option[title='+city+']').size() == 0){
						$.ajax({
							type:'post',
							url:'/debit/selectCity',
							data:'type=area&cityId='+city,
							success:function(data){
								if(data != 0){
									$('#DebitProperty_areaId').append(data);
								}
							}
						 })
					}
					$('#DebitProperty_areaId option').hide();
					$('#DebitProperty_areaId option[value=0]').show();
					$('#DebitProperty_areaId option[title='+city+']').show();
				}
			}*/
			
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
})

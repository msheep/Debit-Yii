(function($){
	$.fn.carFunc = function(options){
		var defaults = {
			car: true,
			brandId: 'brand',
			brandName: 'brand',
			seriesId: 'series',
			seriesName: 'series',
			carId: 'car',
			carName: 'car',
		}
		var options = $.extend(defaults, options);  
		var brand = $('<select />').attr({
			'id':options.brandId,
			'name':options.brandName,
			'class':'brand'
		}).append('<option value="0">---请选择品牌---</option>');
		var series = $('<select />').attr({
			'id':options.seriesId,
			'name':options.seriesName,
			'class':'series'
		}).append('<option value="0">---请选择车系---</option>');
		$(this).append(brand).append(series);
		//car可以存在/或者不存在
		if(options.car !== false){
			var car = $('<select />').attr({
				'id':options.carId,
				'name':options.carName,
				'class':'car'
			}).append('<option value="0">---请选择车型---</option>');
			$(this).append(car);
		}
		
		//初始化品牌
		$.ajax({
			type:'post',
			url:'/debit/selectCar',
			data:'type=brand',
			success:function(data){
				if(data != 0){
					brand.append(data);
				}
			}
		});
		
		//车系
		brand.change(function(){
			var brandId = $.trim(brand.val()); 
			if(brandId != 0){
				if(series.find('option[title='+brandId+']').size() == 0){
					$.ajax({
						type:'post',
						url:'/debit/selectCar',
						data:'type=series&brandId='+brandId,
						success:function(data){
							if(data != 0){
								series.append(data);
							}
						}
					 })
				}
			}
			series.find('option').hide();
			series.find('option[value=0]').show();
			series.find('option[value=0]').attr('selected','selected');
			series.find('option[title='+brandId+']').show();
			if(car){
				car.find('option').hide();
				car.find('option[value=0]').show();
				car.find('option[value=0]').attr('selected','selected');
			}
		});
		
		//车型
		if(car){
			series.change(function(){
				var seriesId = $.trim(series.val()); 
				if(seriesId != 0){
					if(car.find('option[title='+seriesId+']').size() == 0){
						$.ajax({
							type:'post',
							url:'/debit/selectCar',
							data:'type=car&seriesId='+seriesId,
							success:function(data){
								if(data != 0){
									car.append(data);
								}
							}
						 })
					}
				}
				car.find('option').hide();
				car.find('option[value=0]').show();	
				car.find('option[value=0]').attr('selected','selected');
				car.find('option[title='+seriesId+']').show();		
			});
		}
	}
	
})(jQuery);


$(function(){
	$('#carDiv').carFunc({
		'brandId': 'DebitCar_brandId',
		'brandName': 'DebitCar[brandId]',
		'seriesId': 'DebitCar_seriesId',
		'seriesName': 'DebitCar[seriesId]',
		'carId': 'DebitCar_carId',
		'carName': 'DebitCar[carId]',
	});
})

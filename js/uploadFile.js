var params = {
	fileInput: $("#fileImage").get(0),
	dragDrop: $("#preview").get(0),
	url: $("#kefu-form").attr("action"),
	filter: function(files) {
		var arrFiles = [];
		for (var i = 0, file; file = files[i]; i++) {
			if (file.type.indexOf("image") == 0 || (!file.type && /\.(?:jpg|png|gif)$/.test(file.name) /* for IE10 */)) {
				if (file.size >= 1024000) {
					alert('您这张"'+ file.name +'"图片大小过大，应小于1M');	
				} else {
					arrFiles.push(file);	
				}			
			} else {
				alert('文件"' + file.name + '"不是图片。');	
			}
		}
		return arrFiles;
	},
	onSelect: function(files) {
		var html = '', i = 0;
		$("#preview").html('');
		var funAppendImage = function() {
			file = files[i];
			if (file) {
				var reader = new FileReader();
				reader.onload = function(e) {
					html = html + '<div id="uploadList_'+ i +'" class="upload_append_list">'+
						'<a href="' + e.target.result + '" rel="lightbox" title=' + file.name + '>' + 
						'<img id="uploadImage_' + i + '" src="' + e.target.result + '" class="upload_image" /></a>'+
						'<p><span class="name">' + file.name + '</span><a href="javascript:" class="upload_delete" title="删除" data-index="'+ i +'">删除</a></p>'+
						'</div>';	
					i++;
					$('#file-name').val(file.name);
					funAppendImage();
				}
				reader.readAsDataURL(file);
			} else {
				$("#preview").html(html);
				//删除方法
				$(".upload_delete").click(function() {
					ZXXFILE.funDeleteFile(files[parseInt($(this).attr("data-index"))]);
					var thisName = $.trim($(this).siblings('.name').html()); 
					var fileName = $.trim($('#file-name').val());
					fileName = fileName.replace(thisName,'');
					$('#file-name').val(fileName);
					return false;	
				});
			}
		};
		funAppendImage();		
	},
	onDelete: function(file) {
		$("#uploadList_" + file.index).fadeOut();
	},
	onDragOver: function() {
		$(this).addClass("upload_drag_hover");
	},
	onDragLeave: function() {
		$(this).removeClass("upload_drag_hover");
	},
	onProgress: function(file, loaded, total) {
		var eleProgress = $("#uploadProgress_" + file.index), percent = (loaded / total * 100).toFixed(2) + '%';
		eleProgress.show().html(percent);
	},
	onSuccess: function(file, response) {
		$("#uploadInf").append("<p>上传成功，图片地址是：" + response + "</p>");
	},
	onFailure: function(file) {
		$("#uploadInf").append("<p>图片" + file.name + "上传失败！</p>");	
		$("#uploadImage_" + file.index).css("opacity", 0.2);
	},
	onComplete: function() {
		//提交按钮隐藏
		$("#fileSubmit").hide();
		//file控件value置空
		$("#fileImage").val("");
		$("#uploadInf").append("<p>当前图片全部上传完毕，可继续添加上传。</p>");
	}
};
ZXXFILE = $.extend(ZXXFILE, params);
ZXXFILE.init();
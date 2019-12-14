var imgXhr = {};

$(function() {
	$('#file').val('');
	$('#file').change(uploadImage);

	$('#btn_select').click(function() {
		if (imgXhr.readyState == 1) {
			imgXhr.abort();
			$(this).html('Выбрать файл');
			$('#progress').hide();
			$('#comment').show();
		}
		else $('#file').click();
	});
	
	$('#url').click(function(){
		$(this).select();
	});
	
	//alert(window.name);
});

function uploadImage() {
	var file = $('#file')[0].files[0];

	if (file.size > target.maxSize)
	{	
		$('#error').html('Размер выбранного файла превышает допустимый.').show();
		return;
	}

	var fd = new FormData();
	fd.append('Files[file]', file);
	fd.append('YII_CSRF_TOKEN', $('#csrf').html());

	imgXhr = new XMLHttpRequest();

	imgXhr.upload.onprogress = function(e) {
		if (e.loaded == e.total) {
			$('#progress').hide();
			$('#btn_select').html('Выбрать файл');
		}
		else $('#progress > div').css('width', (e.loaded / e.total * 100) + '%');
	};

	imgXhr.onreadystatechange = function() {
		if (this.readyState == 4 && this.response !== null && this.response !== '') {
			var data = JSON.parse(this.response);
			if (data.status == 1)
			{
				$('#url').val(data.data.url).select();
				if (target.autoInsert)
				{
					insertLink(0);
				}
				else
				{
					$('.url').show();
					$('.insert').empty().show();
					for (var i in target.insertTypes)
					{
						var r = eval(target.insertTypes[i].pattern);
						if (r.test(data.data.extension))
							$('.insert').append('или<br><a href="#" onClick="insertLink(\''+i+'\');">'+target.insertTypes[i].title+'</a><br>');
					}
				}
			}
			else
			{
				$('#error').empty().show();
				for (var i in data.data)
				{
					$('#error').append(data.data[i][0]+'<br/>');
				}
			}
		}
	};

	imgXhr.open('post', '/files/upload?target='+target.name);
	imgXhr.send(fd);

	$('#btn_select').html('Отменить загрузку');
	$('#error').hide();
	$('.url').hide();
	$('.insert').hide();
	$('#progress').css('display', 'inline-block');
	$('#progress > div').css('width', '0');
}

function insertLink(type)
{
	var data = $('#url').val();
	eval(target.insertTypes[type].onClick);
	window.close();
}

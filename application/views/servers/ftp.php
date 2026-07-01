<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
?>
<?php echo $header?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<?php include 'application/views/common/menuserver.php';?>
				</div>
				<div class="col-xl-3">
					<div class="card card-custom mb-3 pt-5">
						<div class="card-body">
							<div class="text-center">
								<div class="symbol symbol-60 symbol-circle symbol-xl-90">
									<div class="symbol-label">
										<i class="fa fa-server icon-5x text-primary"></i>
									</div>
								</div>
								<h4 class="font-weight-bolder my-2">FTP Online</h4>
								<div class="text-muted font-weight-bold mb-2"><?php echo $server['location_name'] ?></div>
							</div>
						</div>
					</div>
					<div class="card card-custom mb-3">
						<div class="card-body px-1" style="padding: 0.3rem 2.25rem;">
							<div class="navi navi-hover navi-active navi-link-rounded navi-bold navi-icon-center navi-light-icon">
								<div class="navi-item my-2">
									<a href="javascript:;" data-toggle="modal" data-target="#ftpdiz" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-clone"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Сменить дизайн</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="http://filezilla.ru/get/" target="_blank" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-external-link-alt"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Скачать FTP Клиент</span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-9">
					<div class="row">
						<div class="col-xl-12">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Адрес для доступа по протоколу FTP<small class="text-muted font-size-sm ml-2"><?php echo $server['location_ip2'] ?></small></h3>
								</div>
							</div>
						</div>
						<div class="col-xl-12">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">FTP Порт<small class="text-muted font-size-sm ml-2">21</small></h3>
								</div>
							</div>
						</div>
						<div class="col-xl-12">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Имя пользователя<small class="text-muted font-size-sm ml-2">gs<?php echo $server['server_id'] ?></small></h3>
								</div>
							</div>
						</div>
						<div class="col-xl-12">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Пароль пользователя<small class="text-muted font-size-sm ml-2"><?php echo $server['server_password'] ?></small></h3>
									<div class="card-toolbar">
										<a href="javascript:;" data-toggle="modal" data-target="#ftppass" class="btn btn-clean btn-icon">
										<i class="fas fa-cogs" data-toggle="tooltip" data-placement="right" title="" data-original-title="Изменить"></i>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-12">
					<div class="card card-custom gutter-b d-none d-md-flex">
						<div class="card-header">
							<div class="card-title">
								<h3 class="card-label">Онлайн клиент</h3>
							</div>
						</div>
						<div class="card-body">
							<div id="elfinder"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--begin::Modal-->
<div class="modal fade" id="ftppass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Изменить пароль FTP</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form id="editForm" method="POST" style="padding:0px; margin:0px;">
				<div class="modal-body">
					<div class="card card-body" style="margin-bottom: 1rem;padding: 1rem;">
						<div class="form-group" style="margin-bottom: 0rem;">
							<div class="checkbox-list">
								<label class="checkbox">
								<input type="checkbox" id="editpassword" name="editpassword" onchange="togglePassword()">
								<span></span>
								Сменить пароль FTP
								</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Пароль</label>
						<input class="form-control" id="password" name="password" placeholder="Пароль" disabled="">
					</div>
					<div class="form-group">
						<label>Повторите пароль</label>
						<input class="form-control" id="password2" name="password2" placeholder="Повторите пароль" disabled="">
					</div>
				</div>
				<div class="modal-footer" style="padding: 0.5rem;">
					<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Отмена</button>
					<button type="submit" class="btn btn-primary font-weight-bold">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!--end::Modal-->
<!--begin::Modal-->
<div class="modal fade" id="ftpdiz" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Смена дизайна</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="modal-body">
				<div class="row row-paddingless">
					<div class="col-6">
						<a href="javascript:;" onClick="sendAction_theme('default')" class="d-block py-10 px-5 text-center bg-hover-light border-right border-bottom">
						<i class="flaticon2-drop text-primary icon-3x"></i>
						<span class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">Тема Default</span>
						<span class="d-block text-dark-50 font-size-lg">Применить</span>
						</a>
					</div>
					<div class="col-6">
						<a href="javascript:;" onClick="sendAction_theme('material')" class="d-block py-10 px-5 text-center bg-hover-light border-right">
						<i class="flaticon2-drop text-primary icon-3x"></i>
						<span class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">Тема Material</span>
						<span class="d-block text-dark-50 font-size-lg">Применить</span>
						</a>
					</div>
					<div class="col-6">
						<a href="javascript:;" onClick="sendAction_theme('materialgray')" class="d-block py-10 px-5 text-center bg-hover-light">
						<i class="flaticon2-drop text-primary icon-3x"></i>
						<span class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">Тема Material gray</span>
						<span class="d-block text-dark-50 font-size-lg">Применить</span>
						</a>
					</div>
					<div class="col-6">
						<a href="javascript:;" onClick="sendAction_theme('materiallight')" class="d-block py-10 px-5 text-center bg-hover-light">
						<i class="flaticon2-drop text-primary icon-3x"></i>
						<span class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">Тема Material light</span>
						<span class="d-block text-dark-50 font-size-lg">Применить</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--end::Modal-->
<script type="text/javascript"> 
	$(function () { 
		var myCommands = elFinder.prototype._options.commands; 
		var disabled = ['resize', 'help', 'select']; 
		$.each(disabled, function (i, cmd) {
			(idx = $.inArray(cmd, myCommands)) !== -1 && myCommands.splice(idx, 1); }); 
			var selectedFile = null; 
			var options = { 
				url: '/servers/ftp/getftp/<?php echo $server['server_id']  ?>', 
				rememberLastDir: false, 
				commands: myCommands, 
				lang: 'ru', 
				uiOptions: { 
					toolbar: [ ['back', 'forward'], ['reload'], ['home', 'up'], ['mkdir', 'mkfile', 'upload'], ['open', 'download'], ['info'], ['quicklook'], ['copy', 'cut', 'paste'], ['rm'], ['duplicate', 'rename', 'edit','extract', 'archive'], ['view', 'sort'] ] 
				}, 
				handlers: { 
					select: function (event, elfinderInstance) { 
						if (event.data.selected.length == 1) { 
							var item = $('#' + event.data.selected[0]); 
							if (!item.hasClass('directory')) { 
								selectedFile = event.data.selected[0];
								$('#elfinder-selectFile').show(); return;
							} 
						} 
						$('#elfinder-selectFile').hide(); selectedFile = null; 
					}					
				} 
			};
			$('#elfinder').elfinder(options).elfinder('instance');
			$('.elfinder-toolbar:first').append('<div class="ui-widget-content ui-corner-all elfinder-buttonset" id="elfinder-selectFile" style="display:none; float:right;">'+ '<div class="ui-state-default elfinder-button" title="Select" style="width: 100px;"></div>');
			$('#elfinder-selectFile').click(function () { 
				if (selectedFile != null)
				$.post('file/selectFile', { 
					target: selectedFile 
				}, 
				function (response) { 
				alert(response); 
			}); 
		});
	}); 
</script>
<audio style="display: none;">
   <source src="/application/public/sounds/rm.wav" type="audio/wav">
</audio>
<script src="/application/public/js/elfinder.min.js"></script>
<script src="/application/public/js/i18n/elfinder.ru.js"></script>
<script src="/application/public/js/jquery-ui.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/application/public/css/elfinder.full.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $theme ?>">
<script>
	function sendAction_theme(action) {
		$.ajax({ 
			url: '/servers/ftp/action_theme_ftp/'+action,
			dataType: 'text',
			success: function(data) {
				data = $.parseJSON(data);
				switch(data.status) {
					case 'error':
						toastr.error(data.error);
						break;
					case 'success':
						toastr.success(data.success);
						setTimeout("reload()", 2500);
						break;
				}
			}
		});
	}
</script>
<script>    
	$('#editForm').ajaxForm({ 
		url: '/servers/ftp/ajax/<?php echo $server['server_id'] ?>',
		dataType: 'text',
		success: function(data) {
			console.log(data);
			data = $.parseJSON(data);
			switch(data.status) {
				case 'error':
					toastr.error(data.error);
					$('button[type=submit]').prop('disabled', false);
					break;
				case 'success':
					toastr.success(data.success);
					setTimeout("reload()", 1500);
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			$('button[type=submit]').prop('disabled', true);
		}
	});
	
	function togglePassword() {
		var status = $('#editpassword').is(':checked');
		if(status) {
           $('#password').prop('disabled', false);
           $('#password2').prop('disabled', false);
		} else {
           $('#password').prop('disabled', true);
           $('#password2').prop('disabled', true);
		}
	}
</script>
<?php echo $footer ?>
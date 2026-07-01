<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
?>
<?php echo $admheader ?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="row">
				<div class="col-xl-3">
					<div class="card card-custom mb-3 pt-5">
						<div class="card-body">
							<div class="text-center">
								<div class="symbol symbol-60 symbol-circle symbol-xl-90">
									<div class="symbol-label">
										<?php if($server['server_status'] == 1): ?>
										<i class="fa fa-power-off icon-6x text-danger"></i>
										<?php elseif($server['server_status'] == 2): ?>
										<i class="fa fa-power-off icon-6x text-success"></i>
										<?php elseif($server['server_status'] == 3): ?>
										<i class="fas fa-clock icon-6x text-primary"></i>
										<?php elseif($server['server_status'] == 4 || $server['server_status'] == 5 || $server['server_status'] == 6 || $server['server_status'] == 7): ?>
										<i class="fas fa-clock icon-6x text-info"></i>
										<?php elseif($server['server_status'] == 0): ?>
										<i class="fas fa-exclamation-circle icon-6x text-warning"></i>
										<?php endif; ?>
									</div>
									<i class="symbol-badge symbol-badge-bottom bg-<?php if($server['server_status'] == 0): ?>warning
										<?php elseif($server['server_status'] == 1): ?>danger
										<?php elseif($server['server_status'] == 2): ?>success
										<?php elseif($server['server_status'] == 3): ?>primary
										<?php elseif($server['server_status'] == 4 || $server['server_status'] == 5 || $server['server_status'] == 6 || $server['server_status'] == 7): ?>info
										<?php endif; ?>"></i>
								</div>
								<h4 class="font-weight-bolder my-2"><?php echo $server['game_name'] ?></h4>
								<div class="text-muted font-weight-bold mb-2"><?php if($server['server_status'] == 1): ?>
									Остановлен
									<?php elseif($server['server_status'] == 2): ?>
									Запущен
									<?php elseif($server['server_status'] == 3): ?>
									Устанавливается
									<?php elseif($server['server_status'] == 4): ?>
									Переустанавливается
									<?php elseif($server['server_status'] == 5): ?>
									Создается BackUP
									<?php elseif($server['server_status'] == 6): ?>
									Устанавливается BackUP
									<?php elseif($server['server_status'] == 0): ?>
									Заблокирован
									<?php elseif($server['server_status'] == 7): ?>
									Обновляется
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
					<div class="card card-custom mb-3">
						<div class="card-body px-1" style="padding: 0.3rem 2.25rem;">
							<div class="navi navi-hover navi-active navi-link-rounded navi-bold navi-icon-center navi-light-icon">
								<?php if($server['server_status'] == 1): ?>
								<div class="navi-item my-2">
									<a href="javascript:;" onClick="actionserver(<?php echo $server['server_id'] ?>,'start')" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-power-off"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Запустить</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="javascript:;" onClick="actionserver(<?php echo $server['server_id'] ?>,'reinstall')" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fas fa-spinner"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Переустановить</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="javascript:;" onClick="actionserver(<?php echo $server['server_id'] ?>,'backup')" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-cloud-download-alt"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Сделать BackUP</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="javascript:;" onClick="actionserver(<?php echo $server['server_id'] ?>,'unbackup')" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-cloud-upload-alt"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Развернуть BackUP</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="javascript:;" onClick="actionserver(<?php echo $server['server_id'] ?>,'delete_backup')" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fas fa-quidditch"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Удалить BackUP</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="javascript:;" onClick="actionserver(<?php echo $server['server_id'] ?>,'block')" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-lock"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Блокировать</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="javascript:;" onClick="actionserver(<?php echo $server['server_id'] ?>,'delete')" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-trash-alt"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Удалить сервер</span>
									</a>
								</div>
								<?php elseif($server['server_status'] == 2): ?>
								<div class="navi-item my-2">
									<a href="javascript:;" onClick="actionserver(<?php echo $server['server_id'] ?>,'stop')" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-power-off"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Остановить</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="javascript:;" onClick="actionserver(<?php echo $server['server_id'] ?>,'restart')" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-sync-alt"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Перезапустить</span>
									</a>
								</div>
								<?php elseif($server['server_status'] == 0): ?>
								<div class="navi-item my-2">
									<a href="javascript:;" onClick="actionserver(<?php echo $server['server_id'] ?>,'unblock')" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fas fa-unlock"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Разблокировать</span>
									</a>
								</div>
								<?php endif; ?>
								<?php if($server['game_code'] == "cs" || $server['game_code'] == "csgo" || $server['game_code'] == "css" AND $server['server_status'] == 1): ?>
								<div class="navi-item my-2">
									<a href="javascript:;" data-toggle="modal" data-target="#csfast" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="far fa-sun"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Параметры запуска</span>
									</a>
								</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-9">
					<div class="row">
						<div class="col-xl-12">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Клиент<small class="text-muted font-size-sm ml-2"><a class="text-muted" href="/admin/users/edit/index/<?php echo $server['user_id'] ?>"> <?php echo $server['user_firstname'] ?> <?php echo $server['user_lastname'] ?></a></small></h3>
								</div>
							</div>
						</div>
						<div class="col-xl-12">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">
										Статистика
										<small class="text-muted font-size-sm ml-2" id="stats">
											<div class="spinner spinner-primary"></div>
										</small>
									</h3>
								</div>
							</div>
						</div>
						<div class="col-xl-12 mb-3">
							<div class="separator separator-dashed separator-border-2"></div>
						</div>
						<?php if($server['server_status'] == 2): ?>
						<div class="col-xl-12">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Название<small class="text-muted font-size-sm ml-2"><?php echo $query['hostname'] ?></small></h3>
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Режим<small class="text-muted font-size-sm ml-2"><?php echo $query['gamemode'] ?></small></h3>
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Карта<small class="text-muted font-size-sm ml-2"><?php echo $query['mapname'] ?></small></h3>
								</div>
							</div>
						</div>
						<?php endif; ?>
						<div class="col-xl-6">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Адрес<small class="text-muted font-size-sm ml-2"><?php echo $server['location_ip2'] ?>:<?php echo $server['server_port'] ?></small></h3>
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Слоты<small class="text-muted font-size-sm ml-2"><?php echo $server['server_slots'] ?></small></h3>
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Оплачен до<small class="text-muted font-size-sm ml-2"><?php echo date("d.m.Y", strtotime($server['server_date_end'])) ?></small></h3>
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Локация<small class="text-muted font-size-sm ml-2"><?php echo $server['location_name'] ?></small></h3>
								</div>
							</div>
						</div>
						<?php if($server['game_code'] == "cs" || $server['game_code'] == "mine" || $server['game_code'] == "mcpe" || $server['game_code'] == "ragemp"): ?>
						<div class="col-xl-12">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Версия сервера<small class="text-muted font-size-sm ml-2">
									<?php if($server['game_code'] == "mine" || $server['game_code'] == "mcpe"): ?>
									<?php foreach($cores as $item): ?>
									<?php if($item['corepath'] == $server['server_binary']): ?>
									<?php echo $item['text_name'] ?>
									<?php endif; ?>
									<?php endforeach; ?>
									<?php endif; ?>
									<?php if($server['game_code'] == "cs"): ?>
									<?php foreach($builds as $item): ?>
									<?php if($item['buildpath'] == $server['server_binary']): ?>
									<?php echo $item['text_name'] ?>
									<?php endif; ?>
									<?php endforeach; ?>
									<?php endif; ?>
									<?php if($server['game_code'] == "ragemp"): ?>
									<?php foreach($versions as $item): ?>
									<?php if($item['path'] == $server['server_binary']): ?>
									<?php echo $item['text_name'] ?>
									<?php endif; ?>
									<?php endforeach; ?>
									<?php endif; ?>
									</small></h3>
								</div>
							</div>
						</div>
						<?php endif; ?>
						<?php if($server['game_code'] == "cs" || $server['game_code'] == "csgo" || $server['game_code'] == "css" AND $server['fastdl_status'] == 1): ?>
						<?php if($server['server_status'] == 2 || $server['server_status'] == 1): ?>
						<div class="col-xl-12">
							<?php if($server['server_status'] == 2): ?>
							<div class="alert alert-primary mb-5 p-5" role="alert">
								<div class="input-group">
									<input type="text" class="form-control" placeholder="FastDL URL: http://<?php echo $server['location_ip2'] ?>:8080/fastdl_gs<?php echo $server['server_id'] ?>" value="FastDL URL: http://<?php echo $server['location_ip2'] ?>:8080/fastdl_gs<?php echo $server['server_id'] ?>">
								</div>
							</div>
							<?php elseif($server['server_status'] == 1): ?>
							<div class="accordion accordion-solid accordion-toggle-plus" id="accordionExample">
								<div class="card">
									<div class="card-header" id="fast_info">
										<div class="card-title collapsed" data-toggle="collapse" role="button" data-target="#fast_infos" aria-expanded="false" aria-controls="collapseThree">Добавьте данные строки в конфигурационный файл server.cfg для работы FastDL</div>
									</div>
									<div id="fast_infos" class="card-body-wrapper collapse" aria-labelledby="fast_info" data-parent="#accordionExample" style="">
										<div class="card-body">
											<p>
												sv_downloadurl "http://<?php echo $server['location_ip2'] ?>:8080/fastdl_gs<?php echo $server['server_id'] ?>"<br> 
												sv_consistency 1<br> 
												sv_allowupload 1<br> 
												sv_allowdownload 1
											</p>
										</div>
									</div>
								</div>
							</div>
							<?php endif; ?>
						</div>
						<?php endif; ?>
						<?php endif; ?>
						<?if($server['server_status'] == 1 || $server['server_status'] == 2):?>
						<br><div class="col-xl-12 mb-3">
							<div class="separator separator-dashed separator-border-2"></div>
						</div>
						<div class="col-xl-6">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Адрес FTP-сервера<small class="text-muted font-size-sm ml-2"><?php echo $server['location_ip2'] ?></small></h3>
									<div class="card-toolbar">
										<a href="http://filezilla.ru/get/" target="_blank" class="btn btn-clean btn-icon">
										<i class="fa fa-external-link-alt" data-toggle="tooltip" data-placement="right" title="" data-original-title="Скачать FTP Клиент"></i>
										</a>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Порт FTP-сервера<small class="text-muted font-size-sm ml-2">21</small></h3>
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Имя пользователя<small class="text-muted font-size-sm ml-2">gs<?php echo $server['server_id'] ?></small></h3>
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Пароль доступа<small class="text-muted font-size-sm ml-2"><?php echo $server['server_password'] ?></small></h3>
								</div>
							</div>
						</div>
						<div class="col-xl-12 mb-3">
							<div class="separator separator-dashed separator-border-2"></div>
						</div>
						<div class="col-xl-12">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">MySQL Хост<small class="text-muted font-size-sm ml-2">127.0.0.1/<?php echo $server['location_ip2'] ?></small></h3>
									<div class="card-toolbar">
										<?if($server['server_mysql'] == 1):?>
										<a href="javascript:;" onClick="sendAction(<?php echo $server['server_id'] ?>,'mysqloff')" class="btn btn-clean btn-icon" style="margin-right: .25rem"><i class="fa fa-power-off" data-toggle="tooltip" data-placement="right" title="" data-original-title="Выключить"></i></a>
										<?elseif($server['server_mysql'] == 0):?>
										<a href="javascript:;" onClick="sendAction(<?php echo $server['server_id'] ?>,'mysqlcr')" class="btn btn-clean btn-icon" style="margin-right: .25rem"><i class="fa fa-user-plus" data-toggle="tooltip" data-placement="right" title="" data-original-title="Создать базу"></i></a>
										<?elseif($server['server_mysql'] == 2):?>
										<a href="javascript:;" onClick="sendAction(<?php echo $server['server_id'] ?>,'mysqlon')" class="btn btn-clean btn-icon" style="margin-right: .25rem"><i class="fa fa-power-off" data-toggle="tooltip" data-placement="right" title="" data-original-title="Включить"></i></a>
										<?endif;?>
									</div>
								</div>
							</div>
						</div>
						<?if($server['server_mysql'] == 1):?>
						<div class="col-xl-6">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Имя пользователя<small class="text-muted font-size-sm ml-2">gs<?php echo $server['server_id'] ?></small></h3>
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Пароль доступа<small class="text-muted font-size-sm ml-2"><?php echo $server['db_pass'] ?></small></h3>
								</div>
							</div>
						</div>
						<?endif;?>
						<?endif;?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php if($server['game_code'] == "cs" || $server['game_code'] == "csgo" || $server['game_code'] == "css" AND $server['server_status'] == 1): ?>
<!--begin::Modal-->
<div class="modal fade" id="csfast" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Параметры запуска</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form id="settingStart" method="POST" style="padding:0px; margin:0px;">
				<div class="modal-body">
					<?php if($server['game_code'] == "cs" || $server['game_code'] == "csgo" || $server['game_code'] == "css"): ?>
					<div class="form-group form-md-line-input">
						<label>Античит VAC</label>
						<select class="form-control" id="vac" name="vac">
							<option value="0" <?php if($server['server_vac'] == 0): ?> selected="selected"<?php endif; ?>>Выключен</option>
							<option value="1" <?php if($server['server_vac'] == 1): ?> selected="selected"<?php endif; ?>>Включен</option>
						</select>
					</div>
					<div class="form-group form-md-line-input">
						<label>FastDL</label>
						<select class="form-control" id="fastdl" name="fastdl">
							<option value="0" <?php if($server['fastdl_status'] == 0): ?> selected="selected"<?php endif; ?>>Выключен</option>
							<option value="1" <?php if($server['fastdl_status'] == 1): ?> selected="selected"<?php endif; ?>>Включен</option>
						</select>
					</div>
					<?php endif; ?>
					<?php if($server['game_code'] == "cs"): ?>
					<div class="form-group form-md-line-input">
						<label>FPS</label>
						<select class="form-control" id="fps" name="fps">
							<?php foreach($fps as $item): ?>
							<option value="<?php echo $item ?>" <?php if($server['server_fps'] == $item): ?> selected="selected"<?php endif; ?>><?php echo $item ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<?php endif; ?>
					<?php if($server['game_code'] == "csgo" || $server["game_code"] == "css"): ?>
					<div class="form-group form-md-line-input">
						<label>Tickrate</label>
						<select class="form-control" id="tickrate" name="tickrate">
							<?php foreach($tickrate as $item): ?>
							<option value="<?php echo $item ?>" <?php if($server['server_tickrate'] == $item): ?> selected="selected"<?php endif; ?>><?php echo $item ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<?php endif; ?>
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
<?php endif; ?>
<?php if($server['game_code'] == "cs" || $server['game_code'] == "csgo" || $server['game_code'] == "css" AND $server['server_status'] == 1): ?>
<script>
	$('#settingStart').ajaxForm({ 
		url: '/admin/servers/control/settingStart/<?php echo $server['server_id'] ?>',
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
					$('button[type=submit]').prop('disabled', false);
					setTimeout("reload()", 2500);
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			$('button[type=submit]').prop('disabled', true);		
		}
	}); 
</script>
<?php endif; ?>	
<script> 
	function updateData() {
		$.ajax({
			url: '/admin/servers/control/getData/<?=$server['server_id']?>',
			dataType: 'text',
			success: function (data) {
				data = $.parseJSON(data);
				$("#stats").text("CPU: "+data.cpu+"% | RAM: "+data.ram+"% | SSD: "+data.ssd+"% | Players: "+data.players);
			}
		})
	}

	$(document).ready(function() {
		updateData();
	});
	
	function actionserver(serverid, actionserver) {
		switch(actionserver) {
			case "reinstall":
			{
				if(!confirm("Вы уверенны в том, что хотите переустановить сервер? Все данные будут удалены.")) return;
				break;
			}
			case "backup":
			{
				if(!confirm("Вы уверены в том, что хотите сделать бэкап сервера? Прежний бэкап будет удален.")) return;
				break;
			}
			case "unbackup":
			{
				if(!confirm("Вы уверены в том, что хотите восстановить сервер?")) return;
				break;
			}
			case "delete_backup":
			{
				if(!confirm("Вы уверены в том, что хотите удалить BackUP сервера?")) return;
				break;
			}
			case "delete":
			{
				if(!confirm("Вы уверенны в том, что хотите удалить сервер gs<?echo $server['server_id']?>?")) return;
				break;
			}
			case "block":
			{
				if(!confirm("Вы уверенны в том, что хотите заблокировать сервер gs<?echo $server['server_id']?>?")) return;
				break;
			}
			case "unblock":
			{
				if(!confirm("Вы уверенны в том, что хотите разблокировать сервер gs<?echo $server['server_id']?>?")) return;
				break;
			}
		}
		$.ajax({ 
			url: '/admin/servers/control/actionserver/'+serverid+'/'+actionserver,
			dataType: 'text',
			success: function(data) {
				console.log(data);
				data = $.parseJSON(data);
				switch(data.status) {
					case 'error':
						toastr.error(data.error);
						break;
					case 'success':
						toastr.success(data.success);
						setTimeout("reload()", 1500);
						break;
				}
			},
			beforeSend: function(arr, options) {
	            toastr.warning("Ваш запрос обрабатывается, пожалуйста, подождите...");                          
	        }
		});
	}
</script>
<script>
	function sendAction(serverid, mysqlhostin) {
		$.ajax({ 
			url: '/admin/servers/control/mysqlhostin/'+serverid+'/'+mysqlhostin,
			dataType: 'text',
			success: function(data) {
				console.log(data);
				data = $.parseJSON(data);
				switch(data.status) {
					case 'error':
						toastr.error(data.error);
						break;
					case 'success':
						toastr.success(data.success);
						setTimeout("reload()", 1500);
						break;
				}
			},
			beforeSend: function(arr, options) {
				toastr.warning("Ваш запрос обрабатывается, пожалуйста, подождите...");  
			}
		});
	}
</script>
<?php echo $footer ?>
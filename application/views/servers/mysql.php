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
										<i class="fa fa-database icon-5x text-primary"></i>
									</div>
								</div>
								<h4 class="font-weight-bolder my-2">PHPMyAdmin</h4>
								<div class="text-muted font-weight-bold mb-2"><?if($server['server_mysql'] == 1):?>
									Пользователь активен
									<?elseif($server['server_mysql'] == 0):?>
									Пользователь не создан
									<?elseif($server['server_mysql'] == 2):?>
									Пользователь неактивен
									<?endif;?>
								</div>
							</div>
						</div>
					</div>
					<div class="card card-custom mb-3">
						<div class="card-body px-1" style="padding: 0.3rem 2.25rem;">
							<div class="navi navi-hover navi-active navi-link-rounded navi-bold navi-icon-center navi-light-icon">
								<?if($server['server_mysql'] == 1):?>
								<div class="navi-item my-2">
									<a href="http://<?php echo $server['location_ip2'] ?>:8080/phpmyadmin" target="_blank" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-user-circle"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Войти</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="javascript:;" onClick="sendAction(<?php echo $server['server_id'] ?>,'mysqloff')" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-power-off"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Выключить</span>
									</a>
								</div>
								<?elseif($server['server_mysql'] == 0):?>
								<div class="navi-item my-2">
									<a href="javascript:;" onClick="sendAction(<?php echo $server['server_id'] ?>,'mysqlcr')" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-user-plus"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Создать</span>
									</a>
								</div>
								<?elseif($server['server_mysql'] == 2):?>
								<div class="navi-item my-2">
									<a href="javascript:;" onClick="sendAction(<?php echo $server['server_id'] ?>,'mysqlon')" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-power-off"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Включить</span>
									</a>
								</div>
								<?endif;?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-9">
					<div class="row">
						<?if($server['server_mysql'] == 1):?>
						<div class="col-xl-12">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">MySQL Хост для локального подключения<small class="text-muted font-size-sm ml-2">127.0.0.1</small></h3>
								</div>
							</div>
						</div>
						<div class="col-xl-12">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">MySQL Хост для внешнего подключения<small class="text-muted font-size-sm ml-2"><?php echo $server['location_ip2'] ?></small></h3>
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">MySQL Порт<small class="text-muted font-size-sm ml-2">3306</small></h3>
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">База данных<small class="text-muted font-size-sm ml-2">gs<?php echo $server['server_id'] ?></small></h3>
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
									<h3 class="card-title">Пароль пользователя<small class="text-muted font-size-sm ml-2"><?php echo $server['db_pass'] ?></small></h3>
									<div class="card-toolbar">
										<a href="javascript:;" data-toggle="modal" data-target="#ftppass" class="btn btn-clean btn-icon">
										<i class="fas fa-cogs" data-toggle="tooltip" data-placement="right" title="" data-original-title="Изменить"></i>
										</a>
									</div>
								</div>
							</div>
						</div>
						<?php else: ?>
						<div class="col-xl-12">
							<div class="alert alert-custom alert-light-primary fade show mb-4" role="alert">
								<div class="alert-icon">
									<i class="flaticon-exclamation"></i>
								</div>
								<?if($server['server_mysql'] == 0):?>
								<div class="alert-text">На данный момент база данных не создана.</div>
								<?elseif($server['server_mysql'] == 2):?>
								<div class="alert-text">На данный момент база данных не включена.</div>
								<?endif;?>
								<div class="alert-close">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true"><i class="ki ki-close"></i></span>
									</button>
								</div>
							</div>
						</div>
						<?endif;?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?if($server['server_mysql'] == 1):?>
<!--begin::Modal-->
<div class="modal fade" id="ftppass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Изменить пароль MySQL</h5>
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
								Сменить пароль MySQL
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
<?endif;?>
<script>
	function sendAction(serverid, action) {
		$.ajax({ 
			url: '/servers/mysql/action/'+serverid+'/'+action,
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
				if(action == "mysqloff") toastr.warning("Выключаем базу данных...");
				if(action == "mysqlon") toastr.warning("Включаем базу данных...");
				if(action == "mysqlcr") toastr.warning("Создаем базу данных...");
			}
		});
	}
</script>
<?if($server['server_mysql'] == 1):?>
<script>    
	$('#editForm').ajaxForm({ 
		url: '/servers/mysql/ajax/<?php echo $server['server_id'] ?>',
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
<?endif;?>
<?php echo $footer ?>
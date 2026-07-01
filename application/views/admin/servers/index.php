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
				<?php foreach($servers as $item): ?>
				<div class="col-lg-6 mb-10">
					<div class="card card-custom wave mb-2 bg-light-<?php if($item['server_status'] == 0): ?>warning
						<?php elseif($item['server_status'] == 1): ?>danger
						<?php elseif($item['server_status'] == 2): ?>success
						<?php elseif($item['server_status'] == 3): ?>primary
						<?php elseif($item['server_status'] == 4 || $item['server_status'] == 5 || $item['server_status'] == 6 || $item['server_status'] == 7): ?>info
						<?php endif; ?>">
						<div class="card-body">
							<div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
								<div class="d-flex flex-column mr-5">
									<a href="/admin/servers/control/index/<?php echo $item['server_id'] ?>" class="h4 text-dark text-hover-primary mb-5"><?php echo $item['location_ip2'] ?>:<?php echo $item['server_port'] ?><small class="text-muted font-size-sm ml-2">ID <?php echo $item['server_id'] ?></small></a>
									<p class="text-dark-50"><?php echo $item['game_name'] ?></p>
								</div>
								<div class="ml-6 ml-lg-0 ml-xxl-6 flex-shrink-0">
									<?php if($item['server_status'] == 1): ?>
									<a href="javascript:;" onClick="actionserver(<?php echo $item['server_id'] ?>,'start')" class="btn btn-icon btn-success" data-toggle="tooltip" title="Запустить">
									<i class="fa fa-power-off"></i>
									</a>
									<a href="javascript:;" class="btn btn-icon btn-light" data-toggle="tooltip" title="Сервер должен быть включен!">
									<i class="fa fa-redo"></i>
									</a>
									<?php elseif($item['server_status'] == 2): ?>
									<a href="javascript:;" onClick="actionserver(<?php echo $item['server_id'] ?>,'stop')" class="btn btn-icon btn-danger" data-toggle="tooltip" title="Остановить">
									<i class="fa fa-power-off"></i>
									</a>
									<a href="javascript:;" onClick="actionserver(<?php echo $item['server_id'] ?>,'restart')" class="btn btn-icon btn-info" data-toggle="tooltip" title="Перезапустить">
									<i class="fa fa-redo"></i>
									</a>
									<?php elseif($item['server_status'] == 0 || $item['server_status'] == 3 || $item['server_status'] == 4 || $item['server_status'] == 5 || $item['server_status'] == 6 || $item['server_status'] == 7): ?>
									<a href="javascript:;" class="btn btn-icon btn-light" data-toggle="tooltip" title="Вы не можете использовать это действие!">
									<i class="fa fa-power-off"></i>
									</a>
									<a href="javascript:;" class="btn btn-icon btn-light" data-toggle="tooltip" title="Вы не можете использовать это действие!">
									<i class="fa fa-redo"></i>
									</a>
									<?php endif; ?> 
									<a href="/admin/servers/control/index/<?php echo $item['server_id'] ?>" class="btn btn-icon btn-primary" data-toggle="tooltip" title="Перейти">
									<i class="fa fa-sign-in-alt"></i>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
				<?php if(empty($servers)): ?>
				<div class="col-xl-12 col-xxl-12">
					<div class="alert alert-custom alert-light-primary fade show mb-4" role="alert">
						<div class="alert-icon">
							<i class="flaticon-exclamation"></i>
						</div>
						<div class="alert-text">На данный момент нет игровых серверов.</div>
						<div class="alert-close">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">
							<i class="ki ki-close"></i>
							</span>
							</button>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
			<?php echo $pagination ?>
		</div>
	</div>
</div>
<script>
	function actionserver(serverid, actionserver) {
		$.ajax({ 
			url: '/admin/servers/control/actionserver/'+serverid+'/'+actionserver,
			dataType: 'text',
			success: function(data) {
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
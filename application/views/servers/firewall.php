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
										<i class="fa fa-user-shield icon-3x text-primary"></i>
									</div>
								</div>
								<h4 class="font-weight-bolder my-2">Firewall</h4>
								<div class="text-center font-weight-bold mb-2">
									Блокировка ip адресов
								</div>
							</div>
						</div>
					</div>
					<div class="card card-custom mb-3">
						<div class="card-body px-1" style="padding: 0.3rem 2.25rem;">
							<div class="navi navi-hover navi-active navi-link-rounded navi-bold navi-icon-center navi-light-icon">
								<div class="navi-item my-2">
									<a href="javascript:;" data-toggle="modal" data-target="#firewallinfo" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-exclamation-circle"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Информация</span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-9">
					<div class="card card-custom mb-2">
						<div class="card-body">
							<form id="addForm" method="POST" style="padding:0px; margin:0px;">
								<div class="input-group" style="width: 100%;">
									<input type="text" class="form-control" id="address" name="address" placeholder="Введите IP адрес...">
									<div class="input-group-append">
										<button type="submit" class="btn btn-primary btn-icon" data-toggle="tooltip" title="" data-placement="right" data-original-title="Добавить"><i class="fas fa-plus"></i></button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="row">
						<?php foreach($Firewalls as $item): ?>
						<div class="col-xl-12">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title"><?php echo $item['server_ip'] ?><small class="text-muted font-size-sm ml-2"><?php echo $item['firewall_add'] ?></small></h3>
									<div class="card-toolbar">
										<a href="javascript:;" onclick="sendAction('delete', <?php echo $item['firewall_id'] ?>)" class="btn btn-clean btn-icon">
										<i class="fa fa-times" data-toggle="tooltip" data-placement="right" title="" data-original-title="Разблокировать IP"></i>
										</a>
									</div>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
						<?php if(empty($Firewalls)): ?>
						<div class="col-xl-12">
							<div class="alert alert-custom alert-light-primary fade show mb-4" role="alert">
								<div class="alert-icon">
									<i class="flaticon-exclamation"></i>
								</div>
								<div class="alert-text">На данный момент список пуст.</div>
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
				</div>
			</div>
		</div>
	</div>
</div>
<!--begin::Modal-->
<div class="modal fade" id="firewallinfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Информация</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="modal-body">
				<p>
					Firewall - это гарантированная защита Вашего сервера от нежелательных гостей. Он позволяет ограничить доступ определенным лицам по IP-адресу прямо из Панели управления. В отличие от бана в игре, такая блокировка полностью фильтрует весь трафик с данного IP-адреса.
				</p>
				<hr>
				<h6 class="alert-heading">Примеры для блокировки IP</h6>
				<p>
					1.1.1.1 - Одиночный IP адрес<br>
					1.1.1.0/24 - Сеть из 256 адресов (с 1.1.1.1 по 1.1.1.255)<br>
					1.1.1.0/255.255.255.0 - Сеть из 256 адресов (с 1.1.1.1 по 1.1.1.255)<br>
					1.1.0.0/16 - Сеть из 65536 адресов (с 1.1.1.1 по 1.1.255.255)<br>
					1.1.0.0/255.255.0.0 - Сеть из 65536 адресов (с 1.1.1.1 по 1.1.255.255)
				</p>
				</h4>
			</div>
		</div>
	</div>
</div>
<!--end::Modal-->
<script> 
	$('#addForm').ajaxForm({ 
		url: '/servers/firewall/ajax/<?php echo $server['server_id'] ?>',
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
   
	function sendAction(action, firewallid) {
		switch(action) {
			case "delete":
			{
				if(!confirm("Вы уверенны в том, что хотите разблокировать IP адрес?")) return;
				break;
			}
		}     
		$.ajax({ 
			url: '/servers/firewall/action/'+action+'/<?php echo $server['server_id'] ?>/'+firewallid,
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
			}
		});
	}  
</script>
<?php echo $footer ?>
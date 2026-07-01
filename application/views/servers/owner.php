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
										<i class="fa fa-user-friends icon-3x text-primary"></i>
									</div>
								</div>
								<h4 class="font-weight-bolder my-2">Друзья</h4>
								<div class="text-center font-weight-bold mb-2">
									Совместное управление
								</div>
							</div>
						</div>
					</div>
					<div class="card card-custom mb-3">
						<div class="card-body px-1" style="padding: 0.3rem 2.25rem;">
							<div class="navi navi-hover navi-active navi-link-rounded navi-bold navi-icon-center navi-light-icon">
								<div class="navi-item my-2">
									<a href="javascript:;" data-toggle="modal" data-target="#ownerinfo" class="navi-link">
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
									<input type="text" class="form-control" id="ownerid" name="ownerid" placeholder="Введите ID друга...">
									<div class="input-group-append">
										<button type="submit" class="btn btn-primary btn-icon" data-toggle="tooltip" title="" data-placement="right" data-original-title="Добавить"><i class="fas fa-plus"></i></button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="row">
						<?php foreach($serversOwners as $item): ?>
						<div class="col-xl-12">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title"><?php echo $item['user_firstname'] ?> <?php echo $item['user_lastname'] ?><small class="text-muted font-size-sm ml-2"><?php if($item['owner_status'] == 0): ?>Не подтвержден<?php elseif($item['owner_status'] == 1): ?><?php echo $item['owner_add'] ?><?php endif; ?></small></h3>
									<div class="card-toolbar">
										<a href="javascript:;" onclick="sendAction('delete', <?php echo $item['owner_id'] ?>)" class="btn btn-clean btn-icon">
										<i class="fa fa-times" data-toggle="tooltip" data-placement="right" title="" data-original-title="Удалить"></i>
										</a>
									</div>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
						<?php if(empty($serversOwners)): ?>
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
<div class="modal fade" id="ownerinfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
					Система совладельцев серверов -  это система, позволяющая добавить своего друга в совладельцы. Добавив друга, он также сможет как и вы управлять вашим сервером.
				</p>
			</div>
		</div>
	</div>
</div>
<!--end::Modal-->
<script> 
	$('#addForm').ajaxForm({ 
		url: '/servers/owner/create/<?php echo $server['server_id'] ?>',
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
   
	function sendAction(action, ownerid) {
		switch(action) {
			case "delete":
			{
				if(!confirm("Вы действительно хотите удалить друга?")) return;
				break;
			}
		}
		$.ajax({ 
			url: '/servers/owner/delete/<?php echo $server['server_id'] ?>/'+ownerid,
			dataType: 'text',
			type: 'POST',
			success: function(data) {
				console.log(data);
				data = $.parseJSON(data);
				switch(data.status) {
					case 'error':
						toastr.error(data.error);
						$('#controlBtns button').prop('disabled', false);
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
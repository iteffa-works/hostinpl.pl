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
				<div class="col-xl-4 col-xxl-4 mb-4">
					<div class="card card-custom card-sticky">
						<div class="card-body px-5">
							<div class="navi navi-hover navi-active navi-link-rounded navi-bold navi-icon-center navi-light-icon">
								<div class="navi-item my-2">
									<a href="/admin/tickets/index?status=1" class="navi-link">
									<span class="navi-icon mr-4">
									<i class="fa fa-user-clock icon-lg"></i>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Новые запросы</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="/admin/tickets" class="navi-link">
									<span class="navi-icon mr-4">
									<i class="fa fa-headset icon-lg"></i>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Все запросы</span>
									</a>
								</div>
								<hr>
								<div class="navi-item my-2">
									<a href="/admin/ticketcategory" class="navi-link">
									<span class="navi-icon mr-4">
									<i class="fa fa-list-alt icon-lg"></i>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Все категории</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="/admin/ticketcategory/create" class="navi-link">
									<span class="navi-icon mr-4">
									<i class="fa fa-plus icon-lg"></i>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Создать категорию</span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-8 col-xxl-8">
					<div class="card card-custom card-sticky">
						<div class="card-body px-5">
							<form method="POST" id="createForm" style="padding:0px; margin:0px;">
								<div class="form-group form-md-line-input">
									<input type="text" class="form-control" id="name" name="name" placeholder="Введите тему обращения">
								</div>
								<div class="form-group form-md-line-input">
									<input type="text" class="form-control" id="pid" name="pid" placeholder="Введите ID клиента">
								</div>
								<div class="form-group form-md-line-input">
									<textarea class="form-control" id="text" name="text" rows="3" placeholder="Сообщение..."></textarea>
								</div>
								<hr>
								<button type="submit" class="btn btn-primary btn-lg btn-block">Отправить</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#createForm').ajaxForm({ 
		url: '/admin/tickets/create/ajax',
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
					setTimeout("redirect('/admin/tickets/view/index/" + data.id + "')", 1500);
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			$('button[type=submit]').prop('disabled', true);
		}
	});
</script>
<?php echo $footer ?>
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
							<div class="px-4 mt-4 mb-10">
								<a href="/admin/tickets/create" class="btn btn-block btn-primary font-weight-bold text-uppercase py-4 px-6 text-center">Написать клиенту</a>
							</div>
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
									<a href="/admin/ticketcategory" class="navi-link active">
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
					<div class="card card-custom">
						<div class="card-body">
							<form id="editForm" method="POST">
								<div class="form-group form-md-line-input">
									<input type="text" class="form-control" id="name" name="name" placeholder="Введите название" value="<?php echo $category['category_name'] ?>">
								</div>
								<div class="form-group form-md-line-input">
									<select class="form-control" id="status" name="status" aria-required="true" aria-invalid="false" aria-describedby="delivery-error">
										<option value="0"<?php if($category['category_status'] == 0): ?> selected="selected"<?php endif; ?>>Выключена</option>
										<option value="1"<?php if($category['category_status'] == 1): ?> selected="selected"<?php endif; ?>>Включена</option>
									</select>
								</div>
								<hr>
								<div class="m-btn-group m-btn-group--pill btn-group m-btn-group m-btn-group--pill btn-block" role="group" aria-label="Large button group">
									<button type="submit" class="btn btn-primary btn-outline  btn-block sbold uppercase">Сохранить изменения</button>
									<a data-toggle="tooltip" data-placement="right" title="" data-original-title="Удалить" style="height: 3.1rem;" href="/admin/ticketcategory/edit/delete/<?php echo $category['category_id'] ?>" class="btn btn-danger btn-elevate btn-icon"><i class="fa fa-trash-alt"></i></a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#editForm').ajaxForm({ 
		url: '/admin/ticketcategory/edit/ajax/<?php echo $category['category_id'] ?>',
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
					setTimeout("redirect('/admin/ticketcategory')", 1500);
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			$('button[type=submit]').prop('disabled', true);
		}
	});
</script>
<?php echo $footer ?>
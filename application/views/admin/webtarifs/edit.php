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
			<div class="card card-custom">
				<div class="card-header">
					<div class="card-title">
						<h3 class="card-label">Редактирование тарифа
						</h3>
					</div>
				</div>
				<div class="card-body">
					<form id="editForm" method="POST">
						<div class="form-group form-md-line-input">
							<label>Введите название тарифа</label>
							<input type="text" class="form-control" id="name" name="name" placeholder="Введите название тарифа" value="<?php echo $tarif['tarif_name'] ?>">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите название шаблона в панели ISP</label>
							<input type="text" class="form-control" id="package" name="package" placeholder="Введите название шаблона в панели ISP" value="<?php echo $tarif['package'] ?>">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите стоимость тарифа</label>
							<input type="text" class="form-control" id="price" name="price" placeholder="Введите стоимость тарифа" value="<?php echo $tarif['tarif_price'] ?>">
						</div>
						<div class="form-group form-md-line-input">
							<label>Выберите статус тарифа</label>
							<select class="form-control" id="status" name="status" name="delivery" aria-required="true" aria-invalid="false" aria-describedby="delivery-error">
								<option value="1"<?php if($tarif['tarif_status'] == 1): ?> selected="selected"<?php endif; ?>>Включен</option>
								<option value="0"<?php if($tarif['tarif_status'] == 0): ?> selected="selected"<?php endif; ?>>Выключен</option>
							</select>
						</div>
						<hr>
						<div class="m-btn-group m-btn-group--pill btn-group m-btn-group m-btn-group--pill btn-block" role="group" aria-label="Large button group">
							<button type="submit" class="btn btn-primary btn-outline  btn-block sbold uppercase">Сохранить изменения</button>
							<a data-toggle="tooltip" data-placement="right" title="" data-original-title="Удалить" style="height: 3.1rem;" href="/admin/webtarifs/edit/delete/<?php echo $tarif['tarif_id'] ?>" class="btn btn-danger btn-elevate btn-icon"><i class="fa fa-trash-alt"></i></a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#editForm').ajaxForm({ 
		url: '/admin/webtarifs/edit/ajax/<?php echo $tarif['tarif_id'] ?>',
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
					setTimeout("redirect('/admin/webtarifs')", 1500);
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			$('button[type=submit]').prop('disabled', true);
		}
	});
</script>
<?php echo $footer ?>
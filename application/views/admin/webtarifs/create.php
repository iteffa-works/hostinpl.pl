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
						<h3 class="card-label">Создание тарифа
						</h3>
					</div>
				</div>
				<div class="card-body">
					<form id="createForm" method="POST">
						<div class="form-group form-md-line-input">
							<label>Введите название тарифа</label>
							<input type="text" class="form-control" id="name" name="name" placeholder="Введите название тарифа">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите название шаблона в панели ISP</label>
							<input type="text" class="form-control" id="package" name="package" placeholder="Введите название шаблона в панели ISP">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите стоимость тарифа</label>
							<input type="text" class="form-control" id="price" name="price" placeholder="Введите стоимость тарифа">
						</div>
						<hr>
						<input type="submit" name="otp" class="btn btn-primary m-btn m-btn--air btn-outline  btn-block sbold uppercase" value="Сохранить">
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#createForm').ajaxForm({ 
		url: '/admin/webtarifs/create/ajax',
		dataType: 'json',
		success: function(data) {
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
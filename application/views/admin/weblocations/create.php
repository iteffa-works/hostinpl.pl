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
						<h3 class="card-label">Создание web-локации
						</h3>
					</div>
				</div>
				<div class="card-body">
					<form id="createForm" method="POST">
						<div class="form-group form-md-line-input">
							<label>Введите название локации</label>
							<input type="text" class="form-control" id="name" name="name" placeholder="Введите название локации">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите IP (0.0.0.0)</label>
							<input type="text" class="form-control" id="ip" name="ip" placeholder="Введите IP (0.0.0.0)">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите URL (domain.ru)</label>
							<input type="text" class="form-control" id="url" name="url" placeholder="Введите URL (domain.ru)">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите NS Сервера локации (ns1.domain.ru и ns2.domain.ru)</label>
							<input type="text" class="form-control" id="ns_servers" name="ns_servers" placeholder="Введите NS Сервера локации (ns1.domain.ru и ns2.domain.ru)">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите имя пользователя (можно вводить не только root, но и пользователя с правами администратора)</label>
							<input type="text" class="form-control" id="user" name="user" placeholder="Введите имя пользователя">
						</div>
						<div class="form-group form-md-line-input">
							<label>Пароль</label>
							<input type="text" class="form-control" id="password" name="password" placeholder="Пароль">
						</div>
						<div class="form-group form-md-line-input">
							<label>Повторите пароль</label>
							<input type="text" class="form-control" id="password2" name="password2" placeholder="Повторите пароль">
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
		url: '/admin/weblocations/create/ajax',
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
					setTimeout("redirect('/admin/weblocations')", 1500);
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			$('button[type=submit]').prop('disabled', true);
		}
	});
</script>
<?php echo $footer ?>
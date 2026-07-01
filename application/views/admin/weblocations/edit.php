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
						<h3 class="card-label">Редактирование web-локации
						</h3>
					</div>
				</div>
				<div class="card-body">
					<form id="editForm" method="POST">
						<div class="form-group form-md-line-input">
							<label>Введите название локации</label>
							<input type="text" class="form-control" id="name" name="name" placeholder="Введите название локации" value="<?php echo $location['location_name'] ?>">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите IP (0.0.0.0)</label>
							<input type="text" class="form-control" id="ip" name="ip" placeholder="Введите IP (0.0.0.0)" value="<?php echo $location['location_ip'] ?>">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите URL (domain.ru)</label>
							<input type="text" class="form-control" id="url" name="url" placeholder="Введите URL (domain.ru)" value="<?php echo $location['location_url'] ?>">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите NS Сервера локации (ns1.domain.ru и ns2.domain.ru)</label>
							<input type="text" class="form-control" id="ns_servers" name="ns_servers" placeholder="Введите NS Сервера локации (ns1.domain.ru и ns2.domain.ru)" value="<?php echo $location['ns_servers'] ?>">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите имя пользователя (можно вводить не только root, но и пользователя с правами администратора)</label>
							<input type="text" class="form-control" id="user" name="user" placeholder="Введите имя пользователя" value="<?php echo $location['location_user'] ?>">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите ID тарифов для которых будет доступна локация (через пробел)</label>
							<input type="text" class="form-control" id="location_tarifs" name="location_tarifs" placeholder="Введите ID тарифов для которых будет доступна локация (через пробел)" value="<?php echo $location['location_tarifs'] ?>">
						</div>
						<hr>
						<div class="card card-body" style="margin-bottom: 1rem;padding: 1rem;">
							<div class="form-group" style="margin-bottom: 0rem;">
								<div class="checkbox-list">
									<label class="checkbox">
									<input type="checkbox" id="editpassword" name="editpassword" onChange="togglePassword()">
									<span></span>
									Изменить пароль
									</label>
								</div>
							</div>
						</div>
						<div class="form-group form-md-line-input">
							<label>Пароль</label>
							<input type="password" class="form-control" id="password" name="password" placeholder="Пароль" disabled>
						</div>
						<div class="form-group form-md-line-input">
							<label>Повторите пароль</label>
							<input type="password" class="form-control" id="password2" name="password2" placeholder="Повторите пароль" disabled>
						</div>
						<hr>
						<div class="form-group form-md-line-input">
							<label>Выберите статус локации</label>
							<select class="form-control" id="status" name="status" aria-required="true" aria-invalid="false" aria-describedby="delivery-error">
								<option value="1"<?php if($location['location_status'] == 1): ?> selected="selected"<?php endif; ?>>Включена</option>
								<option value="0"<?php if($location['location_status'] == 0): ?> selected="selected"<?php endif; ?>>Выключена</option>
							</select>
						</div>
						<hr>
						<div class="m-btn-group m-btn-group--pill btn-group m-btn-group m-btn-group--pill btn-block" role="group" aria-label="Large button group">
							<button type="submit" class="btn btn-primary btn-outline  btn-block sbold uppercase">Сохранить изменения</button>
							<a data-toggle="tooltip" data-placement="right" title="" data-original-title="Удалить" style="height: 3.1rem;" href="/admin/weblocations/delete=<?php echo $location['location_id'] ?>" class="btn btn-danger btn-elevate btn-icon"><i class="fa fa-trash-alt"></i></a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#editForm').ajaxForm({ 
		url: '/admin/weblocations/edit/ajax/<?php echo $location['location_id'] ?>',
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
<?php echo $footer ?>
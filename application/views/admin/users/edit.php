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
				<div class="col-xl-4">
					<div class="card card-custom mb-3">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div class="flex-shrink-0 mr-4 symbol symbol-65 symbol-circle">
									<img src="<?php echo $url ?><?if($user['user_img']) {echo $user['user_img'];}else{ echo '/application/public/img/user.png';}?>" alt="image">
								</div>
								<div class="d-flex flex-column mr-auto">
									<a href="javascript:;" class="card-title text-hover-primary font-weight-bolder font-size-h5 text-dark mb-1"><?php echo $user['user_firstname'] ?> <?php echo $user['user_lastname'] ?></a>
									<span class="text-muted font-weight-bold"><?php if($user['user_access_level'] == 3): ?> Администратор <?php elseif($user['user_access_level'] == 2): ?> Тех.Поддержка <?php elseif($user['user_access_level'] == 1): ?> Клиент <?php elseif($user['user_access_level'] == 0): ?> Демонстрация <?php endif; ?></span>
								</div>
							</div>
						</div>
					</div>
					<div class="card card-custom">
						<div class="card-body pt-4">
							<div class="navi navi-bold navi-hover navi-active navi-link-rounded">
								<div class="navi-item mb-2">
									<a href="/admin/servers/index?userid=<?php echo $user['user_id'] ?>" class="navi-link py-4">
									<span class="navi-icon mr-2">
									<i class="fa fa-server"></i>
									</span>
									<span class="navi-text font-size-lg">Сервера пользователя</span>
									</a>
								</div>
								<div class="navi-item mb-2">
									<a href="/admin/webhost/index?userid=<?php echo $user['user_id'] ?>" class="navi-link py-4">
									<span class="navi-icon mr-2">
									<i class="fa fa-globe"></i>
									</span>
									<span class="navi-text font-size-lg">Web сайты пользователя</span>
									</a>
								</div>
								<div class="navi-item mb-2">
									<a href="/admin/tickets/index?userid=<?php echo $user['user_id'] ?>" class="navi-link py-4">
									<span class="navi-icon mr-2">
									<i class="fa fa-headset"></i>
									</span>
									<span class="navi-text font-size-lg">Запросы пользователя</span>
									</a>
								</div>
								<div class="navi-item mb-2">
									<a href="/admin/invoices/index?userid=<?php echo $user['user_id'] ?>" class="navi-link py-4">
									<span class="navi-icon mr-2">
									<i class="fa fa-donate"></i>
									</span>
									<span class="navi-text font-size-lg">Платежи пользователя</span>
									</a>
								</div>
								<div class="navi-item mb-2">
									<a href="/admin/waste/index?userid=<?php echo $user['user_id'] ?>" class="navi-link py-4">
									<span class="navi-icon mr-2">
									<i class="fa fa-tasks"></i>
									</span>
									<span class="navi-text font-size-lg">Операции пользователя</span>
									</a>
								</div>
								<div class="navi-item mb-2">
									<a href="javascript:;" data-toggle="modal" data-target="#authloghostinpl" class="navi-link py-4">
									<span class="navi-icon mr-2">
									<i class="fa fa-map-marker-alt"></i>
									</span>
									<span class="navi-text font-size-lg">Логи авторизации</span>
									</a>
								</div>
								<?php if($user_access_level == 3):?>
								<div class="navi-item mb-2">
									<a href="javascript:;" onClick="action_user('user_del')" class="navi-link py-4">
									<span class="navi-icon mr-2">
									<i class="fa fa-trash-alt"></i>
									</span>
									<span class="navi-text font-size-lg">Удалить пользователя</span>
									</a>
								</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-8">
					<div class="card card-custom">
						<div class="card-body">
							<form id="editForm" method="POST">
								<div class="form-group form-md-line-input">
									<input type="text" class="form-control" id="lastname" name="lastname" placeholder="<?php echo $user['user_lastname'] ?>" value="<?php echo $user['user_lastname'] ?>">
								</div>
								<div class="form-group form-md-line-input">
									<input type="text" class="form-control" id="firstname" name="firstname" placeholder="<?php echo $user['user_firstname'] ?>" value="<?php echo $user['user_firstname'] ?>">
								</div>
								<div class="form-group form-md-line-input">
									<input type="text" class="form-control" id="email" name="email" placeholder="<?php echo $user['user_email'] ?>" value="<?php echo $user['user_email'] ?>">
								</div>
								<div class="form-group form-md-line-input">
									<select class="form-control" id="status" name="status" aria-required="true" aria-invalid="false" aria-describedby="delivery-error">
										<option value="1" <?php if($user['user_status'] == 1): ?> selected="selected"<?php endif; ?>>Разблокирован</option>
										<option value="0" <?php if($user['user_status'] == 0): ?> selected="selected"<?php endif; ?>>Заблокирован</option>
									</select>
								</div>
								<div class="form-group form-md-line-input">
									<select class="form-control" id="accesslevel" name="accesslevel" aria-required="true" aria-invalid="false" aria-describedby="delivery-error">
										<option value="0" <?php if($user['user_access_level'] == 0): ?> selected="selected"<?php endif; ?>>Демонстрация</option>
										<option value="1" <?php if($user['user_access_level'] == 1): ?> selected="selected"<?php endif; ?>>Клиент</option>
										<option value="2" <?php if($user['user_access_level'] == 2): ?> selected="selected"<?php endif; ?>>Техническая поддержка</option>
										<option value="3" <?php if($user['user_access_level'] == 3): ?> selected="selected"<?php endif; ?>>Администрация</option>
									</select>
								</div>
								<div class="form-group form-md-line-input">
									<input type="text" class="form-control" id="balance" name="balance" placeholder="Баланс" value="<?php echo $user['user_balance'] ?>">
								</div>
								<div class="form-group form-md-line-input">
									<input type="text" class="form-control" id="vk_id" name="vk_id" placeholder="ID VK" value="<?php echo $user['user_vk_id'] ?>">
								</div>
								<div class="form-group form-md-line-input">
									<select class="form-control" id="test_server" name="test_server" aria-required="true" aria-invalid="false" aria-describedby="delivery-error">
										<option value="1" <?php if($user['test_server'] == 1): ?> selected="selected"<?php endif; ?>>Тестовый период разрешен</option>
										<option value="2" <?php if($user['test_server'] == 2): ?> selected="selected"<?php endif; ?>>Тестовый период запрещен</option>
									</select>
								</div>
								<div class="form-group form-md-line-input">
									<select class="form-control" id="user_activate" name="user_activate" aria-required="true" aria-invalid="false" aria-describedby="delivery-error">
										<option value="1" <?php if($user['user_activate'] == 1): ?> selected="selected"<?php endif; ?>>Аккаунт подтвержден</option>
										<option value="0" <?php if($user['user_activate'] == 0): ?> selected="selected"<?php endif; ?>>Аккаунт неактивирован</option>
									</select>
								</div>
								<?php if($user_access_level == 3):?>
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
									<input type="password" class="form-control" id="password" name="password" placeholder="Пароль" disabled>
								</div>
								<div class="form-group form-md-line-input">
									<input type="password" class="form-control" id="password2" name="password2" placeholder="Повторите пароль" disabled>
								</div>
								<hr>
								<button type="submit" class="btn btn-primary m-btn m-btn--air btn-outline  btn-block sbold uppercase">Сохранить изменения</button>
								<?php endif; ?>  
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--begin::Modal-->
<div class="modal fade" id="authloghostinpl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Логи авторизации</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-striped m-table">
					<tbody>
						<?php foreach($visitors as $item):?>
						<tr>
							<td><?php echo $item['city'] ?></td>
							<td><?php echo $item['country'] ?>(<?php echo $item['code'] ?>)</td>
							<td><?php echo date("d.m.Y в H:i", strtotime($item['datetime'])) ?></td>
							<td><?php echo $item['ip'] ?></td>
							<td><?php if($item['status'] == 0): ?> 
								Ошибка входа (пароль <?php echo $item['password'] ?>)
								<?php elseif($item['status'] == 1): ?> 
								Вход в систему
								<?php elseif($item['status'] == 2): ?> 
								Выход из системы
								<?php elseif($item['status'] == 3): ?> 
								Попытка востановленя пароля
								<?php elseif($item['status'] == 4): ?> 
								Вход через VK
								<?php elseif($item['status'] == 5): ?> 
								Ошибка входа через VK
								<?php endif; ?> 
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!--end::Modal-->
<script>
	function action_user(action) {
		switch(action) {
			case "user_del":
			{
				if(!confirm("Вы уверенны в том, что хотите удалить пользователя ID-<?php echo $user['user_id'] ?>?")) return;
				break;
			}
		}
		$.ajax({ 
			url: '/admin/users/edit/delete/<?php echo $user['user_id'] ?>',
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
						setTimeout("redirect('/admin/users')", 1500);
						break;
				}
			},
			beforeSend: function(arr, options) {
	            toastr.warning("Ваш запрос обрабатывается, пожалуйста, подождите...");                          
	        }
		});
	}
	
	$('#editForm').ajaxForm({ 
		url: '/admin/users/edit/ajax/<?php echo $user['user_id'] ?>',
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
					setTimeout("reload()", 1100);
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
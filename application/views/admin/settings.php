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
				<div class="card-header card-header-tabs-line nav-tabs-line-3x">
					<div class="card-toolbar">
						<ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
							<li class="nav-item mr-3">
								<a class="nav-link active" data-toggle="tab" href="#setting">
								<span class="nav-icon">
								<i class="fas fa-cog"></i>
								</span>
								<span class="nav-text font-size-lg">Общие настройки</span>
								</a>
							</li>
							<li class="nav-item mr-3">
								<a class="nav-link" data-toggle="tab" href="#pay">
								<span class="nav-icon">
								<i class="fas fa-store"></i>
								</span>
								<span class="nav-text font-size-lg">Платежные системы</span>
								</a>
							</li>
							<li class="nav-item mr-3">
								<a class="nav-link" data-toggle="tab" href="#hostinpl">
								<span class="nav-icon">
								<i class="fas fa-cogs"></i>
								</span>
								<span class="nav-text font-size-lg">Прочие настройки</span>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#info">
								<span class="nav-icon">
								<i class="fas fa-info-circle"></i>
								</span>
								<span class="nav-text font-size-lg">Информация и бонусы</span>
								</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="card-body">
					<form class="form-group form-md-line-input" id="settings" method="post" action="" novalidate="novalidate">
						<div class="tab-content mt-5">
							<div class="tab-pane fade show active" id="setting" role="tabpanel">
								<div class="form-group">
									<label>URL сайта <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="url" name="url" placeholder="Введите URL сайта" value="<?php echo $settings['url'] ?>">
								</div>
								<div class="form-group">
									<label>Название сайта <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="title" name="title" placeholder="Введите название сайта" value="<?php echo $settings['title'] ?>">
								</div>
								<div class="form-group">
									<label>Описание сайта <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="description" name="description" placeholder="Введите описание сайта" value="<?php echo $settings['description'] ?>">
								</div>
								<div class="form-group">
									<label>Ключевые слова <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="keywords" name="keywords" placeholder="Введите ключевые слова" value="<?php echo $settings['keywords'] ?>">
								</div>
								<div class="form-group">
									<label>Имя отправителя <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="mail_sender" name="mail_sender" placeholder="Введите имя отправителя" value="<?php echo $settings['mail_sender'] ?>">
								</div>
								<div class="form-group">
									<label>E-mail Службы поддержки <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="mail_from" name="mail_from" placeholder="Введите E-mail Службы поддержки" value="<?php echo $settings['mail_from'] ?>">
								</div>
								<div class="form-group">
									<label>ID Вашей группы ВКонтакте</label>
									<input type="text" class="form-control" id="public" name="public" placeholder="Введите ID Вашей группы ВКонтакте" value="<?php echo $settings['public'] ?>">
								</div>
								<div class="form-group">
									<label>Cсылка на ваш логотип</label>
									<input type="text" class="form-control" id="logo" name="logo" placeholder="Введите ссылку на ваш логотип" value="<?php echo $settings['logo'] ?>">
								</div>
								<div class="form-group">
									<label>Статистика хостинга</label>
									<select class="form-control" id="status_hostinpl" name="status_hostinpl">
										<option value="0"<?php if($settings['status_hostinpl'] == 0): ?> selected="selected"<?php endif; ?>>Выключена</option>
										<option value="1"<?php if($settings['status_hostinpl'] == 1): ?> selected="selected"<?php endif; ?>>Включена</option>
									</select>
								</div>
								<div class="form-group">
									<label>Веб.хостинг</label>
									<select class="form-control" id="webhost" name="webhost">
										<option value="0"<?php if($settings['webhost'] == 0): ?> selected="selected"<?php endif; ?>>Выключен</option>
										<option value="1"<?php if($settings['webhost'] == 1): ?> selected="selected"<?php endif; ?>>Включен</option>
									</select>
								</div>
							</div>
							<div class="tab-pane fade" id="pay" role="tabpanel">
								<div class="form-group">
									<label>Модуль быстрой оплаты</label>
									<select class="form-control" id="oplata_status" name="oplata_status">
										<option value="1"<?php if($settings['oplata_status'] == 1): ?> selected="selected"<?php endif; ?>>Включен</option>
										<option value="0"<?php if($settings['oplata_status'] == 0): ?> selected="selected"<?php endif; ?>>Выключен</option>
									</select>
								</div>
								<div class="form-group">
									<label>Платежная система быстрой оплаты</label>
									<select class="form-control" id="oplatahostinpl" name="oplatahostinpl">
										<option value="freepay"<?php if($settings['oplatahostinpl'] == "freepay"): ?> selected="selected"<?php endif; ?>>Free-kassa</option>
										<option value="robopay"<?php if($settings['oplatahostinpl'] == "robopay"): ?> selected="selected"<?php endif; ?>>Robokassa</option>
										<option value="litepay"<?php if($settings['oplatahostinpl'] == "litepay"): ?> selected="selected"<?php endif; ?>>Litekassa</option>
										<option value="enotpay"<?php if($settings['oplatahostinpl'] == "enotpay"): ?> selected="selected"<?php endif; ?>>Enot</option>
										<option value="anypay"<?php if($settings['oplatahostinpl'] == "anypay"): ?> selected="selected"<?php endif; ?>>Anypay</option>
										<option value="unitpay"<?php if($settings['oplatahostinpl'] == "unitpay"): ?> selected="selected"<?php endif; ?>>Unitpay</option>
										<option value="yandexkassa"<?php if($settings['oplatahostinpl'] == "yandexkassa"): ?> selected="selected"<?php endif; ?>>Yoomoney</option>
										<option value="qiwi"<?php if($settings['oplatahostinpl'] == "qiwi"): ?> selected="selected"<?php endif; ?>>Qiwi</option>
									</select>
								</div>
								<hr>
								<div class="form-group">
									<label>Платежная система: Freekassa</label>
									<select class="form-control" id="freekassa" name="freekassa">
										<option value="1"<?php if($settings['freekassa'] == 1): ?> selected="selected"<?php endif; ?>>Включена</option>
										<option value="0"<?php if($settings['freekassa'] == 0): ?> selected="selected"<?php endif; ?>>Выключена</option>
									</select>
								</div>
								<div class="form-group">
									<label>Платежная система: Robokassa</label>
									<select class="form-control" id="robokassa" name="robokassa">
										<option value="1"<?php if($settings['robokassa'] == 1): ?> selected="selected"<?php endif; ?>>Включена</option>
										<option value="0"<?php if($settings['robokassa'] == 0): ?> selected="selected"<?php endif; ?>>Выключена</option>
									</select>
								</div>
								<div class="form-group">
									<label>Платежная система: Litekassa</label>
									<select class="form-control" id="litekassa" name="litekassa">
										<option value="1"<?php if($settings['litekassa'] == 1): ?> selected="selected"<?php endif; ?>>Включена</option>
										<option value="0"<?php if($settings['litekassa'] == 0): ?> selected="selected"<?php endif; ?>>Выключена</option>
									</select>
								</div>
								<div class="form-group">
									<label>Платежная система: Enot</label>
									<select class="form-control" id="enotpay" name="enotpay">
										<option value="1"<?php if($settings['enotpay'] == 1): ?> selected="selected"<?php endif; ?>>Включена</option>
										<option value="0"<?php if($settings['enotpay'] == 0): ?> selected="selected"<?php endif; ?>>Выключена</option>
									</select>
								</div>
								<div class="form-group">
									<label>Платежная система: Anypay</label>
									<select class="form-control" id="anypay" name="anypay">
										<option value="1"<?php if($settings['anypay'] == 1): ?> selected="selected"<?php endif; ?>>Включена</option>
										<option value="0"<?php if($settings['anypay'] == 0): ?> selected="selected"<?php endif; ?>>Выключена</option>
									</select>
								</div>
								<div class="form-group">
									<label>Платежная система: Unitpay</label>
									<select class="form-control" id="unitpay" name="unitpay">
										<option value="0"<?php if($settings['unitpay'] == 0): ?> selected="selected"<?php endif; ?>>Выключена</option>
										<option value="1"<?php if($settings['unitpay'] == 1): ?> selected="selected"<?php endif; ?>>Включена</option>
									</select>
								</div>
								<div class="form-group">
									<label>Платежная система: Yoomoney</label>
									<select class="form-control" id="yandexkassa" name="yandexkassa">
										<option value="0"<?php if($settings['yandexkassa'] == 0): ?> selected="selected"<?php endif; ?>>Выключена</option>
										<option value="1"<?php if($settings['yandexkassa'] == 1): ?> selected="selected"<?php endif; ?>>Включена</option>
									</select>
								</div>
								<div class="form-group">
									<label>Платежная система: Qiwi</label>
									<select class="form-control" id="qiwi" name="qiwi">
										<option value="1"<?php if($settings['qiwi'] == 1): ?> selected="selected"<?php endif; ?>>Включена</option>
										<option value="0"<?php if($settings['qiwi'] == 0): ?> selected="selected"<?php endif; ?>>Выключена</option>
									</select>
								</div>
								<hr>
								<label>Платежная система: Free-Kassa</label>
								<div class="form-group form-md-line-input">
									<input type="text" class="form-control" id="fk_login" name="fk_login" placeholder="Введите ID" value="<?php echo $settings['fk_login'] ?>">
									<span class="form-text text-muted">Freekassa ID</span>
								</div>
								<div class="form-group">
									<input type="text" class="form-control" id="fk_password1" name="fk_password1" placeholder="Введите секретный ключ №1" value="<?php echo $settings['fk_password1'] ?>">
									<span class="form-text text-muted">Freekassa Key 1</span>
								</div>
								<div class="form-group">
									<input type="text" class="form-control" id="fk_password2" name="fk_password2" placeholder="Введите секретный ключ №2" value="<?php echo $settings['fk_password2'] ?>">
									<span class="form-text text-muted">Freekassa Key 2</span>
								</div>
								<hr>
								<label>Платежная система: Robokassa</label>
								<div class="form-group">
									<input type="text" class="form-control" id="rk_login" name="rk_login" placeholder="Введите ID" value="<?php echo $settings['rk_login'] ?>">
									<span class="form-text text-muted">Robokassa ID</span>
								</div>
								<div class="form-group">
									<input type="text" class="form-control" id="rk_password1" name="rk_password1" placeholder="Введите секретный ключ №1" value="<?php echo $settings['rk_password1'] ?>">
									<span class="form-text text-muted">Robokassa Key 1</span>
								</div>
								<div class="form-group">
									<input type="text" class="form-control" id="rk_password2" name="rk_password2" placeholder="Введите секретный ключ №2" value="<?php echo $settings['rk_password2'] ?>">
									<span class="form-text text-muted">Robokassa Key 2</span>
								</div>
								<hr>
								<label>Платежная система: Litekassa</label>
								<div class="form-group">
									<input type="text" class="form-control" id="lk_login" name="lk_login" placeholder="Введите ID" value="<?php echo $settings['lk_login'] ?>">
									<span class="form-text text-muted">Litekassa ID</span>
								</div>
								<div class="form-group">
									<input type="text" class="form-control" id="lk_password" name="lk_password" placeholder="Введите секретный ключ" value="<?php echo $settings['lk_password'] ?>">
									<span class="form-text text-muted">Litekassa Key</span>
								</div>
								<hr>
								<label>Платежная система: Enot</label>
								<div class="form-group">
									<input type="text" class="form-control" id="enot_login" name="enot_login" placeholder="Введите ID" value="<?php echo $settings['enot_login'] ?>">
									<span class="form-text text-muted">Enot ID</span>
								</div>
								<div class="form-group">
									<input type="text" class="form-control" id="enot_password1" name="enot_password1" placeholder="Введите секретный ключ №1" value="<?php echo $settings['enot_password1'] ?>">
									<span class="form-text text-muted">Секретный пароль</span>
								</div>
								<div class="form-group">
									<input type="text" class="form-control" id="enot_password2" name="enot_password2" placeholder="Введите секретный ключ №2" value="<?php echo $settings['enot_password2'] ?>">
									<span class="form-text text-muted">Дополнительный ключ</span>
								</div>
								<hr>
								<label>Платежная система: Anypay</label>
								<div class="form-group">
									<input type="text" class="form-control" id="anypay_login" name="anypay_login" placeholder="Введите ID" value="<?php echo $settings['anypay_login'] ?>">
									<span class="form-text text-muted">ID проекта</span>
								</div>
								<div class="form-group">
									<input type="text" class="form-control" id="anypay_password" name="anypay_password" placeholder="Введите секретный ключ" value="<?php echo $settings['anypay_password'] ?>">
									<span class="form-text text-muted">Секретный ключ</span>
								</div>
								<hr>
								<label>Платежная система: Unitpay</label>
								<div class="form-group form-md-line-input">
									<input type="text" class="form-control" id="unitpay_url" name="unitpay_url" placeholder="Введите URL" value="<?php echo $settings['unitpay_url'] ?>">
									<span class="form-text text-muted">Unitpay URL</span>
								</div>
								<div class="form-group">
									<input type="text" class="form-control" id="unitpay_secret" name="unitpay_secret" placeholder="Введите секретный ключ" value="<?php echo $settings['unitpay_secret'] ?>">
									<span class="form-text text-muted">Unitpay секретный ключ</span>
								</div>
								<hr>
								<label>Платежная система: Yoomoney</label>
								<div class="form-group">
									<input type="text" class="form-control" id="yk_login" name="yk_login" placeholder="Введите login" value="<?php echo $settings['yk_login'] ?>">
									<span class="form-text text-muted">Номер счета</span>
								</div>
								<div class="form-group">
									<input type="text" class="form-control" id="yk_password1" name="yk_password1" placeholder="Введите pass" value="<?php echo $settings['yk_password1'] ?>">
									<span class="form-text text-muted">Секретный ключ для проверки оповещения</span>
								</div>
								<hr>
								<label>Платежная система: Qiwi</label>
								<div class="form-group">
									<input type="text" class="form-control" id="qiwipublickey" name="qiwipublickey" placeholder="Введите публичный ключ" value="<?php echo $settings['qiwipublickey'] ?>">
									<span class="form-text text-muted">Публичный ключ</span>
								</div>
								<div class="form-group">
									<input type="text" class="form-control" id="qiwisecretkey" name="qiwisecretkey" placeholder="Введите секретный ключ" value="<?php echo $settings['qiwisecretkey'] ?>">
									<span class="form-text text-muted">Секретный ключ</span>
								</div>
								<div class="form-group">
									<label>Настройки формы Qiwi</label>
									<select class="form-control" id="qiwi_theme" name="qiwi_theme">
										<option value="1"<?php if($settings['qiwi_theme'] == 1): ?> selected="selected"<?php endif; ?>>Включены</option>
										<option value="0"<?php if($settings['qiwi_theme'] == 0): ?> selected="selected"<?php endif; ?>>Выключены</option>
									</select>
								</div>
								<div class="form-group">
									<input type="text" class="form-control" id="qiwi_themecode" name="qiwi_themecode" placeholder="Введите код темы (themeCode)" value="<?php echo $settings['qiwi_themecode'] ?>">
									<span class="form-text text-muted">Код темы (themeCode)</span>
								</div>
							</div>
							<div class="tab-pane fade" id="hostinpl" role="tabpanel">
								<div class="form-group">
									<label>Тестовый период</label>
									<select class="form-control" id="serv_test" name="serv_test">
										<option value="0"<?php if($settings['serv_test'] == 0): ?> selected="selected"<?php endif; ?>>Заказ запрещен</option>
										<option value="1"<?php if($settings['serv_test'] == 1): ?> selected="selected"<?php endif; ?>>Заказ разрешен (по одобрению)</option>
									</select>
								</div>
								<div class="form-group">
									<label>Подтверждение E-mail</label>
									<select class="form-control" id="register" name="register">
										<option value="0"<?php if($settings['register'] == 0): ?> selected="selected"<?php endif; ?>>Включен</option>
										<option value="1"<?php if($settings['register'] == 1): ?> selected="selected"<?php endif; ?>>Выключен</option>
									</select>
								</div>
								<hr>
								<label>Тех.работы</label>
								<div class="form-group">
									<select class="form-control" id="offline" name="offline">
										<option value="0"<?php if($settings['offline'] == 0): ?> selected="selected"<?php endif; ?>>Хостинг доступен</option>
										<option value="1"<?php if($settings['offline'] == 1): ?> selected="selected"<?php endif; ?>>Хостинг закрыт на тех.работы</option>
									</select>
									<span class="form-text text-muted">Статус тех.работ</span>
								</div>
								<div class="form-group form-md-line-input">
									<input type="text" class="form-control" id="offline_res" name="offline_res" placeholder="Введите сообщение тех.работ" value="<?php echo $settings['offline_res'] ?>">
									<span class="form-text text-muted">Сообщение тех.работ</span>
								</div>
								<hr>
								<label>Авторизация VK</label>
								<div class="form-group">
									<input type="text" class="form-control" id="vk_app_id" name="vk_app_id" placeholder="Введите ID приложения" value="<?php echo $settings['vk_app_id'] ?>">
									<span class="form-text text-muted">ID приложения</span>
								</div>
								<div class="form-group">
									<input type="text" class="form-control" id="vk_app_secretkey" name="vk_app_secretkey" placeholder="Введите Защищённый ключ приложения" value="<?php echo $settings['vk_app_secretkey'] ?>">
									<span class="form-text text-muted">Защищённый ключ приложения</span>
								</div>
								<div class="form-group form-md-line-input">
									<select class="form-control" id="vk_app_status" name="vk_app_status">
										<option value="0"<?php if($settings['vk_app_status'] == 0): ?> selected="selected"<?php endif; ?>>Выключена</option>
										<option value="1"<?php if($settings['vk_app_status'] == 1): ?> selected="selected"<?php endif; ?>>Включена</option>
									</select>
									<span class="form-text text-muted">Статус авторизаци</span>
								</div>
								<hr>
								<div class="form-group form-md-line-input">
									<label>Стоимость смены порта</label>
									<input type="text" class="form-control" id="portPrice" name="portPrice" placeholder="Введите стоимость смены порта" value="<?php echo $settings['portPrice'] ?>">
								</div>
							</div>
							<div class="tab-pane fade" id="info" role="tabpanel">
								<div class="form-group">
									<label>Конвертация бонусов</label>
									<input type="text" class="form-control" id="bonus1" name="bonus1" placeholder="Введите сумму" value="<?php echo $settings['bonus1'] ?>">
									<span class="form-text text-muted">Bonus 1</span>
								</div>
								<div class="form-group">
									<input type="text" class="form-control" id="bonus2" name="bonus2" placeholder="Введите сумму" value="<?php echo $settings['bonus2'] ?>">
									<span class="form-text text-muted">Bonus 2</span>
								</div>
								<div class="form-group">
									<input type="text" class="form-control" id="bonus3" name="bonus3" placeholder="Введите сумму" value="<?php echo $settings['bonus3'] ?>">
									<span class="form-text text-muted">Bonus 3</span>
								</div>
								<div class="form-group">
									<input type="text" class="form-control" id="bonus4" name="bonus4" placeholder="Введите сумму" value="<?php echo $settings['bonus4'] ?>">
									<span class="form-text text-muted">Bonus 4</span>
								</div>
								<hr>
								<div class="form-group">
									<label class="form-control-label">Процент реферальной системы (за заказ услуг)</label>
									<input type="text" class="form-control" id="ref_percent" name="ref_percent" placeholder="Введите процент" value="<?php echo $settings['ref_percent'] ?>">
								</div>
								<div class="form-group">
									<label class="form-control-label">Процент бонусов за пополнение</label>
									<input type="text" class="form-control" id="bonus_percent" name="bonus_percent" placeholder="Введите процент" value="<?php echo $settings['bonus_percent'] ?>">
								</div>
								<hr>
								<div class="form-group form-md-line-input">
									<label class="form-control-label">Контактый номер</label>
									<input type="text" class="form-control" id="phone" name="phone" placeholder="Введите номер" value="<?php echo $settings['phone'] ?>">
								</div>
							</div>
							<hr>
							<button type="submit" class="btn btn-primary btn-lg btn-block">Применить настройки</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#settings').ajaxForm({ 
		url: '/admin/settings/ajax',
		dataType: 'text',
		success: function(data) {
			data = $.parseJSON(data);
			switch(data.status) {
				case 'error':
					toastr.error(data.error);
					$('button[type=submit]').prop('disabled', false);
					break;
				case 'success':
					toastr.success(data.success);
					setTimeout("redirect('/admin/settings/index')", 3500);
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			$('button[type=submit]').prop('disabled', true);
		}
	});
</script>
<?php echo $footer ?>
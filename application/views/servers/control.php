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
										<?php if($server['server_status'] == 1): ?>
										<i class="fa fa-power-off icon-6x text-danger"></i>
										<?php elseif($server['server_status'] == 2): ?>
										<i class="fa fa-power-off icon-6x text-success"></i>
										<?php elseif($server['server_status'] == 3): ?>
										<i class="fas fa-clock icon-6x text-primary"></i>
										<?php elseif($server['server_status'] == 4 || $server['server_status'] == 5 || $server['server_status'] == 6 || $server['server_status'] == 7): ?>
										<i class="fas fa-clock icon-6x text-info"></i>
										<?php elseif($server['server_status'] == 0): ?>
										<i class="fas fa-exclamation-circle icon-6x text-warning"></i>
										<?php endif; ?>
									</div>
									<i class="symbol-badge symbol-badge-bottom bg-<?php if($server['server_status'] == 0): ?>warning
										<?php elseif($server['server_status'] == 1): ?>danger
										<?php elseif($server['server_status'] == 2): ?>success
										<?php elseif($server['server_status'] == 3): ?>primary
										<?php elseif($server['server_status'] == 4 || $server['server_status'] == 5 || $server['server_status'] == 6 || $server['server_status'] == 7): ?>info
										<?php endif; ?>"></i>
								</div>
								<h4 class="font-weight-bolder my-2"><?php echo $server['game_name'] ?></h4>
								<div class="text-muted font-weight-bold mb-2"><?php if($server['server_status'] == 1): ?>
									Остановлен
									<?php elseif($server['server_status'] == 2): ?>
									Запущен
									<?php elseif($server['server_status'] == 3): ?>
									Устанавливается
									<?php elseif($server['server_status'] == 4): ?>
									Переустанавливается
									<?php elseif($server['server_status'] == 5): ?>
									Создается BackUP
									<?php elseif($server['server_status'] == 6): ?>
									Устанавливается BackUP
									<?php elseif($server['server_status'] == 0): ?>
									Заблокирован
									<?php elseif($server['server_status'] == 7): ?>
									Обновляется
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
					<div class="card card-custom mb-3">
						<div class="card-body px-1" style="padding: 0.3rem 2.25rem;">
							<div class="navi navi-hover navi-active navi-link-rounded navi-bold navi-icon-center navi-light-icon">
								<?php if($server['server_status'] == 1): ?>
								<div class="navi-item my-2">
									<a href="javascript:;" onClick="sendAction(<?php echo $server['server_id'] ?>,'start')" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-power-off"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Запустить</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="javascript:;" onClick="sendAction(<?php echo $server['server_id'] ?>,'reinstall')" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fas fa-spinner"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Переустановить</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="javascript:;" data-toggle="modal" data-target="#pay" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-cart-plus"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Продлить</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="javascript:;" onClick="sendAction(<?php echo $server['server_id'] ?>,'backup')" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-cloud-download-alt"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Сделать BackUP</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="javascript:;" onClick="sendAction(<?php echo $server['server_id'] ?>,'unbackup')" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-cloud-upload-alt"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Развернуть BackUP</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="javascript:;" onClick="sendAction(<?php echo $server['server_id'] ?>,'delete_backup')" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fas fa-quidditch"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Удалить BackUP</span>
									</a>
								</div>
								<?php elseif($server['server_status'] == 2): ?>
								<div class="navi-item my-2">
									<a href="javascript:;" onClick="sendAction(<?php echo $server['server_id'] ?>,'stop')" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-power-off"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Остановить</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="javascript:;" onClick="sendAction(<?php echo $server['server_id'] ?>,'restart')" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-sync-alt"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Перезапустить</span>
									</a>
								</div>
								<?php elseif($server['server_status'] == 0): ?>
								<div class="navi-item my-2">
									<a href="javascript:;" data-toggle="modal" data-target="#pay" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-cart-plus"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Продлить</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="javascript:;" data-toggle="modal" data-target="#promised" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fas fa-magic"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Обещанный платеж</span>
									</a>
								</div>
								<?php endif; ?>
								<?php if($server['game_code'] == "ragemp"  AND $server['server_status'] == 1): ?>
								<div class="navi-item my-2">
									<a href="javascript:;" data-toggle="modal" data-target="#NodeJS" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fab fa-node-js"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Модули NodeJS</span>
									</a>
								</div>
								<?php endif; ?>
								<?php if($server['game_code'] == "mine" || $server['game_code'] == "mcpe" AND $server['server_status'] == 1): ?>
								<div class="navi-item my-2">
									<a href="javascript:;" data-toggle="modal" data-target="#minecore" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fab fa-whmcs"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Смена ядра</span>
									</a>
								</div>
								<?php endif; ?>
								<?php if($server['game_code'] == "cs" AND $server['server_status'] == 1): ?>
								<div class="navi-item my-2">
									<a href="javascript:;" data-toggle="modal" data-target="#builds" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fab fa-whmcs"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Смена билда</span>
									</a>
								</div>
								<?php endif; ?>
								<?php if($server['game_code'] == "ragemp" AND $server['server_status'] == 1): ?>
								<div class="navi-item my-2">
									<a href="javascript:;" data-toggle="modal" data-target="#versions" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fab fa-whmcs"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Смена версии</span>
									</a>
								</div>
								<?php endif; ?>
								<?php if($server['game_code'] == "cs" || $server['game_code'] == "csgo" || $server['game_code'] == "css" AND $server['server_status'] == 1): ?>
								<div class="navi-item my-2">
									<a href="javascript:;" data-toggle="modal" data-target="#csfast" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="far fa-sun"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Параметры запуска</span>
									</a>
								</div>
								<?php endif; ?>
								<div class="navi-item my-2">
									<a href="/servers/statistics/index/<?php echo $server['server_id'] ?>" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-chart-line"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Статистика</span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-9">
					<div class="row">
						<?php if($server['server_status'] == 2): ?>
						<div class="col-xl-12">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Название<small class="text-muted font-size-sm ml-2"><?php echo $query['hostname'] ?></small></h3>
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Режим<small class="text-muted font-size-sm ml-2"><?php echo $query['gamemode'] ?></small></h3>
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Карта<small class="text-muted font-size-sm ml-2"><?php echo $query['mapname'] ?></small></h3>
									<?php if($server['game_code'] == "cs" || $server['game_code'] == "csgo" || $server['game_code'] == "css"): ?>
									<div class="card-toolbar">
										<a href="javascript:;" data-toggle="modal" data-target="#mapcs" class="btn btn-clean btn-icon">
										<i class="fas fa-cogs" data-toggle="tooltip" data-placement="right" title="" data-original-title="Изменить"></i>
										</a>
									</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<?php endif; ?>
						<div class="col-xl-6">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Адрес<small class="text-muted font-size-sm ml-2"><?php echo $server['location_ip2'] ?>:<?php echo $server['server_port'] ?></small></h3>
									<?php if($server['server_status'] == 1): ?>
									<div class="card-toolbar">
										<a href="javascript:;" data-toggle="modal" data-target="#buyPort" class="btn btn-clean btn-icon">
										<i class="fas fa-cogs" data-toggle="tooltip" data-placement="right" title="" data-original-title="Изменить"></i>
										</a>
									</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Слоты<small class="text-muted font-size-sm ml-2"><?php echo $server['server_slots'] ?></small></h3>
									<?php if($server['server_status'] == 1): ?>
									<div class="card-toolbar">
										<a href="javascript:;" data-toggle="modal" data-target="#buySlots" class="btn btn-clean btn-icon">
										<i class="fas fa-cogs" data-toggle="tooltip" data-placement="right" title="" data-original-title="Изменить"></i>
										</a>
									</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Оплачен до<small class="text-muted font-size-sm ml-2"><?php echo date("d.m.Y", strtotime($server['server_date_end'])) ?></small></h3>
									<div class="card-toolbar">
										<a href="javascript:;" data-toggle="modal" data-target="#pay" class="btn btn-clean btn-icon">
										<i class="fas fa-cogs" data-toggle="tooltip" data-placement="right" title="" data-original-title="Изменить"></i>
										</a>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Локация<small class="text-muted font-size-sm ml-2"><?php echo $server['location_name'] ?></small></h3>
								</div>
							</div>
						</div>
						<?php if($server['game_code'] == "cs" || $server['game_code'] == "mine" || $server['game_code'] == "mcpe" || $server['game_code'] == "ragemp"): ?>
						<div class="col-xl-12">
							<div class="card card-custom mb-3">
								<div class="card-header border-0">
									<h3 class="card-title">Версия сервера<small class="text-muted font-size-sm ml-2">
									<?php if($server['game_code'] == "mine" || $server['game_code'] == "mcpe"): ?>
									<?php foreach($cores as $item): ?>
									<?php if($item['corepath'] == $server['server_binary']): ?>
									<?php echo $item['text_name'] ?>
									<?php endif; ?>
									<?php endforeach; ?>
									<?php endif; ?>
									<?php if($server['game_code'] == "cs"): ?>
									<?php foreach($builds as $item): ?>
									<?php if($item['buildpath'] == $server['server_binary']): ?>
									<?php echo $item['text_name'] ?>
									<?php endif; ?>
									<?php endforeach; ?>
									<?php endif; ?>
									<?php if($server['game_code'] == "ragemp"): ?>
									<?php foreach($versions as $item): ?>
									<?php if($item['path'] == $server['server_binary']): ?>
									<?php echo $item['text_name'] ?>
									<?php endif; ?>
									<?php endforeach; ?>
									<?php endif; ?>
									</small></h3>
								</div>
							</div>
						</div>
						<?php endif; ?>
						<?php if($server['game_code'] == "cs" || $server['game_code'] == "csgo" || $server['game_code'] == "css" AND $server['fastdl_status'] == 1): ?>
						<?php if($server['server_status'] == 2 || $server['server_status'] == 1): ?>
						<div class="col-xl-12">
							<?php if($server['server_status'] == 2): ?>
							<div class="alert alert-primary mb-5 p-5" role="alert">
								<div class="input-group">
									<input type="text" class="form-control" placeholder="FastDL URL: http://<?php echo $server['location_ip2'] ?>:8080/fastdl_gs<?php echo $server['server_id'] ?>" value="FastDL URL: http://<?php echo $server['location_ip2'] ?>:8080/fastdl_gs<?php echo $server['server_id'] ?>">
								</div>
							</div>
							<?php elseif($server['server_status'] == 1): ?>
							<div class="accordion accordion-solid accordion-toggle-plus" id="accordionExample">
								<div class="card">
									<div class="card-header" id="fast_info">
										<div class="card-title collapsed" data-toggle="collapse" role="button" data-target="#fast_infos" aria-expanded="false" aria-controls="collapseThree">Добавьте данные строки в конфигурационный файл server.cfg для работы FastDL</div>
									</div>
									<div id="fast_infos" class="card-body-wrapper collapse" aria-labelledby="fast_info" data-parent="#accordionExample" style="">
										<div class="card-body">
											<p>
												sv_downloadurl "http://<?php echo $server['location_ip2'] ?>:8080/fastdl_gs<?php echo $server['server_id'] ?>"<br> 
												sv_consistency 1<br> 
												sv_allowupload 1<br> 
												sv_allowdownload 1
											</p>
										</div>
									</div>
								</div>
							</div>
							<?php endif; ?>
						</div>
						<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--begin::Modal-->
<div class="modal fade" id="pay" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Продление сервера</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form id="payForm" method="POST" style="padding:0px; margin:0px;">
				<div class="modal-body">
					<div class="form-group mb-2">
						<div class="input-group">
							<select style="width:340px;" class="form-control" id="days" name="days" onchange="updatePrice()">
								<option value="15">15 дней</option>
								<option value="30">30 дней</option>
								<option value="60">60 дней</option>
								<option value="90">90 дней</option>
								<option value="180">180 дней</option>
								<option value="360">360 дней</option>
							</select>
						</div>
					</div>
					<div class="d-flex align-items-center bg-light-primary rounded p-4 mb-2">
						<i class="flaticon-exclamation icon-xl text-primary mr-5"></i>
						<div class="d-flex flex-column flex-grow-1 mr-2">
							<span class="font-weight-bolder text-primary py-1 font-size-lg">Итого к оплате: <span id="price2">0.00 RUB</span></span>
						</div>
					</div>
					<div class="modal-footer" style="padding: 0.5rem;">
						<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Отмена</button>
						<button type="submit" class="btn btn-primary font-weight-bold">Оплатить</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!--end::Modal-->
<?php if($server['server_status'] == 1): ?>
<!--begin::Modal-->
<div class="modal fade" id="buyPort" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Смена порта</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form id="portForm" method="POST" style="padding:0px; margin:0px;">
				<div class="modal-body">
					<div class="form-group mb-2">
						<div class="input-group">
							<input type="text" class="form-control" id="port" name="port" placeholder="Введите порт" value="<?echo $server['server_port']?>">
						</div>
					</div>
					<div class="d-flex align-items-center bg-light-primary rounded p-4 mb-2">
						<i class="flaticon-exclamation icon-xl text-primary mr-5"></i>
						<div class="d-flex flex-column flex-grow-1 mr-2">
							<span class="font-weight-bolder text-primary py-1 font-size-lg">Стоимость смены порта: <span id="price_port"><?php echo $portPrice ?> руб.</span></span>
						</div>
					</div>
					<div class="modal-footer" style="padding: 0.5rem;">
						<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Отмена</button>
						<button type="submit" class="btn btn-primary font-weight-bold">Сменить</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!--end::Modal-->
<!--begin::Modal-->
<div class="modal fade" id="buySlots" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Изменение слотов</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form id="edit_slots" method="POST" style="padding:0px; margin:0px;">
				<div class="modal-body">
					<div class="form-group mb-2">
						<div class="input-group" onkeyup="updateForm(true)">
							<div class="input-group-prepend">
								<button class="btn btn-light-primary btn-icon" type="button" onclick="plusSlots()"><i class="fa fa-angle-up"></i></button>
							</div>
							<input type="text" class="form-control" id="slots" name="slots" value="<?echo $server['server_slots']?>">
							<div class="input-group-append">
								<button class="btn btn-light-primary btn-icon" type="button" onclick="minusSlots()"><i class="fa fa-angle-down"></i></button>
							</div>
						</div>
					</div>
					<div class="d-flex align-items-center bg-light-primary rounded p-4 mb-2">
						<i class="flaticon-exclamation icon-xl text-primary mr-5"></i>
						<div class="d-flex flex-column flex-grow-1 mr-2">
							<span class="font-weight-bolder text-primary py-1 font-size-lg">Итого к оплате: <span id="price">0.00 RUB</span></span>
						</div>
					</div>
					<div class="modal-footer" style="padding: 0.5rem;">
						<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Отмена</button>
						<button type="submit" class="btn btn-primary font-weight-bold">Оплатить</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!--end::Modal-->
<?php endif; ?>
<?php if($server['game_code'] == "ragemp"  AND $server['server_status'] == 1): ?>
<!--begin::Modal-->
<div class="modal fade" id="NodeJS" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Загрузка модулей NodeJS</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form id="loadModulesJS" method="POST" style="padding:0px; margin:0px;">
				<div class="modal-body">
					<div class="input-group mb-2">
						<select style="width:340px;" class="form-control" id="module" name="module">
							<?php foreach($node_modules as $item): ?>
							<option value="<?php echo $item['modulepath'] ?>"><?php echo $item['module_name'] ?></option>
							<?php endforeach; ?>
							<?php if(empty($node_modules)): ?>	
							<option value="cores_empty">Для данной игры пока нет модулей</option>
							<?php endif; ?>
						</select>
					</div>
					<hr>
					<div class="alert alert-primary" role="alert">Модули загружаются в папку node_modules.</div>
				</div>
				<div class="modal-footer" style="padding: 0.5rem;">
					<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Отмена</button>
					<button type="submit" class="btn btn-primary font-weight-bold">Загрузить на сервер</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!--end::Modal-->
<?php endif; ?>
<?php if($server['game_code'] == "mine" || $server['game_code'] == "mcpe" AND $server['server_status'] == 1): ?>
<!--begin::Modal-->
<div class="modal fade" id="minecore" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Смена ядра</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form id="changecore" method="POST" style="padding:0px; margin:0px;">
				<div class="modal-body">
					<div class="input-group mb-2">
						<select style="width:340px;" class="form-control" id="core" name="core">
							<?php foreach($cores as $item): ?>
							<?php if(@$item['continue_select']) continue; ?>
							<?php if($item['corepath'] != $no_core['corepath']): ?>
							<option value="<?php echo $item['corepath'] ?>"><?php echo $item['text_name'] ?> <?php if($item['corepath'] == $server['server_binary']):?>| Установлено<?php endif; ?></option>
							<?php endif; ?>
							<?php endforeach; ?>
							<?php if(empty($cores)): ?>	
							<option value="cores_empty">Для данной игры пока нет ядер</option>
							<?php endif; ?>
						</select>
					</div>
					<hr>
					<div class="alert alert-primary" role="alert">Смена ядра приводит к полной очистке сервера!</div>
				</div>
				<div class="modal-footer" style="padding: 0.5rem;">
					<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Отмена</button>
					<button type="submit" class="btn btn-primary font-weight-bold">Сменить</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!--end::Modal-->
<?php endif; ?>
<?php if($server['game_code'] == "cs" AND $server['server_status'] == 1): ?>
<!--begin::Modal-->
<div class="modal fade" id="builds" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Смена билда</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form id="changebild" method="POST" style="padding:0px; margin:0px;">
				<div class="modal-body">
					<div class="input-group mb-2">
						<select style="width:340px;" class="form-control" id="build" name="build">
							<?php foreach($builds as $item): ?>
							<?php if($item['buildpath'] != $no_build['buildpath']): ?>
							<option value="<?php echo $item['buildpath'] ?>"><?php echo $item['text_name'] ?> <?php if($item['buildpath'] == $server['server_binary']):?>| Установлено<?php endif; ?></option>
							<?php endif; ?>
							<?php endforeach; ?>
							<?php if(empty($builds)): ?>	
							<option value="builds_empty">Для данной игры пока нет билдов</option>
							<?php endif; ?>
						</select>
					</div>
					<hr>
					<div class="alert alert-primary" role="alert">Смена билда приводит к полной очистке сервера!</div>
				</div>
				<div class="modal-footer" style="padding: 0.5rem;">
					<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Отмена</button>
					<button type="submit" class="btn btn-primary font-weight-bold">Сменить</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!--end::Modal-->
<?php endif; ?>
<?php if($server['game_code'] == "ragemp" AND $server['server_status'] == 1): ?>
<!--begin::Modal-->
<div class="modal fade" id="versions" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Смена версии</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form id="changeversion" method="POST" style="padding:0px; margin:0px;">
				<div class="modal-body">
					<div class="input-group mb-2">
						<select style="width:340px;" class="form-control" id="version" name="version">
							<?php foreach($versions as $item): ?>
							<?php if($item['path'] != $no_version['path']): ?>
							<option value="<?php echo $item['path'] ?>"><?php echo $item['text_name'] ?> <?php if($item['path'] == $server['server_binary']):?>| Установлено<?php endif; ?></option>
							<?php endif; ?>
							<?php endforeach; ?>
							<?php if(empty($versions)): ?>	
							<option value="versions_empty">Для данной игры пока нет версий</option>
							<?php endif; ?>
						</select>
					</div>
					<hr>
					<div class="alert alert-primary" role="alert">Смена версии приводит к полной очистке сервера!</div>
				</div>
				<div class="modal-footer" style="padding: 0.5rem;">
					<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Отмена</button>
					<button type="submit" class="btn btn-primary font-weight-bold">Сменить</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!--end::Modal-->
<?php endif; ?>
<?php if($server['game_code'] == "cs" || $server['game_code'] == "csgo" || $server['game_code'] == "css" AND $server['server_status'] == 1): ?>
<!--begin::Modal-->
<div class="modal fade" id="csfast" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Параметры запуска</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form id="settingStart" method="POST" style="padding:0px; margin:0px;">
				<div class="modal-body">
					<?php if($server['game_code'] == "cs" || $server['game_code'] == "csgo" || $server['game_code'] == "css"): ?>
					<div class="form-group form-md-line-input">
						<label>Античит VAC</label>
						<select class="form-control" id="vac" name="vac">
							<option value="0" <?php if($server['server_vac'] == 0): ?> selected="selected"<?php endif; ?>>Выключен</option>
							<option value="1" <?php if($server['server_vac'] == 1): ?> selected="selected"<?php endif; ?>>Включен</option>
						</select>
					</div>
					<div class="form-group form-md-line-input">
						<label>FastDL</label>
						<select class="form-control" id="fastdl" name="fastdl">
							<option value="0" <?php if($server['fastdl_status'] == 0): ?> selected="selected"<?php endif; ?>>Выключен</option>
							<option value="1" <?php if($server['fastdl_status'] == 1): ?> selected="selected"<?php endif; ?>>Включен</option>
						</select>
					</div>
					<?php endif; ?>
					<?php if($server['game_code'] == "cs"): ?>
					<div class="form-group form-md-line-input">
						<label>FPS</label>
						<select class="form-control" id="fps" name="fps">
							<?php foreach($fps as $item): ?>
							<option value="<?php echo $item ?>" <?php if($server['server_fps'] == $item): ?> selected="selected"<?php endif; ?>><?php echo $item ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<?php endif; ?>
					<?php if($server['game_code'] == "csgo" || $server["game_code"] == "css"): ?>
					<div class="form-group form-md-line-input">
						<label>Tickrate</label>
						<select class="form-control" id="tickrate" name="tickrate">
							<?php foreach($tickrate as $item): ?>
							<option value="<?php echo $item ?>" <?php if($server['server_tickrate'] == $item): ?> selected="selected"<?php endif; ?>><?php echo $item ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<?php endif; ?>
				</div>
				<div class="modal-footer" style="padding: 0.5rem;">
					<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Отмена</button>
					<button type="submit" class="btn btn-primary font-weight-bold">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!--end::Modal-->
<?php endif; ?>
<?php if($server['game_code'] == "cs" || $server['game_code'] == "csgo" || $server['game_code'] == "css" AND $server['server_status'] == 2): ?>
<!--begin::Modal-->
<div class="modal fade" id="mapcs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Смена карты</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form id="changemap" method="POST" style="padding:0px; margin:0px;">
				<div class="modal-body">
					<div class="input-group mb-2">
						<select style="width:340px;border-radius: 0;" name="map" class="form-control"><?php echo $maps ?></select>
					</div>
				</div>
				<div class="modal-footer" style="padding: 0.5rem;">
					<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Отмена</button>
					<button type="submit" class="btn btn-primary font-weight-bold">Сменить</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!--end::Modal-->
<?php endif; ?>
<?php if($server['server_status'] == 0): ?>
<!--begin::Modal-->
<div class="modal fade" id="promised" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Обещанный платеж</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="modal-body">
				<div class="alert alert-primary mb-5 p-5" role="alert">
					<div class="input-group">
						<input type="text" class="form-control" disabled="disabled" value="Услуга обещанный платеж продлит сервер на 3 дня.">
						<div class="input-group-append">
							<a href="javascript:;" onClick="sendAction(<?php echo $server['server_id'] ?>,'promised')" class="btn btn-light-primary btn-icon"><i class="fas fa-magic"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--begin::Modal-->
<?php endif; ?>
<script>
	function sendAction(serverid, action) {
		switch(action) {
			case "reinstall":
			{
				if(!confirm("Вы уверенны в том, что хотите переустановить сервер? Все данные будут удалены.")) return;
				break;
			}
			case "backup":
			{
				if(!confirm("Вы уверены в том, что хотите сделать бэкап сервера? Прежний бэкап будет удален.")) return;
				break;
			}
			case "unbackup":
			{
				if(!confirm("Вы уверены в том, что хотите восстановить сервер?")) return;
				break;
			}
			case "delete_backup":
			{
				if(!confirm("Вы уверены в том, что хотите удалить BackUP сервера?")) return;
				break;
			}
			case "promised":
			{
				if(!confirm("Вы действительно хотите активировать обещанный платёж на данный сервер ? С вашего счёта будет списано 15 руб!")) return;
				break;
			}
		}
		$.ajax({ 
			url: '/servers/control/action/'+serverid+'/'+action,
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
			},
			beforeSend: function(arr, options) {
				toastr.warning("Ваш запрос обрабатывается, пожалуйста, подождите...");                                    
			}
		});
	}
</script>
<?php if($server['server_status'] == 1): ?>
<script>
	$('#portForm').ajaxForm({ 
		url: '/servers/control/ajax_port/<?php echo $server['server_id'] ?>',
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
					setTimeout("reload()",1000);
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			$('button[type=submit]').prop('disabled', true);
		}
	});
	
	$('#edit_slots').ajaxForm({ 
		url: '/servers/control/ajax/<?php echo $server['server_id'] ?>',
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
					setTimeout("reload()",1000);
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			$('button[type=submit]').prop('disabled', true);
		}
	});
	
	$(document).ready(function() {
		updateForm();
	});
	
	function updateForm(promo) {
		var gamePrice = <?=$server['game_price']?>;
		var gameMax = <?=$server['game_max_slots']?>;
		var sslots = <?=$server['server_slots']?>;
		var slots = $("#slots").val();
		if(slots < sslots) {
			slots = sslots;
			$("#slots").val(slots);
		} else if(slots > gameMax) {
			slots = gameMax;
			$("#slots").val(slots);
		}
		var price = ((gamePrice * (slots - sslots)) / 30) * <?php echo $days ?>;
		$('#price').text(price.toFixed(2) + ' RUB');
	}
	
	function plusSlots() {
		value = parseInt($('#slots').val());
		$('#slots').val(value+1);
		updateForm();
	}
	
	function minusSlots() {
		value = parseInt($('#slots').val());
		$('#slots').val(value-1);
		updateForm();
	}
</script>
<?php endif; ?>	
<script>
	$('#payForm').ajaxForm({ 
		url: '/servers/control/buy_months/<?php echo $server['server_id'] ?>',
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
						
	$(document).ready(function() {
		updatePrice();
	});
	
	function updatePrice() {
		var price2 = <?php echo $server['game_price'] ?> * <?php echo $server['server_slots'] ?>;
		var days = $("#days option:selected").val();
		switch(days) {
			case "15":
				price2 = price2 / 2;
				break;
			case "30":
				break;
			case "60":
				price2 = price2 * 2;
				break;
			case "90":
				price2 = 3 * price2;
				break;
			case "180":
				price2 = 6 * price2;
				break;
			case "360":
				price2 = 12 * price2;
				break;
		}	
		$('#price2').text(price2.toFixed(2) + ' RUB. Дней: ' + days);
	}
</script>
<?php if($server['game_code'] == "cs" || $server['game_code'] == "csgo" || $server['game_code'] == "css" AND $server['server_status'] == 2): ?>
<script>
	$('#changemap').ajaxForm({ 
		url: '/servers/control/changemap_go/<?php echo $server['server_id'] ?>',
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
					setTimeout("reload()", 2500);
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			toastr.warning("Ваш запрос обрабатывается, пожалуйста, подождите...");   	
			$('button[type=submit]').prop('disabled', true);
		}
	});   
</script>
<?php endif; ?>	
<?php if($server['game_code'] == "cs" || $server['game_code'] == "csgo" || $server['game_code'] == "css"  AND $server['server_status'] == 1): ?>
<script>
	$('#settingStart').ajaxForm({ 
		url: '/servers/control/settingStart/<?php echo $server['server_id'] ?>',
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
					$('button[type=submit]').prop('disabled', false);
					setTimeout("reload()", 2500);
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			$('button[type=submit]').prop('disabled', true);		
		}
	}); 
</script>
<?php endif; ?>	
<?php if($server['game_code'] == "ragemp"  AND $server['server_status'] == 1): ?>
<script>
	$('#loadModulesJS').ajaxForm({ 
		url: '/servers/control/loadModuleJS/<?php echo $server['server_id'] ?>',
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
					$('button[type=submit]').prop('disabled', false);
					setTimeout("reload()", 2000);
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			toastr.warning("Ваш запрос обрабатывается, пожалуйста, подождите...");    
			$('button[type=submit]').prop('disabled', true);		
		}
	});  
</script>
<?php endif; ?>	
<?php if($server['game_code'] == "mine" || $server['game_code'] == "mcpe" AND $server['server_status'] == 1): ?>
<script>
	$('#changecore').ajaxForm({ 
		url: '/servers/control/changecore/<?php echo $server['server_id'] ?>',
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
					$('button[type=submit]').prop('disabled', false);
					setTimeout("reload()", 1500);
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			toastr.warning("Ваш запрос обрабатывается, пожалуйста, подождите...");    
			$('button[type=submit]').prop('disabled', true);		
		}
	}); 
</script>
<?php endif; ?>	
<?php if($server['game_code'] == "cs" AND $server['server_status'] == 1): ?>
<script>
	$('#changebild').ajaxForm({ 
		url: '/servers/control/changebuild/<?php echo $server['server_id'] ?>',
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
					$('button[type=submit]').prop('disabled', false);
					setTimeout("reload()", 1500);
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			toastr.warning("Ваш запрос обрабатывается, пожалуйста, подождите...");    
			$('button[type=submit]').prop('disabled', true);		
		}
	}); 
</script>
<?php endif; ?>
<?php if($server['game_code'] == "ragemp" AND $server['server_status'] == 1): ?>
<script>
	$('#changeversion').ajaxForm({ 
		url: '/servers/control/changeversion/<?php echo $server['server_id'] ?>',
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
					$('button[type=submit]').prop('disabled', false);
					setTimeout("reload()", 1500);
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			toastr.warning("Ваш запрос обрабатывается, пожалуйста, подождите...");    
			$('button[type=submit]').prop('disabled', true);		
		}
	}); 
</script>
<?php endif; ?>
<?php echo $footer ?>
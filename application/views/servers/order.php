<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
?>
<?php echo $header ?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<form id="orderForm" method="POST" style="padding:0px; margin:0px;">
				<div class="row">
					<div class="col-xl-8 col-xxl-8">
						<div class="card card-custom card-stretch card-shadowless bg-gray-100 gutter-b">
							<div class="card-header border-0 pt-7">
								<h3 class="card-title align-items-start flex-column">
									<span class="card-label font-weight-bold font-size-h4 text-dark-75">Параметры сервера</span>
								</h3>
							</div>
							<div class="card-body pt-1 pb-4">
								<div class="form-group form-md-line-input">
									<label>Выберите игру</label>
									<select class="form-control" id="gameid" name="gameid" aria-required="true" aria-invalid="false" aria-describedby="delivery-error" onChange="updateForm()">
										<?php foreach($games as $item): ?> 
										<option value="<?php echo $item['game_id'] ?>"><?php echo $item['game_name'] ?></option>
										<?php endforeach; ?>
										<?php if(empty($games)): ?>
										<option value="0">На данный момент нет доступных игр</option>
										<?php endif; ?>
									</select>
								</div>
								<div class="form-group form-md-line-input">
									<label>Выберите локацию</label>
									<select class="form-control" id="locationid" name="locationid" aria-required="true" aria-invalid="false" aria-describedby="delivery-error">
										<?php foreach($locations as $item): ?> 
										<option value="<?php echo $item['location_id'] ?>" class="<?php echo $item['location_games'] ?>"><?php echo $item['location_name'] ?> | Загруженность <?php echo $item['location_cpu'] ?>%</option>
										<?php endforeach; ?>
										<?php if(empty($locations)): ?>
										<option value="0">На данный момент нет доступных локаций</option>
										<?php endif; ?>
									</select>
								</div>
								<div class="form-group form-md-line-input">
									<label>Выберите период оплаты</label>
									<select class="form-control" id="days" name="days" aria-required="true" aria-invalid="false" aria-describedby="delivery-error" onChange="updateForm()">
										<option value="15">15 дней</option>
										<option value="30">30 дней</option>
										<option value="60">60 дней</option>
										<option value="90">90 дней (-5%)</option>
										<option value="180">180 дней (-10%)</option>
										<option value="360">360 дней (-15%)</option>
										<?if($serv_test == 1):?>
										<option value="0">Тестовый период 3 дня</option>
										<?endif;?>
									</select>
								</div>
								<div class="form-group form-md-line-input">
									<label>Введите количество слотов</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<button type="button" onclick="plusSlots()" class="btn btn-light-primary btn-icon"><i class="fa fa-angle-up"></i></button>
										</div>
										<input oninput="this.value = this.value.replace(/\D/g, '')" class="form-control" id="slots" name="slots" onkeyup="updateForm(true)">
										<div class="input-group-append">
											<button type="button" onclick="minusSlots()" class="btn btn-light-primary btn-icon"><i class="fa fa-angle-down"></i></button>
										</div>
									</div>
								</div>
								<label>Придумайте пароль для FTP</label>
								<div class="input-group">
									<input type="text" class="form-control m-input" id="password" name="password" placeholder="Пароль">
									<div class="input-group-append">
										<button type="button" onClick="genPass()" class="btn btn-light-primary btn-icon"><i class="fa fa-undo"></i></button>
									</div>
								</div>
								<br>
								<div class="form-group form-md-line-input">
									<label>Повторите пароль для FTP</label>
									<input type="text" class="form-control" id="password2" name="password2" placeholder="Повторите пароль">
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-xxl-4">
						<div class="card card-custom card-shadowless bg-gray-100 gutter-b">
							<div class="card-header border-0 pt-7">
								<h3 class="card-title align-items-start flex-column">
									<span class="card-label font-weight-bold font-size-h4 text-dark-75">Информация о заказе</span>
								</h3>
							</div>
							<div class="card-body pt-1 pb-4">
								<div class="accordion accordion-toggle-arrow" id="accordionExample">
									<div class="card">
										<div class="card-header" id="heading3">
											<div class="card-title collapsed" data-toggle="collapse" role="button" data-target="#collapse3" aria-expanded="false" aria-controls="collapseThree">
												<i class="fa fa-gift"></i> Есть купон? 
											</div>
										</div>
										<div id="collapse3" class="card-body-wrapper collapse" aria-labelledby="heading3" data-parent="#accordionExample" style="">
											<div class="card-body">
												<div data-repeater-item="" class="kt--margin-bottom-10">
													<div class="input-group">
														<div class="input-group-prepend">
															<span class="input-group-text">
															<i class="fa fa-gift"></i>
															</span>
														</div>
														<input id="promo" type="text" class="form-control form-control-danger" type="promo" name="promo" placeholder="Купон">
														<div class="input-group-append">
															<button type="button" onclick="promoCode()" class="btn btn-light-primary btn-icon"><i class="fa fa-check"></i></button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<hr>
								<div class="text-center">
									<div class="font-size-h5 font-weight-boldest">Итого к оплате: <span id="price">0.00 руб.</span></div>
									<div class="text-muted font-weight-bold mb-3" id="server">Получение данных...</div>
								</div>
								<hr>
								<button type="submit" class="btn btn-light-primary btn-lg btn-block font-weight-bolder">Создать сервер</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			<div class="card card-custom card-shadowless bg-gray-100">
				<div class="card-header border-0 pt-7">
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label font-weight-bold font-size-h4 text-dark-75">Преимущества нашего хостинга</span>
					</h3>
				</div>
				<div class="card-body pt-0 pb-4">
					<div class="row">
						<div class="col-xl-6">
							<ul class="navi">
								<li class="navi-item">
									<a class="navi-link" href="javascript:;">
									<span class="navi-bullet">
									<i class="bullet"></i>
									</span>
									<span class="navi-text">Мощные процессоры Intel</span>
									<span class="navi-label">
									<i class="fas fa-check text-primary"></i>
									</span>
									</a>
								</li>
								<li class="navi-item">
									<a class="navi-link" href="javascript:;">
									<span class="navi-bullet">
									<i class="bullet"></i>
									</span>
									<span class="navi-text">Защита от DDoS атак на оборудование.</span>
									<span class="navi-label">
									<i class="fas fa-check text-primary"></i>
									</span>
									</a>
								</li>
								<li class="navi-item">
									<a class="navi-link" href="javascript:;">
									<span class="navi-bullet">
									<i class="bullet"></i>
									</span>
									<span class="navi-text">Полный доступ к FTP</span>
									<span class="navi-label">
									<i class="fas fa-check text-primary"></i>
									</span>
									</a>
								</li>
								<li class="navi-item">
									<a class="navi-link" href="javascript:;">
									<span class="navi-bullet">
									<i class="bullet"></i>
									</span>
									<span class="navi-text">Бесплатная база MySQL</span>
									<span class="navi-label">
									<i class="fas fa-check text-primary"></i>
									</span>
									</a>
								</li>
							</ul>
						</div>
						<div class="col-xl-6">
							<ul class="navi">
								<li class="navi-item">
									<a class="navi-link" href="javascript:;">
									<span class="navi-bullet">
									<i class="bullet"></i>
									</span>
									<span class="navi-text">Создание резервных копий backup</span>
									<span class="navi-label">
									<i class="fas fa-check text-primary"></i>       
									</span>
									</a>
								</li>
								<li class="navi-item">
									<a class="navi-link" href="javascript:;">
									<span class="navi-bullet">
									<i class="bullet"></i>
									</span>
									<span class="navi-text">Смена версии сервера одним кликом</span>
									<span class="navi-label">
									<i class="fas fa-check text-primary"></i>
									</span>
									</a>
								</li>
								<li class="navi-item">
									<a class="navi-link" href="javascript:;">
									<span class="navi-bullet">
									<i class="bullet"></i>
									</span>
									<span class="navi-text">Скоростные SSD диски</span>
									<span class="navi-label">
									<i class="fas fa-check text-primary"></i>
									</span>
									</a>
								</li>
								<li class="navi-item">
									<a class="navi-link" href="javascript:;">
									<span class="navi-bullet">
									<i class="bullet"></i>
									</span>
									<span class="navi-text">Удобная панель управления</span>
									<span class="navi-label">
									<i class="fas fa-check text-primary"></i>
									</span>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery.fn.chained = function(parent_selector, options) {
		return this.each(function() {
			var self = this;                                     
			var backup = jQuery(self).clone();
			jQuery(parent_selector).each(function() {             
				jQuery(this).bind("change", function() {
					jQuery(self).html(backup.html());
					var selected = "";                                     
					jQuery(parent_selector).each(function() { 
						selected += "\\" + jQuery(":selected", this).val();
					});
					selected = selected.substr(1);                         
					var first = jQuery(parent_selector).first();
					var selected_first = jQuery(":selected", first).val();
					jQuery("option", self).each(function() {
						if (!jQuery(this).hasClass(selected) && !jQuery(this).hasClass(selected_first) && jQuery(this).val() !== "") { 
							jQuery(this).remove();
						}
					});
					if (1 == jQuery("option", self).size() && jQuery(self).val() === "") {
						jQuery(self).attr("disabled", "disabled"); 
					} else {
						jQuery(self).removeAttr("disabled"); 
					}
					jQuery(self).trigger("change");
				});
				if ( !jQuery("option:selected", this).length ) { 
					jQuery("option", this).first().attr("selected", "selected");
				}
				jQuery(this).trigger("change");                        
			});
		}); 
	};
	jQuery.fn.chainedTo = jQuery.fn.chained;
	jQuery(document).ready(function(){
		jQuery("#locationid").chained("#gameid");
	});
</script>
<script>
	var gameData = {
		<?php foreach($games as $item): ?> 
		<?php echo $item['game_id'] ?>: {
			'minslots': <?php echo $item['game_min_slots'] ?>,
			'maxslots': <?php echo $item['game_max_slots'] ?>,
			'price': <?php echo $item['game_price'] ?>,
			'cpu': <?php echo $item['game_cores'] ?>,
			'ram': <?php echo $item['game_ram'] ?>,
			'ssd': <?php echo $item['game_ssd'] ?>
		},
		<?php endforeach; ?> 
	};
  
	$('#orderForm').ajaxForm({ 
		url: '/servers/order/ajax',
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
					setTimeout("redirect('/servers/control/index/" + data.id + "')", 1500);
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			$('button[type=submit]').prop('disabled', true);
			toastr.warning("Ваш запрос обрабатывается, пожалуйста, подождите...");  
		}
	});
   
	$(document).ready(function() {
		updateForm();
	});
   
	function promoCode(){
		var promo = $("#promo").val();
		$.post("/servers/order/promo",{code: promo},function(data){
			data = $.parseJSON(data);
			switch(data.status) {
				case 'error':
					toastr.error(data.error);
					updateForm();
					break;
				case 'success':
					toastr.success(data.success);
					updateForm(data.skidka);
					break;
			}
		});
	}
   
	function updateForm(promo) {
		var gameID = $("#gameid option:selected").val();
		var slots = $("#slots").val();
		if(slots < gameData[gameID]['minslots']) {
			slots = gameData[gameID]['minslots'];
			$("#slots").val(slots);
		}
		if(slots > gameData[gameID]['maxslots']) {
			slots = gameData[gameID]['maxslots'];
			$("#slots").val(slots);
		}
		var price = gameData[gameID]['price'] * slots;
		var days = $("#days option:selected").val();
		switch(days) {
			case "0":
				price = 0;
				break;
			case "15":
				price = price / 2;
				break;
			case "30":
				break;
			case "60":
				price = price * 2;
				break;
			case "90":
				price = 3 * price * 0.95;
				break;
			case "180":
				price = 6 * price * 0.90;
				break;
			case "360":
				price = 12 * price * 0.85;
				break;
		}			
		if(promo != null){price = price * promo;}
		$('#price').text(price.toFixed(2) + ' руб.');
		$('#server').text('CPU: '+gameData[gameID]['cpu']+' ядер, RAM: '+gameData[gameID]['ram']+' МБ, SSD: '+gameData[gameID]['ssd']+' МБ');
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
   
	function generatePwd() {
		var length = 8,
			charset = "abcdefghijklnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
			retVal = "";
		for (var i = 0, n = charset.length; i < length; ++i) {
			retVal += charset.charAt(Math.floor(Math.random() * n));
		}
		return retVal;
	}
   
	function genPass() {
		document.getElementById('password').type = 'text';
		document.getElementById('password2').type = 'text';
		iString = generatePwd();
		$('#password').val(iString);
		$('#password2').val(iString);
	}
</script>
<?php echo $footer ?>
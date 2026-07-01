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
						<h3 class="card-label">Создание игры
						</h3>
					</div>
				</div>
				<div class="card-body">
					<form id="createForm" method="POST">
						<div class="form-group form-md-line-input">
							<label>Введите название игры</label>
							<input type="text" class="form-control" id="name" name="name" placeholder="Введите название игры">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите код игры</label>
							<input type="text" class="form-control" id="code" name="code" placeholder="Введите код игры">
						</div>
						<div class="form-group form-md-line-input">
							<label>Выберите query-драйвер игры</label>
							<select class="form-control" id="query" name="query" name="delivery" aria-required="true" aria-invalid="false" aria-describedby="delivery-error">
								<?php foreach($queryDrivers as $item): ?>
								<option value="<?php echo $item ?>"><?php echo $item ?></option>
								<?php endforeach; ?> 
							</select>
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите минимальное количество слотов для заказа</label>
							<input type="text" class="form-control" id="minslots" name="minslots" placeholder="Введите минимальное количество слотов для заказа">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите максимальное количество слотов для заказа</label>
							<input type="text" class="form-control" id="maxslots" name="maxslots" placeholder="Введите максимальное количество слотов для заказа">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите минимальный порт для заказа</label>
							<input type="text" class="form-control" id="minport" name="minport" placeholder="Введите минимальный порт для заказа">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите максимальное порт для заказа</label>
							<input type="text" class="form-control" id="maxport" name="maxport" placeholder="Введите максимальное порт для заказа">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите стоимость за один слот</label>
							<input type="text" class="form-control" id="price" name="price" placeholder="Введите стоимость за один слот">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите количество ядер на один сервер</label>
							<input type="text" class="form-control" id="cores" name="cores" placeholder="Введите количество ядер на один сервер">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите объем оперативной памяти на один сервер (в МБ)</label>
							<input type="text" class="form-control" id="ram" name="ram" placeholder="Введите объем оперативной памяти на один сервер (в МБ)">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите объем SSD на один сервер (в МБ)</label>
							<input type="text" class="form-control" id="ssd" name="ssd" placeholder="Введите объем SSD на один сервер (в МБ)">
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
		url: '/admin/games/create/ajax',
		dataType: 'json',
		success: function(data) {
			switch(data.status) {
				case 'error':
					toastr.error(data.error);
					$('button[type=submit]').prop('disabled', false);
					break;
				case 'success':
					toastr.success(data.success);
					setTimeout("redirect('/admin/games')", 1500);
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			$('button[type=submit]').prop('disabled', true);
		}
	});
</script>
<?php echo $footer ?>
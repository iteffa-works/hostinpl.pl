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
						<h3 class="card-label">Редактирование игры
						</h3>
					</div>
				</div>
				<div class="card-body">
					<form id="editForm" method="POST">
						<div class="form-group form-md-line-input">
							<label>Введите название игры</label>
							<input type="text" class="form-control" id="name" name="name" placeholder="Введите название игры" value="<?php echo $game['game_name'] ?>">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите код игры</label>
							<input type="text" class="form-control" id="code" name="code" placeholder="Введите код игры" value="<?php echo $game['game_code'] ?>">
						</div>
						<div class="form-group form-md-line-input">
							<label>Выберите query-драйвер игры</label>
							<select class="form-control" id="query" name="query" name="delivery" aria-required="true" aria-invalid="false" aria-describedby="delivery-error">
								<?php foreach($queryDrivers as $item): ?>
								<option value="<?php echo $item ?>"<?php if($item == $game['game_query']): ?> selected<?php endif; ?>><?php echo $item ?></option>
								<?php endforeach; ?> 
							</select>
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите минимальное количество слотов для заказа</label>
							<input type="text" class="form-control" id="minslots" name="minslots" placeholder="Введите минимальное количество слотов для заказа" value="<?php echo $game['game_min_slots'] ?>">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите максимальное количество слотов для заказа</label>
							<input type="text" class="form-control" id="maxslots" name="maxslots" placeholder="Введите максимальное количество слотов для заказа" value="<?php echo $game['game_max_slots'] ?>">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите минимальный порт для заказа</label>
							<input type="text" class="form-control" id="minport" name="minport" placeholder="Введите минимальный порт для заказа" value="<?php echo $game['game_min_port'] ?>">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите максимальный порт для заказа</label>
							<input type="text" class="form-control" id="maxport" name="maxport" placeholder="Введите максимальный порт для заказа" value="<?php echo $game['game_max_port'] ?>">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите стоимость за один слот</label>
							<input type="text" class="form-control" id="price" name="price" placeholder="Введите стоимость за один слот" value="<?php echo $game['game_price'] ?>">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите количество ядер на один сервер</label>
							<input type="text" class="form-control" id="cores" name="cores" placeholder="Введите количество ядер на один сервер" value="<?php echo $game['game_cores'] ?>">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите объем оперативной памяти на один сервер (в МБ)</label>
							<input type="text" class="form-control" id="ram" name="ram" placeholder="Введите объем оперативной памяти на один сервер (в МБ)" value="<?php echo $game['game_ram'] ?>">
						</div>
						<div class="form-group form-md-line-input">
							<label>Введите объем SSD на один сервер (в МБ)</label>
							<input type="text" class="form-control" id="ssd" name="ssd" placeholder="Введите объем SSD на один сервер (в МБ)" value="<?php echo $game['game_ssd'] ?>">
						</div>
						<div class="form-group form-md-line-input">
							<label>Выберите статус игры</label>
							<select class="form-control" id="status" name="status" name="delivery" aria-required="true" aria-invalid="false" aria-describedby="delivery-error">
								<option value="1"<?php if($game['game_status'] == 1): ?> selected="selected"<?php endif; ?>>Включена</option>
								<option value="0"<?php if($game['game_status'] == 0): ?> selected="selected"<?php endif; ?>>Выключена</option>
							</select>
						</div>
						<hr>
						<div class="m-btn-group m-btn-group--pill btn-group m-btn-group m-btn-group--pill btn-block" role="group" aria-label="Large button group">
							<button type="submit" class="btn btn-primary btn-outline  btn-block sbold uppercase">Сохранить изменения</button>
							<a data-toggle="tooltip" data-placement="right" title="" data-original-title="Удалить" style="height: 3.1rem;" href="/admin/games/edit/delete/<?php echo $game['game_id'] ?>" class="btn btn-danger btn-elevate btn-icon"><i class="fa fa-trash-alt"></i></a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#editForm').ajaxForm({ 
		url: '/admin/games/edit/ajax/<?php echo $game['game_id'] ?>',
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

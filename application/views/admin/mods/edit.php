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
						<h3 class="card-label">Редактирование мода
						</h3>
					</div>
				</div>
				<div class="card-body">
					<form id="editForm" method="POST">
						<div class="form-group form-md-line-input">
							<input type="text" class="form-control" id="name" name="name" value="<?php echo $mod['mod_name'] ?>" placeholder="Введите название мода">
						</div>
						<div class="form-group form-md-line-input">
							<textarea class="form-control" id="textx" name="textx" rows="3"placeholder="Описание..."><?php echo $mod['mod_textx'] ?></textarea>
						</div>
						<div class="form-group form-md-line-input">
							<input type="text" class="form-control" id="img" name="img" value="<?php echo $mod['mod_img'] ?>" placeholder="Введите ссылку на изображение">
						</div>
						<div class="form-group form-md-line-input">
							<select class="form-control" id="gameid" name="gameid" aria-required="true" aria-invalid="false" aria-describedby="delivery-error">
								<?php foreach($games as $item): ?> 
								<option value="<?php echo $item['game_id'] ?>" <?php if($item['game_id'] == $mod['game_id']): ?> selected="selected"<?php endif; ?>><?php echo $item['game_name'] ?></option>
								<?php endforeach; ?> 
							</select>
						</div>
						<div class="form-group form-md-line-input">
							<input type="text" class="form-control" id="url" name="url" value="<?php echo $mod['mod_url'] ?>" placeholder="Введите ссылку на архив (tar)">
						</div>
						<div class="form-group form-md-line-input">
							<input type="text" class="form-control" id="arch" name="arch" value="<?php echo $mod['mod_arch'] ?>" placeholder="Введите название архива">
						</div>
						<hr>
						<div class="form-group form-md-line-input">
							<input type="text" class="form-control" id="price" name="price" value="<?php echo $mod['mod_price'] ?>" placeholder="Введите стоимость мода">
						</div>
						<div class="form-group form-md-line-input">
							<select class="form-control" id="status" name="status" aria-required="true" aria-invalid="false" aria-describedby="delivery-error">
								<option value="0"<?php if($mod['mod_status'] == 0): ?> selected="selected"<?php endif; ?>>Выключен</option>
								<option value="1"<?php if($mod['mod_status'] == 1): ?> selected="selected"<?php endif; ?>>Включен</option>
							</select>
						</div>
						<hr>
						<div class="m-btn-group m-btn-group--pill btn-group m-btn-group m-btn-group--pill btn-block" role="group" aria-label="Large button group">
							<button type="submit" class="btn btn-primary btn-outline  btn-block sbold uppercase">Сохранить изменения</button>
							<a data-toggle="tooltip" data-placement="right" title="" data-original-title="Удалить" style="height: 3.1rem;" href="/admin/mods/edit/delete/<?php echo $mod['mod_id'] ?>" class="btn btn-danger btn-elevate btn-icon"><i class="fa fa-trash-alt"></i></a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#editForm').ajaxForm({ 
		url: '/admin/mods/edit/ajax/<?php echo $mod['mod_id'] ?>',
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
					setTimeout("redirect('/admin/mods')", 1500);
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			$('button[type=submit]').prop('disabled', true);
		}
	});
</script>
<?php echo $footer ?>
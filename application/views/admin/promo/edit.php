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
						<h3 class="card-label">Редактирование промо кода
						</h3>
					</div>
				</div>
				<div class="card-body">
					<form id="editForm" method="POST">
						<div class="form-group form-md-line-input">
							<input type="text" class="form-control" id="cod" name="cod" placeholder="Введите промо-код" value="<?php echo $promo['cod'] ?>">
						</div>
						<div class="form-group form-md-line-input">
							<select class="form-control" id="skidka" name="skidka" aria-required="true" aria-invalid="false" aria-describedby="delivery-error">
								<option value="5" <?php if($promo['skidka'] == 5): ?> selected="selected"<?php endif; ?>>5%</option>
								<option value="10" <?php if($promo['skidka'] == 10): ?> selected="selected"<?php endif; ?>>10%</option>
								<option value="15" <?php if($promo['skidka'] == 15): ?> selected="selected"<?php endif; ?>>15%</option>
								<option value="20" <?php if($promo['skidka'] == 20): ?> selected="selected"<?php endif; ?>>20%</option>
								<option value="25" <?php if($promo['skidka'] == 25): ?> selected="selected"<?php endif; ?>>25%</option>
								<option value="30" <?php if($promo['skidka'] == 30): ?> selected="selected"<?php endif; ?>>30%</option>
								<option value="35" <?php if($promo['skidka'] == 35): ?> selected="selected"<?php endif; ?>>35%</option>
								<option value="40" <?php if($promo['skidka'] == 40): ?> selected="selected"<?php endif; ?>>40%</option>
								<option value="45" <?php if($promo['skidka'] == 45): ?> selected="selected"<?php endif; ?>>45%</option>
								<option value="50" <?php if($promo['skidka'] == 50): ?> selected="selected"<?php endif; ?>>50%</option>
								<option value="55" <?php if($promo['skidka'] == 55): ?> selected="selected"<?php endif; ?>>55%</option>
								<option value="60" <?php if($promo['skidka'] == 60): ?> selected="selected"<?php endif; ?>>60%</option>
								<option value="65" <?php if($promo['skidka'] == 65): ?> selected="selected"<?php endif; ?>>65%</option>
								<option value="70" <?php if($promo['skidka'] == 70): ?> selected="selected"<?php endif; ?>>70%</option>
								<option value="75" <?php if($promo['skidka'] == 75): ?> selected="selected"<?php endif; ?>>75%</option>
								<option value="80" <?php if($promo['skidka'] == 80): ?> selected="selected"<?php endif; ?>>80%</option>
								<option value="85" <?php if($promo['skidka'] == 85): ?> selected="selected"<?php endif; ?>>85%</option>
								<option value="90" <?php if($promo['skidka'] == 90): ?> selected="selected"<?php endif; ?>>90%</option>
								<option value="95" <?php if($promo['skidka'] == 95): ?> selected="selected"<?php endif; ?>>95%</option>
								<option value="100" <?php if($promo['skidka'] == 100): ?> selected="selected"<?php endif; ?>>100%</option>
							</select>
						</div>
						<div class="form-group form-md-line-input">
							<input type="text" class="form-control" id="uses" name="uses" placeholder="Введите количество использований" value="<?php echo $promo['uses'] ?>">
						</div>
						<hr>
						<div class="m-btn-group m-btn-group--pill btn-group m-btn-group m-btn-group--pill btn-block" role="group" aria-label="Large button group">
							<button type="submit" class="btn btn-primary btn-outline  btn-block sbold uppercase">Сохранить изменения</button>
							<a data-toggle="tooltip" data-placement="right" title="" data-original-title="Удалить" style="height: 3.1rem;" href="/admin/promo/edit/delete/<?php echo $promo['id'] ?>" class="btn btn-danger btn-elevate btn-icon"><i class="fa fa-trash-alt"></i></a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#editForm').ajaxForm({ 
		url: '/admin/promo/edit/ajax/<?php echo $promo['id'] ?>',
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
					setTimeout("redirect('/admin/promo')", 1500);
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			$('button[type=submit]').prop('disabled', true);
		}
	});
</script>
<?php echo $footer ?>
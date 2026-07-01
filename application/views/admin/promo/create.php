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
						<h3 class="card-label">Создание промо кода
						</h3>
					</div>
				</div>
				<div class="card-body">
					<form id="createForm" method="POST">
						<div class="form-group form-md-line-input">
							<input type="text" class="form-control" id="cod" name="cod" placeholder="Введите промо-код">
						</div>
						<div class="form-group form-md-line-input">
							<select class="form-control" id="skidka" name="skidka" aria-required="true" aria-invalid="false" aria-describedby="delivery-error">
								<option value="5">5%</option>
								<option value="10">10%</option>
								<option value="15">15%</option>
								<option value="20">20%</option>
								<option value="25">25%</option>
								<option value="30">30%</option>
								<option value="35">35%</option>
								<option value="40">40%</option>
								<option value="45">45%</option>
								<option value="50">50%</option>
								<option value="55">55%</option>
								<option value="60">60%</option>
								<option value="65">65%</option>
								<option value="70">70%</option>
								<option value="75">75%</option>
								<option value="80">80%</option>
								<option value="85">85%</option>
								<option value="90">90%</option>
								<option value="95">95%</option>
								<option value="100">100%</option>
							</select>
						</div>
						<div class="form-group form-md-line-input">
							<input type="text" class="form-control" id="uses" name="uses" placeholder="Введите количество использований">
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
		url: '/admin/promo/create/ajax',
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
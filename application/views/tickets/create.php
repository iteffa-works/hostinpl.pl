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
			<div class="row">
				<div class="col-xl-4 col-xxl-4 mb-4">
					<div class="card card-custom card-sticky">
						<div class="card-body px-5">
							<div class="px-4 mt-4 mb-10">
								<a href="/tickets/create" class="btn btn-block btn-primary font-weight-bold text-uppercase py-4 px-6 text-center">Написать</a>
							</div>
							<div class="navi navi-hover navi-active navi-link-rounded navi-bold navi-icon-center navi-light-icon">
								<div class="navi-item my-2">
									<a href="/tickets" class="navi-link">
									<span class="navi-icon mr-4">
									<i class="flaticon-computer icon-lg"></i>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Мои запросы</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="/tickets/faq" class="navi-link">
									<span class="navi-icon mr-4">
									<i class="flaticon-book icon-lg"></i>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">База знаний</span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-8 col-xxl-8">
					<div class="card card-custom card-sticky">
						<div class="card-body px-5">
							<form method="POST" id="createForm" style="padding:0px; margin:0px;">
								<div class="form-group form-md-line-input mb-4">
									<input type="text" class="form-control" id="name" name="name" placeholder="Введите тему">
								</div>
								<div class="form-group form-md-line-input mb-4">
									<select class="form-control" id="category" name="category" aria-required="true" aria-invalid="false" aria-describedby="delivery-error">
										<?php foreach($category as $item): ?> 
										<option value="<?php echo $item['category_id'] ?>"><?php echo $item['category_name'] ?></option>
										<?php endforeach; ?> 
									</select>
								</div>
								<div class="form-group form-md-line-input mb-4">
									<textarea class="form-control" id="text" name="text" rows="3" placeholder="Сообщение..."></textarea>
								</div>
								<div class="recaptcha">
									<center>
										<div class="g-recaptcha" data-sitekey="<?php echo $recaptcha ?>" id="recaptcha1"></div>
									</center>
								</div>
								<hr>
								<button type="submit" class="btn btn-primary btn-lg btn-block">Отправить</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://www.google.com/recaptcha/api.js?onload=recaptha&render=explicit" async defer></script>
<script>
	var captcha_ticket; 
		var recaptha = function() { 
		captcha_ticket = grecaptcha.render('recaptcha1', {
			'sitekey' : '<?php echo $recaptcha ?>',
			'theme' : 'light'
		});     
	};  
	
	$('#createForm').ajaxForm({ 
		url: '/tickets/create/ajax',
		dataType: 'text',
		success: function(data) {
			console.log(data);
			data = $.parseJSON(data);
			switch(data.status) {
				case 'error':
					toastr.error(data.error);
					grecaptcha.reset(captcha_ticket);
					$('button[type=submit]').prop('disabled', false);
					break;
				case 'success':
					toastr.success(data.success);
					setTimeout("redirect('/tickets/view/index/" + data.id + "')", 1500);
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			$('button[type=submit]').prop('disabled', true);
		}
	});
</script>
<?php echo $footer ?>
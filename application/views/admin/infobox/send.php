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
			<form id="sendMForm" method="POST">
				<div class="card card-custom mb-4">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="symbol symbol-50 mr-5">
								<span class="symbol-label">
								<img src="/application/public/img/user.png" class="h-75" alt="">
								</span>
							</div>
							<div class="d-flex flex-column flex-grow-1">
								<a href="javascript:;" class="text-dark-75 text-hover-primary mb-1 font-size-lg font-weight-bolder"><?php echo $mail['user_firstname'] ?> <?php echo $mail['user_lastname'] ?> <small><?php echo $mail['user_email'] ?></small></a>
								<span class="text-muted font-weight-bold"><?php echo $mail['text'] ?></span>
							</div>
							<span class="text-muted font-weight-normal font-size-sm"><?php echo $mail['inbox_date_add']?></span>
						</div>
					</div>
				</div>
				<div class="card card-custom">
					<div class="card-body">
						<div class="form-group form-md-line-input">
							<textarea class="form-control" id="msg" name="msg" rows="3" placeholder="Сообщение..."></textarea>
						</div>
						<div class="card card-body" style="margin-bottom: 1rem;padding: 1rem;">
							<div class="form-group" style="margin-bottom: 0rem;">
								<div class="checkbox-list">
									<label class="checkbox">
									<input type="checkbox" id="dell" name="dell">
									<span></span>
									Удалить после отправки
									</label>
								</div>
							</div>
						</div>
						<hr>
						<button type="submit" class="btn btn-primary m-btn m-btn--air btn-outline  btn-block sbold uppercase">Отправить</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	$('#sendMForm').ajaxForm({ 
		url: '/admin/infobox/send/sendMForm/<?php echo $mail['id'] ?>',
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
					setTimeout("redirect('/admin/infobox')", 1500);
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			$('button[type=submit]').prop('disabled', true);
		}
	});
</script>
<?php echo $footer ?>
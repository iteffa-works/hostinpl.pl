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
				<div class="col-xl-12">
					<div class="card card-custom mb-3 pt-5">
						<div class="card-body">
							<form id="configEdit" method="POST">
								<textarea name="config" style="border: 6px solid #121921; border-radius:10px; color: #FFF; background-color: #121921;min-width: 100%; max-width: 100%; min-height: 400px; margin-bottom: 10px;"><?php echo $config ?></textarea>
								<button type="submit" class="btn btn-primary">Сохранить</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#configEdit').ajaxForm({ 
		url: '/servers/config/send_config/<?php echo $server['server_id'] ?>',
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
					setTimeout("reload()", 1500);
					break;
			}
		}
	});
</script>
<?echo $footer?>
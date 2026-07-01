<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
?>
<div class="footer bg-white py-4 d-flex flex-lg-column" id="kt_footer">
	<div class="container d-flex flex-column flex-md-row align-items-center justify-content-between">
		<div class="text-dark order-2 order-md-1">
			<span class="text-muted font-weight-bold mr-2">2020©</span>
			<a href="https://hostinpl.ru" target="_blank" class="text-dark-75 text-hover-primary">HOSTINPL 5.6</a>
		</div>
		<div class="nav nav-dark order-1 order-md-2">
			<a href="/contacts" class="nav-link pr-3 pl-0">Контакты</a>
			<a href="https://vk.com/public<?php echo $public ?>" target="_blank" class="nav-link px-3">Сообщество VK</a>
			<a href="javascript:;" class="nav-link pl-3 pr-0">Пользователей на сайте: <div style="margin-left: 0.25rem;" id="online"></div></a>
		</div>
	</div>
</div>
<script>
	toastr.options = {
	"closeButton": true,
	"debug": false,
	"newestOnTop": false,
	"progressBar": true,
	"positionClass": "toast-top-right",
	"preventDuplicates": false,
	"onclick": null,
	"showDuration": "300",
	"hideDuration": "1000",
	"timeOut": "3500",
	"extendedTimeOut": "3000",
	"showEasing": "swing",
	"hideEasing": "linear",
	"showMethod": "fadeIn",
	"hideMethod": "fadeOut"   
	};
</script>
<script type="text/javascript">
	$(document).ready(
		function get() {
			setTimeout(getstatus('online'), 105000);
			setTimeout(get, 35000);
		}
	);
	function getstatus(action) {
		$.ajax({ 
			url: '/main/index/getstatus/'+action,
			dataType: 'text',
			success: function(data) {
				data = $.parseJSON(data);
				switch(data.status) {
					case 'error':
						toastr.error(data.error);
						$('#controlBtns button').prop('disabled', false);
						break;
					case 'online':
						console.info(data.online); 
						$("#online").html(data.online_usr)
						break
				}
			},
		});
	}
</script>
<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
?>
<?echo $header?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="row">
				<?if($qiwi == 1):?>
				<div class="col-lg-4">
					<div class="card card-custom gutter-b">
						<div class="card-body">
							<center><img src="/application/public/img/pay/qiwi.jpg" style="max-width:100%;height:65px;" alt=""></center>
						</div>
						<div class="card-footer">
							<button data-toggle="modal" data-target="#qiwi" class="btn btn-primary btn-lg btn-block">Пополнить</button>
						</div>
					</div>
				</div>
				<?endif;?>
				<?if($unitpay == 1):?>
				<div class="col-lg-4">
					<div class="card card-custom gutter-b">
						<div class="card-body">
							<center><img src="/application/public/img/pay/unitpay.jpg" style="max-width:100%;height:65px;" alt=""></center>
						</div>
						<div class="card-footer">
							<button data-toggle="modal" data-target="#unitpayhostin" class="btn btn-primary btn-lg btn-block">Пополнить</button>
						</div>
					</div>
				</div>
				<?endif;?>
				<?if($enotpay == 1):?>
				<div class="col-lg-4">
					<div class="card card-custom gutter-b">
						<div class="card-body">
							<center><img src="/application/public/img/pay/enot.png" style="max-width:100%;height:65px;" alt=""></center>
						</div>
						<div class="card-footer">
							<button data-toggle="modal" data-target="#enot" class="btn btn-primary btn-lg btn-block">Пополнить</button>
						</div>
					</div>
				</div>
				<?endif;?>
				<?if($anypay == 1):?>
				<div class="col-lg-4">
					<div class="card card-custom gutter-b">
						<div class="card-body">
							<center><img src="/application/public/img/pay/anypay.jpg" style="max-width:100%;height:65px;" alt=""></center>
						</div>
						<div class="card-footer">
							<button data-toggle="modal" data-target="#anypay" class="btn btn-primary btn-lg btn-block">Пополнить</button>
						</div>
					</div>
				</div>
				<?endif;?>
				<?if($litekassa == 1):?>
				<div class="col-lg-4">
					<div class="card card-custom gutter-b">
						<div class="card-body">
							<center><img src="/application/public/img/pay/litekassa.png" style="max-width:100%;height:65px;" alt=""></center>
						</div>
						<div class="card-footer">
							<button data-toggle="modal" data-target="#litepay" class="btn btn-primary btn-lg btn-block">Пополнить</button>
						</div>
					</div>
				</div>
				<?endif;?>
				<?if($robokassa == 1):?>
				<div class="col-lg-4">
					<div class="card card-custom gutter-b">
						<div class="card-body">
							<center><img src="/application/public/img/pay/robokassa.jpg" style="max-width:100%;height:65px;" alt=""></center>
						</div>
						<div class="card-footer">
							<button data-toggle="modal" data-target="#robopay" class="btn btn-primary btn-lg btn-block">Пополнить</button>
						</div>
					</div>
				</div>
				<?endif;?>
				<?if($freekassa == 1):?>
				<div class="col-lg-4">
					<div class="card card-custom gutter-b">
						<div class="card-body">
							<center><img src="/application/public/img/pay/freekassa.png" style="max-width:100%;height:65px;" alt=""></center>
						</div>
						<div class="card-footer">
							<button data-toggle="modal" data-target="#freepay" class="btn btn-primary btn-lg btn-block">Пополнить</button>
						</div>
					</div>
				</div>
				<?endif;?>
				<?if($yandexkassa == 1):?>
				<div class="col-lg-4">
					<div class="card card-custom gutter-b">
						<div class="card-body">
							<center><img src="/application/public/img/pay/yandexmoney.jpg" style="max-width:100%;height:65px;" alt=""></center>
						</div>
						<div class="card-footer">
							<button data-toggle="modal" data-target="#yandexkassabox" class="btn btn-primary btn-lg btn-block">Пополнить</button>
						</div>
					</div>
				</div>
				<?endif;?>
			</div>
		</div>
	</div>
</div>
<?if($unitpay == 1):?>
<!-- ================================================ /unitpay ================================================ -->
<div class="modal fade" id="unitpayhostin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Пополнение баланса</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form id="unitpay" method="POST" class="form_0" style="padding:0px; margin:0px;">
				<div class="modal-body">
					<div class="form-group">
						<label>Введите сумму</label>
						<input class="form-control" id="ammount" name="ammount" placeholder="100">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Отмена</button>
					<button type="submit" class="btn btn-primary font-weight-bold">Пополнить</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ================================================ /unitpay ================================================ -->
<?endif;?>
<?if($robokassa == 1):?>
<!-- ================================================ /robopay ================================================ -->
<div class="modal fade" id="robopay" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Пополнение баланса</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form id="robopay" method="POST" class="form_0" style="padding:0px; margin:0px;">
				<div class="modal-body">
					<div class="form-group">
						<label>Введите сумму</label>
						<input class="form-control" id="ammount" name="ammount" placeholder="100">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Отмена</button>
					<button type="submit" class="btn btn-primary font-weight-bold">Пополнить</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ================================================ /robopay ================================================ -->
<?endif;?>
<?if($freekassa == 1):?>
<!-- ================================================ /freepay ================================================ -->
<div class="modal fade" id="freepay" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Пополнение баланса</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form id="freepay" method="POST" class="form_0" style="padding:0px; margin:0px;">
				<div class="modal-body">
					<div class="form-group">
						<label>Введите сумму</label>
						<input class="form-control" id="ammount" name="ammount" placeholder="100">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Отмена</button>
					<button type="submit" class="btn btn-primary font-weight-bold">Пополнить</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ================================================ /freepay ================================================ -->
<?endif;?>
<?if($enotpay == 1):?>
<!-- ================================================ /enot ================================================ -->
<div class="modal fade" id="enot" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Пополнение баланса</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form id="enot" method="POST" class="form_0" style="padding:0px; margin:0px;">
				<div class="modal-body">
					<div class="form-group">
						<label>Введите сумму</label>
						<input class="form-control" id="ammount" name="ammount" placeholder="100">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Отмена</button>
					<button type="submit" class="btn btn-primary font-weight-bold">Пополнить</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ================================================ /enot ================================================ -->
<?endif;?>
<?if($anypay == 1):?>
<!-- ================================================ /anypay ================================================ -->
<div class="modal fade" id="anypay" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Пополнение баланса</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form id="anypay" method="POST" class="form_0" style="padding:0px; margin:0px;">
				<div class="modal-body">
					<div class="form-group">
						<label>Введите сумму</label>
						<input class="form-control" id="ammount" name="ammount" placeholder="100">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Отмена</button>
					<button type="submit" class="btn btn-primary font-weight-bold">Пополнить</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ================================================ /anypay ================================================ -->
<?endif;?>
<?if($litekassa == 1):?>
<!-- ================================================ /litepay ================================================ -->
<div class="modal fade" id="litepay" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Пополнение баланса</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form id="litepay" method="POST" class="form_0" style="padding:0px; margin:0px;">
				<div class="modal-body">
					<div class="form-group">
						<label>Введите сумму</label>
						<input class="form-control" id="ammount" name="ammount" placeholder="100">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Отмена</button>
					<button type="submit" class="btn btn-primary font-weight-bold">Пополнить</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ================================================ /litepay ================================================ -->
<?endif;?>
<?if($yandexkassa == 1):?>
<div class="modal fade" id="yandexkassabox" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Пополнение баланса</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form id="yandexkassa" method="POST" class="form_0" style="padding:0px; margin:0px;">
				<div class="modal-body">
					<div class="form-group">
						<label>Введите сумму</label>
						<input class="form-control" id="ammount" name="ammount" placeholder="100">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Отмена</button>
					<button type="submit" class="btn btn-primary font-weight-bold">Пополнить</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?endif;?>
<?if($qiwi == 1):?>
<div class="modal fade" id="qiwi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Пополнение баланса</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form id="qiwi" method="POST" class="form_0" style="padding:0px; margin:0px;">
				<div class="modal-body">
					<div class="form-group">
						<label>Введите сумму</label>
						<input class="form-control" id="ammount" name="ammount" placeholder="100">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Отмена</button>
					<button type="submit" class="btn btn-primary font-weight-bold">Пополнить</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?endif;?>
<?if($yandexkassa == 1):?>
<script>
	$('#yandexkassa').ajaxForm({ 
	     url: '/account/pay/yandexkassa',
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
	                 redirect(data.url);
	             break;
	         }
	     },
	     beforeSubmit: function(arr, $form, options) {
	         $('button[type=submit]').prop('disabled', true);
	     }
	 });
</script>
<?endif;?>
<?if($litekassa == 1):?>
<script>
	$('#litepay').ajaxForm({ 
	     url: '/account/pay/litepay',
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
	                 redirect(data.url);
	             break;
	         }
	     },
	     beforeSubmit: function(arr, $form, options) {
	         $('button[type=submit]').prop('disabled', true);
	     }
	 });
</script>
<?endif;?>
<?if($robokassa == 1):?>
<script>
	$('#robopay').ajaxForm({ 
	     url: '/account/pay/robopay',
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
	                 redirect(data.url);
	             break;
	         }
	     },
	     beforeSubmit: function(arr, $form, options) {
	         $('button[type=submit]').prop('disabled', true);
	     }
	 });
</script>
<?endif;?>
<?if($freekassa == 1):?>
<script>
	$('#freepay').ajaxForm({ 
	     url: '/account/pay/freepay',
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
	                 redirect(data.url);
	             break;
	         }
	     },
	     beforeSubmit: function(arr, $form, options) {
	         $('button[type=submit]').prop('disabled', true);
	     }
	 });
</script>
<?endif;?>
<?if($unitpay == 1):?>
<script>
	$('#unitpayhostin').ajaxForm({ 
	    url: '/account/pay/unitpay',
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
	                redirect(data.url);
	                break;
	        }
	    },
	    beforeSubmit: function(arr, $form, options) {
	        $('button[type=submit]').prop('disabled', true);
	    }
	});
</script>
<?endif;?>
<?if($enotpay == 1):?>
<script>
	$('#enot').ajaxForm({ 
	    url: '/account/pay/enotpay',
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
	                redirect(data.url);
	                break;
	        }
	    },
	    beforeSubmit: function(arr, $form, options) {
	        $('button[type=submit]').prop('disabled', true);
	    }
	});
</script>
<?endif;?>
<?if($anypay == 1):?>
<script>
	$('#anypay').ajaxForm({ 
	    url: '/account/pay/anypay',
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
	                redirect(data.url);
	                break;
	        }
	    },
	    beforeSubmit: function(arr, $form, options) {
	        $('button[type=submit]').prop('disabled', true);
	    }
	});
</script>
<?endif;?>
<?if($qiwi == 1):?>
<script>
	$('#qiwi').ajaxForm({ 
	    url: '/account/pay/qiwi',
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
	                redirect(data.url);
	                break;
	        }
	    },
	    beforeSubmit: function(arr, $form, options) {
	        $('button[type=submit]').prop('disabled', true);
	    }
	});
</script>
<?endif;?>
<?echo $footer?>
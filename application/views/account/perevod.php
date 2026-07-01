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
         <div class="card card-custom gutter-b">
            <div class="card-header">
               <div class="card-title">
                  <h3 class="card-label">
                     Перевод средств
                  </h3>
               </div>
            </div>
            <div class="card-body">
               <form class="form-group form-md-line-input" action="#" id="payForm" method="POST">
                  <div class="form-group form-md-line-input">
                     <input type="text" class="form-control" id="userid" name="userid" placeholder="ID Пользователя">
                  </div>
                  <div class="form-group form-md-line-input">
                     <input type="text" class="form-control" id="sum" name="sum" placeholder="Сумма">
                  </div>
                  <button type="submit" class="btn btn-primary btn-lg btn-block">Перевести средства</button>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   $('#payForm').ajaxForm({
   	url: '/account/perevod/ajax',
   	dataType: 'text',
   	success: function(data) {
   		data = $.parseJSON(data);
   		switch(data.status) {
   			case 'error':
   				toastr.error(data.error);
   				break;
   			case 'success':
   				toastr.success(data.success);
   				break;
   		}
   		$('button[type=submit]').prop('disabled', false);
   	},
   	beforeSubmit: function(arr, $form, options) {
   		$('button[type=submit]').prop('disabled', true);
   	}
   });
</script>
<?php echo $footer ?>

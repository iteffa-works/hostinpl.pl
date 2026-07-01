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
            <div class="col-lg-12 mb-10">
               <blockquote class="blockquote text-center">
                  <p class="mb-0">Здесь вы можете обменять свои бонусные баллы.</p>
                  <footer class="blockquote-footer">У вас
                     <cite title="Source Title"><?php echo $users['bonuses'] ?> бонусов</cite>
                  </footer>
               </blockquote>
            </div>
            <div class="col-lg-6 mb-10">
               <div class="card card-custom gutter-b bg-light-primary">
                  <div class="card-body">
                     <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                        <div class="d-flex flex-column mr-5">
                           <a href="javascript:;" class="h1 text-dark text-hover-primary font-weight-bolder">
                           100 COINS
                           </a>
                        </div>
                        <div class="ml-6 ml-lg-0 ml-xxl-6 flex-shrink-0">
                           <a href="javascript:;" onClick="sendAction_exchange('100DEB')" class="btn font-weight-bolder text-uppercase btn-primary py-4 px-6">
                           Обменять на <?php echo $bonus1 ?> RUB
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-lg-6 mb-10">
               <div class="card card-custom gutter-b bg-light-primary">
                  <div class="card-body">
                     <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                        <div class="d-flex flex-column mr-5">
                           <a href="javascript:;" class="h1 text-dark text-hover-primary font-weight-bolder">
                           300 COINS
                           </a>
                        </div>
                        <div class="ml-6 ml-lg-0 ml-xxl-6 flex-shrink-0">
                           <a href="javascript:;"  onClick="sendAction_exchange('300DEB')" class="btn font-weight-bolder text-uppercase btn-primary py-4 px-6">
                           Обменять на <?php echo $bonus2 ?> RUB
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-lg-6 mb-10">
               <div class="card card-custom gutter-b bg-light-primary">
                  <div class="card-body">
                     <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                        <div class="d-flex flex-column mr-5">
                           <a href="javascript:;" class="h1 text-dark text-hover-primary font-weight-bolder">
                           600 COINS
                           </a>
                        </div>
                        <div class="ml-6 ml-lg-0 ml-xxl-6 flex-shrink-0">
                           <a href="javascript:;" onClick="sendAction_exchange('600DEB')" class="btn font-weight-bolder text-uppercase btn-primary py-4 px-6">
                           Обменять на <?php echo $bonus3 ?> RUB
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-lg-6 mb-10">
               <div class="card card-custom gutter-b bg-light-primary">
                  <div class="card-body">
                     <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                        <div class="d-flex flex-column mr-5">
                           <a href="javascript:;" class="h1 text-dark text-hover-primary font-weight-bolder">
                           1000 COINS
                           </a>
                        </div>
                        <div class="ml-6 ml-lg-0 ml-xxl-6 flex-shrink-0">
                           <a href="javascript:;" onClick="sendAction_exchange('1000DEB')" class="btn font-weight-bolder text-uppercase btn-primary py-4 px-6">
                           Обменять на <?php echo $bonus4 ?> RUB
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   function sendAction_exchange(action) {
   $.ajax({ 
        url: '/account/bonus/ajax_action_exchange/'+action,
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
        }
    });
   }
</script>
<?php echo $footer ?>
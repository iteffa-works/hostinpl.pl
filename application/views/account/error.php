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
         <div class="col">
            <div class="alert alert-light alert-elevate fade show" role="alert">
               <div class="alert-text">
                  Внутренняя ошибка. Возможные причины:<br>
                  - Неверная сумма платежа.<br>
                  - Неверный ID магазина.<br>
                  - Не верный ID платежа.<br>
                  - Данный счет уже оплачен.<br>
                  - Платеж был отменен.<br>
                  <br>
                  Если здесь нет выявленной вами причины, то обратитесь в <a href="/tickets/create">Службу поддержки</a>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php echo $footer ?>
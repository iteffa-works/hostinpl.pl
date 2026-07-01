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
                  <h3 class="card-label">Список пользователей
                  </h3>
               </div>
               <?php if($user_access_level == 3):?>
               <div class="card-toolbar">
                  <a href="javascript:;" data-toggle="modal" data-target="#modal-bonus" class="btn btn-clean btn-icon">
                  <i class="fas fa-gift" data-toggle="tooltip" data-placement="right" title="" data-original-title="Провести акцию"></i>
                  </a>
               </div>
               <?php endif; ?>
            </div>
            <div class="card-body" style="padding: 0rem 1rem;">
               <div class="table-responsive">
                  <table class="table table-head-custom table-vertical-center">
                     <thead>
                        <tr>
                           <th><i class="fa fa-list-ol"></i></th>
                           <th>Статус</th>
                           <th>Пользователь</th>
                           <th>E-mail</th>
                           <th>Дата регистрации</th>
                           <th>Статус активации</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach($users as $item): ?> 
                        <tr onClick="redirect('/admin/users/edit/index/<?php echo $item['user_id'] ?>')">
                           <th scope="row"><?php echo $item['user_id'] ?></th>
                           <td>
                              <?php if($item['user_status'] == 0): ?>
                              <span class="badge badge-warning">Заблокирован</span>
                              <?php elseif($item['user_status'] == 1): ?>
                              <span class="badge badge-success">Активен</span>
                              <?php endif; ?>
                           </td>
                           <td><?php echo $item['user_firstname'] ?> <?php echo $item['user_lastname'] ?></td>
                           <td><?php echo $item['user_email'] ?></td>
                           <td><?php echo date("d.m.Y", strtotime($item['user_date_reg'])) ?></td>
                           <td>
                              <?php if($item['user_activate'] == 0): ?>
                              <span class="badge badge-danger">Не подтвержден</span>
                              <?php elseif($item['user_activate'] == 1): ?>
                              <span class="badge badge-info">Подтвержден</span>
                              <?php endif; ?>
                           </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($users)): ?> 
                        <tr>
                           <td colspan="6" class="text-center">На данный момент нет пользователей.</td>
                        </tr>
                        <?php endif; ?> 
                     </tbody>
                  </table>
                  <?php echo $pagination ?> 
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php if($user_access_level == 3):?>
<!--begin::Modal-->
<div class="modal fade" id="modal-bonus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Проведение акции</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
            </button>
         </div>
         <form id="sendFormBonus" method="POST">
            <div class="modal-body">
               <div class="form-group form-md-line-input">
                  <input type="text" class="form-control" id="minsum" name="minsum" placeholder="Введите минимальный бонус">
               </div>
               <div class="form-group form-md-line-input">
                  <input type="text" class="form-control" id="maxsum" name="maxsum" placeholder="Введите максимальный бонус">
               </div>
               <hr>
               <div class="m-form__group form-group">
                  <label class="m-checkbox m-checkbox--bold m-checkbox--state-brand">
                  <input type="checkbox" id="typesum" name="typesum"> Раздача реальных денег
                  <span></span>
                  </label>
                  <br>
                  <label class="m-checkbox m-checkbox--bold m-checkbox--state-brand">
                  <input type="checkbox" id="oneuser" name="oneuser" onChange="toggleOneuser()"> Выдать только 1 пользователю
                  <span></span>
                  </label>
                  <br>
                  <label class="m-checkbox m-checkbox--bold m-checkbox--state-brand">
                  <input type="checkbox" id="createnews" name="createnews"> Создавать новость об проведённом розыгрыше
                  <span></span>
                  </label>
               </div>
               <hr>
               <div class="form-group form-md-line-input">
                  <input type="text" class="form-control" id="oneuserID" name="oneuserID" placeholder="Введите ID пользователя" disabled>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Отмена</button>
               <button type="submit" class="btn btn-primary font-weight-bold">Начать акцию</button>
            </div>
         </form>
      </div>
   </div>
</div>
<!--end::Modal-->
<script>
   $('#sendFormBonus').ajaxForm({ 
       url: '/admin/users/index/ajax/',
       dataType: 'text',
       success: function(data) {
           console.log(data);
           data = $.parseJSON(data);
           switch(data.status) {
               case 'error':
                   toastr.error(data.error);
                   $('button[type=submit]').prop('disabled', false);
                   document.getElementById("oneuserID").value = "";
                   break;
               case 'success':
                   toastr.success(data.success);
                   document.getElementById("oneuserID").value = "";
                   break;
               case 'info':
                   toastr.info(data.info);
                   document.getElementById("oneuserID").value = "";
                   break;
           }   
       },
       beforeSubmit: function(arr, $form, options) {
       }
   });
   function toggleOneuser() {
       var status = $('#oneuser').is(':checked');
       if(status) {
           $('#oneuserID').prop('disabled', false);
       } else {
           $('#oneuserID').prop('disabled', true);
       }
   }
</script>	
<?php endif; ?> 			
<?php echo $footer ?>
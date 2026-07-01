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
         <div class="row">
            <?php foreach($waste as $item): ?>
            <div class="col-xl-6">
               <div class="card card-custom mb-3">
                  <div class="card-header border-0">
                     <h3 class="card-title"><a href="/admin/users/edit/index/<?php echo $item['user_id'] ?>" class="text-dark"><?php echo $item['user_firstname'] ?> <?php echo $item['user_lastname'] ?></a><small class="text-muted font-size-sm ml-2"><?php echo $item['waste_usluga'] ?></small></h3>
                     <div class="card-toolbar">
                        <?php if($item['waste_status'] == 1): ?>
                        <span class="badge badge-danger" data-toggle="tooltip" data-placement="right" title="" data-original-title="Дата операции: <?php echo date("d.m.Y в H:i", strtotime($item['waste_date_add'])) ?>">- <?php echo $item['waste_ammount'] ?> RUB.</span>
                        <?php elseif($item['waste_status'] == 0): ?>
                        <span class="badge badge-success" data-toggle="tooltip" data-placement="right" title="" data-original-title="Дата операции: <?php echo date("d.m.Y в H:i", strtotime($item['waste_date_add'])) ?>">+ <?php echo $item['waste_ammount'] ?> RUB.</span>
                        <?php endif; ?>  
                     </div>
                  </div>
               </div>
            </div>
            <?php endforeach; ?>
            <div class="col-xl-12">
               <?php echo $pagination ?>
               <?php if(empty($waste)): ?>
               <div class="alert alert-custom alert-light-primary fade show mb-4" role="alert">
                  <div class="alert-icon">
                     <i class="flaticon-exclamation"></i>
                  </div>
                  <div class="alert-text">На данный момент нет операций.</div>
                  <div class="alert-close">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">
                     <i class="ki ki-close"></i>
                     </span>
                     </button>
                  </div>
               </div>
               <?php endif; ?>
            </div>
         </div>
      </div>
   </div>
</div>
<?php echo $footer ?>
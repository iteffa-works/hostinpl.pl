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
            <?php foreach($users as $item): ?> 
            <? if (!($item['user_access_level'] == 3)) {
               continue;
               }?>
            <div class="col-lg-6 mb-4">
               <div class="card card-custom card-sticky card-shadowless bg-gray-100 mb-4">
                  <div class="card-body pt-7" style="padding: 0.5rem 2.25rem;">
                     <div class="d-flex align-items-center mb-6">
                        <div class="symbol symbol-35 flex-shrink-0 mr-3">
                           <img alt="Pic" src="<?php echo $url ?><?if($item['user_img']) {echo $item['user_img'];}else{ echo '/application/public/img/user.png';}?>">
                        </div>
                        <div class="d-flex align-items-center flex-wrap flex-row-fluid">
                           <div class="d-flex flex-column pr-5 flex-grow-1">
                              <a href="#" class="text-dark text-hover-primary font-weight-bolder font-size-lg"><?php echo $item['user_firstname'] ?> <?php echo $item['user_lastname'] ?></a>
                              <span class="text-muted font-weight-bold">Администратор</span>
                           </div>
                           <span class="text-dark-50 font-weight-bold font-size-lg py-2">
                           <a href="https://vk.com/write<?php echo $item['user_vk_id'] ?>" target="blank" class="btn btn-icon btn-light-primary mr-2">
                           <i class="far fa-envelope"></i>
                           </a>
                           <a href="https://vk.com/id<?php echo $item['user_vk_id'] ?>" target="blank" class="btn btn-icon btn-light-primary mr-2">
                           <i class="fab fa-vk"></i>
                           </a>
                           </span>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <?php endforeach; ?>
         </div>
      </div>
   </div>
</div>
<?php echo $footer ?>
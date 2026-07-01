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
                  <h3 class="card-label">Список модов
                  </h3>
               </div>
               <div class="card-toolbar">
                  <a href="/admin/mods/create" class="btn btn-sm btn-icon btn-light-primary" data-toggle="tooltip" data-placement="right" title="" data-original-title="Добавить мод">
                  <i class="flaticon2-add-square"></i>
                  </a>
               </div>
            </div>
            <div class="card-body" style="padding: 0rem 1rem;">
               <div class="table-responsive">
                  <table class="table table-head-custom table-vertical-center">
                     <thead>
                        <tr>
                           <th><i class="fa fa-list-ol"></i></th>
                           <th>Игра</th>
                           <th>Название</th>
                           <th>Статус</th>
                           <th>Стоимость</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach($mods as $item): ?>
                        <tr onClick="redirect('/admin/mods/edit/index/<?php echo $item['mod_id'] ?>')">
                           <th scope="row"><?php echo $item['mod_id'] ?></th>
                           <td><?php echo $item['game_name'] ?></td>
                           <td><?php echo $item['mod_name'] ?></td>
                           <td>
                              <?php if($item['mod_status'] == 0): ?> 
                              <span class="badge badge-danger">Выключен</span>
                              <?php elseif($item['mod_status'] == 1): ?> 
                              <span class="badge badge-success">Включен</span>
                              <?php endif; ?>
                           </td>
                           <td><?php echo $item['mod_price'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($mods)): ?> 
                        <tr>
                           <td colspan="5" class="text-center">На данный момент нет модов.</td>
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
<?php echo $footer ?>

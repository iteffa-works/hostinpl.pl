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
            <div class="col-xl-4">
               <div class="card card-custom mb-3">
                  <div class="card-body p-0" style="position: relative;">
                     <div class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">
                        <span class="symbol symbol-50 symbol-light-primary mr-2">
                        <span class="symbol-label">
                        <i class="fa fa-users icon-xl text-primary"></i>
                        </span>
                        </span>
                        <div class="d-flex flex-column text-right">
                           <span class="text-dark-75 font-weight-bolder font-size-h3"></i><?$userov = 0;
                              foreach($users as $itemr): ?>
                           <? $userov++; ?>
                           <?endforeach;echo $userov; ?></span>
                           <span class="text-muted font-weight-bold mt-2">Пользователей</span>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-4">
               <div class="card card-custom mb-3">
                  <div class="card-body p-0" style="position: relative;">
                     <div class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">
                        <span class="symbol symbol-50 symbol-light-primary mr-2">
                        <span class="symbol-label">
                        <i class="fa fa-server icon-xl text-primary"></i>
                        </span>
                        </span>
                        <div class="d-flex flex-column text-right">
                           <span class="text-dark-75 font-weight-bolder font-size-h3"></i><?$tserverov1 = 0;
                              foreach($tservers as $item): ?>
                           <? $tserverov1++; ?>
                           <?endforeach;echo $tserverov1; ?></span>
                           <span class="text-muted font-weight-bold mt-2">Игровых серверов</span>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-4">
               <div class="card card-custom mb-3">
                  <div class="card-body p-0" style="position: relative;">
                     <div class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">
                        <span class="symbol symbol-50 symbol-light-primary mr-2">
                        <span class="symbol-label">
                        <i class="fa fa-dice icon-xl text-primary"></i>
                        </span>
                        </span>
                        <div class="d-flex flex-column text-right">
                           <span class="text-dark-75 font-weight-bolder font-size-h3"></i><?$gamev1 = 0;
                              foreach($games as $itemr): ?>
                           <? if (!($itemr['game_status'] == 1)) { // пропуск нечетных чисел
                              continue;
                              }?>
                           <? $gamev1++; ?>
                           <?endforeach;echo $gamev1; ?></span>
                           <span class="text-muted font-weight-bold mt-2">Доступных игр</span>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <?php foreach($locations as $item): ?>
            <div class="col-xl-4">
               <div class="card card-custom gutter-b">
                  <div class="card-header border-0 pt-6">
                     <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder font-size-h4 text-dark-75"><?php echo $item['location_name'] ?></span>
                        <span class="text-muted mt-3 font-weight-bold font-size-lg">Данные обновлены в <?php echo date("H:i:s", strtotime($item['location_upd'])) ?></span>
                     </h3>
                  </div>
                  <div class="card-body d-flex flex-column pb-20">
                     <div class="progress-vertical min-h-150px flex-grow-1 border-bottom">
                        <div class="text-center mr-1">
                           <div class="progress bg-light-primary mb-4 mr-0 rounded-bottom-0" data-toggle="tooltip" title="" data-original-title="<?php echo $item['location_cpu'] ?>%">
                              <div class="progress-bar bg-primary w-50px rounded-bottom-0" role="progressbar" style="height: <?php echo $item['location_cpu'] ?>%" aria-valuemin="0" aria-valuemax="100"></div>
                           </div>
                           <span class="font-weight-bold font-size-lg text-muted">CPU</span>
                        </div>
                        <div class="text-center mx-1">
                           <div class="progress bg-light-primary mb-4 mr-0 rounded-bottom-0" data-toggle="tooltip" title="" data-original-title="<?php echo (int)$item['location_ram'] ?>%">
                              <div class="progress-bar bg-primary w-50px rounded-bottom-0" role="progressbar" style="height:<?php echo (int)$item['location_ram'] ?>%" aria-valuemin="0" aria-valuemax="100"></div>
                           </div>
                           <span class="font-weight-bold font-size-lg text-muted">RAM</span>
                        </div>
                        <div class="text-center mx-1">
                           <div class="progress bg-light-primary mb-4 mr-0 rounded-bottom-0" data-toggle="tooltip" title="" data-original-title="<?php echo (int)$item['location_hdd'] ?>%">
                              <div class="progress-bar bg-primary w-50px rounded-bottom-0" role="progressbar" style="height:<?php echo (int)$item['location_hdd'] ?>%;" aria-valuemin="0" aria-valuemax="100"></div>
                           </div>
                           <span class="font-weight-bold font-size-lg text-muted">SSD</span>
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
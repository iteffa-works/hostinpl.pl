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
         <div class="card card-custom overflow-hidden">
            <div class="card-body p-0">
               <div class="row justify-content-center bgi-size-cover bgi-no-repeat py-8 px-8 py-md-3 px-md-0" style="background-image: url(/assets/image/bg.jpg);">
                  <div class="col-md-9">
                     <div class="d-flex justify-content-between text-white pt-2">
                        <div class="d-flex flex-column flex-root">
                           <span class="font-weight-bolde mb-2r">Клиент</span>
                           <span class="opacity-70"><?php echo $user_firstname ?> <?php echo $user_lastname ?></span>
                        </div>
                        <div class="d-flex flex-column flex-root">
                           <span class="font-weight-bolder mb-2">Баланс</span>
                           <span class="opacity-70"><? echo $user_balance?> RUB</span>
                        </div>
                        <div class="d-flex flex-column flex-root">
                           <span class="font-weight-bolder mb-2">E-mail</span>
                           <span class="opacity-70"><? echo $user_email?></span>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row justify-content-center py-8 px-8 py-md-10 px-md-0">
                  <div class="col-md-10">
                     <div class="table-responsive">
                        <table class="table">
                           <thead>
                              <tr>
                                 <th class="pl-0 font-weight-bold text-muted text-uppercase">Ид платежа</th>
                                 <th class="text-right font-weight-bold text-muted text-uppercase">Статус</th>
                                 <th class="text-right font-weight-bold text-muted text-uppercase">Дата</th>
                                 <th class="text-right pr-0 font-weight-bold text-muted text-uppercase">Сумма</th>
                                 <th class="text-right pr-0 font-weight-bold text-muted text-uppercase">Платежная система</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php foreach($invoices as $item): ?>
                              <tr class="font-weight-boldest font-size-lg">
                                 <td class="pl-0 pt-7"><?php echo $item['invoice_id'] ?></td>
                                 <td class="text-right pt-7"><?php if($item['invoice_status'] == 0): ?> 
                                    Не оплачен
                                    <?php elseif($item['invoice_status'] == 1): ?> 
                                    Оплачен
                                    <?php endif; ?> 
                                 </td>
                                 <td class="text-right pt-7"><?php echo date("d.m.Y в H:i", strtotime($item['invoice_date_add'])) ?></td>
                                 <td class="text-right pt-7"><?php echo $item['invoice_ammount'] ?> RUB.</td>
                                 <td class="text-primary pr-0 pt-7 text-right"><?php echo $item['system'] ?></td>
                              </tr>
                              <?php endforeach; ?>
                              <?php if(empty($invoices)): ?>
                              <tr>
                                 <td colspan="5" class="text-primary pr-0 pt-7 text-center">На данный момент у вас нет счетов.</td>
                              </tr>
                              <?php endif; ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <div class="row justify-content-center bg-gray-100 py-8 px-8 py-md-10 px-md-0">
                  <div class="col-md-9">
                     <div class="d-flex justify-content-between flex-column flex-md-row font-size-lg">
                        <div class="d-flex flex-column mb-10 mb-md-0">
                           <div class="font-weight-bolder font-size-lg mb-3">Информация</div>
                           <div class="d-flex justify-content-between mb-3">
                              <span class="mr-15 font-weight-bold">Телефон:</span>
                              <span class="text-right"><?php echo $phone ?></span>
                           </div>
                           <div class="d-flex justify-content-between mb-3">
                              <span class="mr-15 font-weight-bold">E-mail:</span>
                              <span class="text-right"><?php echo $mail_from ?></span>
                           </div>
                        </div>
                        <div class="d-flex flex-column text-md-right">
                           <span class="font-size-lg font-weight-bolder mb-1">Общая сумма пополнений</span>
                           <span class="font-size-h2 font-weight-boldest text-primary mb-1"><?$invo = 0;
                              foreach($invoices as $item): ?>
                           <? if (!($item['invoice_status'] == 1)) { // пропуск нечетных чисел
                              continue;
                              }?>
                           <? $invo=$invo+$item['invoice_ammount']; ?>
                           <?endforeach; echo $invo; ?> RUB</span>
                           <span>Номер счета: <?php echo $user_id ?></span>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row justify-content-center py-8 px-8 py-md-10 px-md-0">
                  <div class="col-md-9">
                     <div class="d-flex justify-content-between">
                        <a href="/account/pay" class="btn btn-light-primary font-weight-bold">Пополнить баланс</a>
                        <button type="button" class="btn btn-primary font-weight-bold" onclick="window.print();">Распечатать</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php echo $footer ?>
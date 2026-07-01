<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko (https://vk.com/id00v)
*/
?>
<?php echo $header ?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
   <div class="d-flex flex-column-fluid">
      <div class="container">
         <div class="row">
            <div class="col-lg-6 col-xl-6 mb-5">
               <div class="card card-custom wave wave-animate-slow mb-8 mb-lg-0">
                  <div class="card-body">
                     <div class="d-flex align-items-center p-5">
                        <div class="mr-6">
                           <span class="svg-icon svg-icon-primary svg-icon-4x">
                              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                 <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path d="M3,4 L20,4 C20.5522847,4 21,4.44771525 21,5 L21,7 C21,7.55228475 20.5522847,8 20,8 L3,8 C2.44771525,8 2,7.55228475 2,7 L2,5 C2,4.44771525 2.44771525,4 3,4 Z M10,10 L20,10 C20.5522847,10 21,10.4477153 21,11 L21,19 C21,19.5522847 20.5522847,20 20,20 L10,20 C9.44771525,20 9,19.5522847 9,19 L9,11 C9,10.4477153 9.44771525,10 10,10 Z" fill="#000000"/>
                                    <rect fill="#000000" opacity="0.3" x="2" y="10" width="5" height="10" rx="1"/>
                                 </g>
                              </svg>
                           </span>
                        </div>
                        <div class="d-flex flex-column">
                           <a href="#" class="text-dark text-hover-primary font-weight-bold font-size-h4 mb-3">Удобная панель управления</a>
                           <div class="text-dark-75">С нашей панелью управления, управлять сервером легко и просто с ней справится как опытный пользователь так и новичок.</div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-lg-6 col-xl-6 mb-5">
               <div class="card card-custom wave wave-animate-slow mb-8 mb-lg-0">
                  <div class="card-body">
                     <div class="d-flex align-items-center p-5">
                        <div class="mr-6">
                           <span class="svg-icon svg-icon-primary svg-icon-4x">
                              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                 <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path opacity="0.3" fill-rule="evenodd" clip-rule="evenodd" d="M14.4862 18L12.7975 21.0566C12.5304 21.54 11.922 21.7153 11.4386 21.4483C11.2977 21.3704 11.1777 21.2597 11.0887 21.1255L9.01653 18H5C3.34315 18 2 16.6569 2 15V6C2 4.34315 3.34315 3 5 3H19C20.6569 3 22 4.34315 22 6V15C22 16.6569 20.6569 18 19 18H14.4862Z" fill="black"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6 7H15C15.5523 7 16 7.44772 16 8C16 8.55228 15.5523 9 15 9H6C5.44772 9 5 8.55228 5 8C5 7.44772 5.44772 7 6 7ZM6 11H11C11.5523 11 12 11.4477 12 12C12 12.5523 11.5523 13 11 13H6C5.44772 13 5 12.5523 5 12C5 11.4477 5.44772 11 6 11Z" fill="black"/>
                                 </g>
                              </svg>
                           </span>
                        </div>
                        <div class="d-flex flex-column">
                           <a href="#" class="text-dark text-hover-primary font-weight-bold font-size-h4 mb-3">Техническая поддержка</a>
                           <div class="text-dark-75">Наша техническая поддержка работает "7" дней в неделю "24" часа в сутки "365" дней в году. Мы готовы помочь вам в любое время суток.</div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-lg-12 col-xl-12 mb-5">
               <div class="timeline timeline-justified timeline-4">
                  <div class="timeline-items">
                     <?php foreach($news as $item): ?> 
                     <div class="timeline-item">
                        <div class="timeline-badge">
                           <div class="bg-primary"></div>
                        </div>
                        <div class="timeline-label text-primary">
                           <span class="text-primary font-weight-bold"><?php echo date("d.m.Y в H:i", strtotime($item['news_date_add'])) ?></span>
                        </div>
                        <div class="timeline-content">
                           <?php echo $item['news_title'] ?>
                           <br>
                           <a href="/news/view/index/<?echo $item['news_id']?>" class="text-hover-danger">Читать полностью</a>
                        </div>
                     </div>
                     <?php endforeach; ?>
                     <?php if(empty($news)): ?>
                     <div class="timeline-item">
                        <div class="timeline-badge">
                           <div class="bg-primary"></div>
                        </div>
                        <div class="timeline-label text-primary">
                           <span class="text-primary font-weight-bold">News</span>
                        </div>
                        <div class="timeline-content">
                           К сожалению, на данный момент на хостинге нет новостей
                           <br>
                           <a href="https://vk.com/public<?php echo $public ?>" class="text-hover-danger">Смотреть новости в сообществе VK</a>
                        </div>
                     </div>
                     <?php endif; ?>
                  </div>
               </div>
               <?php echo $pagination ?>
            </div>
         </div>
      </div>
   </div>
</div>
<?php echo $footer ?>

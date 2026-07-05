<?php
/**
 * Dovhopol Mykola Ivanovich (iTeffa)
 * Telegram: https://t.me/iteffa
 * Phone: +380966349498
 * Email: flowaxy.dev@gmail.com
 * Website: https://flowaxy.com/
 *
 * Шаблон страницы настроек плагина Site Landing.
 *
 * Created: 2026-07-02
 * Modified: 2026-07-06
 *
 * © 2026 Flowaxy Digital Studio. All rights reserved.
 */
?>
<?php echo $admheader ?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
   <div class="d-flex flex-column-fluid">
      <div class="container">
         <div class="card card-custom">
            <div class="card-header">
               <div class="card-title">
                  <h3 class="card-label">Site Landing — настройки</h3>
               </div>
            </div>
            <div class="card-body">
               <p class="text-muted mb-4">Плагин заменяет главную страницу для неавторизованных пользователей.</p>
               <p class="mb-0">Шаблон: <code>plugins/site-landing/views/landing.php</code></p>
               <p class="mt-4 mb-0">
                  <a href="/admin/plugins" class="btn btn-light-primary">К списку плагинов</a>
               </p>
            </div>
         </div>
      </div>
   </div>
</div>
<?php echo $footer ?>

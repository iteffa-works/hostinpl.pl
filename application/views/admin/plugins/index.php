<?php
/**
 * Dovhopol Mykola Ivanovich (iTeffa)
 * Telegram: https://t.me/iteffa
 * Phone: +380966349498
 * Email: flowaxy.dev@gmail.com
 * Website: https://flowaxy.com/
 *
 * Шаблон списка плагинов в админке.
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
                  <h3 class="card-label">Плагины</h3>
               </div>
            </div>
            <div class="card-body" style="padding: 0rem 1rem;">
               <div class="table-responsive">
                  <table class="table table-head-custom table-vertical-center">
                     <thead>
                        <tr>
                           <th>ID</th>
                           <th>Название</th>
                           <th>Версия</th>
                           <th>Автор</th>
                           <th>Статус</th>
                           <th>Действия</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach($plugins as $plugin): ?>
                        <tr>
                           <td><?php echo htmlspecialchars($plugin['plugin_id']) ?></td>
                           <td>
                              <strong><?php echo htmlspecialchars($plugin['name']) ?></strong>
                              <?php if(!empty($plugin['description'])): ?>
                              <div class="text-muted font-size-sm"><?php echo htmlspecialchars($plugin['description']) ?></div>
                              <?php endif; ?>
                           </td>
                           <td><?php echo htmlspecialchars($plugin['version']) ?></td>
                           <td><?php echo htmlspecialchars($plugin['author']) ?></td>
                           <td>
                              <?php if(Plugin::is_active($plugin['plugin_id'])): ?>
                              <span class="badge badge-success">Активен</span>
                              <?php elseif(Plugin::is_installed($plugin['plugin_id'])): ?>
                              <span class="badge badge-warning">Установлен</span>
                              <?php else: ?>
                              <span class="badge badge-secondary">Не установлен</span>
                              <?php endif; ?>
                           </td>
                           <td>
                              <?php if(Plugin::is_uninstalled($plugin['plugin_id'])): ?>
                              <a href="/admin/plugins/index/install/<?php echo urlencode($plugin['plugin_id']) ?>" class="btn btn-sm btn-light-primary">Установить</a>
                              <?php endif; ?>
                              <?php if(Plugin::is_installed($plugin['plugin_id'])): ?>
                              <a href="/admin/plugins/index/activate/<?php echo urlencode($plugin['plugin_id']) ?>" class="btn btn-sm btn-success">Активировать</a>
                              <?php endif; ?>
                              <?php if(Plugin::is_active($plugin['plugin_id'])): ?>
                              <a href="/admin/plugins/index/disable/<?php echo urlencode($plugin['plugin_id']) ?>" class="btn btn-sm btn-warning">Отключить</a>
                              <?php if(!empty($plugin['settings_url'])): ?>
                              <a href="<?php echo htmlspecialchars($plugin['settings_url']) ?>" class="btn btn-sm btn-light">Настройки</a>
                              <?php endif; ?>
                              <?php endif; ?>
                              <?php if(!Plugin::is_uninstalled($plugin['plugin_id'])): ?>
                              <a href="/admin/plugins/index/uninstall/<?php echo urlencode($plugin['plugin_id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Удалить плагин? Данные плагина могут быть потеряны.');">Удалить</a>
                              <?php endif; ?>
                           </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($plugins)): ?>
                        <tr>
                           <td colspan="6" class="text-center">Плагины не найдены.</td>
                        </tr>
                        <?php endif; ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php echo $footer ?>

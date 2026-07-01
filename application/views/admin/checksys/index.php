<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
?>
<?php echo $admheader ?>
<?php
   $mysqlExists = false;
   if(function_exists("mysqli_connect"))
    $mysqlExists = true;
   
   $ssh2Exists = false;
   if(function_exists("ssh2_connect"))
    $ssh2Exists = true;
   
   $gdExists = false;
   if(function_exists("gd_info"))
    $gdExists = true;

   $modRewriteExists = false;
   if(in_array('mod_rewrite', apache_get_modules()))
    $modRewriteExists = true;
?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<div class="card card-custom mb-3">
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-head-custom table-vertical-center">
									<thead>
										<tr class="text-left">
											<th>Функция</th>
											<th>Статус</th>
											<th>Установка</th>
											<th>Информация</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Система</td>
											<td>HOSTINPL 5.6</td>
											<td><i class="fa fa-check-circle"></i></td>
											<td><?php echo php_uname() ?></td>
										</tr>
										<tr>
											<td>php_mysql</td>
											<td><?php if($mysqlExists): ?>Установлен<?php else: ?>Не установлен<?php endif; ?></td>
											<td><?php if($mysqlExists): ?><i class="fa fa-check-circle"></i><?php else: ?><code>apt-get install php-mysql</code><?php endif; ?></td>
											<td>Работоспособность бд для сервера.</td>
										</tr>
										<tr>
											<td>php_ssh2</td>
											<td><?php if($ssh2Exists): ?>Установлен<?php else: ?>Не установлен<?php endif; ?></td>
											<td><?php if($ssh2Exists): ?><i class="fa fa-check-circle"></i><?php else: ?><code>apt-get install libssh2-php</code><?php endif; ?></td>
											<td>Подключение по ssh.</td>
										</tr>
										<tr>
											<td>php_gd</td>
											<td><?php if($gdExists): ?>Установлен<?php else: ?>Не установлен<?php endif; ?></td>
											<td><?php if($gdExists): ?><i class="fa fa-check-circle"></i><?php else: ?><code>apt-get install php-gd</code><?php endif; ?></td>
											<td>Просмотр каптчи.</td>
										</tr>
										<tr>
											<td>mod_rewrite</td>
											<td><?php if($modRewriteExists): ?>Установлен<?php else: ?>Не установлен<?php endif; ?></td>
											<td><?php if($modRewriteExists): ?><i class="fa fa-check-circle"></i><?php else: ?><code>a2enmod rewrite</code><?php endif; ?></td>
											<td>Кэширование памяти.</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<?php if($user_access_level == 3):?>
				<div class="col-xl-12">
					<div class="card card-custom mb-3">
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-head-custom table-vertical-center">
									<thead>
										<tr>
											<th>Время</th>
											<th>Консольная команда</th>
											<th>Crontab</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>1 раз в 00:00</td>
											<td>bash -c 'php /var/www/cron.php index'</td>
											<td><code>0 0 * * * bash -c 'php /var/www/cron.php index'</code></td>
										</tr>
										<tr>
											<td>1 раз в минуту</td>
											<td>bash -c 'php /var/www/cron.php gameServers'</td>
											<td><code>*/1 * * * * bash -c 'php /var/www/cron.php gameServers'</code></td>
										</tr>
										<tr>
											<td>1 раз в минуту</td>
											<td>bash -c 'php /var/www/cron.php tasks'</td>
											<td><code>*/1 * * * * bash -c 'php /var/www/cron.php tasks'</code></td>
										</tr>
										<tr>
											<td>1 раз в 10 минут</td>
											<td>bash -c 'php /var/www/cron.php serverReloader'</td>
											<td><code>*/10 * * * * bash -c 'php /var/www/cron.php serverReloader'</code></td>
										</tr>
										<tr>
											<td>1 раз в 30 минут</td>
											<td>bash -c 'php /var/www/cron.php stopServers'</td>
											<td><code>*/30 * * * * bash -c 'php /var/www/cron.php stopServers'</code></td>
										</tr>
										<tr>
											<td>1 раз в 30 минут</td>
											<td>bash -c 'php /var/www/cron.php stopServersQuery'</td>
											<td><code>*/30 * * * * bash -c 'php /var/www/cron.php stopServersQuery'</code></td>
										</tr>
										<tr>
											<td>1 раз в 60 минут</td>
											<td>bash -c 'php /var/www/cron.php updateStats'</td>
											<td><code>*/60 * * * * bash -c 'php /var/www/cron.php updateStats'</code></td>
										</tr>
										<tr>
											<td>1 раз в 60 минут</td>
											<td>bash -c 'php /var/www/cron.php updateStatsLocations'</td>
											<td><code>*/60 * * * * bash -c 'php /var/www/cron.php updateStatsLocations'</code></td>
										</tr>
										<tr>
											<td>1 раз в 7 дней</td>
											<td>bash -c 'php /var/www/cron.php clearLogs'</td>
											<td><code>0 * */7 * * bash -c 'php /var/www/cron.php clearLogs'</code></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<?echo $footer?>
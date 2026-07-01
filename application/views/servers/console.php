<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
?>
<?php echo $header?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<?php include 'application/views/common/menuserver.php';?>
				</div>
				<div class="col-xl-3">
					<div class="card card-custom mb-3 pt-5">
						<div class="card-body">
							<div class="text-center">
								<div class="symbol symbol-60 symbol-circle symbol-xl-90">
									<div class="symbol-label">
										<i class="fa fa-terminal icon-3x text-primary"></i>
									</div>
								</div>
								<h4 class="font-weight-bolder my-2">Консоль</h4>
							</div>
						</div>
					</div>
					<div class="card card-custom mb-3">
						<div class="card-body px-1" style="padding: 0.3rem 2.25rem;">
							<div class="navi navi-hover navi-active navi-link-rounded navi-bold navi-icon-center navi-light-icon">
								<div class="navi-item my-2">
									<a href="javascript:;" data-toggle="modal" data-target="#colorCMD" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-clone"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Сменить дизайн</span>
									</a>
								</div>
								<?if($server['game_query'] == 'samp'):?>
								<div class="navi-item my-2">
									<a href="javascript:;" data-toggle="modal" data-target="#infoCMD" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="fa fa-info-circle"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Доступные команды</span>
									</a>
								</div>
								<?endif;?>
								<div class="navi-item my-2">
									<a href="javascript:;" onclick="clearConsole()" class="navi-link">
									<span class="navi-icon mr-3">
									<span class="svg-icon svg-icon-lg">
									<i class="flaticon2-rubbish-bin"></i>
									</span>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Очистить консоль</span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-9">
					<div class="card card-custom gutter-b">
						<div class="card-body">
							<div onScroll="checkScrollContainer('console_data')" style="overflow-y:scroll;overflow-x:scroll;overflow-y:scroll;white-space:pre; <?php echo $theme ?>" disabled="" class="form-control console" id="console_data">Получение данных...</div>
							<form class="form-inline" role="form" method='POST' id="console_form" action="" >
								<div class="input-group" style="width: 100%;">
									<input type="text" class="form-control" id="cmd" name="cmd" placeholder="Введите команду...">
									<div class="input-group-append">
										<button type="submit" class="btn btn-primary btn-icon" data-toggle="tooltip" title="" data-placement="right" data-original-title="Отправить"><i class="fa fa-terminal"></i></button>
									</div>
								</div>
							</form>
							<div class="subheader__wrapper">
								<?php if($server['game_query'] == "samp"): ?>
								<a href="/servers/console/index/<?php echo $server['server_id'] ?>" class="btn btn-default font-weight-bold px-1 py-1 mr-2">
								./server_log.txt
								</a>
								<a href="/servers/console/index/<?php echo $server['server_id'] ?>?open=screenlog" class="btn btn-default font-weight-bold px-1 py-1 mr-2">
								./screenlog.0
								</a>
								<?php elseif($server['game_query'] == "mtasa"): ?>
								<a href="/servers/console/index/<?php echo $server['server_id'] ?>" class="btn btn-default font-weight-bold px-1 py-1 mr-2">
								./mods/deathmatch/logs/server.log
								</a>
								<?php elseif($server['game_code'] == "mcpe"): ?>
								<a href="/servers/console/index/<?php echo $server['server_id'] ?>" class="btn btn-default font-weight-bold px-1 py-1 mr-2">
								./server.log
								</a>
								<?php elseif($server['game_code'] == "cs"): ?>
								<a href="/servers/console/index/<?php echo $server['server_id'] ?>" class="btn btn-default font-weight-bold px-1 py-1 mr-2">
								./cstrike/qconsole.log
								</a>
								<?php elseif($server['game_code'] == "css"): ?>
								<a href="/servers/console/index/<?php echo $server['server_id'] ?>" class="btn btn-default font-weight-bold px-1 py-1 mr-2">
								./cstrike/console.log
								</a>
								<?php elseif($server['game_code'] == "csgo"): ?>
								<a href="/servers/console/index/<?php echo $server['server_id'] ?>" class="btn btn-default font-weight-bold px-1 py-1 mr-2">
								./csgo/console.log
								</a>
								<?php else: ?>
								<a href="/servers/console/index/<?php echo $server['server_id'] ?>" class="btn btn-default font-weight-bold px-1 py-1 mr-2">
								./screenlog.0
								</a>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php if($server['game_query'] == "samp"): ?>
<!--begin::Modal-->
<div class="modal fade" id="infoCMD" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Доступные команды</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="modal-body">
				<code>cmdlist</code> - Показать список RCON команд<br>
				<code>varlist</code> - Посмотреть список текущих настроек<br>
				<code>kick [ID]</code> - Кикнуть пользователя по его ID (Пример: kick 3)<br>
				<code>ban [ID]</code> - Забанить пользователя по его ID (Пример: ban 3)<br>
				<code>banip [IP]</code> - Забанить IP (Пример: banip 127.0.0.1)<br>
				<code>unbanip [IP]</code> - Разбанить IP (Пример: unbanip 127.0.0.1)<br>
				<code>reloadbans</code> - Перезагрузить samp.ban<br>
				<code>reloadlog</code> - Очистить лог сервера<br>
				<code>exec [имя файла]</code> - Открыть файл .cfg (Пример: exec blah.cfg)<br>
				<code>say [текст]</code> - Сказать в общий чат от лица админа (Пример: say Hello)<br>
				<code>players</code> - Показать всех игроков на сервере с их именами, ip и пингом<br>
				<code>gravity</code> - Изменить гравитацию на сервере - (Пример: gravity 0.008)<br>
				<code>weather [ID]</code> - Изменить погоду на сервере (Пример: weather 2)<br>
				<code>worldtime [время]</code> - Изменить время на сервере (Пример: worldtime 2)<br>
				<code>maxplayers</code> - Изменить макс. количество мест на сервере<br>
				<code>timestamp</code> - Установить часовой пояс<br>
				<code>plugins</code> - Посмотреть список всех установленных плагинов<br>
				<code>filterscripts</code> - Посмотреть список всех установленных фильтрскриптов<br>
				<code>loadfs [название]</code> - Загрузить фильтрскрипт<br>
				<code>unloadfs [название]</code> - Выгрузить фильтрскрипт<br>
				<code>reloadfs [название]</code> - Перезагрузить фильтрскрипт<br>
				<code>password [пароль]</code> - Установить пароль на сервере<br>
				<code>changemode [название]</code> - Изменить режим игры на сервере на заданный<br>
				<code>gmx</code> - Рестарт сервера<br>
				<code>hostname [название]</code> - Изменить название сервера<br>
				<code>gamemodetext [название]</code> - Изменить название мода<br>
				<code>mapname [название]</code> - Изменить название карты<br>
				<code>gamemode [1-15]</code> - Установить порядок гэйм-модов<br>
				<code>instagib [bool]</code> - Включить функцию убийства с одной пули (1 или 0)<br>
				<code>lanmode [bool]</code> - Установить LAN (1 или 0)<br>
				<code>version</code> - Посмотреть версию сервера<br>
				<code>weburl [url]</code> - Установить url сайта на сервере<br>
			</div>
		</div>
	</div>
</div>
<!--end::Modal-->
<?php endif; ?>
<!--begin::Modal-->
<div class="modal fade" id="colorCMD" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Смена дизайна</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="modal-body">
				<div class="row row-paddingless">
					<div class="col-6">
						<a href="javascript:;" onclick="sendAction('default')" class="d-block py-10 px-5 text-center bg-hover-light border-right border-bottom">
						<i class="flaticon2-drop text-primary icon-3x"></i>
						<span class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">Тема Default</span>
						<span class="d-block text-dark-50 font-size-lg">Применить</span>
						</a>
					</div>
					<div class="col-6">
						<a href="javascript:;" onclick="sendAction('Amethyst')" class="d-block py-10 px-5 text-center bg-hover-light border-bottom">
						<i class="flaticon2-drop text-primary icon-3x"></i>
						<span class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">Тема Amethyst</span>
						<span class="d-block text-dark-50 font-size-lg">Применить</span>
						</a>
					</div>
					<div class="col-6">
						<a href="javascript:;" onclick="sendAction('City')"  class="d-block py-10 px-5 text-center bg-hover-light border-right">
						<i class="flaticon2-drop text-primary icon-3x"></i>
						<span class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">Тема City</span>
						<span class="d-block text-dark-50 font-size-lg">Применить</span>
						</a>
					</div>
					<div class="col-6">
						<a href="javascript:;" onclick="sendAction('Flat')" class="d-block py-10 px-5 text-center bg-hover-light">
						<i class="flaticon2-drop text-primary icon-3x"></i>
						<span class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">Тема Flat</span>
						<span class="d-block text-dark-50 font-size-lg">Применить</span>
						</a>
					</div>
					<div class="col-6">
						<a href="javascript:;" onclick="sendAction('Modern')" class="d-block py-10 px-5 text-center bg-hover-light">
						<i class="flaticon2-drop text-primary icon-3x"></i>
						<span class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">Тема Modern</span>
						<span class="d-block text-dark-50 font-size-lg">Применить</span>
						</a>
					</div>
					<div class="col-6">
						<a href="javascript:;" onclick="sendAction('Smooth')" class="d-block py-10 px-5 text-center bg-hover-light">
						<i class="flaticon2-drop text-primary icon-3x"></i>
						<span class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">Тема Smooth</span>
						<span class="d-block text-dark-50 font-size-lg">Применить</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--end::Modal-->
<script src="/assets/js/ansi_up.js"></script>
<script>
	var ansi_up = new AnsiUp;
	$('#console_form').ajaxForm({ 
		url: '/servers/console/sendconsole/<?php echo $server['server_id'] ?>',
		dataType: 'text',
		success: function(data) {
			data = $.parseJSON(data);
			switch(data.status) {
				case 'error':
					toastr.error(data.error);
					$('button[type=submit]').prop('disabled', false);
					break;
				case 'success':
					toastr.success(data.success);
					document.getElementById("cmd").value = "";
					break;
			}
		}
	});
   
    function clearConsole() {
		$.ajax({ 
			url: '/servers/console/clearcon/<?php echo $server['server_id'] ?>',
			type: 'POST',
			dataType: 'text',
			success: function(data) {
				data = $.parseJSON(data);
				switch(data.status) {
					case 'error':
						toastr.error(data.error);
						break;
					case 'success':
						toastr.success(data.success);
						break;
				}
			}
		});
	}
	
    function getConsole() {
		$.get("/servers/console/getconsole/<?php echo $server['server_id'] ?>/<?php echo $fileid ?>")
		.done(function (data) {
			var console = document.getElementById("console_data");
			console.innerHTML = ansi_up.ansi_to_html(data);
			if(containerScrollDown) console.scrollTop = console.scrollHeight;
		});
	}

	setTimeout(function() {
		getConsole();
		setInterval(function() {
			getConsole()
		}, 2000);
	}, 1500);
	
	function sendAction(action) {
		$.ajax({ 
			url: '/servers/console/action_theme_console/'+action,
			dataType: 'text',
			success: function(data) {
				data = $.parseJSON(data);
				switch(data.status) {
					case 'error':
						toastr.error(data.error);
						break;
					case 'success':
						toastr.success(data.success);
						setTimeout("reload()", 1500);
						break;
				}
			}
		});
	}
</script>
<?php echo $footer ?>
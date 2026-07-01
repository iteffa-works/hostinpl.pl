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
            <div class="col-lg-3">
               <div class="d-flex align-items-center mb-3 bg-light-primary rounded p-5">
                  <div class="d-flex flex-column flex-grow-1 mr-2">
                     <a href="javascript:;" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">CPU</a>
                  </div>
                  <span class="font-weight-bolder text-primary py-1 font-size-lg" id="cpu">
                     <div class="spinner spinner-primary mr-5"></div>
                  </span>
               </div>
            </div>
            <div class="col-lg-3">
               <div class="d-flex align-items-center mb-3 bg-light-primary rounded p-5">
                  <div class="d-flex flex-column flex-grow-1 mr-2">
                     <a href="javascript:;" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">RAM</a>
                  </div>
                  <span class="font-weight-bolder text-primary py-1 font-size-lg" id="ram">
                     <div class="spinner spinner-primary mr-5"></div>
                  </span>
               </div>
            </div>
            <div class="col-lg-3">
               <div class="d-flex align-items-center mb-3 bg-light-primary rounded p-5">
                  <div class="d-flex flex-column flex-grow-1 mr-2">
                     <a href="javascript:;" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">SSD</a>
                  </div>
                  <span class="font-weight-bolder text-primary py-1 font-size-lg" id="ssd">
                     <div class="spinner spinner-primary mr-5"></div>
                  </span>
               </div>
            </div>
            <div class="col-lg-3">
               <div class="d-flex align-items-center mb-3 bg-light-primary rounded p-5">
                  <div class="d-flex flex-column flex-grow-1 mr-2">
                     <a href="javascript:;" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">Players</a>
                  </div>
                  <span class="font-weight-bolder text-primary py-1 font-size-lg" id="players">
                     <div class="spinner spinner-primary mr-5"></div>
                  </span>
               </div>
            </div>
            <div class="col-lg-6">
               <div class="card card-custom mb-3">
                  <div class="card-header">
                     <div class="card-title">
                        <h3 class="card-label">История количества игроков</h3>
                     </div>
                  </div>
                  <div class="card-body" style="padding: 0.5rem 0.5rem;">
                     <div id="statsGraph"></div>
                  </div>
               </div>
            </div>
            <div class="col-lg-6">
               <div class="card card-custom mb-3">
                  <div class="card-header">
                     <div class="card-title">
                        <h3 class="card-label">История нагрузки сервера</h3>
                     </div>
                  </div>
                  <div class="card-body" style="padding: 0.5rem 0.5rem;">
                     <div id="loads"></div>
                  </div>
               </div>
            </div>
            <div class="col-lg-12">
               <div class="card card-custom mb-3">
                  <div class="card-header">
                     <div class="card-title">
                        <h3 class="card-label">История операций с сервером</h3>
                     </div>
                  </div>
                  <div class="card-body">
                     <div class="scroll scroll-pull"  data-scroll="true" style="height: 200px; overflow: auto;" data-mobile-height="200">
                        <div class="timeline timeline-2">
                           <div class="timeline-bar"></div>
                           <?$nofire = false;?>      
                           <?php foreach($logs as $item):  ?>
                           <? if($server['server_id'] == $item['server_id']): ?>
                           <?$nofire = true;?>
                           <div class="timeline-item">
                              <div class="timeline-badge <?if($item['status'] == 1):?>bg-success<?elseif($item['status'] == 2):?>bg-warning<?elseif($item['status'] == 3):?>bg-danger<?endif;?>"></div>
                              <div class="timeline-content d-flex align-items-center justify-content-between">
                                 <span class="mr-3">
                                 <?=$item['reason']?>
                                 </span>
                                 <span class="text-muted text-right"><?=$item['date']?></span>
                              </div>
                           </div>
                           <?endif;?>
                           <?php endforeach; ?>
                        </div>
                        <?php if(empty($logs) || $nofire == false): ?>
                        <div class="alert alert-custom alert-light-primary fade show mb-4" role="alert">
                           <div class="alert-icon">
                              <i class="flaticon-exclamation"></i>
                           </div>
                           <div class="alert-text">На данный момент история чиста.</div>
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
      </div>
   </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
	function updateData() {
		$.ajax({
			url: '/servers/statistics/getData/<?=$server['server_id']?>',
			dataType: 'text',
			success: function (data) {
				data = $.parseJSON(data);
				$("#cpu").text(data.cpu+"%");
				$("#ram").text(data.ram+"%");
				$("#ssd").text(data.ssd+"%");
				$("#players").text(data.players);
			}
		})
	}

	$(document).ready(function() {
		updateData();
	});
	
    var online = [
        <?php foreach($stats as $item): ?> 
        ["<?php echo date('Y-m-d\TH:i:s.000\Z', strtotime($item['server_stats_date'])) ?>", <?php echo $item['server_stats_players'] ?>],
        <?php endforeach; ?> 
    ];  

    var loads1 = [
        <?php foreach($stats as $item): ?> 
        ["<?php echo date('Y-m-d\TH:i:s.000\Z', strtotime($item['server_stats_date'])) ?>", <?php echo $item['server_stats_cpu'] ?>],
        <?php endforeach; ?> 
    ]; 

    var loads2 = [
        <?php foreach($stats as $item): ?> 
        ["<?php echo date('Y-m-d\TH:i:s.000\Z', strtotime($item['server_stats_date'])) ?>", <?php echo $item['server_stats_hdd'] ?>],
        <?php endforeach; ?> 
    ]; 

    var loads3 = [
        <?php foreach($stats as $item): ?> 
        ["<?php echo date('Y-m-d\TH:i:s.000\Z', strtotime($item['server_stats_date'])) ?>", <?php echo $item['server_stats_ram'] ?>],
        <?php endforeach; ?> 
    ]; 
	
	const primary = '#6993FF';
	const success = '#1BC5BD';
	const info = '#8950FC';
	const warning = '#FFA800';
	const danger = '#F64E60';

	var ApexChartsStats = function () {
		var players_chart = function () {
			const apexChart = "#statsGraph";
			var options = {
				chart: {
					height: 350,
					type: 'area',
					defaultLocale: 'en',
					locales: [{
						name: 'en',
						options: {
							months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',  'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
							shortMonths: ['Янв.', 'Фев.', 'Март.', 'Апр.', 'Май.', 'Июнь.', 'Июль.', 'Авг.', 'Сент.', 'Окт.', 'Ноя.', 'Дек.'],
							days: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
							shortDays: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
							toolbar: {
								exportToPNG: 'Скачать PNG',
								exportToCSV: 'Скачать CSV',
								exportToSVG: 'Скачать SVG',
								selection: 'Выбор',
								selectionZoom: 'Выбор Zoom',
								zoomIn: 'Увеличить',
								zoomOut: 'Уменьшить',
								pan: 'Панорамирование',
								reset: 'Сброс Масштаба',
							}
						}
					}]
				},
				dataLabels: {
					enabled: false
				},
				series: [{
					name: 'Количевство игроков',
					data: online
				}],
				xaxis: {
					type: 'datetime'
				},
				tooltip: {
					x: {
						format: 'dd.MM.yy в HH:mm'
					},
				},
				colors: [primary]
			};

			var chart = new ApexCharts(document.querySelector(apexChart), options);
			chart.render();
		}

		var loads_chart = function () {
			const apexChart = "#loads";
			var options = {
				series: [{
					name: 'CPU (%)',
					data: loads1
				}, {
					name: 'RAM (%)',
					data: loads3
				}, {
					name: 'SSD (%)',
					data: loads2
				}],
				chart: {
					height: 350,
					type: 'area',
					defaultLocale: 'en',
					locales: [{
						name: 'en',
						options: {
							months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',  'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
							shortMonths: ['Янв.', 'Фев.', 'Март.', 'Апр.', 'Май.', 'Июнь.', 'Июль.', 'Авг.', 'Сент.', 'Окт.', 'Ноя.', 'Дек.'],
							days: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
							shortDays: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
							toolbar: {
								exportToPNG: 'Скачать PNG',
								exportToCSV: 'Скачать CSV',
								exportToSVG: 'Скачать SVG',
								selection: 'Выбор',
								selectionZoom: 'Выбор Zoom',
								zoomIn: 'Увеличить',
								zoomOut: 'Уменьшить',
								pan: 'Панорамирование',
								reset: 'Сброс Масштаба',
							}
						}
					}]
				},
				dataLabels: {
					enabled: false
				},
				stroke: {
					curve: 'smooth'
				},
				xaxis: {
					type: 'datetime'
				},
				tooltip: {
					x: {
						format: 'dd.MM.yy в HH:mm'
					},
				},
				colors: [primary, success, danger]
			};

			var chart = new ApexCharts(document.querySelector(apexChart), options);
			chart.render();
		}	
		

		return {
			init: function () {
				players_chart();
				loads_chart();
			}
		};
	}();

	jQuery(document).ready(function () {
		ApexChartsStats.init();
	});
</script>
<?php echo $footer ?>
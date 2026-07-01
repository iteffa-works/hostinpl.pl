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
						<h3 class="card-label">История нагрузки локации <?php echo $location['location_name'] ?> (ID-<?php echo $location['location_id'] ?>)
						</h3>
					</div>
				</div>
				<div class="card-body">
					<div id="loads"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    var loads1 = [
        <?php foreach($loc_stats as $item): ?> 
        ["<?php echo date('Y-m-d\TH:i:s.000\Z', strtotime($item['location_stats_date'])) ?>", <?php echo $item['location_stats_cpu'] ?>],
        <?php endforeach; ?> 
    ]; 

    var loads2 = [
        <?php foreach($loc_stats as $item): ?> 
        ["<?php echo date('Y-m-d\TH:i:s.000\Z', strtotime($item['location_stats_date'])) ?>", <?php echo $item['location_stats_hdd'] ?>],
        <?php endforeach; ?> 
    ]; 

    var loads3 = [
        <?php foreach($loc_stats as $item): ?> 
        ["<?php echo date('Y-m-d\TH:i:s.000\Z', strtotime($item['location_stats_date'])) ?>", <?php echo $item['location_stats_ram'] ?>],
        <?php endforeach; ?> 
    ]; 
	
	const primary = '#6993FF';
	const success = '#1BC5BD';
	const info = '#8950FC';
	const warning = '#FFA800';
	const danger = '#F64E60';

	var ApexChartsStats = function () {
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
				loads_chart();
			}
		};
	}();

	jQuery(document).ready(function () {
		ApexChartsStats.init();
	});
</script>
<?php echo $footer ?>

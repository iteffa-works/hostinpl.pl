<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko (https://vk.com/id00v)
*/
?>
<?php echo $admheader ?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="card card-custom gutter-b">
						<div class="card-header">
							<div class="card-title">
								<h3 class="card-label">Пополнение счета</h3>
							</div>
						</div>
						<div class="card-body">
							<div id="invoices"></div>
						</div>
					</div>
				</div>
				<div class="col-lg-12">
					<div class="card card-custom gutter-b">
						<div class="card-header">
							<div class="card-title">
								<h3 class="card-label">Регистраций</h3>
							</div>
						</div>
						<div class="card-body">
							<div id="registers"></div>
						</div>
					</div>
				</div>
				<div class="col-lg-12">
					<div class="card card-custom gutter-b">
						<div class="card-header">
							<div class="card-title">
								<h3 class="card-label">Общее количевство игроков</h3>
							</div>
						</div>
						<div class="card-body">
							<div id="players"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
<?php echo $charts; ?>

const primary = '#6993FF';
const success = '#1BC5BD';
const info = '#8950FC';
const warning = '#FFA800';
const danger = '#F64E60';

var ApexChartsStats = function () {
	var players_chart = function () {
		const apexChart = "#players";
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
				data: players
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
	
	var registers_chart = function () {
		const apexChart = "#registers";
		var options = {
			series: [{
				name: 'Количевство регистраций',
				data: registers
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
			colors: [primary]
		};

		var chart = new ApexCharts(document.querySelector(apexChart), options);
		chart.render();
	}

	var invoices_chart = function () {
		const apexChart = "#invoices";
		var options = {
			series: [{
				name: 'Прибыль (руб)',
				data: invoices
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
			registers_chart();
			invoices_chart();
		}
	};
}();

jQuery(document).ready(function () {
	ApexChartsStats.init();
});
</script>		
<?php echo $footer ?>
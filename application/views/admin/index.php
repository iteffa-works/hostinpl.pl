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
			<div class="row">
				<div class="col-xl-6">
					<div class="card card-custom mb-2">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div class="symbol symbol-45 symbol-light mr-5">
									<span class="symbol-label">
										<span class="svg-icon svg-icon-lg svg-icon-primary">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<polygon points="0 0 24 0 24 24 0 24"/>
													<path d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
													<path d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
												</g>
											</svg>
										</span>
									</span>
								</div>
								<div class="d-flex flex-column flex-grow-1">
									<a href="/admin/users" class="text-dark-75 text-hover-primary mb-1 font-size-lg font-weight-bolder">Пользователи</a>
									<div class="d-flex">
										<div class="d-flex align-items-center pr-5">
											<span class="svg-icon svg-icon-md svg-icon-primary pr-1">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<polygon points="0 0 24 0 24 24 0 24"/>
														<rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5" y="7.5" width="2" height="9" rx="1"/>
														<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
													</g>
												</svg>
											</span>
											<span class="text-muted font-weight-bold">Заблокировано: <?$userov2 = 0;
												foreach($users as $itemr): ?>
											<? if (!($itemr['user_status'] == 0)) { // пропуск нечетных чисел
												continue;
												}?>
											<? $userov2++; ?>
											<?endforeach;echo $userov2; ?></span>
										</div>
										<div class="d-flex align-items-center">
											<span class="svg-icon svg-icon-md svg-icon-primary pr-1">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<polygon points="0 0 24 0 24 24 0 24"/>
														<rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5" y="7.5" width="2" height="9" rx="1"/>
														<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
													</g>
												</svg>
											</span>
											<span class="text-muted font-weight-bold">Всего: <?$userov = 0;
												foreach($users as $itemr): ?>
											<? $userov++; ?>
											<?endforeach;echo $userov; ?></span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card card-custom mb-2">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div class="symbol symbol-45 symbol-light mr-5">
									<span class="symbol-label">
										<span class="svg-icon svg-icon-lg svg-icon-primary">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24"/>
													<path opacity="0.3" fill-rule="evenodd" clip-rule="evenodd" d="M14.4862 18L12.7975 21.0566C12.5304 21.54 11.922 21.7153 11.4386 21.4483C11.2977 21.3704 11.1777 21.2597 11.0887 21.1255L9.01653 18H5C3.34315 18 2 16.6569 2 15V6C2 4.34315 3.34315 3 5 3H19C20.6569 3 22 4.34315 22 6V15C22 16.6569 20.6569 18 19 18H14.4862Z" fill="black"/>
													<path fill-rule="evenodd" clip-rule="evenodd" d="M6 7H15C15.5523 7 16 7.44772 16 8C16 8.55228 15.5523 9 15 9H6C5.44772 9 5 8.55228 5 8C5 7.44772 5.44772 7 6 7ZM6 11H11C11.5523 11 12 11.4477 12 12C12 12.5523 11.5523 13 11 13H6C5.44772 13 5 12.5523 5 12C5 11.4477 5.44772 11 6 11Z" fill="black"/>
												</g>
											</svg>
										</span>
									</span>
								</div>
								<div class="d-flex flex-column flex-grow-1">
									<a href="/admin/tickets" class="text-dark-75 text-hover-primary mb-1 font-size-lg font-weight-bolder">Техническая поддержка</a>
									<div class="d-flex">
										<div class="d-flex align-items-center pr-5">
											<span class="svg-icon svg-icon-md svg-icon-primary pr-1">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<polygon points="0 0 24 0 24 24 0 24"/>
														<rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5" y="7.5" width="2" height="9" rx="1"/>
														<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
													</g>
												</svg>
											</span>
											<span class="text-muted font-weight-bold">Новых запросов: <?$ticketov2 = 0;
												foreach($tickets as $item): ?>
											<? if (!($item['ticket_status'] == 1)) { // пропуск нечетных чисел
												continue;
												}?>
											<? $ticketov2++; ?> <?endforeach;echo $ticketov2; ?></span>
										</div>
										<div class="d-flex align-items-center">
											<span class="svg-icon svg-icon-md svg-icon-primary pr-1">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<polygon points="0 0 24 0 24 24 0 24"/>
														<rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5" y="7.5" width="2" height="9" rx="1"/>
														<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
													</g>
												</svg>
											</span>
											<span class="text-muted font-weight-bold">Запросов: <?$ticketov = 0;
												foreach($tickets as $item): ?>
											<? $ticketov++; ?>
											<?endforeach;echo $ticketov; ?></span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card card-custom mb-2">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div class="symbol symbol-45 symbol-light mr-5">
									<span class="symbol-label">
										<span class="svg-icon svg-icon-lg svg-icon-primary">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24"/>
													<path d="M5,2 L19,2 C20.1045695,2 21,2.8954305 21,4 L21,6 C21,7.1045695 20.1045695,8 19,8 L5,8 C3.8954305,8 3,7.1045695 3,6 L3,4 C3,2.8954305 3.8954305,2 5,2 Z M11,4 C10.4477153,4 10,4.44771525 10,5 C10,5.55228475 10.4477153,6 11,6 L16,6 C16.5522847,6 17,5.55228475 17,5 C17,4.44771525 16.5522847,4 16,4 L11,4 Z M7,6 C7.55228475,6 8,5.55228475 8,5 C8,4.44771525 7.55228475,4 7,4 C6.44771525,4 6,4.44771525 6,5 C6,5.55228475 6.44771525,6 7,6 Z" fill="#000000" opacity="0.3"/>
													<path d="M5,9 L19,9 C20.1045695,9 21,9.8954305 21,11 L21,13 C21,14.1045695 20.1045695,15 19,15 L5,15 C3.8954305,15 3,14.1045695 3,13 L3,11 C3,9.8954305 3.8954305,9 5,9 Z M11,11 C10.4477153,11 10,11.4477153 10,12 C10,12.5522847 10.4477153,13 11,13 L16,13 C16.5522847,13 17,12.5522847 17,12 C17,11.4477153 16.5522847,11 16,11 L11,11 Z M7,13 C7.55228475,13 8,12.5522847 8,12 C8,11.4477153 7.55228475,11 7,11 C6.44771525,11 6,11.4477153 6,12 C6,12.5522847 6.44771525,13 7,13 Z" fill="#000000"/>
													<path d="M5,16 L19,16 C20.1045695,16 21,16.8954305 21,18 L21,20 C21,21.1045695 20.1045695,22 19,22 L5,22 C3.8954305,22 3,21.1045695 3,20 L3,18 C3,16.8954305 3.8954305,16 5,16 Z M11,18 C10.4477153,18 10,18.4477153 10,19 C10,19.5522847 10.4477153,20 11,20 L16,20 C16.5522847,20 17,19.5522847 17,19 C17,18.4477153 16.5522847,18 16,18 L11,18 Z M7,20 C7.55228475,20 8,19.5522847 8,19 C8,18.4477153 7.55228475,18 7,18 C6.44771525,18 6,18.4477153 6,19 C6,19.5522847 6.44771525,20 7,20 Z" fill="#000000"/>
												</g>
											</svg>
										</span>
									</span>
								</div>
								<div class="d-flex flex-column flex-grow-1">
									<a href="/admin/servers" class="text-dark-75 text-hover-primary mb-1 font-size-lg font-weight-bolder">Игровые сервера</a>
									<div class="d-flex">
										<div class="d-flex align-items-center pr-5">
											<span class="svg-icon svg-icon-md svg-icon-primary pr-1">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<polygon points="0 0 24 0 24 24 0 24"/>
														<rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5" y="7.5" width="2" height="9" rx="1"/>
														<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
													</g>
												</svg>
											</span>
											<span class="text-muted font-weight-bold">Онлайн: <?$tserverov = 0;
												foreach($tservers as $item): ?>
											<? if (!($item['server_status'] == 2)) { // пропуск нечетных чисел
												continue;
												}?>
											<? $tserverov++; ?>
											<?endforeach;echo $tserverov; ?></span>
										</div>
										<div class="d-flex align-items-center">
											<span class="svg-icon svg-icon-md svg-icon-primary pr-1">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<polygon points="0 0 24 0 24 24 0 24"/>
														<rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5" y="7.5" width="2" height="9" rx="1"/>
														<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
													</g>
												</svg>
											</span>
											<span class="text-muted font-weight-bold">Серверов: <?$tserverov1 = 0;
												foreach($tservers as $item): ?>
											<? $tserverov1++; ?>
											<?endforeach;echo $tserverov1; ?></span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card card-custom mb-2">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div class="symbol symbol-45 symbol-light mr-5">
									<span class="symbol-label">
										<span class="svg-icon svg-icon-lg svg-icon-primary">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24"/>
													<circle fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
													<rect fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
													<path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" fill="#000000"/>
												</g>
											</svg>
										</span>
									</span>
								</div>
								<div class="d-flex flex-column flex-grow-1">
									<a href="/admin/invoices" class="text-dark-75 text-hover-primary mb-1 font-size-lg font-weight-bolder">Платежи</a>
									<div class="d-flex">
										<div class="d-flex align-items-center pr-5">
											<span class="svg-icon svg-icon-md svg-icon-primary pr-1">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<polygon points="0 0 24 0 24 24 0 24"/>
														<rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5" y="7.5" width="2" height="9" rx="1"/>
														<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
													</g>
												</svg>
											</span>
											<span class="text-muted font-weight-bold"><? foreach($invoices as $item): ?> <?endforeach; ?>
											Последний счет: <?echo @$item['invoice_ammount'];?>руб.</span>
										</div>
										<div class="d-flex align-items-center">
											<span class="svg-icon svg-icon-md svg-icon-primary pr-1">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<polygon points="0 0 24 0 24 24 0 24"/>
														<rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5" y="7.5" width="2" height="9" rx="1"/>
														<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
													</g>
												</svg>
											</span>
											<span class="text-muted font-weight-bold">Заработано: <?$invo = 0;
												foreach($invoices as $item): ?>
											<? if (!($item['invoice_status'] == 1)) { // пропуск нечетных чисел
												continue;
												}?>
											<? $invo=$invo+$item['invoice_ammount']; ?>
											<?endforeach; echo $invo; ?> RUB.</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-6">
					<div class="card card-custom mb-2">
						<div class="card-body">
							<div class="scroll scroll-pull" data-scroll="true" style="height: 360px; overflow: auto;" data-mobile-height="250">
								<div class="timeline timeline-5">
									<?php foreach($waste as $item): ?>  
									<div class="timeline-items">
										<div class="timeline-item">
											<div class="timeline-media bg-light-primary">
												<?php if($item['waste_status'] == 1): ?>
												<i class="fa fa-user-minus text-danger icon-md"></i>
												<?php elseif($item['waste_status'] == 0): ?>
												<i class="fa fa-user-check text-success icon-md"></i>
												<?php endif; ?>
											</div>
											<div class="timeline-desc timeline-desc-light-primary">
												<a href="/admin/users/edit/index/<?php echo $item['user_id'] ?>" class="font-weight-bolder text-primary"><?php echo $item['user_firstname'] ?> <?php echo $item['user_lastname'] ?> <?php if($item['waste_status'] == 1): ?>
												<small class="text-danger">( - <?php echo $item['waste_ammount'] ?> RUB. )</small>
												<?php elseif($item['waste_status'] == 0): ?>
												<small class="text-success">( + <?php echo $item['waste_ammount'] ?> RUB. ) </small>
												<?php endif; ?></small></a>
												<a href="/admin/waste/index?userid=<?php echo $item['user_id'] ?>">
													<p class="font-weight-normal text-dark-50 pt-1 pb-2">
														<?php echo $item['waste_usluga'] ?>
													</p>
												</a>
											</div>
										</div>
									</div>
									<?php endforeach; ?>
									<?php if(empty($waste)): ?>
									<div class="alert alert-custom alert-light-primary fade show mb-4" role="alert">
										<div class="alert-icon">
											<i class="flaticon-exclamation"></i>
										</div>
										<div class="alert-text">На данный момент нет операций.</div>
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
				<?php foreach($users as $item): ?>
				<?if($item['user_access_level'] == 1)
					continue;?>
				<div class="col-xl-6">
					<div class="card card-custom mb-2">
						<div class="card-body">
							<div class="d-flex align-items-center mb-0">
								<div class="symbol symbol-45 symbol-light mr-5">
									<span class="symbol-label">
									<img src="/<?if($item['user_img']) {echo $item['user_img'];}else{ echo 'application/public/img/user.png';}?>" class="h-75" alt="">
									</span>
								</div>
								<div class="d-flex flex-column flex-grow-1 font-weight-bold">
									<a href="/admin/users/edit/index/<?php echo $item['user_id'] ?>" class="text-dark text-hover-primary mb-0 font-size-lg"><?php echo $item['user_firstname'] ?> <?php echo $item['user_lastname'] ?></a>
									<span class="text-muted"><?php if($item['user_access_level'] == 2): ?>
									Тех. поддержка
									<?php elseif($item['user_access_level'] == 3): ?>
									Администратор
									<?php endif; ?></span>
								</div>
								<div class="dropdown dropdown-inline ml-2">
									<a href="https://vk.com/id<?php echo $item['user_vk_id'] ?>" target="blank" class="btn btn-hover-light-primary btn-sm btn-icon">
									<i class="fab fa-vk"></i>
									</a>
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
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
				<div class="col-xl-4 col-xxl-4 mb-4">
					<div class="card card-custom card-sticky">
						<div class="card-body px-5">
							<div class="px-4 mt-4 mb-10">
								<a href="/admin/tickets/create" class="btn btn-block btn-primary font-weight-bold text-uppercase py-4 px-6 text-center">Написать клиенту</a>
							</div>
							<div class="navi navi-hover navi-active navi-link-rounded navi-bold navi-icon-center navi-light-icon">
								<div class="navi-item my-2">
									<a href="/admin/tickets/index?status=1" class="navi-link">
									<span class="navi-icon mr-4">
									<i class="fa fa-user-clock icon-lg"></i>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Новые запросы</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="/admin/tickets" class="navi-link">
									<span class="navi-icon mr-4">
									<i class="fa fa-headset icon-lg"></i>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Все запросы</span>
									</a>
								</div>
								<hr>
								<div class="navi-item my-2">
									<a href="/admin/ticketcategory" class="navi-link">
									<span class="navi-icon mr-4">
									<i class="fa fa-list-alt icon-lg"></i>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Все категории</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="/admin/ticketcategory/create" class="navi-link">
									<span class="navi-icon mr-4">
									<i class="fa fa-plus icon-lg"></i>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Создать категорию</span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-8 col-xxl-8">
					<?php foreach($tickets as $item): ?>
					<div class="card card-custom mb-4">
						<div class="card-header border-0" style="min-height: 0;padding-top: 0.5rem;padding-bottom: 0.5rem;">
							<h3 class="card-title"><span class="card-icon">
								<?php if($item['ticket_status'] == 0): ?>
								<i class="fa fa-user-lock text-primary" data-toggle="tooltip" data-placement="right" title="" data-original-title="Вопрос закрыт"></i>
								<?php elseif($item['ticket_status'] == 1): ?>
								<i class="fa fa-user-clock text-primary" data-toggle="tooltip" data-placement="right" title="" data-original-title="Ожидает ответа"></i>
								<?php elseif($item['ticket_status'] == 2): ?>
								<i class="fa fa-user text-primary" data-toggle="tooltip" data-placement="right" title="" data-original-title="Ответ от администрации"></i>
								<?php endif; ?>   
								</span><?php echo $item['ticket_name'] ?><small class="text-muted font-size-sm ml-2"><?php echo date("d.m.Y в H:i", strtotime($item['ticket_date_add'])) ?></small>
							</h3>
							<div class="card-toolbar">
								<a href="/admin/tickets/view/index/<?php echo $item['ticket_id'] ?>" class="btn btn-icon btn-primary mr-2" style="height: 24px;" data-toggle="tooltip" data-placement="right" title="" data-original-title="Перейти" >
								<i class="fa fa-sign-in-alt"></i>
								</a>
							</div>
						</div>
					</div>
					<?php endforeach; ?>
					<?php if(empty($tickets)): ?>
					<div class="alert alert-custom alert-light-primary fade show mb-4" role="alert">
						<div class="alert-icon">
							<i class="flaticon-exclamation"></i>
						</div>
						<div class="alert-text">На данный момент в тех.поддержку нет запросов.</div>
						<div class="alert-close">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">
							<i class="ki ki-close"></i>
							</span>
							</button>
						</div>
					</div>
					<?php endif; ?>
					<?php echo $pagination ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $footer ?>
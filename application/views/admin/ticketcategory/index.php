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
									<a href="/admin/ticketcategory" class="navi-link active">
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
					<div class="card card-custom">
						<div class="card-body" style="padding: 0rem 1rem;">
							<div class="table-responsive">
								<table class="table table-head-custom table-vertical-center">
									<thead>
										<tr>
											<th><i class="fa fa-list-ol"></i></th>
											<th>Название</th>
											<th>Статус</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($category as $item): ?>
										<tr onClick="redirect('/admin/ticketcategory/edit/index/<?php echo $item['category_id'] ?>')">
											<th><?php echo $item['category_id'] ?></th>
											<td><?php echo $item['category_name'] ?></td>
											<td>
												<?php if($item['category_status'] == 0): ?> 
												<span class="badge badge-danger">Выключена</span>
												<?php elseif($item['category_status'] == 1): ?> 
												<span class="badge badge-success">Включена</span>
												<?php endif; ?>
											</td>
										</tr>
										<?php endforeach; ?>
										<?php if(empty($category)): ?> 
										<tr>
											<td colspan="4" class="text-center">На данный момент нет категорий.</td>
										</tr>
										<?php endif; ?> 
									</tbody>
								</table>
								<?php echo $pagination ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $footer ?>
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
						<h3 class="card-label">Список web-локаций
						</h3>
					</div>
					<div class="card-toolbar">
						<a href="/admin/weblocations/create" class="btn btn-sm btn-icon btn-light-primary" data-toggle="tooltip" data-placement="right" title="" data-original-title="Добавить локацию">
						<i class="flaticon2-add-square"></i>
						</a>
					</div>
				</div>
				<div class="card-body" style="padding: 0rem 1rem;">
					<div class="table-responsive">
						<table class="table table-head-custom table-vertical-center">
							<thead>
								<tr>
									<th><i class="fa fa-list-ol"></i></th>
									<th>Статус</th>
									<th>Название</th>
									<th>Пользователь</th>
									<th>IP</th>
									<th>URL</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($locations as $item): ?> 
								<tr onClick="redirect('/admin/weblocations/edit/index/<?php echo $item['location_id'] ?>')">
									<th scope="row"><?php echo $item['location_id'] ?></th>
									<td>
										<?php if($item['location_status'] == 0): ?> 
										<span class="badge badge-danger">Выключена</span>
										<?php elseif($item['location_status'] == 1): ?> 
										<span class="badge badge-success">Включена</span>
										<?php endif; ?>
									</td>
									<td><?php echo $item['location_name'] ?></td>
									<td><?php echo $item['location_user'] ?></td>
									<td><?php echo $item['location_ip'] ?></td>
									<td><?php echo $item['location_url'] ?></td>
								</tr>
								<?php endforeach; ?>
								<?php if(empty($locations)): ?> 
								<tr>
									<td colspan="6" class="text-center">На данный момент нет локаций.</td>
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
<?php echo $footer ?>
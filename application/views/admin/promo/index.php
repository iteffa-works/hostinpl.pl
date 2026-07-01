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
						<h3 class="card-label">Список промо кодов
						</h3>
					</div>
					<div class="card-toolbar">
						<a href="/admin/promo/create" class="btn btn-sm btn-icon btn-light-primary" data-toggle="tooltip" data-placement="right" title="" data-original-title="Добавить промо код">
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
									<td>Код</td>
									<td>Скидка</td>
									<td>Использовано</td>
								</tr>
							</thead>
							<tbody>
								<?php foreach($promos as $item): ?>
								<tr onClick="redirect('/admin/promo/edit/index/<?php echo $item['id'] ?>')">
									<td><?php echo $item['id'] ?></td>
									<td><?echo $item['cod']?></td>
									<td><?php echo $item['skidka'] ?></td>
									<td><?php echo $item['used'] ?>/<?php echo $item['uses'] ?></td>
								</tr>
								<?php endforeach; ?>
								<?php if(empty($promos)): ?>
								<tr>
									<td colspan="4" class="text-center">На данный момент нет промо кодов.</td>
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
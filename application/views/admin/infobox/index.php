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
						<h3 class="card-label">Обратная связь
						</h3>
					</div>
				</div>
				<div class="card-body" style="padding: 0rem 1rem;">
					<div class="table-responsive">
						<table class="table table-head-custom table-vertical-center">
							<thead>
								<tr>
									<th>Отправитель</th>
									<th>Тема</th>
									<th>E-mail</th>
									<th>Дата обращения</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($mail as $item): ?>
								<tr onClick="redirect('/admin/infobox/send/index/<?php echo $item['id']?>')">
									<th scope="row"><?php echo $item['user_lastname'] ?> <?php echo $item['user_firstname'] ?></th>
									<td><?php echo $item['category']?></td>
									<td><?php echo $item['user_email'] ?></td>
									<td><?php echo $item['inbox_date_add']?></td>
								</tr>
								<?php endforeach; ?>
								<?php if(empty($mail)): ?> 
								<tr>
									<td colspan="4" style="text-align: center;">На данный момент нет запросов.</td>
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
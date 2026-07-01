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
				<?php foreach($invoices as $item): ?>
				<div class="col-xl-6">
					<div class="card card-custom mb-3">
						<div class="card-header border-0">
							<h3 class="card-title"> <span class="card-icon">
								<a href="javascript:;" class="badge badge-secondary" data-toggle="tooltip" data-placement="right" title="" data-original-title="ID"><?php echo $item['invoice_id'] ?>
								</a>
								</span><a href="/admin/users/edit/index/<?php echo $item['user_id'] ?>" class="text-dark"><?php echo $item['user_firstname'] ?> <?php echo $item['user_lastname'] ?></a><small class="text-muted font-size-sm ml-2"><?php echo $item['invoice_ammount'] ?> RUB.</small>
							</h3>
							<div class="card-toolbar">
								<span class="badge badge-primary mr-2" data-toggle="tooltip" data-placement="right" title="" data-original-title="Платежная система"><?php echo $item['system'] ?></span>
								<?php if($item['invoice_status'] == 0): ?> 
								<span class="badge badge-danger" data-toggle="tooltip" data-placement="right" title="" data-original-title="Дата операции: <?php echo date("d.m.Y в H:i", strtotime($item['invoice_date_add'])) ?>">Не оплачен</span>
								<?php elseif($item['invoice_status'] == 1): ?>
								<span class="badge badge-success" data-toggle="tooltip" data-placement="right" title="" data-original-title="Дата операции: <?php echo date("d.m.Y в H:i", strtotime($item['invoice_date_add'])) ?>">Оплачен</span>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
				<div class="col-xl-12">
					<?php echo $pagination ?>
					<?php if(empty($invoices)): ?> 
					<div class="alert alert-custom alert-light-primary fade show mb-4" role="alert">
						<div class="alert-icon">
							<i class="flaticon-exclamation"></i>
						</div>
						<div class="alert-text">На данный момент нет платежей.</div>
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
<?php echo $footer ?>
<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
?>
<?php echo $header ?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="row">
				<?php foreach($webhosts as $item): ?>
				<div class="col-lg-6 mb-10">
					<div class="card card-custom wave mb-2 bg-light-<?php if($item['web_status'] == 0): ?>warning
						<?php elseif($item['web_status'] == 1): ?>success
						<?php endif; ?>">
						<div class="card-body">
							<div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
								<div class="d-flex flex-column mr-5">
									<a href="/webhost/control/index/<?php echo $item['web_id'] ?>" class="h4 text-dark text-hover-primary mb-5"><?php echo $item['location_ip'] ?><small class="text-muted font-size-sm ml-2">ID <?php echo $item['web_id'] ?></small></a>
									<p class="text-dark-50"><?php echo $item['tarif_name'] ?></p>
								</div>
								<div class="ml-6 ml-lg-0 ml-xxl-6 flex-shrink-0">
									<a href="/webhost/control/index/<?php echo $item['web_id'] ?>" class="btn btn-icon btn-primary" data-toggle="tooltip" title="Перейти">
									<i class="fa fa-sign-in-alt"></i>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
				<?php if(empty($webhosts)): ?>
				<div class="col-xl-12 col-xxl-12">
					<div class="alert alert-custom alert-light-primary fade show mb-4" role="alert">
						<div class="alert-icon">
							<i class="flaticon-exclamation"></i>
						</div>
						<div class="alert-text">На данный момент у вас нет веб-хостингов.</div>
						<div class="alert-close">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">
							<i class="ki ki-close"></i>
							</span>
							</button>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
			<?php echo $pagination ?>
		</div>
	</div>
</div>
<?php echo $footer ?>
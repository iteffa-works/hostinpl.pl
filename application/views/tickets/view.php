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
				<div class="col-xl-4 col-xxl-4 mb-4 ">
					<div class="card card-custom card-sticky">
						<div class="card-body px-5">
							<div class="px-4 mt-4 mb-10">
								<a href="/tickets/create" class="btn btn-block btn-primary font-weight-bold text-uppercase py-4 px-6 text-center">Написать</a>
							</div>
							<div class="navi navi-hover navi-active navi-link-rounded navi-bold navi-icon-center navi-light-icon">
								<div class="navi-item my-2">
									<a href="/tickets" class="navi-link active">
									<span class="navi-icon mr-4">
									<i class="flaticon-computer icon-lg"></i>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">Мои запросы</span>
									</a>
								</div>
								<div class="navi-item my-2">
									<a href="/tickets/faq" class="navi-link">
									<span class="navi-icon mr-4">
									<i class="flaticon-book icon-lg"></i>
									</span>
									<span class="navi-text font-weight-bolder font-size-lg">База знаний</span>
									</a>
								</div>
								<hr>
								<div class="navi-item my-2">
									<a href="javascript:;" class="navi-link" data-toggle="tooltip" data-placement="right" title="" data-original-title="Тема запроса">
									<span class="navi-icon mr-4">
									<i class="flaticon-multimedia-2 icon-lg"></i>
									</span>
									<span class="navi-text"><?php echo $ticket['ticket_name'] ?></span>
									</a>
									<a href="javascript:;" class="navi-link" data-toggle="tooltip" data-placement="right" title="" data-original-title="Дата запроса">
									<span class="navi-icon mr-4">
									<i class="flaticon-calendar icon-lg"></i>
									</span>
									<span class="navi-text"><?php echo date("d.m.Y в H:i", strtotime($ticket['ticket_date_add'])) ?></span>
									</a>
									<a href="javascript:;" class="navi-link" data-toggle="tooltip" data-placement="right" title="" data-original-title="Статус запроса">
									<span class="navi-icon mr-4">
									<i class="flaticon-exclamation icon-lg"></i>
									</span>
									<span class="navi-text"><?php if($ticket['ticket_status'] == 0): ?> 
									Запрос закрыт
									<?php elseif($ticket['ticket_status'] == 1): ?> 
									Запрос открыт
									<?php elseif($ticket['ticket_status'] == 2): ?> 
									Получен ответ
									<?php endif; ?></span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-8 col-xxl-8">
					<form id="sendForm" action="" method="POST">
						<div class="card card-custom card-sticky">
							<div class="card-header align-items-center px-4 py-3">
								<div class="text-left flex-grow-1">
									<div class="dropdown dropdown-inline">
										<button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="tooltip" data-placement="right" title="" data-original-title="Запрос №<?php echo $ticket['ticket_id'] ?>"><i class="fas fa-info-circle"></i>
										</button>
									</div>
								</div>
								<div class="text-center flex-grow-1">
									<div class="text-dark-75 font-weight-bold font-size-h5">Статус поддержки</div>
									<div>
										<?php echo $sup_status ?>
									</div>
								</div>
								<div class="text-right flex-grow-1">
									<?php if($ticket['ticket_status'] != 0): ?> 
									<button type="button" onClick="sendAction(<?php echo $ticket['ticket_id'] ?>,'closed')" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="tooltip" data-placement="right" title="" data-original-title="Закрыть запрос">
									<i class="fa fa-lock"></i>
									</button>
									<?elseif($ticket['ticket_status'] == 0):?>
									<button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md disabled" data-toggle="tooltip" data-placement="right" title="" data-original-title="Запрос закрыт">
									<i class="fa fa-lock"></i>
									</button>
									<?endif;?>
								</div>
							</div>
							<div class="card-body">
								<div class="scroll scroll-pull"  id="scroll" data-scroll="true" style="height: 350px; overflow: auto;" data-mobile-height="300">
									<div class="messages" id="messagess">
										<div class="alert alert-primary" role="alert">Получаю сообщения...</div>
									</div>
								</div>
							</div>
							<div class="card-footer align-items-center">
								<?php if($ticket['ticket_status'] != 0): ?>
								<input type="text" class="form-control" type="text" id="text" name="text" placeholder="Напишите сообщение...">
								<?elseif($ticket['ticket_status'] == 0):?>
								<input type="text" class="form-control" type="text" id="text" name="text" placeholder="Запрос закрыт..." disabled>
								<?endif;?>
							    <div class="d-flex align-items-center justify-content-between mt-5">
									<div class="mr-3">
										<div class="card card-body" style="margin-bottom: 1rem;padding: 1rem;">
											<div class="form-group" style="margin-bottom: 0rem;">
												<div class="checkbox-list">
													<label class="checkbox">
													<input type="checkbox" checked id="autoscroll">
													<span></span>Автообновление</label></span>
											 </div>
										  </div>
									   </div>
									</div>
								<div>
								<button type="button" class="btn btn-primary btn-icon" data-toggle="modal" data-target="#image"><i class="flaticon2-photo-camera"></i></button>
								<button class="btn btn-primary btn-icon" type="submit"><i class="flaticon2-paper-plane"></i></button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="image" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Импорт изображения</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="modal-body">
				<form id="imgForm" method="POST" action=""  style="padding:0px; margin:0px;">
					<div class="alert alert-primary mb-5 p-5" role="alert">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">
								<i class="fas fa-camera text-primary"></i>
								</span>
							</div>
							<input class="form-control" id="file" name="userfile" type="file">
							<div class="input-group-append">
								<button type="submit" class="btn btn-light-primary btn-icon"><i class="fa fa-download"></i></button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	setInterval(function() {
		$.ajax({
			url: "/tickets/view/getMessagess",
			type: "POST",
			data: {tid: <?=$ticket['ticket_id']?>},
			success: function (data) {
				if (autoscroll.checked == true) {
					$("#messagess").html(data);
					var scroll = document.getElementById("scroll");
					scroll.scrollTop = scroll.scrollHeight;
				}
			}
		})
	}, 2000);
	
    $('#imgForm').ajaxForm({ 
		url: '/tickets/view/uploadFile/<?php echo $ticket['ticket_id'] ?>',            
		dataType: 'text',
		success: function(data) {
			console.log(data);
			data = $.parseJSON(data);
			switch(data.status) {
				case 'error':
					toastr.error(data.error);
					$('button[type=submit]').prop('disabled', false);
					$("#file").val("");
					break;
				case 'success':
					toastr.success(data.success);
					$('button[type=submit]').prop('disabled', false);
					$('#image').modal('hide');
					$("#file").val("");
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			$('button[type=submit]').prop('disabled', true);
		}
	});
	
	$('#sendForm').ajaxForm({ 
		url: '/tickets/view/ajax/<?php echo $ticket['ticket_id'] ?>',            
		dataType: 'text',
		success: function(data) {
			console.log(data);
			data = $.parseJSON(data);
			switch(data.status) {
				case 'error':
					toastr.error(data.error);
					$('button[type=submit]').prop('disabled', false);
					break;
				case 'success':
					toastr.success(data.success);
					$('button[type=submit]').prop('disabled', false);
					$('#text').val('');
					break;
			}
		},
		beforeSubmit: function(arr, $form, options) {
			$('button[type=submit]').prop('disabled', true);
		}
    });
	  
	function sendAction(ticketid, action) {
	    switch(action) {
			case "closed":
			{
				if(!confirm("Вы уверенны в том, что хотите закрыть запрос?")) return;
				break;
			}
		}
		$.ajax({ 
			url: '/tickets/view/action/'+ticketid+'/'+action,
			dataType: 'text',
			success: function(data) {
				console.log(data);
				data = $.parseJSON(data);
				switch(data.status) {
					case 'error':
						toastr.error(data.error);
						$('#controlBtns button').prop('disabled', false);
						break;
					case 'success':
						toastr.success(data.success);
						setTimeout("reload()", 1500);
						break;
				}
			},
			beforeSend: function(arr, options) {
				if(action == "closed") toastr.warning("Закрываем запрос...");
				$('#controlBtns button').prop('disabled', true);
			}
		});
    }
</script>
<?php echo $footer ?>
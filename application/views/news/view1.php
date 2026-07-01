<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko (https://vk.com/id00v)
*/
?>
<?php echo $header ?>
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid" style="margin: 15px 0;">
    <div class="kt-portlet">
		<div class="kt-portlet__head">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					<?echo $new['news_title']?>
					<small><?echo $new['look']?> Просмотров.</small>
				</h3>			
			</div>
			<div class="kt-portlet__head-toolbar">
				<?echo $new['news_date_add']?>
			</div>
		</div>
		<div class="kt-portlet__body">
			<div class="kt-portlet__content">
				<p>
					<?echo nl2br($new['news_text'])?>
				</p>
			</div>
		</div>
	</div>
	<form id="sendForm" action="" method="POST">
		<div class="kt-portlet">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Оставить комментарий
					</h3>	
				</div>
			</div>
			<div class="kt-portlet__body">
				<div class="media-body">
					<textarea class="form-control todo-taskbody-taskdesc" rows="4" name="text" id="text"></textarea>
				</div>
				<hr>
				<button type="submit" class="btn btn-brand m-btn m-btn--air btn-outline  btn-block sbold uppercase">Оставить комментарий</button>
			</div>
		</div>
		<div class="kt-portlet kt-portlet--height-fluid">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">Комментарии</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<div class="kt-widget3">	
					<?php foreach($messages as $item): ?>
					<div class="kt-widget3__item">
						<div class="kt-widget3__header">
							<div class="kt-widget3__user-img">
								<img class="kt-widget3__img" src="<?php echo $url ?><?if($item['user_img']) {echo $item['user_img'];}else{ echo '/application/public/img/user.png';}?>" alt="">
							</div>
							<div class="kt-widget3__info">
								<a href="#" class="kt-widget3__username">
								<?php if($item['user_firstname']){echo $item['user_firstname'];} else {echo 'Гость';} ?> <?php echo $item['user_lastname'] ?>
							    </a><br>
							    <span class="kt-widget3__time"><?php echo date("d.m.Y в H:i", strtotime($item['news_message_date_add'])) ?></span>
							</div>
							<span class="kt-widget3__status kt-font-info">
								<?php if($item['user_access_level'] == 1): ?>
								Пользователь
								<?php endif; ?>
								<?php if($item['user_access_level'] == 2): ?>
								Тех.поддержка
								<?php endif; ?>
								<?php if($item['user_access_level'] == 3): ?>
								Администратор
								<?php endif; ?>
							</span>
						</div>
						<div class="kt-widget3__body">
							<p class="kt-widget3__text"><?php echo nl2br($item['news_message']) ?></p>
						</div>
					</div>
				    <?php endforeach; ?>
				    <?php if(empty($messages)): ?>
						<div class="alert alert-danger">
							На данный момент нет коментариев.
						</div>
				   <?php endif; ?>	
				</div>
			</div>
        </div>
    </form>
</div>
<script>
$('#sendForm').ajaxForm({ 
url: '/news/view/ajax/<?php echo $new['news_id'] ?>',						
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
									$('#text').val('');
									setTimeout("reload()", 1500);
									ajax_url("/news/view/"+data);
									break;
							}
						},
						beforeSubmit: function(arr, $form, options) {
							$('button[type=submit]').prop('disabled', true);
						}
					});</script>
					<script type="text/javascript">
$(function(){
$('#form_pr').submit(function(e){
//отменяем стандартное действие при отправке формы
e.preventDefault();
//берем из формы метод передачи данных
var m_method=$(this).attr('method');
//получаем данные, введенные пользователем в формате input1=value1&input2=value2...,то есть в стандартном формате передачи данных формы
var m_data=$(this).serialize();
$.ajax({
type: m_method,
url: '/news/view/ajax/<?php echo $new['news_id'] ?>',	
data: m_data,
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
									$('#text').val('');
									setTimeout("reload()", 1500);
									break;
							}
							
						 },
						beforeSubmit: function(arr, $form, options) {
							$('button[type=submit]').prop('disabled', true);
						}
});
});
});
</script>
<?php echo $footer ?>
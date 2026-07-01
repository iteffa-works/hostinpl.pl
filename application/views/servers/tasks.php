<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
?>
<?php echo $header?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
   <div class="d-flex flex-column-fluid">
      <div class="container">
         <div class="row">
            <div class="col-xl-12">
               <?php include 'application/views/common/menuserver.php';?>
            </div>
            <div class="col-xl-3">
               <div class="card card-custom mb-3 pt-5">
                  <div class="card-body">
                     <div class="text-center">
                        <div class="symbol symbol-60 symbol-circle symbol-xl-90">
                           <div class="symbol-label">
                              <i class="fas fa-clock icon-5x text-primary"></i>
                           </div>
                        </div>
                        <h4 class="font-weight-bolder my-2">Планировщик задач</h4>
                        <div class="text-muted font-weight-bold mb-2">Выполнение команд по расписанию</div>
                     </div>
                  </div>
               </div>
               <div class="card card-custom mb-3">
                  <div class="card-body px-1" style="padding: 0.3rem 2.25rem;">
                     <div class="navi navi-hover navi-active navi-link-rounded navi-bold navi-icon-center navi-light-icon">
                        <div class="navi-item my-2">
                           <a href="javascript:;" data-toggle="modal" data-target="#tasksinfo" class="navi-link">
                           <span class="navi-icon mr-3">
                           <span class="svg-icon svg-icon-lg">
                           <i class="fa fa-exclamation-circle"></i>
                           </span>
                           </span>
                           <span class="navi-text font-weight-bolder font-size-lg">Информация</span>
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-9">
               <div class="row">
                  <div class="col-xl-12">
                     <div class="card card-custom gutter-b">
                        <div class="card-body">
                           <div class="form-group form-md-line-input">
                              <label>Задача</label>
                              <select class="form-control" id="task" name="task" onChange="editNewTask()">
                                 <option value="enable" <?php if($server['server_status'] != 1): ?>disabled <?php endif; ?>>Включение</option>
                                 <option value="disable" <?php if($server['server_status'] != 2): ?>disabled <?php endif; ?>>Выключение</option>
                                 <option value="restart" <?php if($server['server_status'] != 2): ?>disabled <?php endif; ?>>Перезагрузка</option>
                              </select>
                           </div>
                           <div class="form-group form-md-line-input">
                              <label>Тип задачи</label>
                              <select class="form-control" id="type" name="type">
                                 <option value="single">Одноразовая</option>
                              </select>
                           </div>
                           <div class="form-group form-md-line-input">
                              <label>Время выполнения</label>
                              <select class="form-control" id="time" name="time">
                                 <option value="15min">15 Минут</option>
                                 <option value="30min">30 Минут</option>
                                 <option value="1hour">1 Час</option>
                                 <option value="12hours">12 Часов</option>
                                 <option value="24hours">24 Часа</option>
                              </select>
                           </div>
                           <button id="createTask" class="btn btn-primary">Создать задачу</button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-12">
               <div class="card card-custom gutter-b">
                  <div class="card-header">
                     <div class="card-title">
                        <h3 class="card-label">Список задач</h3>
                     </div>
                  </div>
                  <div class="card-body">
                     <?php foreach($tasks as $item): ?>
                     <div class="card card-custom bg-gray-100 mb-4" id="task-<?php echo $item['task_id'] ?>">
                        <div class="card-header border-0" style="min-height: 0;padding-top: 0.5rem;padding-bottom: 0.5rem;">
                           <h3 class="card-title"><span class="card-icon">
                              <a href="javascript:;" class="btn btn-icon btn-light-primary btn-sm" data-toggle="tooltip" data-placement="right" title="" data-original-title="Тип задачи: <?php echo str_replace("single", "Одноразовая", str_replace("repeating", "Повторяющаяся", $item['task_type'])) ?>">
                              <i class="fa fa-info-circle"></i>
                              </a></span><?php echo str_replace("restart", "Перезагрузка", str_replace("enable", "Включение", str_replace("disable", "Выключение", $item['task_name']))) ?><small class="text-muted font-size-sm ml-2"><?php echo str_replace("15min", "15 минут", str_replace("30min", "30 минут", str_replace("1hour", "1 час", str_replace("12hours", "12 часов", str_replace("24hours", "24 часа", $item['task_time']))))) ?></small>
                           </h3>
                           <div class="card-toolbar">
                              <span class="badge badge-primary" data-toggle="tooltip" data-placement="right" title="" data-original-title="Будет выполнена в" style="margin-right: .25rem"><?php echo date("d.m.Y в H:i", strtotime($item['task_lead_time'])) ?></span>
                              <a href="javascript:;" onClick="sendAction('deleteTask',<?php echo $item['task_id'] ?>)" class="btn btn-icon btn-light-danger btn-xs" data-toggle="tooltip" data-placement="right" title="" data-original-title="Удалить задачу">
                              <i class="fa fa-times"></i>
                              </a>
                           </div>
                        </div>
                     </div>
                     <?php endforeach; ?>
                     <?php if(empty($tasks)): ?>
                     <div class="alert alert-custom alert-light-primary fade show mb-4" role="alert">
                        <div class="alert-icon">
                           <i class="flaticon-exclamation"></i>
                        </div>
                        <div class="alert-text">На данный момент нет задач.</div>
                        <div class="alert-close">
                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true"><i class="ki ki-close"></i></span>
                           </button>
                        </div>
                     </div>
                     <?php endif; ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!--begin::Modal-->
<div class="modal fade" id="tasksinfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Информация</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="modal-body">
				<p>
				   Система планировщик задач -  это система, позволяет автоматически выполнять определенные действия на сервере (запуск, рестарт, выключение и т.д.), с заданным временем или периодичностью.
				</p>
			</div>
		</div>
	</div>
</div>
<!--end::Modal-->
<script>
	function editNewTask() {
		setTimeout(function() {
			var task = $('#task option:selected').val();
			if (task == 'enable' || task == 'disable') {
				$("#type option[value='repeating']").remove();
			} else {
				$("#type").append('<option value="repeating">Повторяющаяся</option>');
			}
		}, 100);
	}

	$(document).ready(function() {
		editNewTask();
	});			
	
	$('#createTask').on('click', function() {
		$.ajax({ 
			url: '/servers/tasks/createTask/<?php echo $server['server_id'] ?>',
			type: 'POST',
			data: {
				'task': $("#task option:selected").val(), 
				'type': $("#type option:selected").val(), 
				'time': $("#time option:selected").val()
			},
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
						setTimeout("reload()", 1500);
						break;
				}
				$('#createTask').removeClass('loading');
			},
			beforeSubmit: function(arr, $form, options) {
				$('#createTask').addClass('loading');
			}
		});
	});	

    function sendAction(action, task_id) {
		switch(action) {
			case "deleteTask":
			{
				if(!confirm("Вы действительно хотите удалить задачу ID-"+task_id+"?")) return;
				break;
			}
		}
		$.ajax({ 
			url: '/servers/tasks/deleteTask/<?php echo $server['server_id'] ?>',
            type: "POST",
            data: {task_id: task_id},
			dataType: 'text',
			success: function(data) {
				data = $.parseJSON(data);
				switch(data.status) {
					case 'error':
						toastr.error(data.error);
						break;
					case 'success':
						toastr.success(data.success);
						setTimeout("reload()", 1500);
						break;
				}
			}
		});
	}	
</script>
<?php echo $footer ?>
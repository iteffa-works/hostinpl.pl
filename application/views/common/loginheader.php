<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
?>
<html lang="ru">
   <head>
      <meta charset="utf-8" />
      <title><?php echo $title ?> | <?php echo $description ?></title>
      <meta name="description" content="<?php echo $description ?>">
      <meta name="keywords" content="<?php echo $keywords ?>">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
      <link href="/assets/css/login-2.css" rel="stylesheet" type="text/css" />
      <link href="/assets/css/plugins.bundle.css" rel="stylesheet" type="text/css" />
      <link href="/assets/css/prismjs.bundle.css" rel="stylesheet" type="text/css" />
      <link href="/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
      <link rel="shortcut icon" href="/favicon.ico" />
   </head>
   <body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled page-loading">
      <div class="d-flex flex-column flex-root">
         <div class="login login-2 login-signin-on d-flex flex-column flex-column-fluid bg-white position-relative overflow-hidden" id="kt_login">
            <div class="login-header py-10 flex-column-auto">
            </div>
            <div class="login-body d-flex flex-column-fluid align-items-stretch justify-content-center">
               <div class="container row">
                  <div class="col-lg-6 d-flex align-items-center">
                     <div class="login-form login-signin">
                        <form class="form w-xxl-550px rounded-lg p-20" novalidate="novalidate" id="kt_login_signin_form" method="POST">
                           <div class="form-group">
                              <label class="font-size-h6 font-weight-bolder text-dark">E-Mail</label>
                              <input class="form-control form-control-solid h-auto p-6 rounded-lg" type="text" name="email" placeholder="Введите свой E-Mail">
                           </div>
                           <div class="form-group">
                              <div class="d-flex justify-content-between mt-n5">
                                 <label class="font-size-h6 font-weight-bolder text-dark pt-5">Пароль</label>
                                 <a href="javascript:;" class="text-primary font-size-h6 font-weight-bolder text-hover-primary pt-5" id="kt_login_forgot">Забыли пароль?</a>
                              </div>
                              <input class="form-control form-control-solid h-auto p-6 rounded-lg" type="password" name="password" placeholder="Введите свой Пароль">
                           </div>
                           <div class="card card-body" style="margin-bottom: 1rem;padding: 1rem;">
                              <center>
                                 <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha ?>" id="recaptcha1"></div>
                              </center>
                           </div>
                           <div class="pb-lg-0 pb-5">
                              <div class="btn-group btn-block" role="group">
                                 <button type="submit" class="btn btn-primary btn-block font-weight-bolder font-size-h6">Войти</button>
								 <?php if($vk_app_status == 1): ?>
								 <button type="button" onClick="redirect('https://oauth.vk.com/authorize?client_id=<?php echo $vk_app_id ?>&scope=email,offline&redirect_uri=<?php echo $url ?>account/login/vk&response_type=code&state=ok')" class="btn btn-outline-primary">VK</button>
								 <?php endif; ?>
							 </div>
                           </div>
                           <hr>
                           <center>
                              <span class="text-muted font-weight-bold font-size-h4">У вас ещё нет аккаунта?<br>
                              <a href="javascript:;" id="kt_login_signup" class="text-primary font-weight-bolder">Создать аккаунт</a></span>
                           </center>
                        </form>
                     </div>
                     <div class="login-form login-signup">
                        <form class="form w-xxl-550px rounded-lg p-20" novalidate="novalidate" id="kt_login_signup_form" method="GET">
                           <div class="form-group">
                              <input class="form-control form-control-solid h-auto p-6 rounded-lg font-size-h6" type="text" id="ref" name="ref" disabled placeholder="<?php
                                 if(isset($_GET['ref'])) {
                                     echo ' Приглашение от: '.$user['user_firstname'].'('.$_GET['ref'].')';
                                 }else echo ' Без приглашения';
                                 ?>">
                           </div>
                           <div class="form-group">
                              <input class="form-control form-control-solid h-auto p-6 rounded-lg font-size-h6"type="text" id="firstname" name="firstname" placeholder="Введите Ваше Имя">
                           </div>
                           <div class="form-group">
                              <input class="form-control form-control-solid h-auto p-6 rounded-lg font-size-h6" type="text" id="lastname" name="lastname" placeholder="Введите Вашу Фамилию">
                           </div>
                           <div class="form-group">
                              <input class="form-control form-control-solid h-auto p-6 rounded-lg font-size-h6" type="text" id="email" name="email" placeholder="Введите Ваш E-Mail">
                           </div>
                           <div class="form-group m-form__group">
                              <div class="row">
                                 <div class="col">
                                    <input class="form-control form-control-solid h-auto p-6 rounded-lg font-size-h6" type="password" id="password" name="password" placeholder="Введите Пароль">
                                 </div>
                                 <div class="col">
                                    <input class="form-control form-control-solid h-auto p-6 rounded-lg font-size-h6" type="password" id="password2" name="password2" placeholder="Повторите Пароль">
                                 </div>
                              </div>
                           </div>
                           <input type="hidden" name="ref" id="ref" value="<?echo @$_GET['ref']?>">
                           <div class="card card-body" style="margin-bottom: 1rem;padding: 1rem;">
                              <center>
                                 <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha ?>" id="recaptcha2"></div>
                              </center>
                           </div>
                           <div class="form-group d-flex flex-wrap pb-lg-0 pb-3">
                              <div class="btn-group btn-block" role="group">
                                 <button type="submit" class="btn btn-primary btn-block font-weight-bolder font-size-h6">Создать аккаунт</button>
                                 <button type="button" id="kt_login_signup_cancel" class="btn btn-outline-primary"><i class="flaticon2-reply-1 icon-x2"></i></button>
                              </div>
                           </div>
                        </form>
                     </div>
                     <div class="login-form login-forgot">
                        <form class="form w-xxl-550px rounded-lg p-20" novalidate="novalidate" id="kt_login_forgot_form" method="POST">
                           <div class="form-group">
                              <input class="form-control form-control-solid h-auto p-6 rounded-lg font-size-h6" id="email" name="email" placeholder="Введите свой E-Mail">
                           </div>
                           <div class="card card-body" style="margin-bottom: 1rem;padding: 1rem;">
                              <center>
                                 <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha ?>" id="recaptcha3"></div>
                              </center>
                           </div>
                           <div class="form-group d-flex flex-wrap pb-lg-0">
                              <div class="btn-group btn-block" role="group">
                                 <button type="submit" class="btn btn-primary btn-block font-weight-bolder font-size-h6">Восстановить пароль</button>
                                 <button type="button" id="kt_login_forgot_cancel" class="btn btn-outline-primary"><i class="flaticon2-reply-1 icon-x2"></i></button>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
                  <div class="col-lg-6 bgi-size-contain bgi-no-repeat bgi-position-y-center bgi-position-x-center min-h-150px mt-10 m-md-0 offcanvas-mobile" style="background-image: url(/application/public/img/hostinpl56.png)"></div>
               </div>
            </div>
            <div class="login-footer py-10 flex-column-auto">
               <div class="container d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-between">
                  <div class="font-size-h6 font-weight-bolder order-2 order-md-1 py-2 py-md-0">
                     <span class="text-muted font-weight-bold mr-2">2020©</span>
                     <a href="https://hostinpl.ru" target="_blank" class="text-dark-50 text-hover-primary"><?php echo $description ?></a>
                  </div>
                  <div class="font-size-h5 font-weight-bolder order-1 order-md-2 py-2 py-md-0">
                     <a href="javascript:;" data-toggle="modal" data-target="#hostin_supports" class="text-primary">Обратная связь</a>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!--begin::Modal-->
      <div class="modal fade" id="hostin_supports" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Обратная связь</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <i aria-hidden="true" class="ki ki-close"></i>
                  </button>
               </div>
               <div class="modal-body">
                  <form id="sendForm" method="POST" style="padding:0px; margin:0px;">
                     <div class="form-group m-form__group">
                        <input class="form-control m-input" type="text" id="firstname" name="firstname" placeholder="Введите ваше имя..">
                     </div>
                     <div class="form-group m-form__group">
                        <input class="form-control m-input" id="lastname" name="lastname" placeholder="Введите вашу фамилию..">
                     </div>
                     <div class="form-group m-form__group">
                        <input class="form-control m-input" type="email" id="email" name="email" placeholder="Введите ваш email..">
                     </div>
                     <div class="form-group form-md-line-input">
                        <select class="form-control" id="subject" name="subject" size="1" aria-required="true" aria-invalid="false" aria-describedby="delivery-error">
                           <option value="Вопроос">Вопрос</option>
                           <option value="Проблемы с сервером">Проблемы с сервером</option>
                           <option value="Проблемы с аккаунтом">Проблемы с аккаунтом</option>
                           <option value="Сотрудничество">Сотрудничество</option>
                        </select>
                     </div>
                     <div class="form-group form-md-line-input">
                        <textarea class="form-control" id="msg" name="msg" rows="3" placeholder="Сообщение..."></textarea>
                     </div>
                     <div class="recaptcha">
                        <center>
                           <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha ?>" id="recaptcha4"></div>
                        </center>
                     </div>
               </div>
               <div class="modal-footer">
               <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Отмена</button>
               <button type="submit" class="btn btn-primary font-weight-bold">Отправить</button>
               </div>
               </form>
            </div>
         </div>
      </div>
      <!--end::Modal-->
      <script src="/assets/js/plugins.bundle.js"></script>
      <script src="/assets/js/prismjs.bundle.js"></script>
      <script src="/assets/js/scripts.bundle.js"></script>
      <script src="/application/public/js/jquery.form.min.js"></script>
      <script src="/application/public/js/main.js"></script>
      <script src="/assets/js/fullcalendar.bundle.js"></script>
      <script src="https://www.google.com/recaptcha/api.js?onload=recaptha&render=explicit" async defer></script>
   </body>
</html>
<script>
   // Class Definition
   var KTLogin = function() {
       var _login;
   
       var _showForm = function(form) {
           var cls = 'login-' + form + '-on';
           var form = 'kt_login_' + form + '_form';
   
           _login.removeClass('login-forgot-on');
           _login.removeClass('login-signin-on');
           _login.removeClass('login-signup-on');
   
           _login.addClass(cls);
   
           KTUtil.animateClass(KTUtil.getById(form), 'animate__animated animate__backInUp');
       }
   
       var _handleSignInForm = function() {
           // Handle forgot button
           $('#kt_login_forgot').on('click', function (e) {
               e.preventDefault();
               _showForm('forgot');
           });
   
           // Handle signup
           $('#kt_login_signup').on('click', function (e) {
               e.preventDefault();
               _showForm('signup');
           });
       }
   
       var _handleSignUpForm = function(e) {
           var form = KTUtil.getById('kt_login_signup_form');
   
           // Handle cancel button
           $('#kt_login_signup_cancel').on('click', function (e) {
               e.preventDefault();
   
               _showForm('signin');
           });
       }
   
       var _handleForgotForm = function(e) {
           // Handle cancel button
           $('#kt_login_forgot_cancel').on('click', function (e) {
               e.preventDefault();
   
               _showForm('signin');
           });
       }
   
       // Public Functions
       return {
           // public functions
           init: function() {
               _login = $('#kt_login');
   
               _handleSignInForm();
               _handleSignUpForm();
               _handleForgotForm();
           }
       };
   }();
   
   // Class Initialization
   jQuery(document).ready(function() {
       KTLogin.init();
   });
   
   	var captcha_login, captcha_register, captcha_restore, captcha_infobox;	
       var recaptha = function() {	
   		captcha_login = grecaptcha.render('recaptcha1', {
   			'sitekey' : '<?php echo $recaptcha ?>',
   			'theme' : 'light'
   		});		
   		captcha_register = grecaptcha.render('recaptcha2', {
   			'sitekey' : '<?php echo $recaptcha ?>',
   		    'theme' : 'light'
   		});
   		captcha_restore = grecaptcha.render('recaptcha3', {
   			'sitekey' : '<?php echo $recaptcha ?>',
   			'theme' : 'light'
   		});
   		captcha_infobox = grecaptcha.render('recaptcha4', {
   			'sitekey' : '<?php echo $recaptcha ?>',
   			'theme' : 'light'
   		});		
   	};		
   			 
   	$('#sendForm').ajaxForm({ 
   	    url: '/account/login/ajax_infobox',
   	    dataType: 'text',
   	    success: function(data) {
   	        console.log(data);
   	        data = $.parseJSON(data);
   	        switch(data.status) {
   				case 'error':
   					toastr.error(data.error);
   					$('button[type=submit]').prop('disabled', false);
   					grecaptcha.reset(captcha_infobox);
   					break;
   				case 'success':
   					toastr.success(data.success);
   					setTimeout("redirect('/')", 1500);
   					break;
   			}
   		},
   	    beforeSubmit: function(arr, $form, options) {
   			$('button[type=submit]').prop('disabled', true);
   		}
   	});
   
   	$('#kt_login_signin_form').ajaxForm({ 
   	    url: '/account/login/ajax',
   	    dataType: 'text',
   	    success: function(data) {
   			console.log(data);
   			data = $.parseJSON(data);
   			switch(data.status) {
   				case 'error':
   					toastr.error(data.error);
   					grecaptcha.reset(captcha_login);
   					$('button[type=submit]').prop('disabled', false);
   					break;
   				case 'success':
   					toastr.success(data.success);
   					setTimeout("redirect('/')", 1500);
   					break;
   			}
   		},
   		beforeSubmit: function(arr, $form, options) {
   			$('button[type=submit]').prop('disabled', true);
   		}
   	});
   	
   	$('#kt_login_signup_form').ajaxForm({ 
   	    url: '/account/login/ajaxReg',
   	    dataType: 'text',
   	    success: function(data) {
   			console.log(data);
   			data = $.parseJSON(data);
   			switch(data.status) {
   				case 'error':
   					toastr.error(data.error);
   					grecaptcha.reset(captcha_register);
   					$('button[type=submit]').prop('disabled', false);
   					break;
   				case 'success':
   					toastr.success(data.success);
   					if(data.user_activate){
   						setTimeout("redirect('/account/login/complete')", 1500);
   					}else{
   						setTimeout("redirect('/')", 1500);
   					}
   					break;
   			}
   		},
   		beforeSubmit: function(arr, $form, options) {
   			$('button[type=submit]').prop('disabled', true);
   		}
   	});
   	
   	$('#kt_login_forgot_form').ajaxForm({ 
   	    url: '/account/restore/ajax',
   	    dataType: 'text',
   	    success: function(data) {
   			console.log(data);
   			data = $.parseJSON(data);
   			switch(data.status) {
   				case 'error':
   					toastr.error(data.error);
   					$('button[type=submit]').prop('disabled', false);
   					grecaptcha.reset(captcha_restore);
   					break;
   				case 'success':
   					toastr.success(data.success);
   					setTimeout("redirect('/')", 1500);
   					break;
   			}
   		},
   		beforeSubmit: function(arr, $form, options) {
   			$('button[type=submit]').prop('disabled', true);
   		}
   	});
</script>
<?php if(isset($error)): ?><script>toastr.error('<?php echo $error ?>');</script><?php endif; ?> 
<?php if(isset($warning)): ?><script>toastr.warning('<?php echo $warning ?>');</script><?php endif; ?> 
<?php if(isset($success)): ?><script>toastr.success('<?php echo $success ?>');</script><?php endif; ?>
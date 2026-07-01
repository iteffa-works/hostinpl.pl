<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
?>
<html lang="ru">
   <head>
      <meta charset="utf-8" />
      <title><?php echo $error ?></title>
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
      <link href="/assets/css/login-2.css" rel="stylesheet" type="text/css" />
      <link href="/assets/css/plugins.bundle.css" rel="stylesheet" type="text/css" />
      <link href="/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
      <link rel="shortcut icon" href="/favicon.ico" />
   </head>
   <body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled page-loading">
      <div class="d-flex flex-column flex-root">
         <div class="login login-2 login-signin-on d-flex flex-column flex-column-fluid bg-white position-relative overflow-hidden" id="kt_login">
            <div class="login-body d-flex flex-column-fluid align-items-stretch justify-content-center">
               <div class="container row">
                  <div class="col-lg-6 d-flex align-items-center">
                     <div class="login-form login-signin">
                        <div class="form rounded-lg p-3">
                           <div class="alert alert-primary text-center h5" role="alert"><?php echo $error ?></div>
                           <hr>
                           <center>
                              <span class="text-muted font-weight-bold font-size-h4">Упс... Что-то пошло не так.<br>
                              <a href="/" id="kt_login_signup" class="text-primary font-weight-bolder">Вернутся на главную</a></span>
                           </center>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-6 bgi-size-contain bgi-no-repeat bgi-position-y-center bgi-position-x-center min-h-150px mt-10 m-md-0 offcanvas-mobile" style="background-image: url(/application/public/img/error.gif)"></div>
               </div>
            </div>
         </div>
      </div>
   </body>
</html>
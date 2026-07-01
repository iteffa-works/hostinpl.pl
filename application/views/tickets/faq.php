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
            <div class="col-xl-4 col-xxl-4 mb-4">
               <div class="card card-custom card-sticky">
                  <div class="card-body px-5">
                     <div class="px-4 mt-4 mb-10">
                        <a href="/tickets/create" class="btn btn-block btn-primary font-weight-bold text-uppercase py-4 px-6 text-center">Написать</a>
                     </div>
                     <div class="navi navi-hover navi-active navi-link-rounded navi-bold navi-icon-center navi-light-icon">
                        <div class="navi-item my-2">
                           <a href="/tickets" class="navi-link">
                           <span class="navi-icon mr-4">
                           <i class="flaticon-computer icon-lg"></i>
                           </span>
                           <span class="navi-text font-weight-bolder font-size-lg">Мои запросы</span>
                           </a>
                        </div>
                        <div class="navi-item my-2">
                           <a href="/tickets/faq" class="navi-link active">
                           <span class="navi-icon mr-4">
                           <i class="flaticon-book icon-lg"></i>
                           </span>
                           <span class="navi-text font-weight-bolder font-size-lg">База знаний</span>
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-8 col-xxl-8">
               <div class="accordion accordion-solid accordion-toggle-plus" id="accordionExample">
                  <div class="card">
                     <div class="card-header" id="heading3">
                        <div class="card-title collapsed" data-toggle="collapse" role="button" data-target="#collapse3" aria-expanded="false" aria-controls="collapseThree">
                           Ошибка UnknownResponse
                        </div>
                     </div>
                     <div id="collapse3" class="card-body-wrapper collapse" aria-labelledby="heading3" data-parent="#accordionExample" style="">
                        <div class="card-body">
                           <p>
                              Данная ошибка решается переименовыванием сервера английскими буквами.
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header" id="heading4">
                        <div class="card-title collapsed" data-toggle="collapse" role="button" data-target="#collapse4" aria-expanded="false" aria-controls="collapseThree">
                           Информация перед установкой плагинов
                        </div>
                     </div>
                     <div id="collapse4" class="card-body-wrapper collapse" aria-labelledby="heading3" data-parent="#accordionExample" style="">
                        <div class="card-body">
                           <p>
                              1. Устанавливайте только нужные Вам плагины.<br> 2. Слишком большое количество плагинов может вызвать увеличение пинга и лаги.<br> 3. Некоторые сочетания плагинов могут вызвать нестабильную работу сервера.
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header" id="heading5">
                        <div class="card-title collapsed" data-toggle="collapse" role="button" data-target="#collapse5" aria-expanded="false" aria-controls="collapseThree">
                           Статус сервера Unknown
                        </div>
                     </div>
                     <div id="collapse5" class="card-body-wrapper collapse" aria-labelledby="heading3" data-parent="#accordionExample" style="">
                        <div class="card-body">
                           <p>
                              Проверьте наличие плагинов .so для вашего сервера в папке plugins, а также прописаны ли они в настройках сервера.
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header" id="heading6">
                        <div class="card-title collapsed" data-toggle="collapse" role="button" data-target="#collapse6" aria-expanded="false" aria-controls="collapseThree">
                           Не запускается сервер
                        </div>
                     </div>
                     <div id="collapse6" class="card-body-wrapper collapse" aria-labelledby="heading3" data-parent="#accordionExample" style="">
                        <div class="card-body">
                           <p>
                              Прежде чем искать решение проблемы и писать нам в тех.поддержку в первую очередь необходимо выяснить, что за ошибка вам попалась.
                              Для этого вам необходимо посмотреть логи сервера и логи запуска сервера.
                              Это файлы server_log.txt и screenlog. Посмотреть вы их можете через ФТП, например.
                              В эти файлы записываются всё, что сервер отдает в командную строку.
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header" id="heading7">
                        <div class="card-title collapsed" data-toggle="collapse" role="button" data-target="#collapse7" aria-expanded="false" aria-controls="collapseThree">
                           Ручное добавление bind в файл конфигурации
                        </div>
                     </div>
                     <div id="collapse7" class="card-body-wrapper collapse" aria-labelledby="heading3" data-parent="#accordionExample" style="">
                        <div class="card-body">
                           <p>
                              Если получилось так, что вам как-то удалось заменить наш стандартный файл конфигурации на свой, где нет строки bind, или просто удалить эту строку из нашего, то для нормальной работы сервера (да и вообще для любой) стоит эту строку вернуть назад. Ваши действия:
                              Скачать на компьютер файл конфигурации server.cfg
                              Открыть файл в любом текстовом редакторе и добавить в самый конец такую строку:<br>
                              bind<br>
                              Сохранить файл<br>
                              Закачать файл назад по фтп с заменой старого
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header" id="heading8">
                        <div class="card-title collapsed" data-toggle="collapse" role="button" data-target="#collapse8" aria-expanded="false" aria-controls="collapseThree">
                          Файл конфигурации server.cfg (SAMP,CRMP,UNITED)
                        </div>
                     </div>
                     <div id="collapse8" class="card-body-wrapper collapse" aria-labelledby="heading3" data-parent="#accordionExample" style="">
                        <div class="card-body">
                           <p>
                              echo - Все, что стоит после этого параметра - будет выводится в консоль сервера при запуске. Стандартно установлено Executing Server Config...<br>
                              lanmode - Указывает где работает сервер, в интернете или в локальной сети, 0 для интернета. Стандартно значение установлено на 0<br>
                              maxplayers.<br>
                              announce - Включает/выключает отображение сервера в Интернете.<br>
                              port - Порт сервера.<br>
                              hostname - Название вашего сервера.<br>
                              gamemode(n) (N) (t) - Игровой мод<br>
                              weburl - Адрес вашего сайта.<br>
                              rcon_password - RCON пароль сервера.<br>
                              filterscripts (N) - Скрипты<br>
                              plugins (N) - Плагины<br>
                              password (p) - Пароль сервера<br>
                              mapname (m) - Название карты<br>
                              bind - Указывает, на каком IP адресе работает сервер. Запрещено изменять на нашем хостинге.<br>
                              rcon 0/1 - Включает/выключает возможность использования RCON-консоли.<br>
                              maxnpc - Кол-во ботов, которые могут быть использоваться на сервере.<br>
                              onfoot_rate - Технический параметр.<br>
                              incar_rate - Технический параметр.<br>
                              weapon_rate - Технический параметр.<br>
                              stream_distance - Технический параметр.<br>
                              stream_rate - Технический параметр.<br>
                              Какой будет пинг?<br>
                              Проверить пинг и общее качество игрового сервера вы можете на тестовых демо серверах.
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header" id="heading9">
                        <div class="card-title collapsed" data-toggle="collapse" role="button" data-target="#collapse9" aria-expanded="false" aria-controls="collapseThree">
                          Minecraft PE: Мой сервер не запускается и просит выбрать язык, что делать? (Nukkit)
                        </div>
                     </div>
                     <div id="collapse9" class="card-body-wrapper collapse" aria-labelledby="heading9" data-parent="#accordionExample" style="">
                        <div class="card-body">
                           <p>
                             Создайте в корневой директории файл <code>nukkit.yml</code><br>
                              Далее укажите в нем такой параметр:<br><br>
							  <code>language: "rus"</code><br><br>
							  Где rus это язык на котором будет работать сервер.<br><br>
							  Поддерживаемые языки:<br>
								<code>eng => English</code><br>
								<code>chs => 中文（简体）</code><br>
								<code>cht => 中文（繁體）</code><br>
								<code>jpn => 日本語</code><br>
								<code>rus => Pyccĸий</code><br>
								<code>spa => Español</code><br>
								<code>pol => Polish</code><br>
								<code>bra => Português-Brasil</code><br>
								<code>kor => 한국어</code><br>
								<code>ukr => Українська</code><br>
								<code>deu => Deutsch</code><br>
								<code>ltu => Lietuviška</code><br>
								<code>idn => Indonesia</code><br>
								<code>cze => Czech</code>
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header" id="heading10">
                        <div class="card-title collapsed" data-toggle="collapse" role="button" data-target="#collapse10" aria-expanded="false" aria-controls="collapseThree">
                           Как подключиться к серверу через ftp?
                        </div>
                     </div>
                     <div id="collapse10" class="card-body-wrapper collapse" aria-labelledby="heading10" data-parent="#accordionExample" style="">
                        <div class="card-body">
                           <p>
                              1. Скачать ftp-клиент, например: FileZilla<br>
                              2. После запуска программы необходимо указать данные от ftp-сервера, их вы получите в управлении вашего игрового сервера, в разделе "FTP Доступ".<br>
                              - в поле хост, введите ip-адрес сервера, с портам (21);<br>
                              - в поле имя пользователя, введите ваш логин от ftp;<br>
                              - в поле пароль, введите ваш пароль от ftp;<br>
                              - поле порт указать "21";<br>
                              3. После ввода данных, нажмите "Быстрое соединение"<br>
                              В правом нижнем углу загрузится структура вашего игрового сервера. Здесь вы можете устанавливать любые плагины и выполнять любые настройки своего игрового сервера.
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header" id="heading11">
                        <div class="card-title collapsed" data-toggle="collapse" role="button" data-target="#collapse11" aria-expanded="false" aria-controls="collapseThree">
                           Что такое купон?
                        </div>
                     </div>
                     <div id="collapse11" class="card-body-wrapper collapse" aria-labelledby="heading3" data-parent="#accordionExample" style="">
                        <div class="card-body">
                           <p>
                              Купон - это код,который вы вводите при заказе сервера,для получения скидки. (Вводить не обязательно).
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header" id="heading11">
                        <div class="card-title collapsed" data-toggle="collapse" role="button" data-target="#collapse12" aria-expanded="false" aria-controls="collapseThree">
                           Как заработать бонусы?
                        </div>
                     </div>
                     <div id="collapse12" class="card-body-wrapper collapse" aria-labelledby="heading12" data-parent="#accordionExample" style="">
                        <div class="card-body">
                           <p>
                              Бонусы можно получить за пополнение баланса.
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header" id="heading13">
                        <div class="card-title collapsed" data-toggle="collapse" role="button" data-target="#collapse13" aria-expanded="false" aria-controls="collapseThree">
                           Через сколько удаляется сервер если его не оплачивать?
                        </div>
                     </div>
                     <div id="collapse13" class="card-body-wrapper collapse" aria-labelledby="heading3" data-parent="#accordionExample" style="">
                        <div class="card-body">
                           <p>
                              Сервер удаляется через 24 часа с момента отключения.
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header" id="heading14">
                        <div class="card-title collapsed" data-toggle="collapse" role="button" data-target="#collapse14" aria-expanded="false" aria-controls="collapseThree">
                           В обязанности службы технической поддержки хостинга входит решение следующих задач
                        </div>
                     </div>
                     <div id="collapse14" class="card-body-wrapper collapse" aria-labelledby="heading3" data-parent="#accordionExample" style="">
                        <div class="card-body">
                           <p>
                              Реагировать на проблемы неработоспособности функций хостинга. Предоставлять необходимую информацию касательно хостинга. Выполнять задачи поставленные клиентом (в случаи если эти задачи невозможно осуществить со стороны клиента, и эти задачи не входят в ряд дополнительных услуг.)
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header" id="heading15">
                        <div class="card-title collapsed" data-toggle="collapse" role="button" data-target="#collapse15" aria-expanded="false" aria-controls="collapseThree">
                           Не входит обязанности службы технической поддержки
                        </div>
                     </div>
                     <div id="collapse15" class="card-body-wrapper collapse" aria-labelledby="heading3" data-parent="#accordionExample" style="">
                        <div class="card-body">
                           <p>
                              Устанавливать и настраивать плагины, моды, звуки, и т.д. даже в том случае, если клиент сам не в состоянии их настроить. Делать за клиента операции, которые может сделать клиент сам через FTP или Панель Управления в дальнейшем сокращёно ПУ Проводить обучение по настройке плагинов, по работе с серверами и другими услугами. Оказывать техподдержку лицам, которые не являются клиентами Хостинга. Проводить обучение по работе с серверами и другими услугами. Отвечать на вопросы которые не имеют отношения к сервисам хостинга.
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header" id="heading16">
                        <div class="card-title collapsed" data-toggle="collapse" role="button" data-target="#collapse16" aria-expanded="false" aria-controls="collapseThree">
                           Важно знать и помнить
                        </div>
                     </div>
                     <div id="collapse16" class="card-body-wrapper collapse" aria-labelledby="heading3" data-parent="#accordionExample" style="">
                        <div class="card-body">
                           <p>
                              Хостинг не отвечает за работоспособность/стабильность Вашего сервера с установленными Вами не стандартных плагинов, карт и других файлов. Мы не несем ответственности за работоспособность сервера при Вашей самостоятельной его настройке, наша задача предоставить Вам выделенный полностью рабочий сервер. Мы не обязаны, исправлять какие либо Ваши ошибки после которых, сервер не запускается или работает нестабильно. Возможно мы сможем помочь Вам в каких-либо вопросах связанных с настройкой, но задавать их нужно во вкладке Тех.Поддержка
                           </p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php echo $footer ?>
<?php
/**
 * Dovhopol Mykola Ivanovich (iTeffa)
 * Telegram: https://t.me/iteffa
 * Phone: +380966349498
 * Email: flowaxy.dev@gmail.com
 * Website: https://flowaxy.com/
 *
 * Landing-страница для неавторизованных пользователей.
 *
 * Created: 2026-07-02
 * Modified: 2026-07-06
 *
 * © 2026 Flowaxy Digital Studio. All rights reserved.
 */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8" />
	<title><?php echo htmlspecialchars($title) ?> | <?php echo htmlspecialchars($description) ?></title>
	<meta name="description" content="<?php echo htmlspecialchars($description) ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<link href="/assets/css/login-2.css" rel="stylesheet" type="text/css" />
	<link href="/assets/css/plugins.bundle.css" rel="stylesheet" type="text/css" />
	<link href="/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" href="/favicon.ico" />
	<style>
		.hostin-landing-hero { max-width: 560px; }
		.hostin-landing-badge {
			display: inline-block;
			padding: 0.35rem 0.85rem;
			border-radius: 2rem;
			font-size: 0.8rem;
			font-weight: 600;
			letter-spacing: 0.02em;
			color: #8950FC;
			background: rgba(137, 80, 252, 0.1);
			margin-bottom: 1.25rem;
		}
		.hostin-landing-features .feature-item {
			display: flex;
			align-items: flex-start;
			margin-bottom: 0.85rem;
		}
		.hostin-landing-features .feature-icon {
			width: 2rem;
			height: 2rem;
			border-radius: 0.65rem;
			background: rgba(137, 80, 252, 0.12);
			color: #8950FC;
			display: flex;
			align-items: center;
			justify-content: center;
			flex-shrink: 0;
			margin-right: 0.85rem;
			font-size: 0.95rem;
		}
		.hostin-landing-art {
			min-height: 420px;
			background-size: contain !important;
			background-position: center center !important;
		}
		@media (min-width: 992px) {
			.hostin-landing-art { min-height: 520px; }
		}
	</style>
</head>
<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled page-loading">
	<div class="d-flex flex-column flex-root">
		<div class="login login-2 login-signin-on d-flex flex-column flex-column-fluid bg-white position-relative overflow-hidden" id="kt_login">
			<div class="login-header py-8 flex-column-auto">
				<div class="container d-flex align-items-center justify-content-between">
					<a href="/">
						<img alt="HOSTINPL" src="<?php echo $logo ?>" class="h-40px" />
					</a>
					<a href="/account/login" class="btn btn-sm btn-light-primary font-weight-bold">Войти</a>
				</div>
			</div>
			<div class="login-body d-flex flex-column-fluid align-items-stretch justify-content-center pb-10 pb-lg-20">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-lg-6 d-flex align-items-center">
							<div class="hostin-landing-hero py-5 py-lg-10">
								<span class="hostin-landing-badge">Плагин Site Landing</span>
								<h1 class="font-weight-bolder text-dark display-4 mb-4"><?php echo htmlspecialchars($title) ?></h1>
								<p class="text-dark-50 font-size-lg line-height-xl mb-6">
									<?php echo htmlspecialchars($description) ?>
								</p>
								<div class="hostin-landing-features mb-8">
									<div class="feature-item">
										<div class="feature-icon"><i class="fas fa-gamepad"></i></div>
										<div>
											<div class="font-weight-bold text-dark">Игровые серверы</div>
											<div class="text-muted font-size-sm">SAMP, MTA, Minecraft, CS, RAGE:MP и другие</div>
										</div>
									</div>
									<div class="feature-item">
										<div class="feature-icon"><i class="fas fa-credit-card"></i></div>
										<div>
											<div class="font-weight-bold text-dark">Биллинг и оплата</div>
											<div class="text-muted font-size-sm">Счета, баланс, популярные платёжные системы</div>
										</div>
									</div>
									<div class="feature-item">
										<div class="feature-icon"><i class="fas fa-headset"></i></div>
										<div>
											<div class="font-weight-bold text-dark">Личный кабинет</div>
											<div class="text-muted font-size-sm">Управление услугами, тикеты, статистика</div>
										</div>
									</div>
								</div>
								<div class="d-flex flex-wrap align-items-center">
									<a href="/account/login" class="btn btn-primary btn-lg font-weight-bold px-8 py-3 mr-3 mb-3">Войти в панель</a>
									<a href="/account/login" class="btn btn-light-primary btn-lg font-weight-bold px-8 py-3 mb-3">Создать аккаунт</a>
								</div>
							</div>
						</div>
						<div class="col-lg-6 bgi-no-repeat bgi-position-center hostin-landing-art mt-10 mt-lg-0" style="background-image: url(/application/public/img/hostinpl56.png)"></div>
					</div>
				</div>
			</div>
			<div class="login-footer py-10 flex-column-auto">
				<div class="container d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-between">
					<div class="font-size-h6 font-weight-bolder order-2 order-md-1 py-2 py-md-0">
						<span class="text-muted font-weight-bold mr-2">2020©</span>
						<a href="https://flowaxy.com" target="_blank" class="text-dark-50 text-hover-primary">HOSTINPL 5.6</a>
						<span class="text-muted mx-2">·</span>
						<a href="https://github.com/iteffa-works/hostinpl.pl" target="_blank" class="text-dark-50 text-hover-primary">GitHub</a>
					</div>
					<div class="font-size-h6 font-weight-bolder order-1 order-md-2 py-2 py-md-0">
						<a href="https://flowaxy.com/donate" target="_blank" class="text-primary">Поддержать проект</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="/assets/js/plugins.bundle.js"></script>
	<script src="/assets/js/scripts.bundle.js"></script>
</body>
</html>

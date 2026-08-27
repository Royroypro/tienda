<?php
$pageTitle = trim((string) ($data['title'] ?? 'Inicio'));
$siteName = 'Seguridad Electrónica Huacho';
$seoTitle = stripos($pageTitle, 'Seguridad Electrónica Huacho') === false
    ? $pageTitle . ' | ' . $siteName
    : $pageTitle;
$seoDescription = (string) ($data['description'] ?? 'Venta e instalación de cámaras de seguridad, cercos eléctricos y soluciones de seguridad para hogares y negocios en Huacho.');
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$canonicalUrl = rtrim(BASE_URL, '/') . ($requestPath === '/' ? '/' : '/' . ltrim($requestPath, '/'));
$isHome = rtrim($requestPath, '/') === '';
$isCart = rtrim($requestPath, '/') === '/principal/carrito';
$businessName = (string) ($data['negocio']['nombre'] ?? $siteName);
$businessPhone = (string) ($data['negocio']['telefono'] ?? '');
$businessEmail = (string) ($data['negocio']['correo'] ?? '');
$businessAddress = (string) ($data['negocio']['direccion'] ?? 'Huacho, Perú');
$structuredData = [
    '@context' => 'https://schema.org',
    '@type' => 'LocalBusiness',
    'name' => $businessName,
    'url' => BASE_URL,
    'logo' => BASE_URL . 'public/img/logo.png',
    'image' => BASE_URL . 'public/img/camaras-de-seguridad-que-convienen-para-casa-o-negocio.jpg',
    'telephone' => $businessPhone,
    'email' => $businessEmail,
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => $businessAddress,
        'addressLocality' => 'Huacho',
        'addressCountry' => 'PE',
    ],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrfToken()); ?>">
    <meta name="description" content="<?php echo e($seoDescription); ?>">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="canonical" href="<?php echo e($canonicalUrl); ?>">
    <meta property="og:locale" content="es_PE">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?php echo e($siteName); ?>">
    <meta property="og:title" content="<?php echo e($seoTitle); ?>">
    <meta property="og:description" content="<?php echo e($seoDescription); ?>">
    <meta property="og:image" content="<?php echo BASE_URL; ?>public/img/camaras-de-seguridad-que-convienen-para-casa-o-negocio.jpg">
    <meta property="og:url" content="<?php echo e($canonicalUrl); ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo e($seoTitle); ?>">
    <meta name="twitter:description" content="<?php echo e($seoDescription); ?>">
    <title><?php echo e($seoTitle); ?></title>
    <script type="application/ld+json"><?php echo json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP); ?></script>
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>public/img/favicon.ico" type="image/x-icon">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>public/css/toastify.min.css" />
    <link href="<?php echo BASE_URL . 'public/admin/DataTables/datatables.min.css'; ?>" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/login.css" type="text/css">
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>

    </div>
    <br>
    <!-- Humberger Begin -->
    
    <div class="humberger__menu__overlay"></div>
    <div class="humberger__menu__wrapper">
        <div class="humberger__menu__logo">
            <a href="<?php echo BASE_URL; ?>"><img src="<?php echo BASE_URL; ?>public/img/logo.png" alt="Seguridad Electrónica Huacho" width="50"></a>
        </div>
        <div class="humberger__menu__cart">
            <ul>
                <!-- <li><a href="#"><i class="fa fa-heart"></i> <span>1</span></a></li> -->
                <li><a href="<?php echo BASE_URL . 'principal/carrito'; ?>"><i class="fa fa-shopping-bag"></i> <span id="numerito">0</span></a></li>
            </ul>
            <!-- <div class="header__cart__price">item: <span>$150.00</span></div> -->
        </div>
        <div class="humberger__menu__widget">
            <div class="header__top__right__auth">
                <?php if (empty($_SESSION['id_usuario'])) { ?>
                    <a href="<?php echo BASE_URL . 'principal/login'; ?>"><i class="fa fa-user"></i> Login</a>
                <?php } else {  ?>
                    <a href="<?php echo BASE_URL . 'profile'; ?>"><i class="fa fa-user"></i> Tu cuenta</a>
                <?php } ?>
            </div>
        </div>
        <nav class="humberger__menu__nav mobile-menu">
            <ul>
                <li class="active"><a href="<?php echo BASE_URL; ?>">Inicio</a></li>
                <li><a href="<?php echo BASE_URL . 'principal/productos'; ?>">Productos</a></li>
                <li><a href="<?php echo BASE_URL . 'principal/contactos'; ?>">Contactos</a></li>
            </ul>
        </nav>
        <div id="mobile-menu-wrap"></div>
        <div class="header__top__right__social">
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-linkedin"></i></a>
            <a href="#"><i class="fa fa-pinterest-p"></i></a>
        </div>
        <div class="humberger__menu__contact">
            <ul>
                <li><i class="fa fa-envelope"></i> <?php echo e($data['negocio']['correo']); ?></li>
                <li>SEGURIDAD ELECTRONICA HUACHO</li>
            </ul>
        </div>
    </div>
    <!-- Humberger End -->

    <!-- Header Section Begin -->
    <header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__left">
                            <ul>
                                <li><i class="fa fa-envelope"></i> <?php echo e($data['negocio']['correo']); ?></li>
                                <li>SEGURIDAD ELECTRONICA HUACHO</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__right">
                            <div class="header__top__right__social">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-linkedin"></i></a>
                                <a href="#"><i class="fa fa-pinterest-p"></i></a>
                            </div>
                            <div class="header__top__right__auth">
                                <?php if (empty($_SESSION['id_usuario'])) { ?>
                                    <a href="<?php echo BASE_URL . 'principal/login'; ?>"><i class="fa fa-user"></i> Login</a>
                                <?php } else {  ?>
                                    <a href="<?php echo BASE_URL . 'profile'; ?>"><i class="fa fa-user"></i> Tu cuenta</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="header__logo">
                        <a href="<?php echo BASE_URL; ?>"><img src="<?php echo BASE_URL; ?>public/img/logo.png" alt="Seguridad Electrónica Huacho" width="50"></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <nav class="header__menu">
                        <ul>
                            <li class="active"><a href="<?php echo BASE_URL; ?>">Inicio</a></li>
                            <li><a href="<?php echo BASE_URL . 'principal/productos'; ?>">Productos</a></li>
                            <li><a href="<?php echo BASE_URL . 'principal/contactos'; ?>">Contactos</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <div class="header__cart">
                        <ul>
                            <!-- <li><a href="#"><i class="fa fa-heart"></i> <span>1</span></a></li> -->
                            <li><a href="<?php echo BASE_URL . 'principal/carrito'; ?>"><i class="fa fa-shopping-bag"></i> <span id="numerito1">0</span></a></li>
                        </ul>
                        <!-- <div class="header__cart__price">item: <span>$150.00</span></div> -->
                    </div>
                </div>
            </div>
            <div class="humberger__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </header>
    <!-- Header Section End -->
    <!-- Hero Section Begin -->
    <div class="d-block d-sm-none" style="text-align: center; font-size: 0rem; font-weight: bold; margin-bottom: 2.5rem; color: #326CB7;"><span style="font-size: 1rem;">SEGURIDAD ELECTRONICA HUACHO</span></div>
    <section class="hero <?php echo $isCart ? 'hero-normal' : ''; ?>">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="hero__categories">
                        
                        <div class="hero__categories__all">
                            <i class="fa fa-bars"></i>
                            <span>Todas las categorias</span>
                        </div>
                        <ul>
                            <?php foreach ($data['categorias'] as $categoria) { ?>
                                <li><a href="<?php echo BASE_URL . 'principal/categoria/' . rawurlencode($categoria['categoria']); ?>"><?php echo e($categoria['categoria']); ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="hero__search">
                        <div class="hero__search__form">
                            <form action="<?php echo BASE_URL . 'principal/productos'; ?>" autocomplete="off">
                                <input type="text" name="search" placeholder="Que estas buscando?">
                                <button type="submit" class="site-btn">BUSCAR</button>
                            </form>
                        </div>
                        <div class="hero__search__phone">
                            <div class="hero__search__phone__icon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="hero__search__phone__text">
                                <h5><?php echo e($data['negocio']['telefono']); ?></h5>
                                <span><?php echo e($data['negocio']['direccion']); ?></span>
                            </div>
                        </div>
                    </div>
                    <p class="service-area-note"><i class="fa fa-map-marker"></i> Atención e instalación en Huacho y alrededores</p>
                    <?php if ($isHome) { ?>
                        
                        <div class="hero__item set-bg" data-setbg="<?php echo BASE_URL; ?>public/img/hero/banner.jpg">
                            <div class="hero__text">
                                <span style="color: #326CB7">SEGURIDAD PARA TU NEGOCIO Y HOGAR</span>
                                <h1>Cámaras de seguridad<br>y cercos eléctricos</h1>
                                <p>Venta, instalación y asesoría para hogares y negocios.</p>
                                <a href="<?php echo BASE_URL . 'principal/productos'; ?>" class="primary-btn">Ver productos</a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

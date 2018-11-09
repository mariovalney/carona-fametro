<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta charset="UTF-8">

        <title><?php av_title(); ?></title>

        <link href="<?php get_theme_image( 'favicon.png', true, true ); ?>" rel="icon" />
        <link href="https://fonts.googleapis.com/css?family=Crimson+Text:400,400i,600|Montserrat:200,300,400" rel="stylesheet">

        <link rel="stylesheet" href="<?php theme_file_url( 'dist/fonts/ionicons/css/ionicons.min.css', true, true ); ?>">
        <link rel="stylesheet" href="<?php theme_file_url( 'dist/fonts/fontawesome/css/font-awesome.min.css', true, true ); ?>">

        <?php $css = ( defined( 'DEBUG' ) && DEBUG ) ? '.css' : '.min.css'; ?>
        <link rel="stylesheet" type="text/css" href="<?php theme_file_url( 'dist/css/styles' . $css, true, true ); ?>">

        <script type="text/javascript">var CF = { swal: [] };</script>
    </head>
    <body class="<?php body_class(); ?>" data-spy="scroll" data-target="#pb-navbar" data-offset="200">

        <?php if ( is_home() ) : ?>
            <nav class="navbar navbar-scrollable navbar-expand-lg navbar-dark pb_navbar pb_scrolled-light" id="pb-navbar">
                <div class="container">
                    <a class="navbar-brand" href="/">
                        <?php _e( 'Carona Fametro', VZR_TEXTDOMAIN ); ?>
                    </a>
                    <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#probootstrap-navbar" aria-controls="probootstrap-navbar" aria-expanded="false" aria-label="Toggle navigation">
                        <span><i class="ion-navicon"></i></span>
                    </button>
                    <div class="collapse navbar-collapse" id="probootstrap-navbar">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="#section-home">
                                    <?php _e( 'Início', VZR_TEXTDOMAIN ); ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#section-about">
                                    <?php _e( 'Como funciona?', VZR_TEXTDOMAIN ); ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#section-faq">
                                    <?php _e( 'FAQ', VZR_TEXTDOMAIN ); ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#section-contact">
                                    <?php _e( 'Contato', VZR_TEXTDOMAIN ); ?>
                                </a>
                            </li>
                            <li class="nav-item cta-btn ml-xl-2 ml-lg-2 ml-md-0 ml-sm-0 ml-0">
                                <a class="nav-link" href="/entrar">
                                    <span class="pb_rounded-4 px-4">
                                        <?php _e( 'Entrar', VZR_TEXTDOMAIN ); ?>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- END nav -->
        <?php else : ?>
            <nav class="navbar navbar-expand-lg navbar-dark pb_navbar pb_scrolled-light scrolled awake" id="pb-navbar">
                <div class="container">
                    <a class="navbar-brand" href="/">
                        <?php _e( 'Carona Fametro', VZR_TEXTDOMAIN ); ?>
                    </a>
                    <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#probootstrap-navbar" aria-controls="probootstrap-navbar" aria-expanded="false" aria-label="Toggle navigation">
                        <span><i class="ion-navicon"></i></span>
                    </button>
                    <div class="collapse navbar-collapse" id="probootstrap-navbar">
                        <ul class="navbar-nav ml-auto">
                            <?php
                                $user = get_logged_user();

                                if ( ! empty( $user ) ) :
                            ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="/perfil">
                                        <?php _e( 'Perfil', VZR_TEXTDOMAIN ); ?>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/sair">
                                        <?php _e( 'Sair', VZR_TEXTDOMAIN ); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- END nav -->
        <?php endif; ?>

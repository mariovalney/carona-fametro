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
    </head>
    <body class="<?php body_class(); ?>" data-spy="scroll" data-target="#pb-navbar" data-offset="200">
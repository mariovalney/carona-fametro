<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta charset="UTF-8">

        <title><?php av_title(); ?></title>

        <link href="<?php theme_file_url( 'dist/images/favicon.png', true, true ) ?>" rel="icon" />

        <?php $css = ( defined( 'DEBUG' ) && DEBUG ) ? '.css' : '.min.css'; ?>
        <link rel="stylesheet" type="text/css" href="<?php theme_file_url( 'dist/css/styles' . $css, true, true ); ?>">
    </head>
    <body class="<?php body_class(); ?>">
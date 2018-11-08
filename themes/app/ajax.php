<?php
    global $avant;

    $file = ( $avant['query_params'][0] ) ?? '';

    if ( empty( $file ) ) {
        av_redirect();
    }

    require 'ajax/' . $file . '.php';
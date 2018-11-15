<?php

/*
 * Ajax Functions: some useful functions to ajax forms.
 *
 * @package Avant
 */

function av_send_json( $response, $status = false ) {
    if ( $status ) {
        $response['status'] = $status;
    }

    echo json_encode( $response );
    exit;
}

function av_send_ops_json( $message ) {
    av_send_json( array(
        'title'     => __( 'Ops' ),
        'message'   => $message,
    ), 'error' );
}
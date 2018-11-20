<?php

$response = array(
    'going'     => [ __( 'Nenhuma carona disponível.', VZR_TEXTDOMAIN ) ],
    'returning' => [ __( 'Nenhuma carona disponível.', VZR_TEXTDOMAIN ) ],
);

av_send_json( $response );
<?php

use Avant\Modules\Phpgeo;
use Avant\Modules\Ride;

$section = create_google_section( false );

if ( ! $section  ) {
    av_send_ops_json( __( 'Você precisa fazer login para buscar uma carona.' ) );
}

$user = get_logged_user();

if ( empty( $user ) ) {
    av_send_ops_json( __( 'Usuário inválido. Faça login e tente novamente' ) );
}

// Route
$route_id = (int) ( $_POST['route'] ?? '' );

$route = get_route_by( 'ID', $route_id );

if ( empty( $route->ID ) ) {
    av_send_ops_json( __( 'Rota inválida' ) );
}

if ( $route->userId !== $user->ID ) {
    av_send_ops_json( __( 'Essa rota não pertence ao usuário logado' ) );
}

//  Default Answer
$response = array(
    'going'     => [ __( 'Nenhuma carona disponível.', VZR_TEXTDOMAIN ) ],
    'returning' => [ __( 'Nenhuma carona disponível.', VZR_TEXTDOMAIN ) ],
);

// Get Rides
$rides = new Ride( $route );

$response['going'] = $rides->getRidesFromStart();
$response['returning'] = $rides->getRidesFromReturn();

av_send_json( $response );
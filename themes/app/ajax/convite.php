<?php

use Avant\Modules\Ride;
use Avant\Modules\Invitation;

$section = create_google_section( false );

if ( ! $section  ) {
    av_send_ops_json( __( 'Você precisa fazer login para enviar um convite.' ) );
}

$user = get_logged_user();

if ( empty( $user ) ) {
    av_send_ops_json( __( 'Usuário inválido. Faça login e tente novamente' ) );
}

// Data
$type = sanitize_text_field( $_POST['type'] ?? '' );
$route_id = (int) ( $_POST['route-id'] ?? '' );
$ride_id = (int) ( $_POST['ride-id'] ?? '' );

// Route
$route = get_route_by( 'ID', $route_id );
$ride = get_route_by( 'ID', $ride_id );

if ( ! in_array( $type, [ 'start', 'return' ] ) || empty( $route->ID ) || empty( $ride->ID ) ) {
    av_send_ops_json( __( 'Rota inválida' ) );
}

if ( (int) $route->userId !== (int) $user->ID ) {
    av_send_ops_json( __( 'Essa rota não pertence ao usuário logado' ) );
}

if ( (int) $ride->userId === (int) $user->ID ) {
    av_send_ops_json( __( 'Você não pode enviar um convite para você mesmo' ) );
}

if ( $route->isDriver === $ride->isDriver ) {
    av_send_ops_json( __( 'Apenas um dos colegas pode ser motorista' ) );
}

// Send invitation
$invitation = new Invitation( $type, $route, $ride );

try {
    $result = $invitation->send();

    if ( $result ) {
        $response = array(
            'title'     => __( 'Convite enviado' ),
            'message'   => __( 'Agora é só esperar o usuário responder' ),
        );

        av_send_json( $response, 'success' );
    }
} catch (Exception $e) {
    error_log( 'Invitation Error: ' . $e->getMessage() );
}

av_send_ops_json( __( 'Não foi possível enviar o convite. Tente novamente.' ) );

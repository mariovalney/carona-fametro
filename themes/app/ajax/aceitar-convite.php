<?php

use Avant\Modules\Ride;
use Avant\Modules\Invitation;

$section = create_google_section( false );

if ( ! $section  ) {
    av_send_ops_json( __( 'Você precisa fazer login para aceitar um convite.' ) );
}

$user = get_logged_user();

if ( empty( $user ) ) {
    av_send_ops_json( __( 'Usuário inválido. Faça login e tente novamente' ) );
}

// Data
$invite_id = (int) ( $_POST['invite-id'] ?? '' );

// Route
$invite = get_invite_by( 'ID', $invite_id );

if ( $invite->isAccepted ) {
    av_send_ops_json( __( 'Esse convite já foi aceito' ) );
}

if ( empty( $invite->ride ) ) {
    av_send_ops_json( __( 'Não foi possível aceitar o convite' ) );
}

$ride = json_decode( $invite->ride );

if ( (int) $ride->userId !== (int) $user->ID ) {
    av_send_ops_json( __( 'Essa rota não pertence ao usuário logado' ) );
}

// Accept invitation
$invitation = new Invitation( $invite );

try {
    $result = $invitation->accept();

    if ( $result ) {
        $response = array(
            'title'     => __( 'Convite aceito' ),
            'message'   => __( 'Agora é só esperar o seu colega entrar em contato' ),
        );

        av_send_json( $response, 'success' );
    }
} catch (Exception $e) {
    error_log( 'Invitation Error: ' . $e->getMessage() );
}

av_send_ops_json( __( 'Não foi possível aceitar o convite. Tente novamente.' ) );


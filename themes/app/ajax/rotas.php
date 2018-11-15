<?php

$section = create_google_section( false );

if ( ! $section  ) {
    av_send_ops_json( __( 'Você precisa fazer login para alterar uma rota.' ) );
}

$user = get_logged_user();

if ( empty( $user ) ) {
    av_send_ops_json( __( 'Usuário inválido. Faça login e tente novamente' ) );
}

$id = sanitize_text_field( $_POST['id'] );
$userId = sanitize_text_field( $_POST['user-id'] );
$startLat = sanitize_text_field( $_POST['start-lat'] );
$startLng = sanitize_text_field( $_POST['start-lng'] );
$endLat = sanitize_text_field( $_POST['end-lat'] );
$endLng = sanitize_text_field( $_POST['end-lng'] );
$startTime = sanitize_text_field( $_POST['start-time'] );
$returnTime = sanitize_text_field( $_POST['return-time'] );
$campusName = sanitize_text_field( $_POST['campus-name'] );
$isDriver = sanitize_text_field( $_POST['is-driver'] );
$dow = sanitize_text_field( $_POST['dow'] );

$isDriver = ( empty( $isDriver ) ) ? 0 : 1;

// Validate Data
if ( $userId != $user->ID ) {
    av_send_ops_json( __( 'Usuário inválido. Faça login e tente novamente' ) );
}

if ( empty( $startLat ) || empty( $startLng ) || empty( $endLat ) || empty( $endLng ) ) {
    av_send_ops_json( __( 'Pontos de saída e entrega inválidos. Tente novamente.' ) );
}

if ( empty( $startTime ) || empty( $returnTime ) ) {
    av_send_ops_json( __( 'Horários de saída inválidos. Tente novamente.' ) );
}

if ( ! \Avant\Modules\Entities\Route::is_valid_campus( $campusName ) ) {
    av_send_ops_json( __( 'Campus inválido. Tente novamente.' ) );
}

if ( $dow < 0 || $dow > 6 ) {
    av_send_ops_json( __( 'Dia da semana inválido. Tente novamente.' ) );
}

// Route
$route = \Avant\Modules\Entities\Route::get_instance( $id );

if ( empty( $route ) ) {
    $route = (object) [];
}

$route->userId = $userId;
$route->startLat = $startLat;
$route->startLng = $startLng;
$route->endLat = $endLat;
$route->endLng = $endLng;
$route->startTime = $startTime;
$route->returnTime = $returnTime;
$route->campusName = $campusName;
$route->isDriver = $isDriver;
$route->dow = $dow;


// Save
$route = new \Avant\Modules\Entities\Route( $route );
$result = $route->save();

if ( ! empty( $route->ID ) ) {
    $route->ID = $result;

    av_send_json( array(
        'title'     => __( 'Rota salva!' ),
        'message'   => '',
        'route'     => $route,
    ), 'success' );
}

av_send_ops_json( __( 'Não conseguimos salvar sua rota. Tente novamente.' ) );
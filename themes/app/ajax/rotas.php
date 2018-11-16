<?php

$section = create_google_section( false );

if ( ! $section  ) {
    av_send_ops_json( __( 'Você precisa fazer login para alterar uma rota.' ) );
}

$user = get_logged_user();

if ( empty( $user ) ) {
    av_send_ops_json( __( 'Usuário inválido. Faça login e tente novamente' ) );
}

// All Routes?
$allRoutes = $_POST['all-routes'] ?? '';
$allRoutes = ( empty( $allRoutes ) ) ? false : true;

// Data
$id = sanitize_text_field( $_POST['id'] );
$userId = sanitize_text_field( $_POST['user-id'] );
$startLat = sanitize_text_field( $_POST['start-lat'] );
$startLng = sanitize_text_field( $_POST['start-lng'] );
$returnLat = sanitize_text_field( $_POST['return-lat'] );
$returnLng = sanitize_text_field( $_POST['return-lng'] );
$startTime = sanitize_text_field( $_POST['start-time'] );
$returnTime = sanitize_text_field( $_POST['return-time'] );
$startPlace = sanitize_text_field( $_POST['start-place'] );
$returnPlace = sanitize_text_field( $_POST['return-place'] );
$campusName = sanitize_text_field( $_POST['campus-name'] );
$dow = sanitize_text_field( $_POST['dow'] );

$isDriver = $_POST['is-driver'] ?? '';
$isDriver = ( empty( $isDriver ) ) ? 0 : 1;

// Validate Data
if ( $userId != $user->ID ) {
    av_send_ops_json( __( 'Usuário inválido. Faça login e tente novamente' ) );
}

if ( empty( $startLat ) || empty( $startLng ) || empty( $returnLat ) || empty( $returnLng ) ) {
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
$route->returnLat = $returnLat;
$route->returnLng = $returnLng;
$route->startTime = $startTime;
$route->returnTime = $returnTime;
$route->startPlace = $startPlace;
$route->returnPlace = $returnPlace;
$route->campusName = $campusName;
$route->isDriver = $isDriver;
$route->dow = $dow;

// Save
if ( ! $allRoutes ) {
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
}

// All Routes
delete_routes_by( 'userId', $user->ID );

$success = 0;
for ($i = 0; $i < 7; $i++) {
    unset( $route->ID );

    $route = new \Avant\Modules\Entities\Route( $route );
    $route->dow = $i;

    if ( $route->save() ) {
        $success++;
    }
}

if ( empty( $success ) ) {
    av_send_ops_json( __( 'Não conseguimos salvar suas rotas. Tente novamente.' ) );
}

if ( $success < 7 ) {
    av_send_json( array(
        'title'     => __( 'Rota salva!' ),
        'message'   => __( 'Salvamos sua rota, mas houveram alguns erros. Por favor, verifique se todas as rotas foram atualizadas.' ),
        'route'     => 'all',
        'status'    => 'warning'
    ) );
}

av_send_json( array(
    'title'     => __( 'Rotas atualizadas!' ),
    'message'   => '',
    'route'     => 'all',
), 'success' );

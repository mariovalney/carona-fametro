<?php

use Avant\Modules\Phpgeo;
use Avant\Modules\Ride;

// $section = create_google_section( false );

// if ( ! $section  ) {
//     av_send_ops_json( __( 'Você precisa fazer login para buscar uma carona.' ) );
// }

$user = get_logged_user();

if ( empty( $user ) ) {
    av_send_ops_json( __( 'Usuário inválido. Faça login e tente novamente' ) );
}

// Route
$route_id = (int) ( $_POST['route'] ?? '' );
$route_id = 2; // DEBUG
// $route_id = 4; // DEBUG - Maracanau

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

?>
    <script id="__bs_script__">//<![CDATA[
        document.write("<script async src='/browser-sync/browser-sync-client.js?v=2.26.3'><\/script>".replace("HOST", location.hostname));
    //]]></script>
    <pre>
<?php

// Get Rides
$rides = new Ride( $route );
// $rides = $rides->getRidesFromStart();

$response['going'] = $rides->getRidesFromStart();
$response['returning'] = $rides->getRidesFromReturn();

av_send_json( $response );
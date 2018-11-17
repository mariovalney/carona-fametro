<?php

global $avant;

if ( ! session_id() ) session_start();

$google = new \Avant\Modules\Google();
$google = $google->login();

// Check for parameters
$subpage = ( $avant['query_params'][0] ) ?? '';

// Process Logout
if ( $subpage === 'logout' ) {
    unset( $_SESSION['id_token_token'] );

    $google->revokeToken();
    session_destroy();

    header( 'Location: ' . BASE_URL );
    return;
}

// Process Code
if ( isset( $_GET['code'] ) ) {
    $token = $google->fetchAccessTokenWithAuthCode( $_GET['code'] );

    // Store in the session also
    $_SESSION['id_token_token'] = $token;

    header( 'Location: ' . BASE_URL . 'entrar' );
    return;
}

// If we have token, go to profile
if ( ! empty( $_SESSION['id_token_token'] ) && isset( $_SESSION['id_token_token']['id_token'] ) ) {

    // Check user can login
    $google->setAccessToken( $_SESSION['id_token_token'] );

    $token_info = $google->verifyIdToken();
    $email = $token_info['email'] ?? '';

    if ( empty( $email ) ) {
        unset( $_SESSION['id_token_token'] );
        header( 'Location: ' . BASE_URL . 'entrar' );
        exit;
    }

    if ( empty( $token_info['email_verified'] ) || ! preg_match( '/^.*@(?:[a-z]*\.)*fametro.com.br$/', $email ) ) {
        unset( $_SESSION['id_token_token'] );

        error_log( 'Invalid Login: ' . $email );
        error_log( print_r( $token_info, true ) );

        header( 'Location: ' . BASE_URL . 'entrar/0' );
        return;
    }

    header( 'Location: ' . BASE_URL . 'rotas' );
    return;
}

/**
 * If is page/0
 * Restrict Access info
 */
if ( $subpage === '0' ) {
    include_header(); ?>

    <section class="pb_section pb_section_with_padding">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-md-6 text-center mb-5">
                    <h5 class="text-uppercase pb_font-15 mb-2 pb_color-dark-opacity-3 pb_letter-spacing-2">
                        <strong><?php _e( 'Ops...', VZR_TEXTDOMAIN ); ?></strong>
                    </h5>
                    <h2><?php _e( 'Acesso Restrito', VZR_TEXTDOMAIN ); ?></h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-xs-12 text-center">
                    <p><?php _e( 'Apenas alunos da Fametro podem utilizar o sistema. Para isso você precisa ter um e-mail institucional válido.', VZR_TEXTDOMAIN ); ?></p>
                    <p><?php
                            printf(
                                __( 'Caso tenha um e-mail válido, tente %s e escolha o e-mail da Fametro na tela de seleção do Google.', VZR_TEXTDOMAIN ),
                                '<a href="/sair">' . __( 'entrar novamente', VZR_TEXTDOMAIN ) . '</a>'
                            );
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <?php include_footer();
    exit;
}

/**
 * If nothing to do, go to authentication URL
 */

header( 'Location: ' . $google->createAuthUrl() );
exit;
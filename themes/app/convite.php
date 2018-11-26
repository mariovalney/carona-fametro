<?php
    global $avant;

    create_google_section();

    // User
    $user = get_logged_user();
    if ( empty( $user ) ) av_redirect();

    // Check for invite parameter
    $invite = ( $avant['query_params'][0] ) ?? '';
    $invite = get_invite_by( 'ID', $invite );

    if ( empty( $invite ) ) av_redirect();

    // Check user
    $ride = json_decode( $invite->ride );
    $route = json_decode( $invite->route );

    if ( empty( $ride ) || empty( $route ) ) av_redirect();
    if ( (int) $route->userId !== (int) $user->ID && (int) $ride->userId !== (int) $user->ID ) av_redirect();

    // Classmate
    $classmate_user = ( $route->userId == $user->ID ) ? $ride->userId : $route->userId;
    $classmate_user = ( ! empty( $route->userId ) ) ? get_user_by( 'ID', $classmate_user ) : '';
    if ( empty( $classmate_user ) ) av_redirect();

    include_header();
?>

    <section class="pb_section pb_section_with_padding">
        <div class="container">
            <?php if ( $invite->isAccepted ) : ?>

                <div class="row justify-content-center mb-5">
                    <div class="col-md-6 text-center mb-5">
                        <h2><?php _e( 'Convite Aceito', VZR_TEXTDOMAIN ); ?></h2>
                        <p><?php _e( 'Agora é só entrar em contato com o seu colega', VZR_TEXTDOMAIN ); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <p><?php _e( 'Confira abaixo os dados do seu colega e entre em contato:', VZR_TEXTDOMAIN ); ?></p>
                        <div class="ride-user-wrapper">
                            <img src="<?php echo get_user_avatar( $classmate_user->ID ); ?>">
                            <div>
                                <p class="name"><?php echo $classmate_user->displayName ?></p>
                                <p class="bio"><?php echo nl2br( $classmate_user->bio ) ?></p>
                                <p class="mail"><?php echo $classmate_user->email ?></p>
                                <p class="phone"><?php echo $classmate_user->phone ?></p>
                            </div>
                        </div>
                        <p><?php _e( 'Confira a rota proposta:', VZR_TEXTDOMAIN ); ?></p>
                        <div id="map-invite" class="map-routes"
                            data-route-lat="<?php echo ( $invite->type == 'start' ) ? $route->startLat : $route->returnLat ?>"
                            data-route-lng="<?php echo ( $invite->type == 'start' ) ? $route->startLng : $route->returnLng ?>"
                            data-route-campus="<?php echo $route->campusName ?>"
                            data-ride-lat="<?php echo ( $invite->type == 'start' ) ? $ride->startLat : $ride->returnLat ?>"
                            data-ride-lng="<?php echo ( $invite->type == 'start' ) ? $ride->startLng : $ride->returnLng ?>"
                            data-ride-campus="<?php echo $ride->campusName ?>"
                        ></div>
                        <div class="show-invite-route-panel">
                            <a href="#">
                                <?php _e( 'Mostrar/esconder descrição', VZR_TEXTDOMAIN ); ?>
                            </a>
                        </div>
                        <div id="invite-route-panel"></div>
                    </div>
                </div>

            <?php else : ?>

                <div class="row justify-content-center mb-5">
                    <div class="col-md-6 text-center mb-5">
                        <h2><?php _e( 'Convite', VZR_TEXTDOMAIN ); ?></h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php if ( $ride->userId == $user->ID ) : ?>
                            <p><?php _e( 'Confira abaixo a rota sugerida e algumas informações sobre seu colega:', VZR_TEXTDOMAIN ); ?></p>
                            <div id="map-invite" class="map-routes"
                                data-route-lat="<?php echo ( $invite->type == 'start' ) ? $route->startLat : $route->returnLat ?>"
                                data-route-lng="<?php echo ( $invite->type == 'start' ) ? $route->startLng : $route->returnLng ?>"
                                data-route-campus="<?php echo $route->campusName ?>"
                                data-ride-lat="<?php echo ( $invite->type == 'start' ) ? $ride->startLat : $ride->returnLat ?>"
                                data-ride-lng="<?php echo ( $invite->type == 'start' ) ? $ride->startLng : $ride->returnLng ?>"
                                data-ride-campus="<?php echo $ride->campusName ?>"
                            ></div>
                            <div class="show-invite-route-panel">
                                <a href="#">
                                    <?php _e( 'Mostrar/esconder descrição', VZR_TEXTDOMAIN ); ?>
                                </a>
                            </div>
                            <div id="invite-route-panel"></div>
                            <div class="ride-user-wrapper only-bio">
                                <img src="<?php echo get_user_avatar( $classmate_user->ID ); ?>">
                                <div>
                                    <p class="name"><?php echo $classmate_user->displayName ?></p>
                                    <p class="bio"><?php echo nl2br( $classmate_user->bio ) ?></p>
                                </div>
                            </div>
                            <form action="/ajax/aceitar-convite" method="POST" class="invite-form ajax-form">
                                <input type="hidden" name="invite-id" value="<?php echo $invite->ID; ?>">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary btn-lg btn-block pb_btn-pill btn-shadow-blue" value="<?php _e( 'Aceitar convite', VZR_TEXTDOMAIN ); ?>">
                                </div>
                            </form>
                        <?php else : ?>
                            <p><?php _e( 'Seu colega ainda não respondeu ao seu convite.', VZR_TEXTDOMAIN ); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

            <?php endif; ?>
        </div>
    </section>

<?php
    include_footer();
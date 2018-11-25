<?php
    create_google_section();

    $user = get_logged_user();

    if ( empty( $user ) ) {
        av_redirect();
    }

    include_header();

    $week = array(
        0 => __( 'Domingo', VZR_TEXTDOMAIN ),
        1 => __( 'Segunda-feira', VZR_TEXTDOMAIN ),
        2 => __( 'Terça-feira', VZR_TEXTDOMAIN ),
        3 => __( 'Quarta-feira', VZR_TEXTDOMAIN ),
        4 => __( 'Quinta-feira', VZR_TEXTDOMAIN ),
        5 => __( 'Sexta-feira', VZR_TEXTDOMAIN ),
        6 => __( 'Sábado', VZR_TEXTDOMAIN ),
    );

    /**
     * Ordening the week from today to last day
     * We should sum arrays to keep keys
     */
    $current_dow = date( 'w' );
    $week = array_slice( $week, $current_dow, ( 7 - $current_dow ), true ) + array_slice( $week, 0, $current_dow, true );

    // Retrieving and ordering Routes
    $routes = get_routes_by( 'userId', $user->ID );
    if ( ! empty( $routes ) ) {
        $routes_from_database = $routes;
        $routes = [];

        foreach ( $routes_from_database as $route ) {
            $routes[ $route->dow ] = $route;
        }
    }
?>

    <section class="pb_section pb_section_with_padding">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-md-6 text-center mb-5">
                    <h2><?php _e( 'Caronas', VZR_TEXTDOMAIN ); ?></h2>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div id="rides-list" class="pb_accordion">
                        <?php foreach ( $week as $dow => $name ) : $route = $routes[ $dow ] ?? []; ?>
                            <div class="item" data-route-id="<?php echo $route->ID ?? 0; ?>">
                                <span class="item-title pb_font-22 py-4">
                                    <?php
                                        echo $name;

                                        if ( ! empty( $route->isDriver ) ) :
                                    ?>

                                        <span>
                                            <i class="ion-android-car"></i>
                                            <?php _e( 'Motorista', VZR_TEXTDOMAIN ); ?>
                                        </span>

                                    <?php endif; ?>
                                </span>
                                <div class="collapse show" role="tabpanel">
                                    <div class="py-3">
                                        <?php if ( ! empty( $route ) ) : ?>
                                            <div class="route-going"
                                                data-route-id="<?php echo $route->ID; ?>"
                                                data-route-is-driver="<?php echo $route->isDriver ? '1' : '0'; ?>"
                                                data-route-point="<?php echo $route->startLat . ',' . $route->startLng; ?>"
                                                data-route-campus="<?php echo $route->campusName;?>"
                                            >
                                                <p class="route-description">
                                                    <?php
                                                        printf(
                                                            __( '%s Saindo às %s de %s até o campus %s.', VZR_TEXTDOMAIN ),
                                                            '<span>' . __( 'IDA', VZR_TEXTDOMAIN ) . ':</span>',
                                                            $route->startTime,
                                                            $route->startPlace,
                                                            $route->campusName
                                                        );
                                                    ?>
                                                </p>
                                                <div class="available-rides">
                                                    <p>
                                                        <?php
                                                            if ( $route->isDriver ) {
                                                                _e( 'Colegas pedindo carona:', VZR_TEXTDOMAIN );
                                                            } else {
                                                                _e( 'Colegas oferecendo carona:', VZR_TEXTDOMAIN );
                                                            }
                                                        ?>
                                                    </p>
                                                    <ul class="list-group"></ul>
                                                </div>
                                            </div>
                                            <div class="route-returning"
                                                data-route-id="<?php echo $route->ID; ?>"
                                                data-route-is-driver="<?php echo $route->isDriver ? '1' : '0'; ?>"
                                                data-route-point="<?php echo $route->returnLat . ',' . $route->returnLng; ?>"
                                                data-route-campus="<?php echo $route->campusName;?>"
                                            >
                                                <p class="route-description">
                                                    <?php
                                                        printf(
                                                            __( '%s Saindo às %s do campus %s para %s.', VZR_TEXTDOMAIN ),
                                                            '<span>' . __( 'VOLTA', VZR_TEXTDOMAIN ) . ':</span>',
                                                            $route->returnTime,
                                                            $route->campusName,
                                                            $route->returnPlace
                                                        );
                                                    ?>
                                                </p>
                                                <div class="available-rides">
                                                    <p>
                                                        <?php
                                                            if ( $route->isDriver ) {
                                                                _e( 'Colegas pedindo carona:', VZR_TEXTDOMAIN );
                                                            } else {
                                                                _e( 'Colegas oferecendo carona:', VZR_TEXTDOMAIN );
                                                            }
                                                        ?>
                                                    </p>
                                                    <ul class="list-group"></ul>
                                                </div>
                                            </div>
                                        <?php else : ?>
                                            <p>
                                                <?php _e( 'Nenhuma rota cadastrada para esse dia.', VZR_TEXTDOMAIN ); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade modal-routes" id="modal-rides" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="/ajax/convite" method="POST" class="ajax-form validate-form">
                    <input name="user-id" type="hidden" value="<?php echo $user->ID; ?>" required>
                    <input name="invited-user-id" type="hidden" value="" required>
                    <input name="route-id" type="hidden" value="" required>

                    <div class="modal-header">
                        <h5 class="modal-title">
                            <?php _e( 'Carona', VZR_TEXTDOMAIN ); ?>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="<?php _e( 'Fechar', VZR_TEXTDOMAIN ); ?>">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12">
                                <div id="map-rides" class="map-rides"></div>
                                <p class="route-description"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <p><?php _e( 'Dados do colega:', VZR_TEXTDOMAIN ); ?></p>
                                <div class="avatar-wrapper">
                                    <div class="avatar"></div>
                                    <div class="invited-user-name"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <p><?php _e( 'Confira seu desvio:', VZR_TEXTDOMAIN ); ?></p>
                                <div id="modal-rides-panel"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-lg btn-block btn-submit-invite pb_btn-pill btn-shadow-blue"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
    include_footer();
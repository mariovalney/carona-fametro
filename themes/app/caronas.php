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
                                            <div class="route-going">
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
                                            <div class="route-returning">
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

<?php
    include_footer();
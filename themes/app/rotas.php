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
?>

    <section class="pb_section pb_section_with_padding">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-md-6 text-center mb-5">
                    <h2><?php printf( __( 'Olá, %s!', VZR_TEXTDOMAIN ), $user->firstName ); ?></h2>
                    <p><?php _e( 'Aqui você pode escolher as suas rotas.', VZR_TEXTDOMAIN ); ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div id="routes" class="pb_accordion" data-children=".item">
                        <?php foreach ( $week as $dow => $name ) : ?>
                            <div class="item">
                                <a data-toggle="collapse" data-parent="#routes" href="#routes<?php echo $dow; ?>" aria-expanded="false" aria-controls="routes<?php echo $dow; ?>" class="pb_font-22 py-4">
                                    <?php echo $name; ?>
                                </a>
                                <div id="routes<?php echo $dow; ?>" class="<?php echo ( $dow == $current_dow ) ? 'collapse show' : 'collapse'; ?>" role="tabpanel">
                                    <div class="py-3">
                                        <?php _e( 'Nenhuma rota cadastrada para esse dia.', VZR_TEXTDOMAIN ); ?>
                                        <a href="#" data-toggle="modal" data-target="#modal-route-<?php echo $dow; ?>"><?php _e( 'Adicionar ?', VZR_TEXTDOMAIN ); ?></a>
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
    $routes = get_routes_by( 'userId', $user->ID );
    if ( ! empty( $routes ) ) {
        $routes_from_database = $routes;
        $routes = [];

        foreach ( $routes_from_database as $route ) {
            $routes[ $route->dow ] = $route;
        }
    }

    for ( $i = 0; $i < 7; $i++ ) :
        $modalId = 'modal-route-' . $i;
        $route = $routes[ $i ] ?? [];

        if ( empty( $route ) ) {
            $route = (object) array(
                'ID'            => 0,
                'userId'        => $user->ID,
                'startLat'      => '',
                'startLng'      => '',
                'endLat'        => '',
                'endLng'        => '',
                'startTime'     => '07:00',
                'returnTime'    => '10:20',
                'campusName'    => get_default_campus(),
                'isDriver'      => 0,
                'dow'           => $i,
            );
        }
?>

    <div class="modal fade modal-routes" id="<?php echo $modalId; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="/ajax/rotas" method="POST" class="ajax-form validate-form">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <?php echo $week[ $i ]; ?>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="<?php _e( 'Fechar', VZR_TEXTDOMAIN ); ?>">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input name="id" type="text" value="<?php echo $route->ID; ?>" required>
                        <input name="user-id" type="text" value="<?php echo $route->userId; ?>" required>
                        <input name="start-lat" type="text" value="<?php echo $route->startLat; ?>" required>
                        <input name="start-lng" type="text" value="<?php echo $route->startLng; ?>" required>
                        <input name="end-lat" type="text" value="<?php echo $route->endLat; ?>" required>
                        <input name="end-lng" type="text" value="<?php echo $route->endLng; ?>" required>
                        <input name="start-time" type="text" value="<?php echo $route->startTime; ?>" required>
                        <input name="return-time" type="text" value="<?php echo $route->returnTime; ?>" required>
                        <input name="campus-name" type="text" value="<?php echo $route->campusName; ?>" required>
                        <input name="is-driver" type="text" value="<?php echo $route->isDriver; ?>" required>
                        <input name="dow" type="text" value="<?php echo $route->dow; ?>" required>

                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-lg btn-block pb_btn-pill btn-shadow-blue" value="<?php _e( 'Atualizar', VZR_TEXTDOMAIN ); ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <?php _e( 'Salvar', VZR_TEXTDOMAIN ); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
    endfor;

    include_footer();
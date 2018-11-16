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
                                        <?php if ( ! empty( $routes[ $dow ] ) ) : $route = $routes[ $dow ]; ?>
                                            <p>
                                                <?php
                                                    printf(
                                                        __( 'Saindo às %s de %s e retornando às %s do campus %s para %s.', VZR_TEXTDOMAIN ),
                                                        $route->startTime,
                                                        $route->startPlace,
                                                        $route->returnTime,
                                                        $route->campusName,
                                                        $route->returnPlace
                                                    );
                                                ?>
                                                <a href="#" data-toggle="modal" data-target="#modal-route-<?php echo $dow; ?>">
                                                    <?php _e( '(Alterar)', VZR_TEXTDOMAIN ); ?>
                                                </a>
                                            </p>
                                        <?php else : ?>
                                            <p>
                                                <?php _e( 'Nenhuma rota cadastrada para esse dia.', VZR_TEXTDOMAIN ); ?>
                                                <a href="#" data-toggle="modal" data-target="#modal-route-<?php echo $dow; ?>"><?php _e( 'Adicionar ?', VZR_TEXTDOMAIN ); ?></a>
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
    for ( $i = 0; $i < 7; $i++ ) :
        $modalId = 'modal-route-' . $i;
        $route = $routes[ $i ] ?? [];

        if ( empty( $route ) ) {
            $route = (object) array(
                'ID'            => 0,
                'userId'        => $user->ID,
                'startLat'      => '',
                'startLng'      => '',
                'returnLat'     => '',
                'returnLng'     => '',
                'startPlace'    => '',
                'returnPlace'   => '',
                'startTime'     => '07:00',
                'returnTime'    => '10:20',
                'campusName'    => get_default_campus(),
                'isDriver'      => 0,
                'dow'           => $i,
            );
        }
?>

    <div class="modal fade modal-routes" id="<?php echo $modalId; ?>" data-dow="<?php echo $i; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="/ajax/rotas" method="POST" class="ajax-form validate-form">
                    <input name="id" type="hidden" value="<?php echo $route->ID; ?>" required>
                    <input name="user-id" type="hidden" value="<?php echo $route->userId; ?>" required>
                    <input name="start-lat" type="hidden" value="<?php echo $route->startLat; ?>" required>
                    <input name="start-lng" type="hidden" value="<?php echo $route->startLng; ?>" required>
                    <input name="return-lat" type="hidden" value="<?php echo $route->returnLat; ?>" required>
                    <input name="return-lng" type="hidden" value="<?php echo $route->returnLng; ?>" required>
                    <input name="dow" type="hidden" value="<?php echo $route->dow; ?>" required>

                    <div class="modal-header">
                        <h5 class="modal-title">
                            <?php echo $week[ $i ]; ?>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="<?php _e( 'Fechar', VZR_TEXTDOMAIN ); ?>">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-8 col-md-8">
                                <p><?php _e( 'Escolha seus pontos de partida e retorno:', VZR_TEXTDOMAIN ); ?></p>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4 is-driver-switch-wrapper">
                                <label class="switch">
                                    <input name="is-driver" type="checkbox" value="1" <?php checked( $route->isDriver, '1' ); ?>>
                                    <span class="slider round"></span>
                                    <span class="label">
                                        <?php _e( 'Sou motorista', VZR_TEXTDOMAIN ); ?>
                                    </span>
                                </label>
                            </div>
                            <div class="col-xs-12 col-sm-12">
                                <div id="map-routes-<?php echo $i; ?>" class="map-routes"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 form-group">
                                <label><?php _e( 'Ponto de Partida:', VZR_TEXTDOMAIN ); ?></label>
                                <input name="start-place" class="form-control" type="text" value="<?php echo $route->startPlace; ?>" required>
                                <a class="find-marker btn btn-primary" href="#" data-input-type="start"><?php _e( 'Procurar' ); ?></a>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 form-group">
                                <label><?php _e( 'Ponto de Partida:', VZR_TEXTDOMAIN ); ?></label>
                                <input name="return-place" class="form-control" type="text" value="<?php echo $route->returnPlace; ?>" required>
                                <a class="find-marker btn btn-primary" href="#" data-input-type="return"><?php _e( 'Procurar' ); ?></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-4 form-group">
                                <label><?php _e( 'Seu Campus:', VZR_TEXTDOMAIN ); ?></label>
                                <select class="form-control" name="campus-name">
                                    <?php foreach ( get_campus_for_options() as $campus ): ?>
                                        <option value="<?php echo $campus; ?>" <?php selected( $campus, $route->campusName ); ?>>
                                            <?php echo $campus; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 form-group">
                                <label><?php _e( 'Horário de Partida', VZR_TEXTDOMAIN ); ?></label>
                                <input class="form-control mask-time" name="start-time" type="text" value="<?php echo $route->startTime; ?>" required>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 form-group">
                                <label><?php _e( 'Horário de Retorno', VZR_TEXTDOMAIN ); ?></label>
                                <input class="form-control mask-time" name="return-time" type="text" value="<?php echo $route->returnTime; ?>" required>
                            </div>
                        </div>
                        <!-- TODO: Add remove route -->
                        <div class="all-routes">
                            <label class="switch small right">
                                <input name="all-routes" type="checkbox" value="1">
                                <span class="slider round"></span>
                                <span class="label">
                                    <?php _e( 'Todos os dias?', VZR_TEXTDOMAIN ); ?>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-lg btn-block pb_btn-pill btn-shadow-blue">
                            <?php _e( 'Atualizar', VZR_TEXTDOMAIN ); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
    endfor;

    include_footer();
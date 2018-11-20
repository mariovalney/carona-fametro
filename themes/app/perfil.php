<?php
    create_google_section();

    $user = get_logged_user();

    if ( empty( $user ) ) {
        av_redirect();
    }

    include_header();
?>

    <section class="pb_section pb_section_with_padding">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-md-6 text-center mb-5">
                    <img src="<?php echo get_user_avatar( $user->ID ); ?>" class="avatar">
                    <h2><?php printf( __( 'Olá, %s!', VZR_TEXTDOMAIN ), $user->firstName ); ?></h2>
                </div>
            </div>
            <div class="row justify-content-center margin-bottom">
                <div class="col-xs-12 text-center">
                    <p><?php _e( 'Bem-vindo ao seu perfil.', VZR_TEXTDOMAIN ); ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <form action="/ajax/perfil" method="POST" class="ajax-form bg-white rounded validate-form">
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    <label><?php _e( 'Nome:', VZR_TEXTDOMAIN ); ?></label>
                                    <input name="first-name" type="text" class="form-control py-3 reverse" value="<?php echo $user->firstName; ?>" required>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    <label><?php _e( 'Sobrenome:', VZR_TEXTDOMAIN ); ?></label>
                                    <input name="last-name" type="text" class="form-control py-3 reverse" value="<?php echo $user->lastName; ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    <label><?php _e( 'E-mail Institucional:', VZR_TEXTDOMAIN ); ?></label>
                                    <input name="email" type="text" class="form-control py-3 reverse" value="<?php echo $user->email; ?>" disabled required>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    <label><?php _e( 'Telefone:', VZR_TEXTDOMAIN ); ?></label>
                                    <input name="phone" type="text" class="form-control py-3 reverse mask-phone" value="<?php echo $user->phone; ?>" placeholder="<?php _e( '(85) 9 9999-9999', VZR_TEXTDOMAIN ); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <div class="form-group">
                                    <label><?php _e( 'Biografia:', VZR_TEXTDOMAIN ); ?></label>
                                    <textarea name="bio" class="form-control py-3 reverse" rows="6" placeholder="<?php _e( 'Fale um pouco sobre você', VZR_TEXTDOMAIN ); ?>" required><?php echo $user->bio; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-lg btn-block pb_btn-pill btn-shadow-blue" value="<?php _e( 'Atualizar', VZR_TEXTDOMAIN ); ?>">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php include_footer();
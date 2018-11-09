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
                    <img src="<?php echo $user['avatar']; ?>" class="avatar">
                    <h2><?php printf( __( 'Olá, %s!', VZR_TEXTDOMAIN ), $user['first_name'] ); ?></h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-xs-12 text-center">
                    <p><?php _e( 'Bem-vindo ao seu perfil.', VZR_TEXTDOMAIN ); ?></p>
                </div>
            </div>
        </div>
    </section>

<?php include_footer();
<?php

$section = create_google_section( false );

if ( ! $section  ) {
    av_send_ops_json( __( 'Você precisa fazer login para alterar seu perfil.' ) );
}

$user = get_logged_user();

if ( empty( $user ) ) {
    av_send_ops_json( __( 'Usuário inválido. Faça login e tente novamente' ) );
}

// Data
$firstName = sanitize_text_field( $_POST['first-name'] ?? '' );
$lastName = sanitize_text_field( $_POST['last-name'] ?? '' );
$bio = sanitize_textarea_field( $_POST['bio'] ?? '' );
$phone = sanitize_textarea_field( $_POST['phone'] ?? '' );

if ( empty( $firstName ) || empty( $lastName ) || empty( $bio ) || empty( $phone ) ) {
    av_send_ops_json( __( 'Dados inválidos. Tente novamente.' ) );
}

$nothing_changed = (
    ( $user->firstName == $firstName ) &&
    ( $user->lastName == $lastName ) &&
    ( $user->bio == $bio ) &&
    ( $user->phone == $phone )
);

if ( $nothing_changed ) {
    $result = true;
} else {
    $user->firstName = $firstName;
    $user->lastName = $lastName;
    $user->bio = $bio;
    $user->phone = $phone;

    $result = $user->save();
}

if ( ! empty( $result ) ) {
    av_send_json( array(
        'title'     => __( 'Perfil salvo' ),
        'message'   => '',
    ), 'success' );
}

av_send_ops_json( __( 'Não conseguimos salvar seu perfil.' . "\n" . 'Você alterou algo?' ) );
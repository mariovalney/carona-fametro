<?php

$name = sanitize_text_field( ( $_POST['contact-name'] ?? '' ) );
$email = sanitize_mail_field( ( $_POST['contact-email'] ?? '' ) );
$message = sanitize_textarea_field( ( $_POST['contact-message'] ?? '' ) );

if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
    av_send_json( __( 'Dados inválidos. Tente novamente.' ), 'error' );
}

$body = 'Nome: ' . $name . "\n";
$body .= 'E-mail: ' . $email . "\n\n";
$body .= nl2br( $message );

$headers = 'Reply-To: ' . $email;

try {
    $sender = new \Avant\Modules\Sender();
    $result = $sender->send( false, 'Carona Fametro - Contato do ' . $name, $body, $headers );
} catch (Exception $e) {
    av_send_json( $e->getMessage(), 'error' );
}

if ( $result ) {
    av_send_json( array(
        'title'     => __( 'Tudo certo!' ),
        'message'   => __( 'Entraremos em contato assim que possível.' ),
    ), 'success' );
}

av_send_json( __( 'Não conseguimos salvar seu contato. Tente novamente.' ), 'error' );


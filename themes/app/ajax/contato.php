<?php

$response = array(
    'title'     => __( 'Ops' ),
    'message'   => __( 'Não conseguimos salvar seu contato. Tente novamente.' ),
    'status'    => 'error',
);

$name = sanitize_text_field( ( $_POST['contact-name'] ?? '' ) );
$email = sanitize_mail_field( ( $_POST['contact-email'] ?? '' ) );
$message = sanitize_textarea_field( ( $_POST['contact-message'] ?? '' ) );

if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
    echo json_encode( $response );
    exit;
}

$body = 'Nome: ' . $name . "\n";
$body .= 'E-mail: ' . $email . "\n\n";
$body .= nl2br( $message );

$headers = 'Reply-To: ' . $email;

try {
    $sender = new \Avant\Modules\Sender();
    $result = $sender->send( false, 'Carona Fametro - Contato do ' . $name, $body, $headers );
} catch (Exception $e) {
    $result = false;
    $response['message'] = $e->getMessage();
}

if ( $result ) {
    $response = array(
        'title'     => __( 'Tudo certo!' ),
        'message'   => __( 'Entraremos em contato assim que possível.' ),
        'status'    => 'success',
    );
}

echo json_encode( $response );
exit;


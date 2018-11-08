<?php
    session_start();

    $google = new \Avant\Modules\Google();
    $google = $google->login( $_SESSION['id_token_token'] );

    $token_data = $google->verifyIdToken();
?>

<pre><?php var_export( $token_data ); ?></pre>
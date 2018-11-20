<?php

echo "Comment this line if you really know what are doing."; exit;

require dirname( __FILE__ ) . '/script_loader.php';

/**
 * This script creates dummy users
 *
 * Before run, be sure to check the initial $i
 */

for ( $i = 1; $i <= 99; $i++ ) {
    $user = \Avant\Modules\Entities\User::get_instance( (object) [] );

    $pad_i = str_pad( $i, 2, '0', STR_PAD_LEFT );

    $user->googleId = '9999999999999999999' . $pad_i;
    $user->email = 'fulano' . $pad_i . 'detal@aluno.fametro.com.br';
    $user->firstName = 'Fulano ' . $pad_i;
    $user->lastName = 'de Tal';
    $user->displayName = 'Fulano ' . $pad_i . ' de Tal';
    $user->avatar = BASE_URL . THEMES_DIR . '/' . THEME . '/dist/images/user_placeholder.jpg';
    $user->bio = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean sem velit, pharetra vel condimentum venenatis, elementum at diam.' . "\n" . 'Quisque mollis nibh nunc, ut malesuada diam semper bibendum.Mauris volutpat metus a feugiat lacinia. Sed purus metus, laoreet et vulputate vitae, volutpat congue purus.';
    $user->phone = '(85) 99999-9999';

    $user->save();
}

echo "OK";
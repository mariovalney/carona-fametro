<?php

/**
 * Module Google
 *
 * @package Avant\Modules
 */

namespace Avant\Modules;

class Google {

    private static $client;

    public static function getGoogleClient() {
        if ( empty( self::$client ) ) {
            $client = new \Google_Client();
            $client->setAuthConfig( self::getClientCredentials() );
            self::$client = $client;
        }

        return self::$client;
    }

    private static function getClientCredentials() {
        // Up 2 levels to avoid public access
        $oauth_credentials = dirname( dirname( ROOT ) ) . '/caronafametro_client_credentials.json';

        if ( ! file_exists( $oauth_credentials ) ) {
            error_log( 'Missing Google Credentials' );
            return;
        }

        return $oauth_credentials;
    }

    public function login( $token = false ) {
        $client = self::getGoogleClient();

        // Scope
        $client->setScopes( 'email' );
        $client->addScope( \Google_Service_Oauth2::PLUS_ME );
        $client->addScope( \Google_Service_Oauth2::USERINFO_PROFILE );

        $client->setAccessType( 'offline' );

        // Redirect URL
        $client->setRedirectUri( BASE_URL . 'entrar' );
        // $client->setRedirectUri( 'http://localhost:8080/entrar' ); // For debug

        // Set Access Token if provided
        if ( $token ) {
            $client->setAccessToken( $token );
        }

        return $client;
    }

    public static function create_section( $redirect = true ) {
        if ( ! session_id() ) session_start();

        $client = self::getGoogleClient();

        if ( ! empty( $_SESSION['id_token_token'] ) && isset( $_SESSION['id_token_token']['id_token'] ) ) {
            // Check user can login
            $client->setAccessToken( $_SESSION['id_token_token'] );

            $token_info = $client->verifyIdToken();
            $email = $token_info['email'] ?? '';

            if ( empty( $token_info['email_verified'] ) || ! preg_match( '/^.*@(?:[a-z]*\.)*fametro.com.br$/', $email ) ) {
                unset( $_SESSION['id_token_token'] );

                if ( ! $redirect ) return false;

                header( 'Location: ' . BASE_URL . 'entrar/0' );
                exit;
            }

            return true;
        }

        if ( ! $redirect ) return false;

        header( 'Location: ' . BASE_URL . 'entrar' );
        exit;
    }

}
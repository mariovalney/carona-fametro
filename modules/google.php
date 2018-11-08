<?php

/**
 * Module Google
 *
 * @package Avant\Modules
 */

namespace Avant\Modules;

class Google {

    private function getGoogleClient() {
        $client = new \Google_Client();
        $client->setAuthConfig( $this->getClientCredentials() );

        return $client;
    }

    private function getClientCredentials() {
        // Up 2 levels to avoid public access
        $oauth_credentials = dirname( dirname( ROOT ) ) . '/caronafametro_client_credentials.json';

        if ( ! file_exists( $oauth_credentials ) ) {
            error_log( 'Missing Google Credentials' );
            return;
        }

        return $oauth_credentials;
    }

    public function login( $token = false ) {
        $client = $this->getGoogleClient();

        // Scope
        $client->setScopes( 'email' );

        // Redirect URL
        $client->setRedirectUri( BASE_URL . 'entrar' );

        // Set Access Token if provided
        if ( $token ) {
            $client->setAccessToken( $token );
        }

        return $client;
    }

}
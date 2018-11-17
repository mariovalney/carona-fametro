<?php

/**
 * Module User
 *
 * @package Avant\Modules
 */

namespace Avant\Modules;

class User {

    private static $instance;

    public static function getInstance() {
        if ( empty( self::$instance ) ) {
            self::$instance = new User();
        }

        return self::$instance;
    }

    public function getLoggedUser() {
        if ( ! session_id() ) session_start();

        if ( empty( $_SESSION['id_token_token'] ) || empty( $_SESSION['id_token_token']['id_token'] ) ) {
            return [];
        }

        $google = Google::getGoogleClient();
        $google->setAccessToken( $_SESSION['id_token_token'] );
        $token_info = $google->verifyIdToken();

        if ( empty( $token_info['sub'] ) ) {
            return [];
        }

        $user = get_user_by( 'googleId', $token_info['sub'] );

        if ( ! empty( $user ) ) {
            return $user;
        }

        return $this->getUserDataFromGoogle();
    }

    private function getUserDataFromGoogle() {
        $user_from_google = [];

        try {
            $user_from_google = $this->getDataFromGoogle();
        } catch (Exception $e) {
            error_log( $e->getMessage() );
        }

        if ( empty( $user_from_google['id'] ) ) {
            return [];
        }

        $user = get_user_by( 'googleId', $user_from_google['id'] );

        if ( empty( $user ) ) {
            $user = create_user( $user_from_google['id'], array(
                'email'         => $user_from_google['email'],
                'firstName'     => $user_from_google['first_name'],
                'lastName'      => $user_from_google['last_name'],
                'displayName'   => $user_from_google['display_name'],
                'avatar'        => $user_from_google['avatar'],
            ) );

            if ( $user ) {
                return get_user_by( 'ID', $user );
            }
        }

        return $user;
    }

    private function getDataFromGoogle() {
        $userinfo = '';
        $google = Google::getGoogleClient();

        try {
            $oauth = new \Google_Service_Oauth2( $google );

            if ( empty( $oauth ) ) {
                throw new Exception( 'Invalid Oauth' );
            }

            $userinfo = $oauth->userinfo->get();
        } catch ( \Exception $e ) {
            error_log( $e->getMessage() );
        }

        if ( empty( $userinfo ) ) return [];

        return array(
            'id'            => $userinfo['id'],
            'email'         => $userinfo['email'],
            'first_name'    => $userinfo['givenName'],
            'last_name'     => $userinfo['familyName'],
            'display_name'  => $userinfo['name'],
            'avatar'        => $userinfo['picture'],
        );
    }
}
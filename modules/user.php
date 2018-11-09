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

    public function getUserData() {
        $user = $this->getDataFromGoogle();

        if ( empty( $user['id'] ) ) {
            return [];
        }

        return $user;
    }

    private function getDataFromGoogle() {
        $google = Google::getGoogleClient();

        $oauth = new \Google_Service_Oauth2( $google );

        if ( empty( $oauth ) ) return [];

        $userinfo = $oauth->userinfo->get();

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
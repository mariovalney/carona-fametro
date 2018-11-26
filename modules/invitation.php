<?php

/**
 * Module Invite
 *
 * @package Avant\Modules
 */

namespace Avant\Modules;

use Avant\Modules\Entities\Route;
use Avant\Modules\Entities\Invite;
use Avant\Modules\Database;
use Avant\Modules\Sender;

class Invitation {

    private $type;

    private $route;

    private $ride;

    public function __construct( $type, Route $route, Route $ride ) {
        $this->type = $type;
        $this->route = $route;
        $this->ride = $ride;
    }

    public function send() {
        $invite = $this->getInvite();
        $invite->addStaticRoutesData();

        if ( empty( $invite->route ) ) {
            throw new \Exception( __( 'Rota inválida' ) );
        }

        if ( empty( $invite->ride ) ) {
            throw new \Exception( __( 'Carona inválida' ) );
        }

        // Save to get a ID
        if ( empty( $invite->save() ) ) {
            throw new \Exception( __( 'Não foi possível criar o convite' ) );
        }

        $to = $this->createInviteMailTo( $invite );
        $subject = $this->createInviteMailSubject( $invite );
        $body = $this->createInviteMailContent( $invite );

        // Invalid Invite
        if ( empty( $body  ) ) {
            $invite->delete();
            throw new \Exception( __( 'Não foi possível enviar o e-mail com o convite' ) );
        }

        // Try send mail
        try {
            $sender = new Sender();
            $result = $sender->send( $to, $subject, $body );
        } catch (Exception $e) {
            error_log( 'Erro ao enviar convite: ' . $e->getMessage() );
        }

        if ( ! $result ) {
            $invite->delete();
            throw new \Exception( __( 'Não foi possível enviar o e-mail com o convite' ) );
        }

        $invite->sendedMails = (int) $invite->sendedMails + 1;
        $invite->save();

        return true;
    }

    private function getInvite() {
        $result = Database::instance()->get( 'invite', array(
            'routeId'   => $this->route->ID,
            'rideId'    => $this->ride->ID,
            'type'      => $this->type,
        ) );

        if ( ! empty( $result ) ) {
            return $result[0];
        }

        // New Invite
        $invite = (object) array(
            'routeId'       => $this->route->ID,
            'rideId'        => $this->ride->ID,
            'type'          => $this->type,
            'sendedMails'   => 0,
            'isAccepted'    => 0,
        );

        return new Invite( $invite );
    }

    private function createInviteMailTo( Invite $invite ) {
        $ride = json_decode( $invite->ride );
        $user = get_user_by( 'ID', $ride->userId );

        return ( empty( $user->email ) ) ? '' : $user->email;
    }

    private function createInviteMailSubject( Invite $invite ) {
        $ride = json_decode( $invite->ride );

        return ( $ride->isDriver ) ? __( 'Carona Fametro - Pedido de Carona' ) : __( 'Carona Fametro - Convite de Carona' );
    }

    private function createInviteMailContent( Invite $invite ) {
        $ride = json_decode( $invite->ride );

        $ride_user = get_user_by( 'ID', ( $ride->userId ?? 0 ) );

        if ( empty( $ride_user ) || empty( $ride_user->firstName ) ) {
            return '';
        }

        $message = '<h1>' . sprintf( __( 'Olá, %s!' ), $ride_user->firstName ) . '</h1>';
        if ( $ride->isDriver ) {
            $message .= '<p>' . __( 'Você recebeu um pedido de carona de um colega.' ) . '</p>';
        } else {
            $message .= '<p>' . __( 'Você recebeu um convite de carona de um colega.' ) . '</p>';
        }

        $link = BASE_URL . 'convite/' . $invite->ID;

        $message .= '<p>&nbsp;</p>';
        $message .= '<p>' . __( 'Clique no botão abaixo para ver os detalhes e confirmar o convite:' ) . '</p>';
        $message .= '<a href="' . $link . '" style="display: block; background: #1D82FF; color: #FFFFFF; padding: 15px 20px; text-align: center; font-size: 22px; text-decoration: none">' . __( 'Ver convite' ) . '</a>';
        $message .= '<p>&nbsp;</p>';
        $message .= '<p>' . sprintf( 'Copie o link a seguir para o navegador, caso não consiga clicar no botão: %s', '<span style="color: blue; text-decoration: underline; display: block;">' . $link . '</span>' ) . '</p>';

        return $message;
    }
}
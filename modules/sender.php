<?php

/**
 * Module Sender
 *
 * @package Avant\Modules
 */

namespace Avant\Modules;

use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;

class Sender {

    const DEFAULT_TO = 'mario.andrades@aluno.fametro.com.br';

    public function mailer() {
        $phpmailer = new PHPMailer(true);

        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = '5d068c4a13f52a';
        $phpmailer->Password = 'abefafd23f31a1';

        $phpmailer->CharSet = 'UTF-8';
        $phpmailer->setFrom( 'contato@caronafametro.mariovalney.com', 'Carona Fametro' );

        return $phpmailer;
    }

    public function send( $to, $subject, $message, $headers = '' ) {
        $to = ( empty( $to ) ) ? self::DEFAULT_TO : $to;

        if ( ! DEBUG ) {
            $headers .= "\r\n" . 'Content-Type: text/html; charset=UTF-8';
            return mail( $to, $subject, $message, $headers );
        }

        $mailer = $this->mailer();

        $mailer->addAddress( $to, 'Mário Valney' );

        $mailer->Subject = $subject;
        $mailer->Body = $message;

        if ( ! is_array( $headers ) ) {
            $headers = explode( "\r\n", $headers );
            foreach ( $headers as $key => $header ) {
                $header = explode( ':', $header );
                $headers[ trim( $header[0] ) ] = trim( $header[1] );
                unset( $headers[ $key ] );
            }
        }

        // HTML/UTF8
        $header['Content-Type'] = 'text/html; charset=UTF-8';

        foreach ( $headers as $key => $value) {
            $mailer->addCustomHeader( $key, $value );
        }

        return $mailer->send();
    }
}
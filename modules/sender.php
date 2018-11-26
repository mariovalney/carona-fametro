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
        $mailer->Body = $this->getHtmlBody( $message );

        if ( ! is_array( $headers ) ) {
            $headers = explode( "\r\n", $headers );
            foreach ( $headers as $key => $header ) {
                $header = explode( ':', $header );

                if ( count( $header ) !== 2 ) continue;

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

    public function getHtmlBody( $content ) {
        return '<table id="corpo_do_email" width="600" border="0" cellpadding="0" cellspacing="0" style="font-size:14px; font-weight:normal; color:#333333; font-family: Lato, Verdana, Arial, Tahoma;">
            <tr>
                <td valign="top" width="600" height="10" colspan="30" bgcolor="#1D82FF"></td>
            </tr>

            <tr>
                <td valign="top" width="20" height="30" colspan="1" bgcolor="#1D82FF"></td>
                <td valign="top" width="580" height="30" colspan="29" bgcolor="#1D82FF">
                    <a href="' . BASE_URL . '" style="font-size: 29px; line-height: 30px; color: #FFFFFF; text-decoration: none; margin: 0;">' . SITE_NAME . '</a>
                </td>
            </tr>

            <tr>
                <td valign="top" width="600" height="10" colspan="30" bgcolor="#1D82FF"></td>
            </tr>

            <tr>
                <td valign="top" width="600" height="30" colspan="30" bgcolor="#FFFFFF"></td>
            </tr>

            <tr>
                <td valign="top" width="20" colspan="1" bgcolor="#FFFFFF"></td>
                <td valign="top" id="conteudo" width="560" colspan="28" bgcolor="#FFFFFF">' . $content . '</td>
                <td valign="top" width="20" colspan="1" bgcolor="#FFFFFF"></td>
            </tr>
        </table>';
    }
}
<?php

function selected( $selected, $value, $echo = true ) {
    $is_selected = ( trim( $value ) === trim( $selected ) );

    if ( ! $echo )  {
        return $is_selected;
    }

    if ( $is_selected ) {
        echo 'selected="selected"';
    }
}

function checked( $checked, $value, $echo = true ) {
    $is_checked = ( trim( $value ) === trim( $checked ) );

    if ( ! $echo )  {
        return $is_checked;
    }

    if ( $is_checked ) {
        echo 'checked="checked"';
    }
}
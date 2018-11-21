<?php

function getTimeRange( $time, $minutes = 10 ) {
    $time = strtotime( $time );

    return [
        date( 'H:i', $time - ( $minutes * 60 ) ),
        date( 'H:i', $time + ( $minutes * 60 ) ),
    ];
}
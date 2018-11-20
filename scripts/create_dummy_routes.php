<?php

echo "Comment this line if you really know what are doing."; exit;

require dirname( __FILE__ ) . '/script_loader.php';

/**
 * This script creates dummy data using this 42 latlng points and distributing to users
 *
 * Before run, be sure to change the initial and last userId in line 241
 */

$addresses = array(
    array(
        'Av. Mister Hull, 5436 - Antônio Bezerra, Fortaleza - CE, 60356-682, Brasil',
        '-3.7397080272041614',
        '-38.593072387402344',
    ),
    array(
        'R. Santa Rosa, 92 - Quintino Cunha, Fortaleza - CE, 60351-552, Brasil',
        '-3.7342265428505614',
        '-38.59238574189453',
    ),
    array(
        'R. Carlos Araújo, 598 - Quintino Cunha, Fortaleza - CE, 60351-010, Brasil',
        '-3.7325135719684837',
        '-38.58826586884766',
    ),
    array(
        'R. Profa. Maria Clara, 1254a - Jardim Iracema, Fortaleza - CE, 60341-542, Brasil',
        '-3.724291265255149',
        '-38.591527435009766',
    ),
    array(
        'R. Misericórdia, 272 - Jardim Iracema, Fortaleza - CE, 60341-465, Brasil',
        '-3.7200087834117785',
        '-38.5851759640625',
    ),
    array(
        'Rua General Tomé Cordeiro, 890 - Jardim Iracema, Fortaleza - CE, 60330-672, Brasil',
        '-3.716411482579244',
        '-38.5851759640625',
    ),
    array(
        'R. Manuel Carneiro, 115 - Floresta, Fortaleza - CE, 60330-522, Brasil',
        '-3.7155549802181884',
        '-38.57848117036133',
    ),
    array(
        'Rua Coronel Mozart Gondim, 1177 - Alagadico Sao Gerardo, Fortaleza - CE, 60320-250, Brasil',
        '-3.7299441093815315',
        '-38.560113403027344',
    ),
    array(
        'Rua Dona Leopoldina, 98 - Centro, Fortaleza - CE, 60160-150, Brasil',
        '-3.7260042521599583',
        '-38.51805636567383',
    ),
    array(
        'R. Silveira Filho, 448 - Jóquei Clube, Fortaleza - CE, 60520-055, Brasil',
        '-3.7703694467628126',
        '-38.57590624970703',
    ),
    array(
        'Av. Carneiro de Mendonça, 1092 - Jóquei Clube, Fortaleza - CE, 60440-160, Brasil',
        '-3.7628326613988876',
        '-38.56852481049805',
    ),
    array(
        'R. Matos Vasconcelos, 1190 - Damas, Fortaleza - CE, 60426-105, Brasil',
        '-3.7552958108315058',
        '-38.55427691621094',
    ),
    array(
        'Rua São Mateus, 948 - Parreão, Fortaleza - CE, 60410-329, Brasil',
        '-3.762661370064161',
        '-38.5383124081543',
    ),
    array(
        'R. Inglaterra, 470 - Itaperi, Fortaleza - CE, 60714-150, Brasil',
        '-3.7842438125184197',
        '-38.558568450634766',
    ),
    array(
        'Rua Desembargador Otacílio Peixoto, 171 - Passaré, Fortaleza - CE, 60743-680, Brasil',
        '-3.8000020818193625',
        '-38.53882739228516',
    ),
    array(
        'Rua 26° Batalhão, 220 - Mondubim, Fortaleza - CE, 60767-413, Brasil',
        '-3.8106216227518734',
        '-38.569554778759766',
    ),
    array(
        'R. I, 165 - São Bento, Fortaleza - CE, 60732-441, Brasil',
        '-3.8107929045968176',
        '-38.62019488496094',
    ),
    array(
        'R. Nossa Sra. Aparecida, 4 - Mondubim, Fortaleza - CE, Brasil',
        '-3.831860310958243',
        '-38.587579223339844',
    ),
    array(
        'R. 34, 491 - Pref. José Walter, Fortaleza - CE, 60750-480, Brasil',
        '-3.8279209166008203',
        '-38.555821868603516',
    ),
    array(
        'R. Saquarema, 269 - Conj. Palmeiras, Fortaleza - CE, 60870-120, Brasil',
        '-3.8433358345367066',
        '-38.525609466259766',
    ),
    array(
        'R. 13, 177 - Barroso, Fortaleza - CE, 60862-760, Brasil',
        '-3.8239815041205834',
        '-38.51050326508789',
    ),
    array(
        'R. Luciano Alves, 3470 - Jangurussu, Fortaleza - CE, 60870-640, Brasil',
        '-3.8484740786578513',
        '-38.510674926464844',
    ),
    array(
        'R. Contorno Um, 622 - Lt Prq Sol Nascente, Eusébio - CE, 61760-000, Brasil',
        '-3.851899557520427',
        '-38.456944915478516',
    ),
    array(
        'R. Guiomar Novaes, 1356 - Sapiranga Coite, Fortaleza - CE, 60833-224, Brasil',
        '-3.799830797828804',
        '-38.46638629121094',
    ),
    array(
        'R. Francisco Moreira, 478 - Praia Do Futuro, Fortaleza - CE, 60175-973, Brasil',
        '-3.7460459507556343',
        '-38.45162341279297',
    ),
    array(
        'Rua José Setúbal Pessoa, 426 - Cais do Porto, Fortaleza - CE, 60180-765, Brasil',
        '-3.721550479274115',
        '-38.47050616425781',
    ),
    array(
        'Av. Eng. Santana Jr., 1343 - Cocó, Fortaleza - CE, 60175-657, Brasil',
        '-3.7397080272041614',
        '-38.484410735791016',
    ),
    array(
        'Rua Afonso Celso, 70 - Aldeota, Fortaleza - CE, 60140-190, Brasil',
        '-3.7359395103901436',
        '-38.51050326508789',
    ),
    array(
        'R. Ivo Rebouças, 94 - Eng. Luciano Cavalcante, Fortaleza - CE, 60810-760, Brasil',
        '-3.7713971851624506',
        '-38.49522540253906',
    ),
    array(
        'R. Alisson Batista de Medeiros, 672 - Cidade dos Funcionários, Fortaleza - CE, 60822-095, Brasil',
        '-3.8000020818193625',
        '-38.490933868115235',
    ),
    array(
        'R. Federação, 1041 - Vicente Pinzon, Fortaleza - CE, 60181-400, Brasil',
        '-3.7292589180894384',
        '-38.47205111665039',
    ),
    array(
        'Av. Barão de Studart, 2983 - Dionísio Torres, Fortaleza - CE, 60120-002, Brasil',
        '-3.749471836270828',
        '-38.51101824921875',
    ),
    array(
        'Av. Padre José Holanda do Vale, 144 - Cagado, Maracanaú - CE, 61912-010, Brasil',
        '-3.85087191530893',
        '-38.64285418671875',
    ),
    array(
        'Av. Luiz Pereira Lima, 1207 - Luzardo Viana, Maracanaú - CE, 61910-105, Brasil',
        '-3.86183336820275',
        '-38.649720641796875',
    ),
    array(
        'R. 47, 319 - Jereissati II, Maracanaú - CE, 61901-090, Brasil',
        '-3.8799879629778258',
        '-38.61796328706055',
    ),
    array(
        'R. 9, 146 - Novo Maracanau, Pacatuba - CE, 61905-600, Brasil',
        '-3.8940318159228164',
        '-38.62088153046875',
    ),
    array(
        'R. Luís Girão, 447 - Cj Alto Da Mangueira, Maracanaú - CE, 61905-010, Brasil',
        '-3.889921444154734',
        '-38.632726165478516',
    ),
    array(
        'Av. IX, 419 - Jereissati II, Maracanaú - CE, 61901-090, Brasil',
        '-3.881186837599465',
        '-38.61761996430664',
    ),
    array(
        'Av. Oito, 229 - Jereissati I, Maracanaú - CE, 61900-680, Brasil',
        '-3.8828995126779735',
        '-38.60938021821289',
    ),
    array(
        'R. 10, 268 - Cj C Jereissati I II, Maracanaú - CE, 61900-290, Brasil',
        '-3.8746786406381117',
        '-38.620023223583985',
    ),
);


// All Campus Names
$campi = array(
    'Conselheiro Estelita',
    'Padre Ibiapina',
    'Guilherme Rocha',
    'Carneiro da Cunha',
    'Maracanaú',
);

// Get Users
$db = \Avant\Modules\Database::instance();
$users = $db->get( 'user' );

$address_index = 0;
$campus_index = 0;

// Routes for All Users
foreach ( $users as $user ) {
    // Escape any user?
    if ( in_array( $user->ID, [] ) ) continue;

    // Get two addresses
    if ( $address_index > 38 && $address_index < 39 ) {
        $address_index = 38;
    }

    if ( $address_index > 39 ) {
        $address_index = 0;
    }

    $start_address = $addresses[ $address_index ];
    $end_address = $addresses[ $address_index + 1 ];

    // Get Campus
    if ( $campus_index > 4 ) {
        $campus_index = 0;
    }

    if ( $address_index > 38 ) {
        $campus_index = 4;
    }

    $campus = $campi[ $campus_index ];

    // Create 7 Routes
    for ( $dow = 0; $dow < 7; $dow++) {
        $route = \Avant\Modules\Entities\Route::get_instance( (object) [] );

        $route->userId = $user->ID;
        $route->startLat = $start_address[1];
        $route->startLng = $start_address[2];
        $route->returnLat = $end_address[1];
        $route->returnLng = $end_address[2];
        $route->startTime = ( $dow > 3 ) ? '07:00' : '18:00';
        $route->returnTime = ( $dow > 3 ) ? '10:20' : '22:00';
        $route->startPlace = $start_address[0];
        $route->returnPlace = $end_address[0];
        $route->campusName = $campus;
        $route->isDriver = ( $dow % 2 === 0 ) ? 1 : 0;
        $route->dow = $dow;

        $route->save();
    }

    $campus_index++;
    $address_index = $address_index + 2;
}

echo "OK";
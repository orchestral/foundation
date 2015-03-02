<?php

return [
    'process'       => 'Processo di installazione',
    'hide-password' => 'La password del database è nascosta per ragioni di sicurezza.',
    'verify'        => 'Per favore, accertati che la seguente configurazione sia corretta rispetto al tuo :filename.',
    'solution'      => 'Soluzione',

    'status'     => [
        'still' => 'Da aggiustare',
        'work'  => 'Funzionante',
        'not'   => 'Non funzionante',
    ],

    'connection' => [
        'status'  => 'Stato della connessione',
        'success' => 'Riuscita',
        'fail'    => 'Fallita',
    ],

    'auth'     => [
        'title'       => "Impostazioni per l'autenticazione",
        'driver'      => 'Driver',
        'model'       => 'Modello',
        'requirement' => [
            'driver'     => 'Orchestra Platform funziona solo con il Driver di Eloquent per Auth',
            'instanceof' => "Il nome del modello deve essere un'istanza di :class",
        ],
    ],

    'database' => [
        'title'    => 'Impostazioni del database',
        'host'     => 'Host',
        'name'     => 'Nome database',
        'password' => 'Password',
        'username' => 'Nome utente',
        'type'     => 'Tipo di database',
    ],

    'steps'    => [
        'requirement' => 'Controlla i requisiti',
        'account'     => 'Crea un amministratore',
        'application' => "Informazioni dell'applicazione",
        'done'        => "Fatto",
    ],

    'system'   => [
        'title'       => 'Requisiti di sistema',
        'description' => 'Per favore, accertati che il seguente requisito sia soddisfatto prima di installare Orchestra Platform.',
        'requirement' => 'Requisito',
        'status'      => 'Stato',

        'writableStorage' => [
            'name'     => "Permessi di scrittura su :path",
            'solution' => "Cambia i permessi della cartella a 0777, tuttavia potrebbe causare problemi di sicurezza se la cartella è accessibile dal web.",
        ],
        'writableAsset' => [
            'name'     => "Permessi di scrittura su :path",
            'solution' => "Cambia i permessi della cartella a 0777. Una volta completata l'installazione, riporta i permessi a 0755.",
        ],
    ],

    'user' => [
        'created'   => "L'utente è stato creato, ora puoi eseguire il login alla pagina di amministrazione",
        'duplicate' => "Impossibile installare quando esiste già un utente registrato.",
    ],
];

<?php

return array(
    'safe-mode' => 'Orchestra Platform è in modalità sicura.',

    'account' => array(
        'password' => array(
            'invalid' => 'La password attuale non corrisponde, ti preghiamo di riprovare',
            'update'  => 'La tua password è stata aggiornata',
        ),
        'profile' => array(
            'update' => 'Il tuo profilo è stato aggiornato',
        ),

    ),

    'credential' => array(
        'invalid-combination' => 'La combinazione di nome utente e password non è valida',
        'logged-in'           => 'Hai eseguito il login',
        'logged-out'          => 'Hai eseguito il logout',
        'unauthorized'        => "Non sei autorizzato ad accedere a quest'azione",
        'register'            => array(
            'email-fail'    => "Impossibile inviare l'e-mail di conferma della registrazione",
            'email-send'    => "L'e-mail di conferma della registrazione è stata inviata, controlla la tua posta in arrivo",
            'existing-user' => 'Questo indirizzo e-mail è già associato ad un altro utente',
        ),
    ),

    'db-failed' => 'Impossibile salvare nel database',
    'db-404'    => 'Il dato richiesto non è disponibile nel database',

    'extensions' => array(
        'activate'         => "L'estensione :name è stata attivata",
        'deactivate'       => "L'estensione :name è stata disattivata",
        'configure'        => "La configurazione dell'estensione :name è stata aggiornata",
        'update'           => "L'estensione :name è stata aggiornata",
        'depends-on'       => "Non è stato possibile attivare 'estensione :name perché dipende da :dependencies",
        'other-depends-on' => "Non è stato possibile disattivare l'estensione :name perché :dependencies dipende da essa",
    ),

    'settings' => array(
        'update'        => "Le impostazioni dell'applicazione sono state aggiornate",
        'system-update' => 'Orchestra Foundation è stata aggiornata',
    ),

    'users' => array(
        'create' => "L'utente è stato creato",
        'update' => "L'utente è stato aggiornato",
        'delete' => "L'utente è stato rimosso",
    ),
);

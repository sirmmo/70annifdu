<?php
/**
 * Il file base di configurazione di WordPress.
 *
 * Questo file definisce le seguenti configurazioni: impostazioni MySQL,
 * Prefisso Tabella, Chiavi Segrete, Lingua di WordPress e ABSPATH.
 * E' possibile trovare ultetriori informazioni visitando la pagina: del
 * Codex {@link http://codex.wordpress.org/Editing_wp-config.php
 * Editing wp-config.php}. E' possibile ottenere le impostazioni per
 * MySQL dal proprio fornitore di hosting.
 *
 * Questo file viene utilizzato, durante l'installazione, dallo script
 * rimepire i valori corretti.
 *
 * @package WordPress
 */

// ** Impostazioni MySQL - E? possibile ottenere questoe informazioni
// ** dal proprio fornitore di hosting ** //
/** Il nome del database di WordPress */
define('DB_NAME', '70anni');

/** Nome utente del database MySQL */
define('DB_USER', 'root');

/** Password del database MySQL */
define('DB_PASSWORD', 'cip50Z321');

/** Hostname MySQL  */
define('DB_HOST', 'localhost');

/** Charset del Database da utilizare nella creazione delle tabelle. */
define('DB_CHARSET', 'utf8mb4');

/** Il tipo di Collazione del Database. Da non modificare se non si ha
idea di cosa sia. */
define('DB_COLLATE', '');

/**#@+
 * Chiavi Univoche di Autenticazione e di Salatura.
 *
 * Modificarle con frasi univoche differenti!
 * E' possibile generare tali chiavi utilizzando {@link https://api.wordpress.org/secret-key/1.1/salt/ servizio di chiavi-segrete di WordPress.org}
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'fZ>|uA-r,!zx2 QWWY+3@<ukNt{W)xcRM:6cfT-P3+uH-5W<6+|?U-S,LUV9RB1N');
define('SECURE_AUTH_KEY',  '%AJ+j6kc /b)bHXA}&-e8;B%iI-3 InS@7@+q|=YcsNUei|:/0Q~i^]gg0o917<E');
define('LOGGED_IN_KEY',    'JGpvY@UgxsM)h!JsMX*jOC[+ja2Bm3A$9K]PP^obYA-%- Jiq]g#B-7/=/LS6={M');
define('NONCE_KEY',        'j%%-+%VLaH#lwxM7hy3in|InXZ6o^3T@IQi2xuFo$;dse;,8~,n{-Z7f|;uTX^jE');
define('AUTH_SALT',        '?f|* ehr+G;FEjsg{}=C`u^2%^JZRtF5sQ-M+W6=IB=B17sw}!?|LtEMd,u1;c!4');
define('SECURE_AUTH_SALT', 'u;DC4&-C6.2{GR[0Y5L2@aS$B!m2l.i||,-,M }+kF$q[_-|1*d=WWz?W|j~p* h');
define('LOGGED_IN_SALT',   '9195T|6|_RW3sC6|6%Y~GN(aM,fuyvX8f[~e-h^||Sy+.|dD-|-2? +XFP7Jx-8a');
define('NONCE_SALT',       '|Q 8cRU6~zY}@eC-/)}A(bj7VCY;qceyS^B&L69X/nW,Cvm-L<[q9,?]3k:(Yp=m');

/**#@-*/

/**
 * Prefisso Tabella del Database WordPress .
 *
 * E' possibile avere installazioni multiple su di un unico database if you give each a unique
 * fornendo a ciascuna installazione un prefisso univoco.
 * Solo numeri, lettere e sottolineatura!
 */
$table_prefix  = 'wp_';

/**
 *
 * Modificare questa voce a TRUE per abilitare la visualizzazione degli avvisi
 * durante lo sviluppo.
 * E' fortemente raccomandato agli svilupaptori di temi e plugin di utilizare
 * WP_DEBUG all'interno dei loro ambienti di sviluppo.
 */
define('WP_DEBUG', false);

/* Finito, interrompere le modifiche! Buon blogging. */

/** Path assoluto alla directory di WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Imposta lle variabili di WordPress ed include i file. */
require_once(ABSPATH . 'wp-settings.php');

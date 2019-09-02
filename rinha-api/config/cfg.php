<?php


/** O nome do banco de dados*/
define('DB_NAME', 'rinhapp');

/** Usuário do banco de dados MySQL */
define('DB_USER', 'root');

/** Senha do banco de dados MySQL */
define('DB_PASSWORD', 'root');

/** nome do host do MySQL */
define('DB_HOST', 'localhost');

/** caminho absoluto para a pasta do sistema **/
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
		
/** caminho do arquivo de banco de dados **/
if ( !defined('DBAPI') )
	define('DBAPI', ABSPATH . 'config/database.php');

?>


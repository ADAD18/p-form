<?php 

/**
 * 
 */

//Comprobar que fue Wordpress quien ejecuta la desistalacion
if (!defined('WP_UNINSTALL_PLUGIN')) exit;
// Borrar la tabla de la base de datos wp_option
delete_option( '_pf_db_cpt' );


?>
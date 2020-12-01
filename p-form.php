<?php
/*
Plugin Name: Plugin Form
Plugin URI:
Description: Plugin de formulario
Version: 1.0
Author: Andersson
Author  URI: 
License: GPL 2
*/

defined( 'ABSPATH' ) or die( "Bye bye" );

// Definicion de la ruta del directorio
define( "PF_RUTA", plugin_dir_path( __FILE__ ) );
// definicion del nombre en la base datos
define( "PF_DB", '_pf_db_cpt' ); 


/**
 * Funcion de activacion del plugin
 * 
 */
function pf_activation(){

    if( ! get_option( PF_DB ) ) {
        add_option( PF_DB, '' ,'' ,'no');
    }else{

    }

}
register_activation_hook( __FILE__, 'pf_activation' );

/**
 * Funcion que se invoca en el init
 */
function pf_new_type_post() {

    $envio=@$_POST[ 'enviar_type_post' ];

    if ( isset( $envio ) ) {
        $action = @$_POST["action"];

        $argu = array(
            "label" => $_POST[ 'label' ],
            "name" => $_POST[ 'name' ],
            "singular_name" => $_POST[ 'singular_name' ],
            "description" => $_POST[ 'description' ],
            "public" => $_POST['public'],
            "menu_icon" => $_POST['menu_icon'],
            "supports" => $_POST['supports'] 
        );

        $pf_cpt = pf_generate_cpt( $argu );
        //echo  json_encode( $pf_cpt );
        //$res = pf_add_cpt( $pf_cpt );
        if ( 'agregar' == $action ) {
            pf_create_cpt( $pf_cpt );

        }else{
            
            if ( 'modificar' == $action ) {
               
                $cpt_id = @$_POST['id'];
                if ( isset( $cpt_id ) ) {
                    pf_update_cpt( $pf_cpt, $cpt_id);
                }else{
                    pf_update_cpt( $pf_cpt );
                }    
            }else{
                if ( 'borrar' == $action ){
                    $cpt_id = @$_POST['id'];
                    if ( isset( $cpt_id ) ) {
                        pf_delete_cpt( $cpt_id );
                    }
                }        
            }
        }

        $home = @$_POST[ 'home' ];
        if ( isset( $home ) ){
            wp_redirect( $home );
        }else{
            wp_redirect( $_SERVER['HTTP_REFERER'] );
        }
    }else{

        $cpt_json = get_option( PF_DB );

         if ( false != $cpt_json && '' != $cpt_json ) {

            $cpt = json_decode( $cpt_json, true);
            foreach ($cpt as $key => $value) {
                $val = pf_add_cpt( $value );
            }
        } 
    }
    
}
add_action( 'init', 'pf_new_type_post' );

/**
 * Funcion para la carga de los scripts
 */
function pf_enqueue_scripts(){

    wp_register_script( 'pf_script_func', plugins_url(  'assets/js/functions.js',  __FILE__ ) );

    wp_enqueue_script( 'pf_script_func' );

    wp_register_style( 'pf_style_func', plugins_url(  'assets/css/style.css',  __FILE__ ) );

    wp_enqueue_style( 'pf_style_func' );

}
add_action( 'admin_enqueue_scripts', 'pf_enqueue_scripts' );

/**
 * Funcion para desactivacion del plugin
 */
function pf_deactivation_form(){

    remove_menu_page( 'pf-form' );

}
register_deactivation_hook( __FILE__, 'pf_deactivation_form' );





require PF_RUTA . '/includes/function_aux.php';

require PF_RUTA .'/includes/functions.php';



?>
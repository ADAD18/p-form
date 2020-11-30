<?php 

/**
 * Funcion para generar el formato de un Custom Post Type
 * 
 * @param array  $param Parametros del custom post type
 * 
 * 
 * @return array 
 */

function pf_generate_cpt( $argu ){

    $name = $argu[ 'name' ];
    $singular_name = $argu[ 'singular_name' ];

    if(  "" != $argu['supports'] ){
        $supports = explode( ',', $argu[ 'supports'] );
    }else{
        $supports = array('title', 'editor' ); 
    }

    $label = array(
        'name' => $name,
        'singular_name' => $singular_name,
        'menu_name' => $name,
        'name_admin_bar' => $singular_name,
        'add_new' => 'Añadir nuevo',
        'add_new_item' => 'Añadir nuevo '. $singular_name,
        'new_item' => 'Nuevo '. $singular_name ,
        'edit_item' => $singular_name,
        'view_item' => 'Ver '. $singular_name,
        'all_items' => 'Todos los '. $name,
        'parent_item' => 'Padre del '. $singular_name,
        'search_items' => 'Buscar '. $name,
        'not_found' => 'No hay '. $name,
        'not_found_in_trash' => 'No hay ' . $singular_name . ' en la papelera',
    );

    $result = array(
        'label' => $argu[ 'label' ],
        'labels' => $label,
        'description' => $argu[ 'description' ],
        'public' => ( "true" == $argu[ 'public' ] ) ? true : false,
        'has_archive' => true,
        'menu_icon' => ( '' != $argu[ 'menu_icon' ] ) ? $argu[ 'menu_icon' ] : 'dashicons-sticky',
        'supports' => $supports 
    ); 

    return $result;
}

/**
 * Funcion para crear y guardar e la base de datos un Custom Post Type
 * 
 * @param  array Parametros para la creacion del custom post type, proporcionados por el formulario
 * 
 */


function pf_create_cpt( $param ) {

    
        $cpt_json = get_option( PF_DB );

        if ( false != $cpt_json && '' != $cpt_json ) {
            
            $cpt = json_decode( $cpt_json );

            array_push($cpt, $param);

            $send_json = json_encode( $cpt ); 
            echo $send_json;
            update_option( PF_DB, $send_json );

        }else{

            $send_json = json_encode( array($param) ); 

            update_option( PF_DB, $send_json );
        }

} 

/**
 * Funcion para registrar un Custom Post Type
 * 
 * @param  array Parametros para el registo del custom post type
 * 
 * @return boolean
 * 
 */

function pf_add_cpt( $param ){
    if ( ! post_type_exists( $param["label"] ) ) {

        register_post_type( $param["label"] , $param );

        $res = post_type_exists( $param["label"] );
        
        return $res;

    }else{
        return false;
    }
}

function pf_listar_cpt() {

    $cpt_json = get_option( PF_DB );
    $cpt = json_decode( $cpt_json, true);
    
    return $cpt;
}

function pf_get_dato_cpt ( $param, $cpts = '' ){
    
    if ( '' != $cpts) {
        
    }else{
        $cpts = pf_listar_cpt();
    }
    

   if ( false != $cpts && '' != $cpts ) {

        $datos = false;
        $i=0;
        foreach ($cpts as $key => $cpt) {
            if ( $cpt["label"] == $param ) {
                $datos = $cpt;
                break;
            }
            $i++;
        }
        
        return array( $datos, $i );
   }else{
       return false;
   }
}

function pf_update_cpt( $param, $id = '' ) {

    $cpts = pf_listar_cpt();
    
    if ( '' != $id ) {
        if ( isset( $cpts[ $id ] ) ) {
            $cpts[ $id ] = $param;
        }
    }else{
        $cpt_update = pf_get_dato_cpt( $param['label'], $param );
        $cpts[ $cpt_update[1]  ] = $param;
    }

    $send_json = json_encode( $cpts ); 

    update_option( PF_DB, $send_json );
    
} 

function pf_delete_cpt( $id ) {
    
    if ( '' != $id ) { 
        $cpts = pf_listar_cpt();
        if ( isset( $cpts[$id] ) ) {
            unset( $cpts[$id] );
            $send_json = json_encode( $cpts );
            update_option( PF_DB, $send_json );
        }

    }
}


?>
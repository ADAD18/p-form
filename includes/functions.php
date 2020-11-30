<?php
/**
 * Archivo para crear la administracion del plugin en el admin menu
 * 
 */

defined('ABSPATH') or die("Bye bye");

    //echo PF_DB;

function pf_admin_menu(){

    //Agregar el form al admin menu
    add_menu_page( 'Tipo de Post', 'Tipo de Post', 'manage_options', 'pf-form', 'pf_page' );

    //agrega el submenu Crear un nuevo type post
    add_submenu_page( 'pf-form', 'Crear Nuevo','Nuevo', 'manage_options', 'pf-add', 'pf_page_form' );

    //agrega un enlace para Editar el type post
    add_submenu_page( '', 'Editar','Editar', 'manage_options', 'pf-edit', 'pf_page_edit' );
    
    //agrega un enlace para Borrar el type post
    add_submenu_page( '', 'Borrar','Borrar', 'manage_options', 'pf-delete', 'pf_page_delete' );

}
add_action( 'admin_menu', 'pf_admin_menu' );

function pf_page(){
    ?>
         <div class="wrap">
            <?php pf_title( "Tipo de Post" ); ?>
            <?php pf_button(1,"Añadir nuevo", admin_url( 'admin.php?page=pf-add')); ?>
            <hr class="wp-header-end">
            <?php pf_table() ;?>   
            <hr>
        </div>
<?php    
}

function pf_page_form(){
    ?>
    <div class="wrap">
      <?php pf_title( "Tipo de Post" ); ?>
      <hr class="wp-header-end">
       <?php pf_form(); ?>
       <hr class="wp-header-end" >
    </div>
    <?php
}

function pf_page_edit(){
    ?>
    <div class="wrap">
      <?php pf_title( "Tipo de Post" ); ?>
      <hr class="wp-header-end">
       <?php pf_form(); ?>
       <hr class="wp-header-end" >
    </div>
    <?php
}

function pf_page_delete(){
    $name = @$_GET['delete'];
    $id = @$_GET['id']; 
    ?>
    <div class="wrap">
      <?php pf_title( "Tipo de Post" ); ?>
      <hr class="wp-header-end">
      
      <?php pf_panel_borrar( 'Esta seguro que desea borrar el Tipo de Post: ' .$name, admin_url( 'admin.php?page=pf-form'), [ $id, $name ] ) ;?>   
       <hr class="wp-header-end" >
    </div>
    <?php
}

function pf_form( $param = "Tipo de post" ){

    $supports = array( 'title' => 'Titulo', 'editor'=>'Editor', 'author'=>'Autor', 'thumbail' => 'Imagen destacata', 'excerpt' => 'Extracto' );

    $cpt_id = @$_GET['id'];
    $cpt = @$_GET['edit'];

    if ( isset( $cpt_id ) ) {

       $datos = pf_listar_cpt();
       if ( '' != $datos) {
            $dato = $datos[ $cpt_id ];
       }

    }else{
        if ( isset( $cpt ) ) {

            $datos = pf_get_dato_cpt( $cpt );
            if ( false != $datos ) {
                $dato = $datos[0];
                $cpt_id = $datos[1];
            }
        }
    }
?>
        <div class="form-wrap">
            <form action=" <?php echo admin_url( 'admin.php?page=pf-form'); ?>" method="POST"  class ="validate">
                <input type="hidden" name="action" value="<?php echo (isset( $dato['label'] ))?  'modificar' : 'agregar'; ?>" >
                <input type="hidden" name="home" value="<?php echo admin_url( 'admin.php?page=pf-form'); ?>" >
                <?php
                    if (isset( $cpt_id ) ) {
                    ?>
                        <input type="hidden" name="id" value="<?php echo $cpt_id; ?>" >
                    <?php
                    }
                ?>
                <table class="wp-list-table widefat fixed striped table-view-list posts">
                    <tr>
                        <th><label for="label" require >Nombre de la etiqueta del <?php echo $param; ?></label></th>
                        <th><input type="text" name="label" required value="<?php echo (isset( $dato["label"] ))?  $dato["label"] : ''; ?>" ></th>
                    </tr>
                    <tr>
                        <th><label for="name" require >Nombre del <?php echo $param; ?></label></th>
                        <th><input type="text" name="name" required value="<?php echo (isset( $dato["labels"]["name"] ))?  $dato["labels"]["name"] : ''; ?>" ></th>
                    </tr>
                    <tr>
                        <th><label for="singular_name" require >Nombre en singular del <?php echo $param; ?></label></th>
                        <th> <input type="text" name="singular_name" required value="<?php echo (isset( $dato["labels"]["singular_name"] ))?  $dato["labels"]["singular_name"] : ''; ?>"></th>
                    </tr>
                    <tr>
                        <th><label for="description">Descripcion del <?php echo $param; ?></label></th>
                        <th> <textarea name="description" cols="50" rows="5"><?php echo (isset( $dato["description"] ))?  $dato["description"] : ''; ?></textarea></th>
                    </tr>
                    <tr>
                        <th>
                            <label for="public">Publico </label>
                        </th>
                        <th>
                            <label>
                                <input type="radio" name="public" value="true" 
                                 <?php if ( isset( $dato["public"] ) ) { 
                                        echo ( 'true' == $dato["public"] ) ? 'checked':'';
                                        }else{ 
                                            echo "checked"; 
                                        } ?>> Si
                            </label>
                            <label>
                            <input type="radio" name="public" value="false" <?php
                                 if ( isset( $dato["public"] ) ) { ( true == $dato["public"] ) ? '':'checked'; }?> > No
                            </label>
                        </th>
                    </tr>
                    <tr>
                        <th><label for="supports">Soporte para el <?php echo $param; ?></label></th>
                        <th>
                            <div>
                            <input type="hidden" name="supports" id="supports" value="<?php echo (isset( $dato["supports"] ))?  implode(",", $dato["supports"]) : ''; ?>">
                            <?php 
                               foreach ($supports as $key => $value) {
                                   ?>
                                     <p> <input type="checkbox" value="<?php echo $key;?>" onClick="pf_add( this )" <?php
                                      if ( isset( $dato["supports"] ) ) {
                                          $num = count( $dato["supports"] );
                                          for ($i=0; $i < $num; $i++) { 
                                            if (  $dato["supports"][$i] == $key ) {
                                                echo 'checked';
                                            }
                                          }
                                     }
                                     ?> > <?php echo $value;?></p>
                                   <?php
                                   $i++;
                               }
                            ?>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th><label for="menu_icon" require >Icono del <?php echo $param; ?></label></th>
                        <th> <input type="text" name="menu_icon" value="<?php echo (isset( $dato["menu_icon"] ))?  $dato["menu_icon"] : ''; ?>" ></th>
                    </tr>
                    <tr>
                        <th colspan="2" >
                               <button type="submit" name="enviar_type_post" class="button button-primary">Guardar</button>
                        </th>
                    </tr>
                </table>
            </form>
        </div>
<?php
}

function pf_table( ){
      
?>
        <table class="wp-list-table widefat fixed striped table-view-list posts">
                <thead>
                     <tr>
                        <th scope="col" id="title" class="manage-column column-title column-primary">
                            <span>Titulo</span>
                        </th>
                        <th class="manage-column column-cathegory" id="description">
                            Descipcion
                        </th>
                        <th class="manage-column column-author"  id="status">
                            Estado
                        </th>
                    </tr>
                </thead>
                <tbody id="the-list">
                <?php 
                    $cpts = pf_listar_cpt();
                if ( '' != $cpts ) {
                foreach ($cpts as $key => $cpt) {
                     
                ?>
                    <tr id="post" class="iedit author-other level-0 type-page status-publish hentry ">
                        <th class="title column-title has-row-actions column-primary page-title" colname="Titulo" >
                            <strong>
                                <a class="row-title" href="<?php echo admin_url( 'admin.php?page=pf-edit&id=' . $key . '&edit=' .$cpt["label"] );?>"> <?php echo $cpt["labels"]["singular_name"]; ?> </a>
                            </strong> 
                            <div class="row-actions">
                                <span class="edit"><a href="<?php echo admin_url( 'admin.php?page=pf-edit&id=' . $key . '&edit=' .$cpt["label"] )?>" >Editar</a></span>
                                <span class="trash"><a href="<?php echo  admin_url( 'admin.php?page=pf-delete&id=' . $key . '&delete=' .$cpt["labels"]["name"] ); ?>">Borrar</a></span>
                            </div>
                        </th>
                        <th class="author column-cathegory" colname="description">
                            <?php echo substr( $cpt["description"], 0, 20 ) . '[...]'; ?>
                        </th>
                        <th class="author column-author" colname="status">
                            <?php echo ( true == $cpt["public"]) ? 'Publico' : 'No Publico' ; ?>
                        </th>
                    </tr>
                <?php
                
                 }
                }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th scope="col" id="title" class="manage-column column-title column-primary">
                            <span>Titulo</span>
                        </th>
                        <th class="manage-column column-cathegory">
                            Descipcion
                        </th>
                        <th class="manage-column column-author">
                            Estado
                        </th>
                    </tr>
                </tfoot>
        </table>
<?php
}



function pf_button( $opction = 1, $title, $link='submit' ){

    switch( $opction ){
        case 1:
       ?>
            <a class="page-title-action" href="<?php echo $link; ?>"><?php echo $title; ?></a>
          <?php
        break;

        case 2: 
        ?>
            <p class="submit">
            <input type="submit" value="<?php echo $title; ?>" name="<?php echo $link; ?>" class="button button-primary">
            </p>
        <?php
        break;
    }
}

function pf_title( $title ){
    ?>
    <h1 class="wp-heading-inline" > <?php echo $title; ?> </h1>
    <?php
}

function pf_panel_borrar( $title = '', $link = '', $values = [] ){
    ?>
        <div id="welcome-panel" class="welcome-panel">
            <h2> <?php echo $title; ?> </h2>
            <div class="welcome-panel-column">
                <form action="<?php echo $link; ?>" method="POST">
                    <input type="hidden" name="action" value="borrar" >
                    <input type="hidden" name="home" value="<?php echo $link; ?>" >
                    <input type="hidden" name="id" value="<?php echo $values[0]; ?>" >
                    <input type="hidden" name="name" value="<?php echo $values[1]; ?>" >
                    <button type="submit" name="enviar_type_post" class="button button-primary button-hero load-customize hide-if-no-customize">Borrar</button>
                </form>
                <a class="welcome-panel-close" href="<?php echo $link; ?>">Descartar</a>
            </div>
        </div>
    <?php
}

?>

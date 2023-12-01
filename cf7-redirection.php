<?php
/*
Plugin Name: Custom CF7 Redirect
Description: Redirige determinados formularios de Contact Form 7 después de enviarlos.
Version: 1.0
Author: Tu Nombre
*/

// Acciones cuando se activa y desactiva el plugin
register_activation_hook(__FILE__, 'custom_cf7_redirect_activate');
register_deactivation_hook(__FILE__, 'custom_cf7_redirect_deactivate');

// Acciones cuando se envía el formulario de Contact Form 7
add_action('wpcf7_mail_sent', 'custom_cf7_redirect');

// Acciones cuando se carga la página de administración
add_action('admin_menu', 'custom_cf7_redirect_admin_menu');

function custom_cf7_redirect_activate()
{
    // Puedes inicializar valores por defecto o realizar otras tareas de activación aquí
}

function custom_cf7_redirect_deactivate()
{
    // Puedes realizar tareas de desactivación aquí
}

function custom_cf7_redirect()
{
    // Obtiene la lista de IDs de formularios almacenados
    $form_ids = get_option('custom_cf7_redirect_form_ids', array());

    // Obtén el ID del formulario actual
    $current_form_id = intval($_POST['_wpcf7']);

    var_dump($current_form_id);

    // Verifica si el formulario actual coincide con algún ID que quieres redirigir
    if (in_array($current_form_id, $form_ids)) {
        // URL a la que quieres redirigir después de enviar el formulario
        $redirect_url = '/inicio';

        // Redirige al usuario
        wp_redirect($redirect_url);
        exit;
    }
}

function custom_cf7_redirect_admin_menu()
{
    // Añade una página al menú de administración
    add_menu_page(
        'Configuración de Redirección CF7',
        'Redirección CF7',
        'manage_options',
        'custom-cf7-redirect-settings',
        'custom_cf7_redirect_settings_page'
    );
}

function custom_cf7_redirect_settings_page()
{
    // Obtiene los IDs de formularios almacenados
    $form_ids = get_option('custom_cf7_redirect_form_ids', array());

    // Procesar formularios enviados
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Actualizar IDs con los valores del formulario
        $form_ids = isset($_POST['custom_cf7_redirect_form_ids']) ? array_map('sanitize_text_field', explode(',', $_POST['custom_cf7_redirect_form_ids'])) : array();

        // Agregar 0 por defecto si no se ha ingresado ningún ID
        if (empty($form_ids)) {
            $form_ids = array(0);
        }

        // Guardar los IDs actualizados
        update_option('custom_cf7_redirect_form_ids', $form_ids);

        // Mostrar mensaje de actualización
        echo '<div class="updated"><p>Configuración actualizada correctamente.</p></div>';
    }

    // Muestra el formulario de configuración
    ?>
    <div class="wrap">
        <h2>Configuración de Redirección CF7</h2>
        <form method="post" action="">
            <?php
            // Agrega un campo nonce para seguridad
            wp_nonce_field('custom_cf7_redirect_settings', 'custom_cf7_redirect_nonce');
            ?>
            <label for="custom_cf7_redirect_form_ids">IDs de Formularios a Redirigir (separados por comas):</label>
            <input type="text" name="custom_cf7_redirect_form_ids" id="custom_cf7_redirect_form_ids" value="<?php echo esc_attr(implode(',', $form_ids)); ?>" />
            <?php
            submit_button('Guardar Cambios');
            ?>
        </form>

        <!-- Mostrar los IDs guardados -->
        <h3>IDs Guardados:</h3>
        <?php
        if (!empty($form_ids)) {
            echo '<ul>';
            foreach ($form_ids as $id) {
                echo '<li>' . esc_html($id) . '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No se han guardado IDs.</p>';
        }
        ?>
    </div>
    <?php
}



// Acciones para inicializar y registrar las opciones del plugin
add_action('admin_init', 'custom_cf7_redirect_admin_init');

function custom_cf7_redirect_admin_init()
{
    // Registra una sección y campos de opciones (opcional, ya que no estás usando configuraciones de registro)
}


?>

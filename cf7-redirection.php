<?php
/*
Plugin Name: Custom CF7 Redirect
Description: Redirige determinados formularios de Contact Form 7 después de enviarlos.
Version: 1.0
Author: Rafael Carreño Olaya
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

    // ID del formulario de Contact Form 7 que quieres redirigir
    $form_id_to_redirect = 123;

    // Verifica si el formulario actual coincide con el ID que quieres redirigir
    if (in_array($form_id_to_redirect, $form_ids)) {
        // URL a la que quieres redirigir después de enviar el formulario
        $redirect_url = 'https://tu-sitio.com/pagina-de-destino';

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
    // Muestra el formulario de configuración
    ?>
    <div class="wrap">
        <h2>Configuración de Redirección CF7</h2>
        <form method="post" action="options.php">
            <?php
            // Carga las opciones guardadas
            settings_fields('custom_cf7_redirect_options');
            do_settings_sections('custom-cf7-redirect-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Acciones para inicializar y registrar las opciones del plugin
add_action('admin_init', 'custom_cf7_redirect_admin_init');

function custom_cf7_redirect_admin_init()
{
    // Registra una sección y campos de opciones
    add_settings_section(
        'custom_cf7_redirect_section',
        'Configuración de IDs de Formularios',
        'custom_cf7_redirect_section_callback',
        'custom-cf7-redirect-settings'
    );

    add_settings_field(
        'custom_cf7_redirect_form_ids',
        'IDs de Formularios a Redirigir',
        'custom_cf7_redirect_form_ids_callback',
        'custom-cf7-redirect-settings',
        'custom_cf7_redirect_section'
    );

    // Registra las opciones con una función de validación personalizada
    register_setting('custom_cf7_redirect_options', 'custom_cf7_redirect_form_ids', 'custom_cf7_redirect_validate');
}

function custom_cf7_redirect_section_callback()
{
    // Puedes agregar instrucciones o descripciones aquí
}

function custom_cf7_redirect_form_ids_callback()
{
    // Obtiene los IDs de formularios almacenados
    $form_ids = get_option('custom_cf7_redirect_form_ids', array());

    // Muestra un campo de texto para cada ID de formulario
    echo '<input type="text" name="custom_cf7_redirect_form_ids[]" value="' . esc_attr(implode(',', $form_ids)) . '" />';
}

function custom_cf7_redirect_validate($input)
{
    // Validación: Asegúrate de que cada ID sea un número y está separado por comas
    $validated_ids = array_map('intval', explode(',', $input));

    // Elimina valores duplicados
    $validated_ids = array_unique($validated_ids);

    // Filtra los valores no válidos
    $validated_ids = array_filter($validated_ids, 'is_numeric');

    // Puedes agregar más validaciones según sea necesario

    // Retorna los IDs validados
    return $validated_ids;
}
?>

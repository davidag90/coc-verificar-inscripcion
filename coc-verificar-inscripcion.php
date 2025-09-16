<?php

/**
 * Plugin Name: COC Verificar Inscripción
 * Description: Plugin para verificar la inscripción de asistentes a las 23° Jornadas Internacionales.
 * Version: 0.2
 * Author: David Alejandro Gómez
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: coc-verificar-inscripcion
 */

// Evitar el acceso directo al archivo
if (! defined('ABSPATH')) {
  exit;
}

// Incluir el archivo de funciones
// require_once plugin_dir_path( __FILE__ ) . 'includes/functions.php';

// Add this near the top of your file, after the initial plugin information
require_once plugin_dir_path(__FILE__) . 'includes/admin-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes.php';

// Queue and localize script with AJAX URL and nonce
function coc_verificar_inscripcion_enqueue_scripts()
{
  wp_enqueue_style('coc-verificar-inscripcion-styles', plugin_dir_url(__FILE__) . 'public/css/coc-verificar-inscripcion-public.css', array(), null, 'all');
  wp_enqueue_script('coc-verificar-inscripcion-script', plugin_dir_url(__FILE__) . 'public/js/coc-verificar-inscripcion-public.js', array('jquery'), null, true);

  $upload_dir = WP_CONTENT_URL . '/uploads';
  $plugin_upload_dir = $upload_dir . '/coc-verificar-inscripcion';
  $json_inscripciones_url = $plugin_upload_dir . '/inscripciones.json';

  wp_localize_script('coc-verificar-inscripcion-script', 'coc_ajax_object', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce'    => wp_create_nonce('coc_verificar_inscripcion_nonce'),
    'json_inscripciones' => $json_inscripciones_url,
  ));
}

add_action('wp_enqueue_scripts', 'coc_verificar_inscripcion_enqueue_scripts');

// Activación del plugin
function coc_verificar_inscripcion_activate()
{
  // Código a ejecutar al activar el plugin (si es necesario)
}
register_activation_hook(__FILE__, 'coc_verificar_inscripcion_activate');

// Desactivación del plugin
function coc_verificar_inscripcion_deactivate()
{
  // Código a ejecutar al desactivar el plugin (si es necesario)
}
register_deactivation_hook(__FILE__, 'coc_verificar_inscripcion_deactivate');

// Desinstalación del plugin
function coc_verificar_inscripcion_uninstall()
{
  // Delete the uploaded JSON file
  $json_file_path = get_option('coc_json_inscripciones');
  if ($json_file_path && file_exists($json_file_path)) {
    unlink($json_file_path);
  }

  // Delete the upload directory
  $upload_dir = wp_upload_dir();
  $plugin_upload_dir = $upload_dir['basedir'] . '/coc-verificar-inscripcion';
  if (file_exists($plugin_upload_dir)) {
    rmdir($plugin_upload_dir);
  }

  // Delete the option
  delete_option('coc_json_inscripciones');
}

register_uninstall_hook(__FILE__, 'coc_verificar_inscripcion_uninstall');

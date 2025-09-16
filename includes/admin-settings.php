<?php
// Prevent direct access
if (!defined('ABSPATH')) {
  exit;
}

function coc_admin_menu()
{
  add_options_page(
    'Ajustes de Verificador de Inscripción',
    'Verificar Inscripción',
    'manage_options',
    'coc-verificar-inscripcion',
    'coc_settings_page'
  );
}
add_action('admin_menu', 'coc_admin_menu');

function coc_settings_page()
{
  // Check user capabilities
  if (!current_user_can('manage_options')) {
    return;
  }

  // Save Settings
  if (isset($_POST['coc_save_settings'])) {
    check_admin_referer('coc_settings_nonce');

    if (isset($_FILES['coc_json_file']) && !empty($_FILES['coc_json_file']['tmp_name'])) {
      $file = $_FILES['coc_json_file'];

      // Validate file type
      $file_type = $file['type'];

      if ($file_type !== 'application/json' && $file_type !== 'text/json') {
        add_settings_error(
          'coc_messages',
          'coc_message',
          'Formato de archivo no soportado: <' . var_dump($file) . '>. Por favor, utilice un archivo JSON.',
          'error'
        );
      } else {
        // Create uploads directory if it doesn't exist
        $upload_dir = wp_upload_dir();

        $plugin_upload_dir = $upload_dir['basedir'] . '/coc-verificar-inscripcion';

        if (!file_exists($plugin_upload_dir)) {
          wp_mkdir_p($plugin_upload_dir);
        }

        // Move uploaded file
        $new_file_path = $plugin_upload_dir . '/inscripciones.json';

        if (move_uploaded_file($file['tmp_name'], $new_file_path)) {
          update_option('coc_json_inscripciones', $new_file_path);
          add_settings_error(
            'coc_messages',
            'coc_message',
            'Archivo JSON subido y guardado correctamente.',
            'success'
          );
        }
      }
    }
  }

  // Show settings errors
  settings_errors('coc_messages');

  // Get current file path
  $current_file = get_option('coc_json_inscripciones');
?>
  <div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form action="" method="post" enctype="multipart/form-data">
      <?php wp_nonce_field('coc_settings_nonce'); ?>
      <table class="form-table">
        <tr>
          <th scope="row">
            <label for="coc_json_file">Archivo JSON con Inscripciones</label>
          </th>
          <td>
            <input type="file" name="coc_json_file" id="coc_json_file" accept=".json">
            <?php if ($current_file): ?>
              <p class="description">
                Archivo actual: <?php echo esc_html(basename($current_file)); ?>
              </p>
            <?php endif; ?>
          </td>
        </tr>
      </table>
      <p class="submit">
        <input type="submit" name="coc_save_settings" class="button-primary" value="Guardar Cambios">
      </p>
    </form>
  </div>
<?php
}

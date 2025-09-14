<?php
// Evitar el acceso directo al archivo
if (!defined('ABSPATH')) {
  exit;
}

// Registrar el shortcode [coc_verificar_inscripcion]
function coc_verificar_inscripcion_shortcode()
{
  ob_start();
  // Generate nonce for security
  $nonce = wp_create_nonce('coc_verificar_inscripcion_nonce');

  // Form HTML
  echo '<form class="d-flex align-items-center justify-content-end justify-content-md-start flex-wrap my-4" id="coc-verificar-inscripcion-form">';
  echo '<div id="search-input" class="d-flex align-items-center justify-content-start">';
  echo '<label for="dni" class="form-label mb-0 me-3">Ingresa tu DNI:</label>';
  echo '<input type="text" class="form-control" id="dni" name="dni">';
  echo '</div>'; // #search-input
  echo '<button type="submit" class="btn btn-warning" id="verifica-dni">Verificar Inscripción</button>';
  echo '<input type="hidden" name="nonce" value="' . $nonce . '">';
  echo '</form>'; // #coc-verificar-inscripcion-form
  echo '<div id="coc-verificar-inscripcion-result" class="alert d-flex align-items-center" role="alert">';
  echo '</div>';

  return ob_get_clean();
}

add_shortcode('coc_verificar_inscripcion', 'coc_verificar_inscripcion_shortcode');

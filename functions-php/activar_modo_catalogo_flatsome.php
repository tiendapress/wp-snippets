// ✅ Función reutilizable para chequear si el modo catálogo está activado
function modo_catalogo_activado() {
    return function_exists('get_field') && get_field('activar_modo_catalogo', 'option');
}

// ✅ Ocultar botón "Añadir al carrito" en página individual
add_action('wp', function() {
    if (modo_catalogo_activado()) {
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
        remove_action('woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30);
        remove_action('woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30);
        remove_action('woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30);
        remove_action('woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30);
    }
});

// ✅ Ocultar botón en la tienda (loop de productos)
add_filter('woocommerce_loop_add_to_cart_link', function($link, $product) {
    return modo_catalogo_activado() ? '' : $link;
}, 10, 2);

// ✅ Ocultar precios
add_filter('woocommerce_get_price_html', function($price, $product) {
    return modo_catalogo_activado() ? '' : $price;
}, 99, 2);

// ✅ Ocultar ícono del carrito (header predeterminado de Flatsome)
add_action('init', function() {
    if (modo_catalogo_activado()) {
        remove_action('flatsome_header', 'flatsome_header_cart', 60);
    }
});

// ✅ Ocultar ícono del carrito también por CSS (por si está en el builder)
add_action('wp_head', function() {
    if (modo_catalogo_activado()) {
        echo '<style>
            .header-cart-link, .cart-icon {
                display: none !important;
            }
        </style>';
    }
});

// ✅ Limpiar automáticamente el carrito
add_action('init', function() {
    if (modo_catalogo_activado() && function_exists('WC') && WC()->cart) {
        WC()->cart->empty_cart();
    }
});

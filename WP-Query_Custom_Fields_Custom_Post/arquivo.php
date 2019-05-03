$args = array (
	'post_type'              => array( 'documentos' ),
	'post_status'            => array( 'publish' ),
	'nopaging'               => true,
	'orderby'		 => array( 'unidade' => 'ASC', 'tipo' => 'ASC', 'post_date' => 'DESC' )
	'meta_key' => 'unidade'
);

$services = new WP_Query( $args );

if ( $services->have_posts() ) {
	while ( $services->have_posts() ) {
		$services->the_post();
		// do something
	}
} else {
	// no posts found
}

// Restore original Post Data
wp_reset_postdata();
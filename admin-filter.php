<?php
/*
Plugin Name: Admin Filter
Plugin URI: https://github.com/modaresimr/admin-filter
Description: Add filterable column on admin panel
Version: 1.0.0
Author: Ali Modaresi
Author URI: https://github.com/modaresimr/
GitHub Plugin URI: https://github.com/modaresimr/admin-filter
*/

function filter_posts_by_taxonomies( $post_type, $which ) {


	// A list of taxonomy slugs to filter by
	$taxonomies = get_object_taxonomies($post_type,'objects');

	foreach ( $taxonomies as $taxonomy_slug=>$taxonomy_obj ) {
	
		// Retrieve taxonomy data
		if(!$taxonomy_obj->show_admin_column)
			continue;
		$taxonomy_name = $taxonomy_obj->labels->name;

		// Retrieve taxonomy terms
		$terms = get_terms( $taxonomy_slug );

		// Display filter HTML
		echo "<select name='{$taxonomy_slug}[]' id='{$taxonomy_slug}' class='postform select2' multiple>";
		echo '<option value="">' . sprintf( esc_html__( 'All %s', 'admin_filter_all_text' ), $taxonomy_name ) . '</option>';
		foreach ( $terms as $term ) {
			printf(
				'<option value="%1$s" %2$s>%3$s (%4$s)</option>',
				$term->slug,
				( ( isset( $_GET[$taxonomy_slug] ) && ( $_GET[$taxonomy_slug] == $term->slug ) ) ? ' selected="selected"' : '' ),
				$term->name,
				$term->count
			);
		}
		echo '</select>';
	}
	
    $dir = plugin_dir_path( __FILE__ );
	wp_enqueue_style( "select2-css", $dir . 'lib/select2/select2.css' );
	wp_enqueue_script( "select2-js", $dir . 'lib/select2/select2.js' );
	echo '<script>$("select.select2").select2();</script>';
}
add_action( 'restrict_manage_posts', 'filter_posts_by_taxonomies' , 10, 2);

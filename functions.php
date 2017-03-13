<?php
/**
 * Konkurrent functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Konkurrent
 */

/**
 * Theme Setup.
 */
require get_template_directory() . '/inc/setup.php';


/**
 * Rest-Api Endpoints.
 */
require get_template_directory() . '/inc/konkurrent-rest-api-endpoints.php';


/**
 * Multiple Image Support.
 */
require get_template_directory() . '/inc/konkurrent-second-featured-image.php';


/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

add_action( 'rest_api_init', function () {
	register_rest_route( 'react/v2', '/options', array(
		'methods' => 'GET',
		'callback' => 'my_awesome_func',
	) );


  register_rest_route( get_plugin_namespace(), '/menus', array(
                array(
                    'methods'  => WP_REST_Server::READABLE,
                    'callback' => 'get_menus',
                )
            ) );

            register_rest_route( get_plugin_namespace(), '/menus/(?P<id>\d+)', array(
                array(
                    'methods'  => WP_REST_Server::READABLE,
                    'callback' => 'get_menu',
                    'args'     => array(
                        'context' => array(
                        'default' => 'view',
                        ),
                    ),
                )
            ) );

            register_rest_route( get_plugin_namespace(), '/menu-locations', array(
                array(
                    'methods'  => WP_REST_Server::READABLE,
                    'callback' => 'get_menu_locations',
                )
            ) );

            register_rest_route( get_plugin_namespace(), '/menu-locations/(?P<location>[a-zA-Z0-9_-]+)', array(
                array(
                    'methods'  => WP_REST_Server::READABLE,
                    'callback' => 'get_menu_location',
                )
            ) );


} );


function get_plugin_namespace() {
		    return 'wp-api-menus/v2';
	    }

// add_action(init, 'my_awesome_func');

function my_awesome_func() {
  $blog_info = '';
  $site_title = get_bloginfo();
  $blog_info['site_title'] = $site_title;
}

function get_api_namespace() {
            return 'wp/v2';
        }


/**
         * Get menus.
         *
         * @since  1.2.0
         * @return array All registered menus
         */
        function get_menus() {

            $rest_url = trailingslashit( get_rest_url() . get_plugin_namespace() . '/menus/' );
            $wp_menus = wp_get_nav_menus();

            $i = 0;
            $rest_menus = array();
            foreach ( $wp_menus as $wp_menu ) :

                $menu = (array) $wp_menu;

                $rest_menus[ $i ]                = $menu;
                $rest_menus[ $i ]['ID']          = $menu['term_id'];
                $rest_menus[ $i ]['name']        = $menu['name'];
                $rest_menus[ $i ]['slug']        = $menu['slug'];
                $rest_menus[ $i ]['description'] = $menu['description'];
                $rest_menus[ $i ]['count']       = $menu['count'];

                $rest_menus[ $i ]['meta']['links']['collection'] = $rest_url;
                $rest_menus[ $i ]['meta']['links']['self']       = $rest_url . $menu['term_id'];

                $i ++;
            endforeach;

            return apply_filters( 'rest_menus_format_menus', $rest_menus );
        }


        /**
         * Get a menu.
         *
         * @since  1.2.0
         * @param  $request
         * @return array Menu data
         */
        function get_menu( $request ) {

            $id             = (int) $request['id'];
            $rest_url       = get_rest_url() . get_api_namespace() . '/menus/';
            $wp_menu_object = $id ? wp_get_nav_menu_object( $id ) : array();
            $wp_menu_items  = $id ? wp_get_nav_menu_items( $id ) : array();

            $rest_menu = array();

            if ( $wp_menu_object ) :

                $menu = (array) $wp_menu_object;
                $rest_menu['ID']          = abs( $menu['term_id'] );
                $rest_menu['name']        = $menu['name'];
                $rest_menu['slug']        = $menu['slug'];
                $rest_menu['description'] = $menu['description'];
                $rest_menu['count']       = abs( $menu['count'] );

                $rest_menu_items = array();
                foreach ( $wp_menu_items as $item_object ) {
	                $rest_menu_items[] = $this->format_menu_item( $item_object );
                }

                $rest_menu_items = $this->nested_menu_items($rest_menu_items, 0);

                $rest_menu['items']                       = $rest_menu_items;
                $rest_menu['meta']['links']['collection'] = $rest_url;
                $rest_menu['meta']['links']['self']       = $rest_url . $id;

            endif;

            return apply_filters( 'rest_menus_format_menu', $rest_menu );
        }

        /**
         * Get menu for location.
         *
         * @since 1.2.0
         * @param  $request
         * @return array The menu for the corresponding location
         */
        function get_menu_location( $request ) {

            $params     = $request->get_params();
            $location   = $params['location'];
            $locations  = get_nav_menu_locations();

            if ( ! isset( $locations[ $location ] ) ) {
	            return array();
            }

            $wp_menu = wp_get_nav_menu_object( $locations[ $location ] );
            $menu_items = wp_get_nav_menu_items( $wp_menu->term_id );

			/**
			 * wp_get_nav_menu_items() outputs a list that's already sequenced correctly.
			 * So the easiest thing to do is to reverse the list and then build our tree
			 * from the ground up
			 */
			$rev_items = array_reverse ( $menu_items );
			$rev_menu  = array();
			$cache     = array();

			foreach ( $rev_items as $item ) :
                            $slug = basename( get_permalink($item->object_id) );
				$formatted = array(
					'ID'          => abs( $item->ID ),
					'order'       => (int) $item->menu_order,
					'parent'      => abs( $item->menu_item_parent ),
					'title'       => $item->title,
					'url'         => $item->url,
					'attr'        => $item->attr_title,
					'target'      => $item->target,
					'classes'     => implode( ' ', $item->classes ),
					'xfn'         => $item->xfn,
					'description' => $item->description,
					'object_id'   => abs( $item->object_id ),
					'object'      => $item->object,
					'type'        => $item->type,
					'type_label'  => $item->type_label,
					'children'    => array(),
					'slug'        => $slug
				);

				if ( array_key_exists( $item->ID , $cache ) ) {
					$formatted['children'] = array_reverse( $cache[ $item->ID ] );
				}

        $formatted = apply_filters( 'rest_menus_format_menu_item', $formatted );

				if ( $item->menu_item_parent != 0 ) {

					if ( array_key_exists( $item->menu_item_parent , $cache ) ) {
						array_push( $cache[ $item->menu_item_parent ], $formatted );
					} else {
						$cache[ $item->menu_item_parent ] = array( $formatted, );
					}

				} else {

					array_push( $rev_menu, $formatted );
				}

			endforeach;
			return array_reverse ( $rev_menu );
        }

        /**
         * Get menu locations.
         *
         * @since 1.2.0
         * @param  $request
         * @return array All registered menus locations
         */
        function get_menu_locations( $request ) {

            $locations        = get_nav_menu_locations();
            $registered_menus = get_registered_nav_menus();
	        $rest_url         = get_rest_url() . get_api_namespace() . '/menu-locations/';
            $rest_menus       = array();

            if ( $locations && $registered_menus ) :

                foreach ( $registered_menus as $slug => $label ) :

	                // Sanity check
	                if ( ! isset( $locations[ $slug ] ) ) {
		                continue;
	                }

	                $rest_menus[ $slug ]['ID']                          = $locations[ $slug ];
                    $rest_menus[ $slug ]['label']                       = $label;
                    $rest_menus[ $slug ]['meta']['links']['collection'] = $rest_url;
                    $rest_menus[ $slug ]['meta']['links']['self']       = $rest_url . $slug;

                endforeach;

            endif;

            return $rest_menus;
        }

// GET PRIMARY MENU
  //http://localhost/konkurrent/wp-json/wp-api-menus/v2/menu-locations/primary


add_action( 'rest_api_init', 'slug_register_starship' );
function slug_register_starship() {
    register_rest_field( ['page', 'post'],
        'featured_image_url',
        array(
            'get_callback'    => 'kk_get_featured_image_url',
            'update_callback' => null,
            'schema'          => null,
        )
    );
		register_rest_field( ['page', 'post'],
        'post_meta_data',
        array(
            'get_callback'    => 'kk_get_post_meta_data',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}

function kk_get_post_meta_data( $object, $field_name, $request  ) {
	$author = $object['author'];
	$author = get_user_by( 'ID', $author );
	$author = $author->data->display_name;
	$formatted_date = strtotime($object['modified_gmt']);
	$post_day = date('d',$formatted_date);
	$post_month = date('M',$formatted_date);
	$formatted_date = date('M d, Y',$formatted_date);

	return array(
		'post_author' => $author,
		'formatted_date' => $formatted_date,
		'post_day'		=>	$post_day,
		'post_month'		=>	$post_month
	);
}

function kk_get_featured_image_url( $object, $field_name, $request ) {
    return get_the_post_thumbnail_url($object[ 'id' ]);
}

function my_rest_prepare_post( $data, $post, $request ) {
	$_data = $data->data;
	$contact_details = get_post_meta($post->ID, '_kk_contact_details', true);
	$_data['contact_details'] = $contact_details;
	$data->data = $_data;
	return $data;
}
add_filter( 'rest_prepare_page', 'my_rest_prepare_post', 10, 3 );

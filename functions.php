<?php

require get_theme_file_path('/inc/search-route.php');

add_action('wp_enqueue_scripts', 'pokepress_assets');

function pokepress_assets()
{
    wp_enqueue_style('pokepress_main_styles', get_theme_file_uri() . '/assets/main.css' , NULL, '1.0');
    wp_enqueue_script( 'pokepress_main_script', get_theme_file_uri() . '/assets/main.js', NULL, '1.0', true );
    wp_localize_script('pokepress_main_script', 'pokeData', array(
        'root_url' => get_site_url(),
    ));
}

// Pagination

function pagination_bar($my_query) {
    $total_pages = $my_query->max_num_pages;

    if ($total_pages > 1){
        $current_page = max(1, get_query_var('paged'));

        echo paginate_links(array(
            'base' => get_pagenum_link(1) . '%_%',
            'format' => '/page/%#%',
            'current' => $current_page,
            'total' => $total_pages,
        ));
    }
}

// Pokemon CPT

add_action('init', 'register_pokemon_cpt');

function register_pokemon_cpt() {
    // Pokemon Post Type
    register_post_type('pokemon', array(
        'rewrite' => array('slug' => 'pokemon'),
        'supports' => array('title', 'editor', 'excerpt'),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Pokemon',
            'add_new_item' => 'Add New Pokemon',
            'edit_item' => 'Edit Pokemon',
            'all_items' => 'All Pokemon',
            'singular_name' => 'Pokemon'
        ),
        'menu_icon' => 'dashicons-buddicons-activity',
        'show_in_rest' => true
    ));
    // Pokemon Custom Taxonomy
	$labels = array(
		'name'                       => _x( 'Pokemon Types', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Pokemon Type', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Pokemon Types', 'text_domain' ),
		'all_items'                  => __( 'All Pokemon Types', 'text_domain' ),
		'new_item_name'              => __( 'New Pokemon Type', 'text_domain' ),
		'add_new_item'               => __( 'Add New Pokemon Type', 'text_domain' ),
		'edit_item'                  => __( 'Edit Pokemon Type', 'text_domain' ),
		'update_item'                => __( 'Update Pokemon Type', 'text_domain' ),
		'view_item'                  => __( 'View Pokemon Type', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate Pokemon Types with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove Pokemon Types', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Pokemon Types', 'text_domain' ),
		'search_items'               => __( 'Search Pokemon Types', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No Pokemon Types', 'text_domain' ),
		'items_list'                 => __( 'Pokemon Types list', 'text_domain' ),
		'items_list_navigation'      => __( 'Pokemon Types list navigation', 'text_domain' ),
	);
    $rewrite = array(
      'slug' => 'pokemon-type', // This controls the base slug that will display before each term
      'with_front' => true, // Don't display the category base before "/locations/"
      'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
    );

	$args = array(
		'labels'                     => $labels,
        'rewrite'                    => $rewrite,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'pokemon-type', 'pokemon', $args );

}

if(!wp_next_scheduled( 'update_pokemon_list')){
    wp_schedule_event( time(), 'monthly', 'get_pokemon_from_api');
}
add_action('wp_ajax_nopriv_get_pokemon_from_api', 'get_pokemon_from_api');
add_action('wp_ajax_get_pokemon_from_api', 'get_pokemon_from_api');


function get_pokemon_from_api(){

    $limit =  50;
    $offset =  (!empty($_POST['offset'])) ? $_POST['offset'] :  0;
    $pokemons = [];
    $data = wp_remote_retrieve_body(wp_remote_get('https://pokeapi.co/api/v2/pokemon?limit=' . $limit . '&offset=' . $offset));

    $data = json_decode($data);

    if(!(is_array($data->results)) || empty($data->results)){
        return false;
    }

    $pokemons = $data->results;
    foreach ($pokemons as $pokemon) {
        $pokemon_slug = sanitize_title($pokemon->name);

        $existing_pokemon = get_page_by_path( $pokemon_slug, OBJECT, 'pokemon' );

        if($existing_pokemon == null){
            $pokemon_data = get_pokemon_data($pokemon->url);
            $inserted_pokemon = wp_insert_post([
                    'post_name' => $pokemon_slug,
                    'post_title' => ucfirst(sanitize_title($pokemon->name)),
                    'post_type' => 'pokemon',
                    'post_status' => 'publish',
                    'menu_order' => (int) $pokemon->id
            ]);
            if(is_wp_error($inserted_pokemon)){
                continue;
            }
            if($inserted_pokemon && $pokemon_data) {



                // name
                update_field('field_609a95f8c2e31', $pokemon_data->name ,$inserted_pokemon);
                // pokedex id
                update_field('field_609a9605c2e32', $pokemon_data->id ,$inserted_pokemon);
                // image

                $image_url = $pokemon_data->sprites->other->dream_world->front_default;
                if(empty($image_url)){
                    $image_url = $pokemon_data->sprites->other->{'official-artwork'}->front_default;
                }
                if(empty($image_url)){
                    $image_url = $pokemon_data->sprites->front_default;
                }

                update_field('field_609a9662c2e33', $image_url ,$inserted_pokemon);
                // icon
                update_field('field_609f9c7caba25', $pokemon_data->sprites->front_default ,$inserted_pokemon);
                // height
                update_field('field_609f9cab16146', $pokemon_data->height ,$inserted_pokemon);
                // weight
                update_field('field_609f9cbd16147', $pokemon_data->weight ,$inserted_pokemon);
                // base experience
                update_field('field_609f9cc416148', $pokemon_data->base_experience ,$inserted_pokemon);
                // Stat HP
                update_field('field_609f9cd716149', $pokemon_data->stats[0]->base_stat ,$inserted_pokemon);
                // Stat Speed
                update_field('field_609f9ce11614a', $pokemon_data->stats[5]->base_stat ,$inserted_pokemon);
                // Stat Attack
                update_field('field_609f9cf61614b', $pokemon_data->stats[1]->base_stat ,$inserted_pokemon);
                // Stat Defence
                update_field('field_609f9cfe1614c', $pokemon_data->stats[2]->base_stat ,$inserted_pokemon);
                // Stat Spl Attk
                update_field('field_609f9d041614d', $pokemon_data->stats[3]->base_stat ,$inserted_pokemon);
                // Stat Spl Defence
                update_field('field_609f9d151614e', $pokemon_data->stats[4]->base_stat ,$inserted_pokemon);

                $type_array = [];

                foreach ($pokemon_data->types as $item) {
                    array_push($type_array, $item->type->name);
                }
                // Type
                update_field('field_609a969dc2e34', implode(",", $type_array) ,$inserted_pokemon);
                // Type Taxonomy
                wp_set_object_terms( $inserted_pokemon, $type_array, 'pokemon-type');
            }
        }
        sleep(1);
    }

    $offset = $offset + 50;
    sleep(1);

    wp_remote_post(admin_url('admin-ajax.php?action=get_pokemon_from_api'), [
        'blocking' => false,
        'sslverify' => false,
        'body' => [
            'offset' => $offset
        ]
    ]);

}

function get_pokemon_data($pokemon_url){
    return json_decode(wp_remote_retrieve_body(wp_remote_get($pokemon_url)));
}

function pokemon_taxonomy_filter($query) {
    if ( ! is_admin() && $query->is_archive() && $query->is_main_query() ) {
            $query->set('orderby','meta_value_num');
            $query->set('posts_per_page', 50);
            $query->set('meta_key','pokedex_id',);
            $query->set('order','ASC');
    }
}
add_action( 'pre_get_posts', 'pokemon_taxonomy_filter' );
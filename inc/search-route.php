<?php

add_action('rest_api_init', 'pokepressRegisterSearch');

function pokepressRegisterSearch(){
    register_rest_route( 'pokepress/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'pokepressSearchResults'
   ));
}

function pokepressSearchResults($data){
    $mainQuery = new WP_Query(array(
        'post_type' => 'pokemon',
        'orderby' => 'meta_value_num',
        'meta_key' => 'pokedex_id',
        'order'	=> 'ASC',
        'posts_per_page' => 10,
        's' => sanitize_text_field($data['term'])
    ));
    $results = [];

    while($mainQuery->have_posts()){
        $mainQuery->the_post();
        array_push($results,
                array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'id' => str_pad(get_field('pokedex_id'), 3 , "0" ,STR_PAD_LEFT),
                    'icon' => get_field('icon'),
                )
    );
    }

    return $results;
}
<?php get_header(); ?>

<?php

// The Query
$the_query = new WP_Query( array(
    'post_type' => 'pokemon',
    'posts_per_page' => 50,
    'paged' => get_query_var( 'paged' ),
    'orderby' => 'meta_value_num',
    'meta_key' => 'pokedex_id',
    'order'	=> 'ASC'
    ));
?>

<div class="pokepress_pagination">
    <?php pagination_bar($the_query); ?>
</div>

<div class="card-grid">
    <?php
    // The Loop
    if ( $the_query->have_posts() ) :
        while ($the_query->have_posts()) :
            $the_query->the_post();
        ?>
        <a class="page_link" href="<?php the_permalink(); ?>">
        <div class="card">
            <div class="card_image">
                <img class="pokemon_image" src="<?php the_field('image') ?>" alt="<?php the_title() ?>">
            </div>
            <div class="card_description">
                <h4 class="pokedex_id"><?= str_pad(get_field('pokedex_id'), 3 , "0" ,STR_PAD_LEFT) ?></h4>
                <h3 class="pokemon_title"><?php the_title() ?></h3>
                <div class="pokemon_types">
                    <?php
                        $types = explode(",", get_field('type'));
                        foreach ($types as $type) {
                            echo '<h5 class="pokedex_type ' . $type . '">' . $type . '</h5>';
                        }
                    ?>
                </div>
            </div>

        </div>
        </a>

        <?php endwhile; ?>
    <?php endif; ?>
</div>

<div class="pokepress_pagination">
    <?php pagination_bar($the_query); ?>
</div>

<?php

/* Restore original Post Data */
wp_reset_postdata();


// Load Footer
get_footer();

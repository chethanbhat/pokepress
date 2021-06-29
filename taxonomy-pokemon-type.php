<?php get_header(); ?>

<h1 class="page_title"><?php echo get_the_archive_title() ?></h1>

<div class="pokepress_pagination">
    <?php pagination_bar($wp_query); ?>
</div>

<div class="card-grid">
    <?php
    // The Loop
    if ( have_posts() ) :
        while (have_posts()) :
            the_post();
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
                        $types = get_the_terms( get_the_ID(), 'pokemon-type' );
                        foreach ($types as $type) {
                            echo '<a class="page_link" href="' . esc_url(site_url('/')) . 'pokemon-type/'  . $type->slug  . '"><h5 class="pokedex_type ' . $type->name . '">' . $type->name . '</h5></a>';
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
    <?php pagination_bar($wp_query); ?>
</div>



<?php

// Load Footer
get_footer();

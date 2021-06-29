<?php get_header(); ?>


    <?php
        while (have_posts()) :
           the_post();
        ?>
        <?php get_template_part('template_parts/single-pagination') ?>
        <div class="page_card">
            <div class="page_card_left">
                <img class="pokemon_image" src="<?php the_field('image') ?>" alt="<?php the_title() ?>">
                <h3 class="pokemon_title"><?php the_title() ?></h3>
                <h4 class="pokedex_id"><?= str_pad(get_field('pokedex_id'), 3 , "0" ,STR_PAD_LEFT) ?></h4>
                <div class="pokemon_types">
                    <?php
                        $types = get_the_terms( get_the_ID(), 'pokemon-type' );
                        foreach ($types as $type) {
                            echo '<a class="page_link" href="' . esc_url(site_url('/')) . 'pokemon-type/'  . $type->slug  . '"><h5 class="pokedex_type ' . $type->name . '">' . $type->name . '</h5></a>';
                        }
                    ?>
                </div>
            </div>
            <div class="page_card_right">
                <h2>Details</h2>
                <h4>Height: <span class="data"><?php the_field('height'); ?> in <span></h4>
                <h4>Weight: <span class="data"><?php the_field('weight'); ?> lbs <span></h4>
                <h4>Base Exp: <span class="data"><?php the_field('base_experience'); ?><span></h4>
                <hr>
                <h2>Stats</h2>
                <div class="stat">
                    <h4>HP</h4>
                    <div class="progress_bar pb_stats_hp">
                        <div class="progress stats_hp" style="width:<?php the_field('stat_hp') ?>%"></div>
                    </div>
                </div>
                <div class="stat">
                    <h4>Speed</h4>
                    <div class="progress_bar pb_stats_speed">
                        <div class="progress stats_speed" style="width:<?php the_field('stat_speed') ?>%"></div>
                    </div>
                </div>
                <div class="stat">
                    <h4>Attack</h4>
                    <div class="progress_bar pb_stats_attack">
                        <div class="progress stats_attack" style="width:<?php the_field('stat_attack') ?>%"></div>
                    </div>
                </div>
                <div class="stat">
                    <h4>Defence</h4>
                    <div class="progress_bar pb_stats_defence">
                        <div class="progress stats_defence" style="width:<?php the_field('stat_defence') ?>%"></div>
                    </div>
                </div>
                <div class="stat">
                    <h4>Spl. Attack</h4>
                    <div class="progress_bar pb_stats_spl_attack">
                        <div class="progress stats_spl_attack" style="width:<?php the_field('stat_spl_attack') ?>%"></div>
                    </div>
                </div>
                <div class="stat">
                    <h4>Spl. Defence</h4>
                    <div class="progress_bar pb_stats_spl_defence">
                        <div class="progress stats_spl_defence" style="width:<?php the_field('stat_spl_defence') ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php get_template_part('template_parts/single-pagination') ?>


        <?php
        endwhile;

/* Restore original Post Data */
wp_reset_postdata();


// Load Footer
get_footer();

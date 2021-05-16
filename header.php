<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body>
<header>
    <a class="page_link" href="<?php echo esc_url(site_url()); ?>">
        <h1 class="main_title">PokePress !</h1>
    </a>
    <p>An effort to create a pokedex on WordPress using custom post type, advanced custom fields and api request to <a class="pokeapi" href="https://pokeapi.co/">PokeAPI</a></p>
        <div class="search">
            <div class="search_form">
            <input type="search" name="" id="search_input" placeholder="Search your favorite pokemon..." >
            <input type="submit" value="Search" id="search_btn">
        </div>
        <div id="search_results" class="search_results hide">
        </div>
    </div>
</header>
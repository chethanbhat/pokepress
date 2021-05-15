<!-- Pagination -->
<div class="single_post_pagination">
    <?php if($prevLink = get_previous_post_link()): ?>
    <span class="prev_link">
        <?php echo $prevLink; ?>
    </span>
    <?php else: ?>
        <div></div>
    <?php endif; ?>

    <span class="home_link">
        <a href="<?php echo esc_url(site_url('/')) ?>">Home</a>
    </span>

    <?php if($nextLink = get_next_post_link()): ?>
    <span class="next_link">
        <?php echo $nextLink; ?>
    </span>
    <?php else: ?>
    <div></div>
    <?php endif; ?>
</div>
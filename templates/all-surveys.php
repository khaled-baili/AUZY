<div class="container">
    <?php
    require_once WP_PLUGIN_DIR . '/auzy-tests/frontend.php';
    $frontend = new Frontend();
    $frontend->show_all_surveys();
    ?>
</div>
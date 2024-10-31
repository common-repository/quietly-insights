<?php
/**
 * Admin First-time Activation Notice
 */
?>

<div class="quietly-analytics__installed updated">
    <p>
        <?php
            /* translators: first time activation title; %s = <strong></strong> */
            printf( __( 'The %sQuietly Insights%s plugin has been activated!', QUIETLY_ANALYTICS_SLUG ), '<strong>', '</strong>' );
        ?>
    </p>
    <p>
        <?php
            /* translators: first time activation description */
            _e( 'Connect the plugin with your Quietly Insights Property ID to finish the installation.', QUIETLY_ANALYTICS_SLUG );
        ?>
    </p>
    <p>
        <a href="<?php echo admin_url( 'admin.php?page=' . QUIETLY_ANALYTICS_SLUG ); ?>" class="button-admin">
            <?php
                /* translators: first time activation connect button label */
                _e( 'Add your Property ID', QUIETLY_ANALYTICS_SLUG );
            ?>
        </a>
    </p>
</div>

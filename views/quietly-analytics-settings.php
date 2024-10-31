<?php
/**
 * Plugin Settings Screen
 */
    $logoPath = plugins_url( '../images/logo-quietly-insights.png', __FILE__ );
    $successPath = plugins_url( '../images/icon-check.png', __FILE__ );
    $failPath = plugins_url( '../images/icon-x.png', __FILE__ );
    $qap_id = get_option('qap_id');
?>
<div class="quietly-analytics__wrapper">
    <div class="quietly-analytics__header">
        <img src=<?php echo $logoPath ?> class="quietly-analytics__header--image" />
    </div>
    <div class="quietly-analytics__inner">
        <div class="quietly-analytics__module">
            <h2 class="quietly-analytics__module--header">
                What is Quietly Insights?
            </h2>
            <div class="quietly-analytics__module--content">
                Quietly Insights is a content marketing recommendations platform that helps content marketers focus on their strategy by measuring, analyzing, and summarizing data into actionable recommendations.<br /><br />
                Visit <a href="https://insights.quiet.ly" target="_blank">insights.quiet.ly</a> to learn more about Quietly Insights.
            </div>
        </div>
        <div class="quietly-analytics__module">
            <h2 class="quietly-analytics__module--header">
                Enter Your Property ID
            </h2>
            <div class="quietly-analytics__module--content">
                Get your Property ID from the <a href="https://insights.quiet.ly/app/#/0/install" target="_blank">Install page of your property settings</a> in Quietly Insights, and paste it here.<br />
                Can’t find it? Check out our <a href="https://insights.quiet.ly/support/dt_articles/install-quietly-insights-website/" target="_blank">detailed installation guide here</a>.
            </div>
            <div class="quietly-analytics__module--form" id="quietly-analytics__pin-form">
                <form method="post" action="options.php">
                    <input type="text" class="quietly-analytics__module--input" name="qap_id" value="<?php echo $qap_id; ?>" placeholder="Enter your Property ID here" />
                    <button type="submit" class="quietly-analytics__module--button">Verify</button>
                    <div class="quietly-analytics__module--spinner"></div>
                    <div class="quietly-analytics__module--result">
                        <img class="result-success" src=<?php echo $successPath ?> width="34">
                        <img class="result-fail" src=<?php echo $failPath ?> width="34">
                    </div>
                </form>
            </div>
            <div class="quietly-analytics__module--content">
                <div class="quietly-analytics__module--message -success">
                    You've successfully verified the Quietly Insights plugin! Go to <a href="https://insights.quiet.ly" target="_blank">insights.quiet.ly</a> to create/edit/manage your reports.<br />

                    Using a new Quietly Insights account for this website? <a href="#" class="quietly-analytics__module--re-enter">Click here to update the Property ID</a>.
                </div>
                <div class="quietly-analytics__module--message -fail">Oops! Something went wrong and we couldn't verify this Property ID. Please try again later, or go to <a href="https://insights.quiet.ly"  target="_blank">insights.quiet.ly</a> to view our FAQ and support section.</div>
            </div>
            <div class="quietly-analytics__module--content">
                Don't have a property Id? Sign up for Quietly Insights <a href="https://insights.quiet.ly" target="_blank">here</a>.
            </div>
        </div>
        <div class="quietly-analytics__module -no-border">
            <div class="quietly-analytics__module--content">
                Found a bug or have a feature request? Shoot us an email at <a href="mailto:productsupport@quiet.ly">productsupport@quiet.ly</a>.
            </div>
            <div class="quietly-analytics__module--content -padding-top">
                <strong>Resouces:</strong> <a href="http://insights.quiet.ly/support" target="_blank">Knowledge Base</a> | <a href="http://insights.quiet.ly" target="_blank">Plugin Website</a> | <a href="http://insights.quiet.ly/app" target="_blank">Quietly Insights Website</a>
            </div>
            <div class="quietly-analytics__module--content -padding-top">
                <strong>Plugin version:</strong> <?php echo QUIETLY_ANALYTICS_VERSION ?>
            </div>
        </div>
    </div>
</div>

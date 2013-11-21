

<div class="row">
    <div class="large-12 columns">
        <h2>System information</h2>
        <?php
        foreach ($view->error as $key => $domain) {
            echo "<h3>" . ucfirst($key) . "</h3>";


            foreach ($domain as $error) {
                echo '<div data-alert class="alert-box alert">' . $error . '</div>';
            }
        }
        
        if(!is_array($view->error))
        {
             echo "<h3>" . _("Congratulations") . "</h3>";
             echo '<div data-alert class="alert-box success">' . _("System is up and running") . '</div>';
        }
        ?>
    </div>
    
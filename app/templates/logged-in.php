<?php
    $this->layout('_json');
    $this->start('body');
?>
    "data": {
        "authenticated": true,
        "user": <?php
            $user = json_encode($session->toArray(), JSON_PRETTY_PRINT);

            if ($user !== false) {
                echo str_replace('}', "\t}", str_replace('    ', "\t\t", $user));
            } else {
                echo 'Error processing token';
            }
        ?>

    }
<?php $this->stop() ?>

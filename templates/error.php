<?php
    $this->layout('_json');
    $this->start('body');
?>
    "errors": [
        {
            "title": "<?php echo addcslashes($error, '"') ?>"
        }
    ]
<?php $this->stop() ?>

<?php
require_once '../vendor/autoload.php';
?>

<div class="ui warning icon message">
    <i class="exclamation triangle icon"></i>
    <div class="content">
        <div class="header">
            Alertas:
        </div>
        <ul class="list">
            <?php
            foreach ($messages as $item) {
                echo "<li>$item</li>" . PHP_EOL;
            }
            ?>
        </ul>
    </div>
</div>
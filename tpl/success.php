<?php
require_once '../vendor/autoload.php';
?>

<div class="ui success icon message">
    <i class="check square icon"></i>
    <div class="content">
        <div class="header">
            Sucesso:
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
<?php

require_once '../vendor/autoload.php';

carregaTemplate('header');
?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <div class="active section">Tags</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="hashtag icon"></i>
    <div class="content">
        Tags
        <div class="sub header">Operações envolvendo tags de receita e despesa.</div>
    </div>
</h2><!-- título -->

<!-- opções -->
<div class="ui list">
    <div class="item">
        <i class="angle right icon"></i>
        <div class="content">
            <a class="header" href="tags-gerenciar.php">Tags</a>
            <div class="description">Gerencia a lista de tags.</div>
        </div>
    </div>
    
</div><!-- opções -->

<?php carregaTemplate('footer'); ?>
<?php

require_once '../vendor/autoload.php';

carregaTemplate('header');
?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <div class="active section">Receitas</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="plus circle icon"></i>
    <div class="content">
        Receitas
        <div class="sub header">Operações envolvendo receitas.</div>
    </div>
</h2><!-- título -->

<!-- opções -->
<div class="ui list">
    <div class="item">
        <i class="angle right icon"></i>
        <div class="content">
            <a class="header" href="receita-adicionar.php">Previsão individual</a>
            <div class="description">Inclui uma previsão de receita individual.</div>
        </div>
    </div>
    <div class="item">
        <i class="angle right icon"></i>
        <div class="content">
            <a class="header" href="receita-repetir.php">Repetição de previsão</a>
            <div class="description">Repete em vários meses uma mesma previsão da receita.</div>
        </div>
    </div>
    <div class="item">
        <i class="angle right icon"></i>
        <div class="content">
            <a class="header" href="receita-parcelar.php">Parcelamento de receita</a>
            <div class="description">Lança uma receita com o recebimento parcelado.</div>
        </div>
    </div>
    <div class="item">
        <i class="angle right icon"></i>
        <div class="content">
            <a class="header" href="receitas-gerenciar.php">Receitas</a>
            <div class="description">Gerencia as receitas registradas.</div>
        </div>
    </div>
</div><!-- opções -->

<?php carregaTemplate('footer'); ?>
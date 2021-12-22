<?php

require_once '../vendor/autoload.php';

carregaTemplate('header');
?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <div class="active section">Contábil</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="closed captioning icon"></i>
    <div class="content">
        Contabilidade
        <div class="sub header">Operações contábeis.</div>
    </div>
</h2><!-- título -->

<!-- opções -->
<div class="ui list">
    <div class="item">
        <i class="angle right icon"></i>
        <div class="content">
            <a class="header" href="lancar-transacao-manual.php">Transação manual</a>
            <div class="description">Lança uma transação de forma manual.</div>
        </div>
    </div>
    <div class="item">
        <i class="angle right icon"></i>
        <div class="content">
            <a class="header" href="gerir-planodecontas.php">Plano de contas</a>
            <div class="description">Gerenciamento do plano de contas contábeis.</div>
        </div>
    </div>
    <div class="item">
        <i class="angle right icon"></i>
        <div class="content">
            <a class="header" href="gerenciar-transacoes.php">Transações</a>
            <div class="description">Gerencia as transações registradas.</div>
        </div>
    </div>
</div><!-- opções -->

<?php carregaTemplate('footer'); ?>
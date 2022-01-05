<?php

require_once '../vendor/autoload.php';

carregaTemplate('header');
?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <div class="active section">Despesas</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="minus circle icon"></i>
    <div class="content">
        Despesas
        <div class="sub header">Operações envolvendo despesas.</div>
    </div>
</h2><!-- título -->

<!-- opções -->
<div class="ui list">
    <div class="item">
        <i class="angle right icon"></i>
        <div class="content">
            <a class="header" href="despesa-adicionar.php">Previsão individual</a>
            <div class="description">Inclui uma previsão de despesa individual.</div>
        </div>
    </div>
    <div class="item">
        <i class="angle right icon"></i>
        <div class="content">
            <a class="header" href="despesa-repetir.php">Repetição de previsão</a>
            <div class="description">Repete em vários meses uma mesma previsão da despesa.</div>
        </div>
    </div>
    <div class="item">
        <i class="angle right icon"></i>
        <div class="content">
            <a class="header" href="despesa-parcelar.php">Parcelamento de despesa</a>
            <div class="description">Lança uma despesa parcelada.</div>
        </div>
    </div>
    <div class="item">
        <i class="angle right icon"></i>
        <div class="content">
            <a class="header" href="despesas-gerenciar.php">Despesas</a>
            <div class="description">Gerencia as despesas registradas.</div>
        </div>
    </div>
    <div class="item">
        <i class="angle right icon"></i>
        <div class="content">
            <a class="header" href="despesas-mp.php">Pagar despesas selecionadas por meio de pagamento</a>
            <div class="description">Permite pagar várias despesas de acordo com um meio de pagamento.</div>
        </div>
    </div>
    <div class="item">
        <i class="angle right icon"></i>
        <div class="content">
            <a class="header" href="mp-gerenciar.php">Meios de pagamento</a>
            <div class="description">Gerenciamento dos meios de pagamento.</div>
        </div>
    </div>
</div><!-- opções -->

<?php carregaTemplate('footer'); ?>
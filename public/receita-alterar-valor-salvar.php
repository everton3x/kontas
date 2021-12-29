<?php
// print_r($_POST);
require_once '../vendor/autoload.php';
carregaTemplate('header');

$cod = 0;
if (key_exists('cod', $_POST)) $cod = $_POST['cod'];

$valor = 0.0;
if (key_exists('valor', $_POST)) $valor = $_POST['valor'];

$observacao = '';
if (key_exists('observacao', $_POST)) $observacao = $_POST['observacao'];

$result = salvarAlteracaoReceita($cod, $valor, $observacao);
?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <a class="section" href="receitas-painel.php">Receitas</a>
    <div class="divider"> / </div>
    <div class="section">Previsão</div>
    <div class="divider"> / </div>
    <div class="section">Alteração</div>
    <div class="active section">Salvando...</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="save icon"></i>
    <div class="content">
        Alteração da previsão da receita
        <!-- <div class="sub header">Operações contábeis.</div> -->
    </div>
</h2>
<div class="ui divider"></div><!-- título -->

<?php
if ($result['success'] === true) {
    carregaTemplate('success', ['messages' => $result['messages']]);
} elseif ($result['success'] === false) {
    carregaTemplate('error', ['messages' => $result['errors']]);
} else {
    carregaTemplate('warning', ['messages' => ['Retorno inesperado!']]);
}
?>

<div class="ui buttons">
    <a class="ui primary button" href="receita-detalhe.php?cod=<?=$cod;?>">Detalhes</a>
    <div class="or" data-text="ou"></div>
    <a class="ui positive button" href="index.php#receita_<?= $cod; ?>">Início</a>
</div>

<?php carregaTemplate('footer'); ?>
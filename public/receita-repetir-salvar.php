<?php
// print_r($_POST);
require_once '../vendor/autoload.php';
carregaTemplate('header');

$periodo = false;
if (key_exists('periodo', $_POST)) $periodo = $_POST['periodo'];

$valorInicial = false;
if (key_exists('valorInicial', $_POST)) $valorInicial = $_POST['valorInicial'];

$descricao = false;
if (key_exists('descricao', $_POST)) $descricao = $_POST['descricao'];

$agrupador = '';
if (key_exists('agrupador', $_POST)) $agrupador = $_POST['agrupador'];

$parcela = 1;
if (key_exists('parcela', $_POST)) $parcela = $_POST['parcela'];

$tags = [];
if (key_exists('tags', $_POST)) $tags = $_POST['tags'];

$result = salvarReceitaRepetida($periodo, $descricao, $valorInicial, $agrupador, $parcela, $tags);
// $result['success'] = true;
// $result['messages'][] = 'testes';
// $result['cod'] = '15f039898beb244f5250933e237fb74d971f44ee';
// print_r($result);
?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <a class="section" href="receitas-painel.php">Receitas</a>
    <div class="divider"> / </div>
    <a class="section" href="receitas-adicionar.php">Repetição</a>
    <div class="divider"> / </div>
    <div class="active section">Salvando...</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="save icon"></i>
    <div class="content">
        Repetição da previsão da receita
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
    <a class="ui button" href="receita-repetir.php">Novo</a>
    <div class="or" data-text="ou"></div>
    <a class="ui positive button" href="index.php">Início</a>
</div>

<?php carregaTemplate('footer'); ?>
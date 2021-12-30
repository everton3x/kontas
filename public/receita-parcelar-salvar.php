<?php
// print_r($_POST);
// exit();
require_once '../vendor/autoload.php';
carregaTemplate('header');

$parcela = 2;
if (key_exists('parcela', $_POST)) $parcela = $_POST['parcela'];

$valorInicial = false;
if (key_exists('valorInicial', $_POST)) $valorInicial = $_POST['valorInicial'];

$tipoValor = '';
if (key_exists('tipoValor', $_POST)) $tipoValor = $_POST['tipoValor'];

switch ($tipoValor) {
    case 'parcela':
        $valor = round($valorInicial * $parcela, 2);
        break;
    case 'total':
        $valor = $valorInicial;
        break;
}

$descricao = false;
if (key_exists('descricao', $_POST)) $descricao = $_POST['descricao'];

$agrupador = false;
if (key_exists('agrupador', $_POST)) $agrupador = $_POST['agrupador'];


$parcelas = [];
if (key_exists('nrparcela', $_POST)) $parcelas = $_POST['nrparcela'];

$periodos = [];
if (key_exists('periodoparcela', $_POST)) $periodos = $_POST['periodoparcela'];

$valores = [];
if (key_exists('valorparcela', $_POST)) $valores = $_POST['valorparcela'];

$tags = [];
if (key_exists('tags', $_POST)) $tags = $_POST['tags'];

$result = salvarReceitaParcelada($periodos, $descricao, $valores, $agrupador, $parcelas, $tags, $valor, $parcela);
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
    <a class="section" href="receita-adicionar.php">Parcelamento</a>
    <div class="divider"> / </div>
    <div class="active section">Salvando...</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="save icon"></i>
    <div class="content">
        Parcelamento da receita
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
    <a class="ui button" href="receita-parcelar.php">Novo</a>
    <div class="or" data-text="ou"></div>
    <a class="ui positive button" href="index.php">Início</a>
</div>

<?php carregaTemplate('footer'); ?>
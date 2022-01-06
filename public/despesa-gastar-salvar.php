<?php
// print_r($_POST);
// exit();
require_once '../vendor/autoload.php';
carregaTemplate('header');

$cod = 0;
if (key_exists('cod', $_POST)) $cod = $_POST['cod'];

$data = '';
if (key_exists('data', $_POST)) $data = $_POST['data'];

$valor = 0.0;
if (key_exists('valor', $_POST)) $valor = $_POST['valor'];

$vencimento = null;
if (key_exists('vencimento', $_POST)) $vencimento = $_POST['vencimento'];

$observacao = null;
if (key_exists('observacao', $_POST)) $observacao = $_POST['observacao'];

$mp = 0;
if (key_exists('mp', $_POST)) $mp = $_POST['mp'];

$autopagar = 0;
if (key_exists('autopagar', $_POST)) $autopagar = 1;


$result = salvarGasto($cod, $data, $valor, $vencimento, $observacao, $mp, $autopagar);

?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <a class="section" href="despesas-gerenciar.php">Despesas</a>
    <div class="divider"> / </div>
    <a class="section" href="javascript:history.back()">Gastar</a>
    <div class="divider"> / </div>
    <div class="active section">Salvando...</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="save icon"></i>
    <div class="content">
        Gastar
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
    <a class="ui button" href="despesas-gerenciar.php">Voltar</a>
    <div class="or" data-text="ou"></div>
    <a class="ui positive button" href="despesa-detalhe.php?cod=<?= $result['cod']; ?>">Detalhes</a>
</div>

<?php carregaTemplate('footer'); ?>
<?php
// print_r($_POST);
require_once '../vendor/autoload.php';
carregaTemplate('header');

$cod = 0;
if (key_exists('cod', $_POST)) $cod = $_POST['cod'];

$valor = 0.0;
if (key_exists('valor', $_POST)) $valor = $_POST['valor'];

$data = '';
if (key_exists('data', $_POST)) $data = $_POST['data'];

$observacao = '';
if (key_exists('observacao', $_POST)) $observacao = $_POST['observacao'];

$result = salvarRecebimento($cod, $valor, $data, $observacao);
?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <a class="section" href="receitas-gerenciar.php">Receitas</a>
    <div class="divider"> / </div>
    <a class="section" href="receita-detalhe.php?cod=<?=$cod;?>">Detalhe</a>
    <div class="divider"> / </div>
    <a class="section" href="javascript:history.back()">Receber</a>
    <div class="active section">Salvando...</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="save icon"></i>
    <div class="content">
        Recebimento de receita
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
    <a class="ui positive button" href="index.php">Início</a>
</div>

<?php carregaTemplate('footer'); ?>
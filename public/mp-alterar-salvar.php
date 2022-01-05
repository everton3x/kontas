<?php
// print_r($_POST);
// exit();
require_once '../vendor/autoload.php';
carregaTemplate('header');

$cod = 0;
if (key_exists('cod', $_POST)) $cod = $_POST['cod'];

$mp = '';
if (key_exists('mp', $_POST)) $mp = $_POST['mp'];

$autopagar = 0;
if (key_exists('autopagar', $_POST)) $autopagar = 1;

$status = 1;
if (key_exists('status', $_POST)) $status = 0;

$result = salvarAlteracaoMeioPagamento($cod, $mp, $autopagar, $status);
?>
<!-- trilha -->
<div class="ui breadcrumb">
<a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <a class="section" href="despesas-gerenciar.php">Despesas</a>
    <div class="divider"> / </div>
    <a class="section" href="mp-gerenciar.php">Meios de pagamento</a>
    <div class="divider"> / </div>
    <a class="section" href="javascript:history.back();">Alteração</a>
    <div class="active section">Salvando...</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="save icon"></i>
    <div class="content">
        Alteração de meio de pagamento
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
    <a class="ui primary button" href="mp-gerenciar.php">Voltar</a>
    <div class="or" data-text="ou"></div>
    <a class="ui positive button" href="index.php">Início</a>
</div>

<?php carregaTemplate('footer'); ?>
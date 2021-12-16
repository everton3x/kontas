<?php
// print_r($_POST);exit();
require_once '../vendor/autoload.php';

$codigo = $_GET['codigo'];
$info = buscarDadosDaContaContabil($codigo);
$nome = $info['nome'];

$result = excluirContaContabil($codigo);

carregaTemplate('header');
?>

<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <a class="section" href="painel-contabil.php">Contábil</a>
    <div class="divider"> / </div>
    <a class="section" href="gerir-planodecontas.php">Gerir plano de contas</a>
    <div class="divider"> / </div>
    <div class="active section">Conta contábil</div>
    <div class="divider"> :: </div>
    <div class="section">Excluindo...</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="trash icon"></i>
    <div class="content">
        Conta contábil <?= formatarCodigoContaContabil($codigo); ?> <?= $nome; ?>
        <!-- <div class="sub header">Listar, adicionar, editar e excluir contas contábeis.</div>-->
    </div>
</h2>
<div class="ui divider"></div><!-- título -->

<?php
if($result['success'] === false){
    carregaTemplate('error', [
        'messages' => $result['errors']
    ]);
}elseif($result['success'] === true){
    carregaTemplate('success', [
        'messages' => $result['messages']
    ]);
}else {
    carregaTemplate('warning', [
        'messages' => ['Retorno inválido!']
    ]);
}
?>

<?php carregaTemplate('footer'); ?>
<?php
// print_r($_POST);
require_once '../vendor/autoload.php';

$id = $_POST['id'];
$data = $_POST['data'];
$historico = $_POST['historico'];
$lancamentos = $_POST['lancamentos'];
$data = date_create_from_format('Y-m-d', $data);
$result = adicionarTransacao($id, $data, $historico, $lancamentos);
carregaTemplate('header');
?>

<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <a class="section" href="painel-contabil.php">Contábil</a>
    <div class="divider"> / </div>
    <a class="section" href="lancar-transacao-manual.php">Transação manual</a>
    <div class="divider"> : </div>
    <div class="active section">Salvando...</div>
</div><!-- trilha -->

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
carregaTemplate('footer');
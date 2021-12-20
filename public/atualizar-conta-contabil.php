<?php
// print_r($_POST);
require_once '../vendor/autoload.php';

$codigo = $_GET['codigo'];
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$debitaQuando = $_POST['debitaQuando'];
$debitaQuando = $_POST['debitaQuando'];
$creditaQuando = $_POST['creditaQuando'];
if(key_exists('devedor', $_POST) && !key_exists('credor', $_POST)){
    $naturezaSaldo = 'D';
}elseif(!key_exists('devedor', $_POST) && key_exists('credor', $_POST)){
    $naturezaSaldo = 'C';
}elseif(key_exists('devedor', $_POST) && key_exists('credor', $_POST)){
    $naturezaSaldo = 'DC';
}else{
    $naturezaSaldo = '';
}

$result = atualizarContaContabil($codigo, $nome, $descricao, $debitaQuando, $creditaQuando, $naturezaSaldo);

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
    <div class="section">Conta contábil</div>
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
?>

<div class="ui text menu">
    <div class="header item">O que deseja fazer agora?</div>
    <a class="item" href="detalhes-conta-contabil.php?codigo=<?=$codigo;?>">
        Voltar para os detalhes da conta
    </a>
    <a class="item" href="gerir-planodecontas.php#cc<?=$codigo;?>">
        Voltar para o plano de contas
    </a>
    <a class="item" href="painel-contabil.php">
        Voltar para as opções contábeis
    </a>
</div>

<?php carregaTemplate('footer');?>
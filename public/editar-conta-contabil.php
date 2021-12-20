<?php
// print_r($_POST);exit();
require_once '../vendor/autoload.php';

$codigo = $_GET['codigo'];
$info = buscarDadosDaContaContabil($codigo);
$tipoNivel = $info['tipoNivel'];
$nome = $info['nome'];
$descricao = $info['descricao'];
$debitaQuando = $info['debitaQuando'];
$creditaQuando = $info['creditaQuando'];
$naturezaSaldo = $info['naturezaSaldo'];
$status = $info['status'];
$eDevedor = '';
$eCredor = '';
switch ($naturezaSaldo) {
    case 'D':
        $eDevedor = 'checked ';
        break;
    case 'C':
        $eCredor = 'checked ';
        break;
    case 'DC':
        $eDevedor = 'checked ';
        $eCredor = 'checked ';
        break;

    default:
        break;
}

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
    <div class="section">Edição</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="edit icon"></i>
    <div class="content">
        Conta contábil <?= formatarCodigoContaContabil($codigo); ?> <?= $nome; ?>
        <!-- <div class="sub header">Listar, adicionar, editar e excluir contas contábeis.</div>-->
    </div>
</h2>
<div class="ui divider"></div><!-- título -->

<!-- Formulário -->
<form class="ui form">
    <h4 class="ui dividing header">Informação Editável</h4>
    <div class="field">
        <label>Nome</label>
        <input type="text" name="nome" required autofocus value="<?= $nome; ?>">
    </div>
    <div class="field">
        <label>Descrição</label>
        <textarea name="descricao"><?= $descricao; ?></textarea>
    </div>
    <div class="field">
        <label>Quando debita</label>
        <textarea name="debitaQuando"><?= $debitaQuando; ?></textarea>
    </div>
    <div class="field">
        <label>Quando credita</label>
        <textarea name="creditaQuando"><?= $creditaQuando; ?></textarea>
    </div>
    <div class="grouped fields">
        <label>Natureza do saldo</label>
        <div class="ui <?=$eDevedor;?>checkbox">
            <input id="devedor" type="checkbox" name="devedor" <?= $eDevedor; ?>>
            <label for="devedor">Devedor</label>
        </div>
        <div class="ui <?=$eCredor;?>checkbox">
            <input id="credor" type="checkbox" name="credor" <?= $eCredor; ?>>
            <label for="credor">Credor</label>
        </div>
    </div>
    <!-- botões do formulário -->
    <div class="ui divider"></div>
    <button class="ui left floated negative button" formaction="gerir-planodecontas.php#cc<?=$codigo;?>" formmethod="GET"><i class="cancel icon"></i>Cancelar</button>
    <button class="ui right floated positive button" type="submit" formaction="atualizar-conta-contabil.php?codigo=<?=$codigo;?>" formmethod="POST"><i class="save icon"></i>Salvar</button>
    <!-- botões do formulário -->
</form><!-- Formulário -->
<script>
    $('.ui.checkbox')
        .checkbox();
</script>
<?php carregaTemplate('footer'); ?>
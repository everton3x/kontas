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
    <div class="section">Exclusão</div>
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

<!-- mensagem -->
<div class="ui warning message">
    <div class="header">
        <h3>Tem certeza que deseja apagar esta conta contábil?</h3>
        <p>Esta ação não poderá ser desfeita.</p>
    </div>
    <p>Só será possível excluir a conta contábil se:</p>
    <ul class="list">
        <li>For uma conta sintética sem contas filhas.</li>
        <li>For uma conta analítica sem lançamentos.</li>
    </ul>
    <div class="ui divider"></div>
    <div class="ui buttons">
        <a class="ui primary button" href="gerir-planodecontas.php#cc<?= $codigo; ?>">
            Quero desistir.
        </a>
        <a class="ui negative button" href="#confirmar">
            Quero realmente excluir!
        </a>
    </div>
</div>
<div class="ui divider"></div><!-- mensagem -->

<!-- tabela das contas -->
<table class="ui celled table">
    <thead>
        <tr>
            <th>Item</th>
            <th>Valor</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Nome</td>
            <td><?= $nome; ?></td>
        </tr>
        <tr>
            <td>Analítica/Sintética</td>
            <td><?= explicaTipoNivelContaContabil($tipoNivel); ?></td>
        </tr>
        <tr>
            <td>Natureza do saldo</td>
            <td><?= explicaNaturezaDoSaldo($naturezaSaldo); ?></td>
        </tr>
        <tr>
            <td>Descrição</td>
            <td><?= $descricao; ?></td>
        </tr>
        <tr>
            <td>Quando debita</td>
            <td><?= $debitaQuando; ?></td>
        </tr>
        <tr>
            <td>Quando credita</td>
            <td><?= $creditaQuando; ?></td>
        </tr>
        <tr>
            <td>Status</td>
            <td><?= explicaStatusDaContaContabil($status); ?></td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">
                <a id="confirmar"></a>
                <div class="ui icon buttons">
                    <a class="ui button" href="gerir-planodecontas.php#cc<?= $codigo; ?>">
                        <i class="angle left icon"></i>
                        Voltar
                    </a>
                    <a class="ui negative button" href="excluir-conta-contabil.php?codigo=<?= $codigo; ?>">
                        <i class="trash icon"></i>
                        Apagar
                    </a>
                </div>
            </th>
        </tr>
    </tfoot>
</table><!-- tabela das contas -->

<?php carregaTemplate('footer'); ?>
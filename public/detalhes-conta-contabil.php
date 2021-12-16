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
$hierarquia = pegarHierarquiaSuperiorDaContaContabil($codigo);

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
    <div class="section">Detalhes</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="eye icon"></i>
    <div class="content">
        Conta contábil <?= formatarCodigoContaContabil($codigo); ?> <?= $nome; ?>
        <!-- <div class="sub header">Listar, adicionar, editar e excluir contas contábeis.</div>-->
    </div>
</h2>
<div class="ui divider"></div><!-- título -->

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
                <div class="ui icon buttons">
                    <a class="ui button" href="gerir-planodecontas.php#cc<?= $codigo; ?>">
                        <i class="angle left icon"></i>
                        Voltar
                    </a>
                    <a class="ui primary button" href="editar-conta-contabil.php?codigo=<?= $codigo; ?>">
                        <i class="edit icon"></i>
                        Editar
                    </a>
                    <a class="ui negative button" href="confirmar-exclusao-conta-contabil.php?codigo=<?= $codigo; ?>">
                        <i class="trash icon"></i>
                        Apagar
                    </a>
                </div>
            </th>
        </tr>
    </tfoot>
</table><!-- tabela das contas -->

<!-- título -->
<h4 class="ui header">
    <i class="sitemap icon"></i>
    <div class="content">
        Hierarquia da Conta contábil
        <!-- <div class="sub header">Listar, adicionar, editar e excluir contas contábeis.</div>-->
    </div>
</h4>
<div class="ui divider"></div><!-- título -->
<!-- tabela da hierarquia -->
<table class="ui celled table">
    <thead>
        <tr>
            <th>Código</th>
            <th>Nome</th>
            <th>Nível</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach($hierarquia as $item){
                $dados = buscarDadosDaContaContabil($item);
                $codFormatado = formatarCodigoContaContabil($dados['codigo']);
                echo '<tr><td>';
                echo "<a href='detalhes-conta-contabil.php?codigo={$dados['codigo']}'>{$codFormatado}</a>";
                echo '</td><td>';
                echo $dados['nome'];
                echo '</td><td>';
                echo explicaTipoNivelContaContabil($dados['tipoNivel']);
                echo '</td></tr>';
            }
        ?>
    </tbody>
</table><!-- tabela da hierarquia -->

<?php carregaTemplate('footer'); ?>
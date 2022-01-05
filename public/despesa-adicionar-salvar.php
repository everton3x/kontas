<?php
// print_r($_POST);
// exit();
require_once '../vendor/autoload.php';
carregaTemplate('header');

$periodo = false;
if (key_exists('periodo', $_POST)) $periodo = $_POST['periodo'];

$valorInicial = false;
if (key_exists('valorInicial', $_POST)) $valorInicial = $_POST['valorInicial'];

$descricao = false;
if (key_exists('descricao', $_POST)) $descricao = $_POST['descricao'];

$agrupador = '';
if (key_exists('agrupador', $_POST)) $agrupador = $_POST['agrupador'];

$parcela = 0;
if (key_exists('parcela', $_POST)) $parcela = $_POST['parcela'];

$gastoem = null;
if (key_exists('gastoem', $_POST) && strlen($_POST['gastoem']) > 0) $gastoem = $_POST['gastoem'];
$mp = 0;
if (key_exists('mp', $_POST)) $mp = $_POST['mp'];
$autopagar = 0;
if (key_exists('autopagar', $_POST)) $autopagar = $_POST['autopagar'];

$tags = [];
if (key_exists('tags', $_POST)) $tags = $_POST['tags'];

$result = salvarDespesa($periodo, $descricao, $valorInicial, $agrupador, $parcela, $tags, $gastoem, $mp, $autopagar);

?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <a class="section" href="despesas-gerenciar.php">Despesas</a>
    <div class="divider"> / </div>
    <a class="section" href="javascript:history.back()">Previsão</a>
    <div class="divider"> / </div>
    <div class="active section">Salvando...</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="save icon"></i>
    <div class="content">
        Previsão individual da despesa
        <!-- <div class="sub header">Operações contábeis.</div> -->
    </div>
</h2>
<div class="ui divider"></div><!-- título -->

<?php
if ($result['success'] === true) {
    carregaTemplate('success', ['messages' => $result['messages']]);
    $despesa = buscarDadosDaDespesa($result['cod']);
} elseif ($result['success'] === false) {
    carregaTemplate('error', ['messages' => $result['errors']]);
} else {
    carregaTemplate('warning', ['messages' => ['Retorno inesperado!']]);
}
?>

<?php if ($despesa !== []) : ?>
    <table class="ui table">
        <tbody>
            <tr>
                <td>Código</td>
                <td><?=$despesa['cod'];?></td>
            </tr>
            <tr>
                <td>Período</td>
                <td><?=int2DateTime($despesa['periodo'])->format('F/Y');?></td>
            </tr>
            <tr>
                <td>Descrição</td>
                <td><?=$despesa['descricao'];?></td>
            </tr>
            <tr>
                <td>Valor</td>
                <td><?=formatNumber($despesa['valorInicial']);?></td>
            </tr>
            <tr>
                <td>Agrupador</td>
                <td><?=$despesa['agrupador'];?></td>
            </tr>
            <tr>
                <td>Parcela</td>
                <td><?=$despesa['parcela'];?></td>
            </tr>
        </tbody>
    </table>
    <div class="ui buttons">
        <a class="ui button" href="despesa-adicionar.php">Novo</a>
        <div class="or" data-text="ou"></div>
        <a class="ui positive button" href="despesa-detalhe.php?cod=<?= $result['cod']; ?>">Detalhes</a>
    </div>
<?php endif; ?>

<?php carregaTemplate('footer'); ?>
<?php
// print_r($_POST);
// exit();
require_once '../vendor/autoload.php';
carregaTemplate('header');

$periodo = false;
if (key_exists('periodo', $_POST)) $periodo = $_POST['periodo'];

$repeticoes = false;
if (key_exists('repeticoes', $_POST)) $repeticoes = $_POST['repeticoes'];

$valorInicial = false;
if (key_exists('valorInicial', $_POST)) $valorInicial = $_POST['valorInicial'];

$descricao = false;
if (key_exists('descricao', $_POST)) $descricao = $_POST['descricao'];

$agrupador = '';
if (key_exists('agrupador', $_POST)) $agrupador = $_POST['agrupador'];

$parcela = 0;
if (key_exists('parcela', $_POST)) $parcela = $_POST['parcela'];

$tags = [];
if (key_exists('tags', $_POST)) $tags = $_POST['tags'];

$result = [
    'success' => true,
    'errors' => [],
    'messages' => []
];
for ($i = 0; $i < $repeticoes; $i++) {
    $item = salvarDespesa($periodo, $descricao, $valorInicial, $agrupador, $parcela, $tags, null, null, null);
    if ($item['success'] === false) $result['success'] = false;
    $result['errors'] = array_merge($result['errors'], $item['errors']);
    $result['messages'] = array_merge($result['messages'], $item['messages']);
    $periodo = proximoPeriodo($periodo)->format('Y-m');
}

?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <a class="section" href="despesas-gerenciar.php">Despesas</a>
    <div class="divider"> / </div>
    <a class="section" href="javascript:history.back()">Repetir</a>
    <div class="divider"> / </div>
    <div class="active section">Salvando...</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="save icon"></i>
    <div class="content">
        Previsão repetida da despesa
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
    <a class="ui button" href="despesa-repetir.php">Novo</a>
    <div class="or" data-text="ou"></div>
    <a class="ui positive button" href="despesas-gerenciar.php">Voltar</a>
</div>

<?php carregaTemplate('footer'); ?>
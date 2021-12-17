<?php
// print_r($_POST);exit();
require_once '../vendor/autoload.php';

$pai = $_GET['pai'];
$infoPai = buscarDadosDaContaContabil($pai);
$listaContas = buscarContasContabeisPossiveis($pai);
$contas = [];
foreach ($listaContas as $item) {
    $contas[$item] = buscarDadosDaContaContabil($item);
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
    <div class="section">Criar</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="plus icon"></i>
    <div class="content">
        Conta contábil superior <?= formatarCodigoContaContabil($pai); ?> <?= $infoPai['nome']; ?>
        <!-- <div class="sub header">Listar, adicionar, editar e excluir contas contábeis.</div>-->
    </div>
</h2>
<div class="ui divider"></div><!-- título -->

<!-- título -->
<h4 class="ui header">
    <i class="sitemap icon"></i>
    <div class="content">
        Contas contábeis irmãs
        <!-- <div class="sub header">Listar, adicionar, editar e excluir contas contábeis.</div>-->
    </div>
</h4>
<div class="ui divider"></div><!-- título -->
<!-- tabela de irmãs -->
<table class="ui celled table">
    <thead>
        <tr>
            <th></th>
            <th>Código</th>
            <th>Nome</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($contas as $codigo => $item) {
            if ($item === []) {
                echo '<tr><td class="center aligned">', "<input type='radio' name='codigo' value='$codigo' form='form-detalhes'>", '</td><td>', formatarCodigoContaContabil($codigo), '</td><td><a href="#detalhes"><i class="angle double down icon"></i></a></td></tr>', PHP_EOL;
            } else {
                echo '<tr><td></td><td>', formatarCodigoContaContabil($codigo), '</td><td>', $item['nome'], '</td></tr>', PHP_EOL;
            }
        }
        ?>
    </tbody>
</table><!-- tabela de irmãs -->

<a id="detalhes"></a>
<!-- título -->
<h2 class="ui header">
    <i class="edit icon"></i>
    <div class="content">
        Detalhes da conta contábil
        <!-- <div class="sub header">Listar, adicionar, editar e excluir contas contábeis.</div>-->
    </div>
</h2>
<div class="ui divider"></div><!-- título -->

<!-- Formulário -->
<form class="ui form" id="form-detalhes">
    <h4 class="ui dividing header">Detalhes da conta contábil</h4>
    <div class="required field">
        <label>Nome</label>
        <input type="text" name="nome" required>
    </div>
    <div class="grouped fields">
        <label for="fruit">Nível da conta contábil:</label>
        <div class="field">
            <div class="ui radio checkbox">
                <input type="radio" name="tipoNivel" class="hidden" value="S">
                <label>Sintético</label>
            </div>
        </div>
        <div class="field">
            <div class="ui radio checkbox">
                <input type="radio" name="tipoNivel" class="hidden" value="A">
                <label>Analítico</label>
            </div>
        </div>
    </div>
    <div class="field">
        <label>Descrição</label>
        <textarea name="descricao"></textarea>
    </div>
    <div class="field">
        <label>Quando debita</label>
        <textarea name="debitaQuando"></textarea>
    </div>
    <div class="field">
        <label>Quando credita</label>
        <textarea name="creditaQuando"></textarea>
    </div>
    <div class="grouped fields">
        <label for="fruit">Natureza do saldo</label>
        <div class="ui checkbox">
            <input type="checkbox" tabindex="0" class="hidden" name="devedor">
            <label>Devedor</label>
        </div>
        <div class="ui checkbox">
            <input type="checkbox" tabindex="0" class="hidden" name="credor">
            <label>Credor</label>
        </div>
    </div>
    <!-- botões do formulário -->
    <div class="ui divider"></div>
    <button class="ui left floated negative button" formaction="gerir-planodecontas.php#cc<?= $codigo; ?>" formmethod="GET"><i class="cancel icon"></i>Cancelar</button>
    <button class="ui right floated positive button" type="submit" formaction="adicionar-conta-contabil.php" formmethod="POST"><i class="save icon"></i>Salvar</button>
    <!-- botões do formulário -->
</form><!-- Formulário -->
<script>
    $('.ui.checkbox')
        .checkbox();
    $('.ui.radio.checkbox')
        .checkbox();
</script>

<?php carregaTemplate('footer'); ?>
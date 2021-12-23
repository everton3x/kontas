<?php
// print_r($_POST);exit();
require_once '../vendor/autoload.php';
carregaTemplate('header');

$periodo = date('Y-m');
if (key_exists('periodo', $_POST)) $periodo = $_POST['periodo'];
if (key_exists('periodo', $_GET)) $periodo = $_GET['periodo'];

?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <a class="section" href="painel-receitas.php">Receitas</a>
    <div class="divider"> / </div>
    <div class="active section">Previsão individual</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="edit icon"></i>
    <div class="content">
        Previsão individual da receita
        <!-- <div class="sub header">Operações contábeis.</div> -->
    </div>
</h2>
<div class="ui divider"></div><!-- título -->

<!-- formulário -->
<form class="ui form">
    <h4 class="ui dividing header">Informações básicas</h4>
    <div class="required field">
        <label>Período</label>
        <input type="month" name="periodo" required autofocus value="<?= $periodo; ?>">
    </div>
    <div class="required field">
        <label>Descrição</label>
        <input type="text" name="descricao" required placeholder="Descreva a receita">
    </div>
    <div class="fields">
        <div class="three wide required field">
            <label>Valor</label>
            <input type="number" name="valorInicial" required min="0" step="0.01">
        </div>
        <div class="five wide required field">
            <label>Devedor</label>
            <input type="text" name="devedor" required placeholder="Escolha ou digite o nome do devedor." list="devedores">
            <datalist id="devedores">
                <option value="Fulano">
                <option value="Beltrano">
                <option value="Cicrano">
            </datalist>
        </div>
    </div>

    <h4 class="ui dividing header">Informações opcionais</h4>
    <div class="fields">
        <div class="five wide field">
            <label>Agrupador</label>
            <input type="text" name="agrupador" placeholder="Agrupador de parcelas/despesas">
        </div>
        <div class="two wide field">
            <label>Parcela</label>
            <input type="number" name="parcela" min="0" step="1">
        </div>
    </div>

    <h4 class="ui dividing header">Configurações contábeis</h4>
    <div class="field">
        <label>Conta de Resultado</label>
        <select name="ccResultado">
            <option value="">Nenhum</option>
            <?php
            echo montaOptionsDasContasContabeis();
            ?>
        </select>
    </div>
    <div class="field">
        <label>Conta de Ativo</label>
        <select name="ccAtivo">
            <option value="">Nenhum</option>
            <?php
            echo montaOptionsDasContasContabeis();
            ?>
        </select>
    </div>
    <div class="field">
        <label>Conta de Controle</label>
        <select name="ccCentroReceitaDespesa">
            <option value="">Nenhum</option>
            <?php
            echo montaOptionsDasContasContabeis();
            ?>
        </select>
    </div>
    <!-- botões do formulário -->
    <div class="ui divider"></div>
    <button class="ui left floated negative button" formaction=""><i class="cancel icon"></i>Cancelar</button>
    <button class="ui right floated positive button" type="submit" formaction="adicionar-receita-individual.php" formmethod="POST"><i class="save icon"></i>Salvar</button>
    <!-- botões do formulário -->
</form><!-- formulário -->

<?php carregaTemplate('footer'); ?>
<?php
// print_r($_POST);
require_once '../vendor/autoload.php';
carregaTemplate('header');

$periodo = date('Y-m');
if (key_exists('periodo', $_POST)) $periodo = $_POST['periodo'];
if (key_exists('periodo', $_GET)) $periodo = $_GET['periodo'];

$valorInicial = 0.0;
if (key_exists('valorInicial', $_POST)) $valorInicial = $_POST['valorInicial'];

$descricao = '';
if (key_exists('descricao', $_POST)) $descricao = $_POST['descricao'];

$agrupador = '';
if (key_exists('agrupador', $_POST)) $agrupador = $_POST['agrupador'];

$parcela = 0;
if (key_exists('parcela', $_POST)) $parcela = $_POST['parcela'];

$tags = [];
if (key_exists('tags', $_POST)) $tags = $_POST['tags'];

if (key_exists('tag', $_POST)) $tags[] = $_POST['tag'];
?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <a class="section" href="receitas-gerenciar.php">Receitas</a>
    <div class="divider"> / </div>
    <div class="active section">Previsão</div>
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
        <input type="text" name="descricao" required placeholder="Descreva a receita" value="<?= $descricao; ?>" autocomplete="off">
    </div>
    <div class="fields">
        <div class="three wide required field">
            <label>Valor</label>
            <input type="number" name="valorInicial" required min="0.01" step="0.01" value="<?= $valorInicial; ?>">
        </div>
    </div>

    <a id="taglist"></a>
    <h4 class="ui dividing header">Informações opcionais</h4>
    <div class="fiels">
        <div class="three wide field">
            <div class="ui action input">
                <input type="text" name="tag" placeholder="Informe as tags desejadas" list="tags" autocomplete="off">
                <button class="ui icon button" formaction="receita-adicionar.php#taglist" formmethod="POST">
                    <i class="plus squared icon"></i>
                </button>
            </div>
        </div>
        <div class="field">
            <div class="ui blue labels">
                <!-- tags -->
                <!--<div class="ui label">
                    <i class="hashtag icon"></i>
                    Tag1
                    <a href="">
                        <i class="delete icon"></i>
                    </a>
                </div>-->
                <?php foreach ($tags as $index => $tag) : ?>
                    <div class="ui label" id="tag_<?= $index; ?>">
                        <i class="hashtag icon"></i>
                        <?= $tag; ?>
                        <div class="detail">
                            <a href="#taglist" onclick="deletarTag(<?= $index; ?>)">
                                <i class="delete inverted link icon"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div><!-- tags -->
        </div>
    </div>
    <div class="fields">
        <div class="five wide field">
            <label>Agrupador</label>
            <input type="text" name="agrupador" placeholder="Agrupador de parcelas/despesas" value="<?= $agrupador; ?>" autocomplete="off">
        </div>
        <div class="two wide field">
            <label>Parcela</label>
            <input type="number" name="parcela" min="0" step="1" value="<?= $parcela; ?>">
        </div>
    </div>

    <h4 class="ui dividing header">Recebimento</h4>
    <div class="fields">
        <div class="four wide field">
            <label>Recebido em</label>
            <input type="date" name="recebidoem">
        </div>
    </div>

    <!-- botões do formulário -->
    <div class="ui divider"></div>
    <button class="ui left floated negative button" onclick="history.back()"><i class="cancel icon"></i>Cancelar</button>
    <button class="ui right floated positive button" type="submit" formaction="receita-adicionar-salvar.php" formmethod="POST"><i class="save icon"></i>Salvar</button>
    <!-- botões do formulário -->
    <?php foreach ($tags as $index => $tag) : ?>
        <input type="hidden" name="tags[<?= $index; ?>]" value="<?= $tag; ?>" id="tagf_<?= $index; ?>">
    <?php endforeach; ?>
</form><!-- formulário -->

<datalist id="tags">
    <!--<option value="Fulano">-->
    <?php foreach (listarTags() as $item) : ?>
        <option value="<?= $item['tag']; ?>">
        <?php endforeach; ?>
</datalist>

<script>
    function deletarTag(index) {
        idLabel = 'tag_' + index;
        idField = 'tagf_' + index;
        document.getElementById(idLabel).remove();
        document.getElementById(idField).remove();
    }
</script>

<?php carregaTemplate('footer'); ?>
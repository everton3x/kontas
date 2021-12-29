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

$parcela = 2;
if (key_exists('parcela', $_POST)) $parcela = $_POST['parcela'];

$tags = [];
if (key_exists('tags', $_POST)) $tags = $_POST['tags'];

if (key_exists('tag', $_POST) && strlen($_POST['tag']) > 0) $tags[] = $_POST['tag'];

$parcelas = [];
if (key_exists('parcelas', $_POST)) $parcelas[] = $_POST['parcelas'];

$tipoValor = '';
if (key_exists('tipoValor', $_POST)) $tipoValor = $_POST['tipoValor'];

$parcelas = [];
if (key_exists('calcular', $_GET)) {
    if ($tipoValor === 'parcela') {
        $valorTotal = round($_POST['valorInicial'] * $parcela, 2);
        $valorParcela = $_POST['valorInicial'];
    } else {
        $valorTotal = $_POST['valorInicial'];
        $valorParcela = round($_POST['valorInicial'] / $parcela, 2);
    }

    $saldo = $valorTotal;
    // $valor = $valorParcela;
    for ($i = 1; $i <= $parcela; $i++) {
        if($i == $parcela) {
            $valor = $saldo;
        }else{
            $valor = $valorParcela;
        }
        // echo "$parcela -> $i -> $saldo -> $valor<br>";
        $saldo = round($saldo - $valor, 2);
        $parcelas[$i] = [
            'periodo' => $periodo,
            'valor' => $valor
        ];
        $periodo = proximoPeriodo($periodo);
        if ($periodo instanceof DateTime) $periodo = $periodo->format('Y-m');
    }
}

?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <a class="section" href="receitas-painel.php">Receitas</a>
    <div class="divider"> / </div>
    <div class="active section">Parcelamento</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="edit icon"></i>
    <div class="content">
        Parcelamento da receita
        <!-- <div class="sub header">Operações contábeis.</div> -->
    </div>
</h2>
<div class="ui divider"></div><!-- título -->

<!-- formulário -->
<form class="ui form">
    <h4 class="ui dividing header">Informações básicas</h4>
    <div class="required field">
        <label>Período inicial</label>
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
        <div class="grouped fields">
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="tipoValor" class="hidden" value="total" <?=($tipoValor == 'total')? 'checked' : '';?>>
                    <label>Valor total</label>
                </div>
            </div>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="tipoValor" class="hidden" value="parcela" <?=($tipoValor == 'parcela')? 'checked' : '';?>>
                    <label>Valor da parcela</label>
                </div>
            </div>
        </div>
    </div>
    <div class="fields">
        <div class="five wide required field">
            <label>Agrupador</label>
            <input type="text" name="agrupador" placeholder="Agrupador de parcelas/despesas" value="<?= $agrupador; ?>" required autocomplete="off">
        </div>
        <div class="two wide required field">
            <label>Parcelas</label>
            <input type="number" name="parcela" min="2" step="1" value="<?= $parcela; ?>" required>
        </div>
        <button class="ui primary button" formaction="receita-parcelar.php?calcular=calcular#parcelas" formmethod="POST">
            <i class="calculator icon"></i>
            Calular
        </button>
    </div>

    <a id="taglist"></a>
    <h4 class="ui dividing header">Informações opcionais</h4>
    <div class="fiels">
        <div class="three wide field">
            <div class="ui action input">
                <input type="text" name="tag" placeholder="Informe as tags desejadas" list="tags" autocomplete="off">
                <?php
                    if($parcelas !== []){
                        $calcular = '?calcular=calcular';
                    }else{
                        $calcular = '';
                    }
                ?>
                <button class="ui icon button" formaction="receita-parcelar.php<?=$calcular;?>#taglist" formmethod="POST">
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

    <!-- parcelas -->

    <a id="parcelas"></a>
    <h4 class="ui dividing header">Parcelas</h4>
    <?php if ($parcelas !== []) : ?>
        <?php foreach ($parcelas as $index => $item) : ?>
            <div class="fields">
                <div class="field">
                    <label>Parcela</label>
                    <input type="number" name="nrparcela[<?= $index; ?>]" value="<?= $index; ?>" readonly>
                </div>
                <div class="field">
                    <label>Período</label>
                    <input type="month" name="periodoparcela[<?= $index; ?>]" value="<?= $item['periodo']; ?>" readonly>
                </div>
                <div class="field">
                    <label>Valor</label>
                    <input type="number" name="valorparcela[<?= $index; ?>]" value="<?= $item['valor']; ?>" required min="0.01" step="0.01" onchange="atualizaTotal()" class="parcela">
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
            <div class="field">
                <label>Total</label>
                <input id="total" value="<?=$valorTotal;?>" readonly type="number">
            </div>
    <!-- parcelas -->

    <!-- botões do formulário -->
    <div class="ui divider"></div>
    <button class="ui left floated negative button" formaction=""><i class="cancel icon"></i>Cancelar</button>
    <button class="ui right floated positive button" type="submit" formaction="receita-parcelar-salvar.php" formmethod="POST"><i class="save icon"></i>Salvar</button>
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

    function atualizaTotal(el)
    {
        var parcelas = document.getElementsByClassName('parcela');
        // console.log(parcelas);
        var total = 0.0;
        for(var i = 0; i < parcelas.length; i++){
            total = total + parseFloat(parcelas[i].value);
            // console.log(parcelas[i]);
        }
        // alert(total)
        document.getElementById('total').value = total;

    }

    $('.ui.radio.checkbox')
        .checkbox();
</script>

<?php carregaTemplate('footer'); ?>
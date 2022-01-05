<?php

require_once '../vendor/autoload.php';

carregaTemplate('header');

$con = conexao();

$mp = $con->prepare("SELECT * FROM mp ORDER BY mp ASC, status ASC");
$mp->execute();

?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <a class="section" href="despesas-painel.php">Despesas</a>
    <div class="divider"> / </div>
    <div class="active section">Meios de pagamento</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="credit card outline icon"></i>
    <div class="content">
        Meios de pagamento
        <!--<div class="sub header">Operações envolvendo receitas.</div>-->
    </div>
</h2><!-- título -->

<!-- tabela -->
<a id="lista"></a>
<table class="ui table">
    <thead>
        <tr>
            <th>Meio de pagamento</th>
            <th>Autopagar</th>
            <th>Status</th>
            <th class="right aligned">
                <a href="#novo" class="ui primary labeled icon button">
                    <i class="plus square icon"></i>
                    Novo
                </a>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($mp->fetchAll(PDO::FETCH_ASSOC) as $item) : ?>
            <tr>
                <td><?= $item['mp']; ?></td>
                <td>
                    <?php
                    switch ($item['autopagar']) {
                        case 0:
                            $autopagar = 'circle outline';
                            break;
                        case 1:
                            $autopagar = 'check circle outline';
                            break;
                        default:
                            $autopagar = 'question circle outline';
                            break;
                    }
                    ?>
                    <i class="<?= $autopagar; ?> icon"></i>
                </td>
                <td>
                    <?php
                    switch ($item['status']) {
                        case 0:
                            $status = 'check circle outline';
                            break;
                        case 1:
                            $status = 'circle outline';
                            break;
                        default:
                            $status = 'question circle outline';
                            break;
                    }
                    ?>
                    <i class="<?= $status; ?> icon"></i>
                </td>
                <td>
                    <a class="ui secondary icon button" href="mp-alterar.php?cod=<?= $item['cod']; ?>">
                        <i class="edit icon"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<!-- tabela -->

<!-- novo -->
<a id="novo"></a>
<h4 class="ui header">Novo</h4>
<div class="ui divider"></div>
<form class="ui form">
    <div class="required field">
        <label>Meio de pagamento</label>
        <input id="mp" type="text" name="mp" required autocomplete="off" placeholder="Nome do meio de pagamento">
    </div>
    <div class="field">
        <div class="ui toggle checkbox">
            <input type="checkbox" class="hidden" name="autopagar">
            <label>Autopagar</label>
        </div>
    </div>
    <!-- botões do formulário -->
    <div class="ui divider"></div>
    <a class="ui left floated negative button"><i class="cancel icon" href="#lista"></i>Cancelar</a>
    <button class="ui right floated positive button" type="submit" formaction="mp-adicionar-salvar.php" formmethod="POST"><i class="save icon"></i>Salvar</button>
    <!-- botões do formulário -->
</form>
<script>
    $('.ui.checkbox')
        .checkbox();
</script>


<!-- novo -->

<?php carregaTemplate('footer'); ?>
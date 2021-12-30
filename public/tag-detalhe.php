<?php

require_once '../vendor/autoload.php';

carregaTemplate('header');

$tag = '';
if (key_exists('tag', $_GET)) $tag = $_GET['tag'];

$con = conexao();
$receitas = $con->prepare("SELECT receitasresumo.* FROM receitasresumo, tags WHERE receitasresumo.cod = tags.receita AND tags.tag LIKE :tag ORDER BY periodo ASC");
$receitas->execute([
    ':tag' => $tag
]);
?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <a class="section" href="">Tags</a>
    <div class="divider"> / </div>
    <div class="active section">Detalhes</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="search plus icon"></i>
    <div class="content">
        Detalhes da tag #<?=$tag;?>
    </div>
</h2><!-- título -->

<a id="receitas">

<!-- receitas -->
<h3 class="ui header">
    <i class="plus circle icon"></i>
    <div class="content">
        Receitas
    </div>
</h3>

<table class="ui table">
    <thead>
        <tr>
            <th>Período</th>
            <th>Descrição</th>
            <th class="right aligned">Previsto</th>
            <th class="right aligned">Recebido</th>
            <th class="right aligned">A receber</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($receitas->fetchAll(PDO::FETCH_ASSOC) as $item) : ?>
            <tr>
                <td><?= int2DateTime($item['periodo'])->format('F/Y'); ?></td>
                <td><?= $item['descricao']; ?></td>
                <td class="right aligned"><?= formatNumber(round($item['valorInicial'] + $item['alteracao'], 2)); ?></td>
                <td class="right aligned"><?= formatNumber($item['recebido']); ?></td>
                <td class="right aligned"><?= formatNumber(round($item['valorInicial'] + $item['alteracao'] - $item['recebido'], 2)); ?></td>
                <td>
                    <a class="ui secondary icon button" href="receita-detalhe.php?cod=<?= $item['cod']; ?>">
                        <i class="eye icon"></i>
                    </a>
                    <a class="ui primary icon button" href="receita-alterar-valor.php?cod=<?= $item['cod']; ?>">
                        <i class="random icon"></i>
                    </a>
                    <a class="ui green icon button" href="receita-receber.php?cod=<?= $item['cod']; ?>">
                        <i class="download icon"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<!-- receitas -->

<a id="despesas"></a>


<?php carregaTemplate('footer'); ?>
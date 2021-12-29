<?php

require_once '../vendor/autoload.php';

carregaTemplate('header');

$con = conexao();

if (key_exists('periodoInicial', $_POST)){
    $pInicial = date_create_from_format('Y-m', $_POST['periodoInicial']);
}else{
    $pInicial = new DateTime();
}
if (key_exists('periodoFinal', $_POST)){
    $pFinal = date_create_from_format('Y-m', $_POST['periodoFinal']);
}else{
    $pFinal = new DateTime();
    $pFinal->add(new DateInterval('P11M'));
}

if (key_exists('descricao', $_POST) && strlen($_POST['descricao']) > 0){
    $descricao = $_POST['descricao'];
}else{
    $descricao = '%';
}
$receitas = $con->prepare("SELECT * FROM receitasresumo WHERE (periodo BETWEEN :pi AND :pf) AND descricao LIKE :descricao ORDER BY periodo ASC");
$receitas->execute([
    ':pi' => $pInicial->format('Ym'),
    ':pf' => $pFinal->format('Ym'),
    ':descricao' => $descricao
]);

?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <div class="section">Receitas</div>
    <div class="divider"> / </div>
    <div class="active section">Gerenciar</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="tasks icon"></i>
    <div class="content">
        Gerenciamento de receitas
        <!--<div class="sub header">Operações envolvendo receitas.</div>-->
    </div>
</h2><!-- título -->

<!-- filtro -->
<h4 class="ui header">Filtros</h4>
<div class="ui divider"></div>
<form class="ui form" action="#lista">
    <h5 class="ui dividing header">Período</h5>
    <div class="fields">
        <div class="field">
            <label>Inicial</label>
            <input type="month" name="periodoInicial" value="<?= $pInicial->format('Y-m'); ?>">
        </div>
        <div class="field">
            <label>Final</label>
            <input type="month" name="periodoFinal" value="<?= $pFinal->format('Y-m'); ?>">
        </div>
    </div>
    <h5 class="ui dividing header">Descrição</h5>
    <div class="field">
        <label>Descrição</label>
        <input type="text" name="descricao" placeholder="Curingas: % e _, para vários ou único caracteres.">
    </div>
    <button class="ui primary right labeled icon button" formmethod="POST">
        Filtrar
        <i class="search icon"></i>
    </button>
</form>
<div class="ui divider"></div><!-- filtro -->

<!-- tabela -->
<a id="lista"></a>
<h4 class="ui header">Receitas</h4>
<div class="ui divider"></div>
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
                <td class="right aligned"><?= formatNumber($item['previsto']); ?></td>
                <td class="right aligned"><?= formatNumber($item['recebido']); ?></td>
                <td class="right aligned"><?= formatNumber($item['areceber']); ?></td>
                <td>
                    <a class="ui primary icon button" href="receita-detalhe.php?cod=<?=$item['cod'];?>">
                        <i class="eye icon"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<!-- tabela -->

<?php carregaTemplate('footer'); ?>
<?php

require_once '../vendor/autoload.php';

carregaTemplate('header');

$con = conexao();

if (key_exists('tag', $_POST) && strlen($_POST['tag']) > 0) {
    $tag = $_POST['tag'];
} else {
    $tag = '%';
}
$tags = $con->prepare("SELECT * FROM tags WHERE tag LIKE :tag ORDER BY tag ASC");
$tags->execute([
    ':tag' => $tag
]);

?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <a class="section" href="tags-painel.php">Tags</a>
    <div class="divider"> / </div>
    <div class="active section">Gerenciar</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="hashtag icon"></i>
    <div class="content">
        Gerenciamento de tags
        <!--<div class="sub header">Operações envolvendo receitas.</div>-->
    </div>
</h2><!-- título -->

<!-- filtro -->
<h4 class="ui header">Filtros</h4>
<div class="ui divider"></div>
<form class="ui form" action="#lista">
    <div class="field">
        <label>Tag</label>
        <input type="text" name="tag" placeholder="Curingas: % e _, para vários ou único caracteres.">
    </div>
    <button class="ui primary right labeled icon button" formmethod="POST">
        Filtrar
        <i class="search icon"></i>
    </button>
    <button class="ui secondary right labeled icon button" formaction="#lista" formmethod="POST">
        Limpar
        <i class="eraser icon"></i>
    </button>
</form>
<div class="ui divider"></div><!-- filtro -->

<!-- tabela -->
<a id="lista"></a>
<h4 class="ui header">Tags</h4>
<div class="ui divider"></div>
<table class="ui table">
    <thead>
        <tr>
            <th>Tag</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tags->fetchAll(PDO::FETCH_ASSOC) as $item) : ?>
            <tr>
                <td><?= $item['tag']; ?></td>
                <td>
                    <a class="ui secondary icon button" href="tag-detalhe.php?tag=<?= $item['tag']; ?>">
                        <i class="eye icon"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<!-- tabela -->

<?php carregaTemplate('footer'); ?>
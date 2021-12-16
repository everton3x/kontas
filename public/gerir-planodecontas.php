<?php
// print_r($_POST);exit();
require_once '../vendor/autoload.php';
carregaTemplate('header');
?>

<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <a class="section" href="painel-contabil.php">Contábil</a>
    <div class="divider"> / </div>
    <div class="active section">Gerir plano de contas</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="edit icon"></i>
    <div class="content">
        Plano de Contas
        <div class="sub header">Listar, adicionar, editar e excluir contas contábeis.</div>
    </div>
</h2>
<div class="ui divider"></div><!-- título -->

<!-- tabela das contas -->
<table class="ui celled table">
    <thead>
        <tr>
            <th>Código</th>
            <th>Nome</th>
            <th>Nível</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach(buscarContasContabeis() as $item){
            echo '<tr>';
            echo '<td class="left aligned">';
            echo "<a id='cc{$item['codigo']}'></a>";
            echo formatarCodigoContaContabil($item['codigo']);
            echo '</td>';
            echo '<td class="left aligned">';
            echo $item['nome'];
            echo '</td>';
            echo '<td class="center aligned">';
            echo $item['tipoNivel'];
            echo '</td>';
            echo '<td>';
            echo "<a class='ui right floated small negative icon button' href='confirmar-exclusao-conta-contabil.php?codigo={$item['codigo']}'><i class='trash icon'></i></a>";
            echo "<a class='ui right floated small primary icon button' href='editar-conta-contabil.php?codigo={$item['codigo']}'><i class='edit icon'></i></a>";
            switch ($item['tipoNivel']) {
                case 'S':
                    echo "<a class='ui right floated small positive icon button' href='adicionar-conta-contabil.php?pai={$item['codigo']}'><i class='plus icon'></i></a>";
                    break;
                    case 'A':
                        echo "<div class='ui disabled right floated small positive icon button'><i class='plus icon'></i></div>";
                        break;
                    }
                echo "<a class='ui right floated small basic icon button' href='detalhes-conta-contabil.php?codigo={$item['codigo']}'><i class='eye icon'></i></a>";
            echo '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table><!-- tabela das contas -->

<?php carregaTemplate('footer'); ?>
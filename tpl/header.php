<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Gerenciador de Orçamento e Finanças Pessoais.">
    <meta name="keywords" content="Finanças,Contabilidade,Orçamento">
    <meta name="author" content="Everton da Rosa">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <title>Kontas</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css" integrity="sha512-8bHTC73gkZ7rZ7vpqUQThUDhqcNFyYi2xgDgPDHc+GXVGHXq+xPjynxIopALmOPqzo9JZj0k6OqqewdGO3EsrQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js" integrity="sha512-dqw6X88iGgZlTsONxZK9ePmJEFrmHwpuMrsUChjAw1mRUhUITE5QU9pkcSox+ynfLhL15Sv2al5A0LVyDCmtUw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document)
            .ready(function() {

                // fix main menu to page on passing
                $('.main.menu').visibility({
                    type: 'fixed'
                });
                $('.overlay').visibility({
                    type: 'fixed',
                    offset: 80
                });

                // lazy load images
                // $('.image').visibility({
                //     type: 'image',
                //     transition: 'vertical flip in',
                //     duration: 500
                // });

                // show dropdown on hover
                // $('.main.menu  .ui.dropdown').dropdown({
                //     on: 'hover'
                // });
            });
    </script>

    <style type="text/css">
        body {
            /* background-color: #FFFFFF; */
        }

        .main.container {
            margin-top: 2em;
        }

        .main.menu {
            margin-top: 4em;
            border-radius: 0;
            border: none;
            box-shadow: none;
            transition:
                box-shadow 0.5s ease,
                padding 0.5s ease;
        }

        /* .main.menu .item img.logo {
            margin-right: 1.5em;
        } */

        .overlay {
            float: left;
            margin: 0em 3em 1em 0em;
        }

        .overlay .menu {
            position: relative;
            left: 0;
            transition: left 0.5s ease;
        }

        .main.menu.fixed {
            background-color: #FFFFFF;
            border: 1px solid #DDD;
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2);
        }

        .overlay.fixed .menu {
            left: 800px;
        }

        /* .text.container .left.floated.image {
            margin: 2em 2em 2em -4em;
        }

        .text.container .right.floated.image {
            margin: 2em -4em 2em 2em;
        } */

        .ui.footer.segment {
            margin: 5em 0em 0em;
            padding: 5em 0em;
        }
    </style>
</head>

<body>

    <div class="ui main text container">
        <h1 class="ui header">Kontas</h1>
        <p>Gerenciador de orçamento e finanças pessoais.</p>
    </div>


    <div class="ui borderless main menu">
        <div class="ui text container">
            <a class="header item" href="index.php">
                <i class="large kickstarter k icon"></i>
                Kontas
            </a>
            <div class="ui secondary labeled icon menu">
                <a class="item" href="receitas-painel.php">
                    <i class="plus circle icon"></i>
                    Receitas
                </a>
                <a class="item" href="despesas-painel.php">
                    <i class="minus circle icon"></i>
                    Despesas
                </a>
                <a class="item" href="tags-painel.php">
                    <i class="hashtag icon"></i>
                    Tags
                </a>
                <a class="item" href="relatorios-painel.php">
                    <i class="chart pie icon"></i>
                    Relatórios
                </a>
                <a class="item" href="outras-opcoes.php">
                    <i class="bars icon"></i>
                    Mais
                </a>
            </div>
        </div>
    </div>

    <!-- início do conteúdo -->
    <div class="ui container">
<?php
include_once("../../rotas.php");
require_once $funcoesRoute;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre nós - Hamtaro PetShop</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="../css-estatico/sobre-nos.css">
    <link rel="stylesheet" href="../css-estatico/header.css">
    <link rel="icon" href="../img-dinamico/dog-icon.png">

</head>

<body>
  <a href="" class="zap">
    <img src="../img-estatico/whatsapp.png" alt="whatsapp">
  </a>
    <header>
        <a href="<?php echo $homeRoute; ?>" class="logo">
            <img src="../img-estatico/logo.svg" alt="">
        </a>

        <div class="responsive">

            <img onmousedown="fechaMenu()" src="../img-estatico/fechar.png" class="fechaMenu" alt="fecha">
            <div class="links">
                <a href="<?php echo $homeRoute; ?>">HOME</a>
                <a href="<?php echo $blogRoute; ?>">BLOG</a>
                <a href="<?php echo $sobreRoute; ?>">SOBRE</a>
                <a href="<?php echo $contatoRoute; ?>">CONTATO</a>

            </div>

            <div class="acesso">
                <?php
                if (loged()) {
                    if (isset($_SESSION['tipo'])) {
                        // Se o usuário logado for um funcionário, ele é levado para a pág de agendamento
                        header("Location: " . $agendamentoFunRoute);
                    } else {
                        // Esses botões só aparecem quando o usuário estive logado
                        echo "
                        <a href='$fazAgendamentoCliRoute'>Fazer Agendamento</a>
                        <a href='$cadAnimaisCliRoute'>Cadastrar Animais</a>
                        ";
                    }
                } else {
                    // Esses botões aparecem se o usuário não estiver logado
                    echo "<a href='$loginCliRoute'><img src='../img-estatico/login.svg' alt=''> Login</a>";
                    echo "<a href='$cadastroCliRoute'>Cadastro</a>";
                }
                // if (isset($_SESSION['msgRotaProibidaCli'])){
                //   echo $_SESSION['msgRotaProibidaCli'];
                //   unset($_SESSION['msgRotaProibidaCli']);
                // }

                ?>
            </div>
        </div>



        <div class="perfilHambur">

            <?php
            if (loged()) {
                if (isset($_SESSION['tipo'])) {
                    // Se o usuário logado for um funcionário, ele é levado para a pág de agendamento
                    header("Location: " . $agendamentoFunRoute);
                } else {
                    // Esses botões só aparecem quando o usuário estive logado
                    echo "  <div class='perfil' onmousedown='menuPerfil()'>
                        <img src='../img-estatico/account_circle.svg'>
                        <p>></p>
                        </div>
                        
                        
                        <div class='menu-perfil'>
                        <p>Bem Vindo! " . $_SESSION['nomeCliente'] . "</p>
                        <a href='$meuPerfilCliRoute'><img src='../img-estatico/account_circle.svg'> Meu Perfil</a>
                        <a href='$animaisCliRoute'>Meus Animais</a>
                        <a href='$agendamentoCliRoute'>Meus Agendamentos</a>
                        <button onclick='executeFunctions(" . '"logoff" , ""' . ")'>Sair</button>
                        </div>";
                }
            }
            ?>

            <img onmousedown="abreMenu()" src="../img-estatico/menu.png" class="menu" alt="menu">
        </div>
    </header>
    <img src="../img-estatico/efeito-sobrenos.svg" alt="efeito de onda" class="efeito-onda">

    <div class="container">

        <section class="sobre-nos row">

            <img src="../img-estatico/cachorro-sobre-nos.svg" alt="" class="col-md-4">

            <div class="texto col-md-8">
                <h1 class="titulo">SOBRE NÓS</h1>
                <p>
                    Bem-vindo ao Hamtaro Pet Shop - O lugar perfeito para cuidar do seu melhor amigo!
                    Somos um pet shop dedicado a proporcionar aos animais de estimação e seus donos uma experiência excepcional. Nossa equipe é formada por amantes de animais apaixonados que estão comprometidos em cuidar dos seus animais de estimação como se fossem os nossos próprios.
                    No Hamtaro Pet Shop, acreditamos que todos os animais merecem amor, cuidado e atenção especial.</p>
            </div>
        </section>

    </div>

    <div class="container-fluid fundo-colaboradores">

        <section class="colaboradores container">

            <div class="titulos-box-colaboradores">
                <h2 class="titulo">COLABORADORES</h2>
                <p class="sub-titulo">Conheça a equipe que realizou a construção desse site para a aula de Banco de
                    Dados e Programação Web Back-end.</p>
            </div>

            <div class="box-cards">



                <div class="aluno">




                    <h3>Michael Almeida</h3>
                    <img src="michael.svg" alt="imagem do colaborador michael" class="perfil">

                    <div class="redes">
                        <a href="https://github.com/michaelsalmeida"><img src="../img-estatico/github-sobrenos.svg" alt="link do github do aluno michael" target="_blank"></a>

                        <a href="https://www.linkedin.com/in/michael-almeida-34a97b22a/"><img src="../img-estatico/linkedin.svg" alt="link do linkedin do aluno michael" target="_blank"></a>
                    </div>
                </div>



                <div class="aluno">

                    <h3>Luiz Gustavo</h3>
                    <img src="../img-estatico/gustavo.svg" alt="imagem do colaborador michael" class="perfil">

                    <div class="redes">
                        <a href="https://github.com/luizbrito6"><img src="../img-estatico/github-sobrenos.svg" alt="link do github do aluno luiz" target="_blank"></a>

                        <a href="https://www.linkedin.com/in/luiz-gustavo-gon%C3%A7alves-brito/"><img src="../img-estatico/linkedin.svg" alt="link do linkedin do aluno luiz" target="_blank"></a>
                    </div>
                </div>


                <div class="aluno">




                    <h3>Matheus Farias</h3>
                    <img src="../img-estatico/matheus.svg" alt="imagem do colaborador matheus" class="perfil">

                    <div class="redes">
                        <a href="https://github.com/MatthewsTomts"><img src="../img-estatico/github-sobrenos.svg" alt="link do github do aluno matheus" target="_blank"></a>

                        <a href="https://www.linkedin.com/in/matheus-farias-524942206/"><img src="../img-estatico/linkedin.svg" alt="link do linkedin do aluno matheus" target="_blank"></a>
                    </div>
                </div>


                <div class="aluno">




                    <h3>Mayck Luciano</h3>
                    <img src="../img-estatico/mayck.svg" alt="imagem do colaborador mayck" class="perfil">

                    <div class="redes">
                        <a href="https://github.com/MayckL2"><img src="../img-estatico/github-sobrenos.svg" alt="link do github do aluno mayck" target="_blank"></a>

                        <a href="https://www.linkedin.com/in/mayck-luciano/"><img src="../img-estatico/linkedin.svg" alt="link do linkedin do aluno mayck" target="_blank"></a>
                    </div>
                </div>


            </div>

        </section>



    </div>

    <section class="container pagamento">
        <div class="botoes">
            <button onclick="formaPagamento()" class="btn-pagamento">Formas de pagamentos</button>
            <button onclick="formaValores()" class="btn-pagamento btn-valores">Valores</button>
        </div>

        <div class="conteudo-pagamento">

            <div id="primeiro" class="item-pagamento">

                <img src="../img-estatico/debito.svg" alt="débito">
                <p>Débito</p>

            </div>


            <div id="segundo" class="item-pagamento">

                <img src="../img-estatico/pix.svg" alt="pix">
                <p>Pix</p>

            </div>

            <div id="terceiro" class="item-pagamento">

                <img src="../img-estatico/credito.svg" alt="crédito">
                <p>Crédito</p>

            </div>

            <div id="quarto" class="item-pagamento">

                <img src="../img-estatico/boleto.svg" alt="crédito">
                <p>Boleto</p>

            </div>

        </div>
    </section>


    <section class="valores-empresa container-fluid fundo-colaboradores">

        <div class="titulos-box-colaboradores">
            <h2 class="titulo titulo-valores">VALORES DA EMPRESA</h2>
        </div>

        <div class="box-valores">

            <div class="item-valor">
                <img src="../img-estatico/amor.svg" alt="ícone do amor">

                <h4>Amor pelos animais</h4>
                <p>Uma empresa de petshop deve ter um amor incondicional pelos animais e demonstrar isso através do
                    cuidado e da atenção dispensada a eles.</p>
            </div>

            <div class="item-valor">
                <img src="../img-estatico/inovacao.svg" alt="ícone da inovação">

                <h4>Inovação</h4>
                <p>O mercado de petshop está em constante evolução e é importante que a empresa acompanhe as tendências
                    e busque inovar em seus produtos e serviços.</p>
            </div>

            <div class="item-valor">
                <img src="../img-estatico/compromisso.svg" alt="ícone do compromisso">

                <h4>Compromisso com a saúde </h4>
                <p>A empresa deve oferecer serviços e produtos de qualidade para garantir a saúde e o bem-estar dos
                    animais, desde alimentação saudável até tratamentos.</p>
            </div>

            <div class="item-valor">
                <img src="../img-estatico/agilidade.svg" alt="ícone da agilidade">

                <h4>Agilidade</h4>
                <p>Uma empresa de petshop deve ter um amor incondicional pelos animais e demonstrar isso através do
                    cuidado e da atenção dispensada a eles.</p>
            </div>

            <div class="item-valor">
                <img src="../img-estatico/etica.svg" alt="ícone da ética">

                <h4>Ética e transparência </h4>
                <p>A empresa deve agir com ética em todos os aspectos de seu negócio, desde a seleção de fornecedores
                    até o atendimento aos clientes, e ser transparente em suas práticas e políticas.</p>
            </div>

            <div class="item-valor">
                <img src="../img-estatico/respeito.svg" alt="ícone do respeito">

                <h4>Respeito</h4>
                <p>Na nossa petshop, o respeito é um valor inegociável, pois acreditamos que todos os animais merecem
                    ser tratados com cuidado, atenção e carinho, independentemente de sua raça, idade ou condição
                    física.</p>
            </div>

        </div>


    </section>

    <footer>
        <a href="<?php echo $homeRoute; ?>" class="logo">
            <img src="../img-estatico/logo.svg" alt="">
        </a>

        <div class="links">

            <div>
                <a href="<?php echo $blogRoute; ?>">BLOG</a>
                <a class="subLinks" href="<?php echo $blogRoute; ?>">Noticias</a>
                <a class="subLinks" href="<?php echo $blogRoute; ?>">Depoimentos</a>
                <a class="subLinks" href="<?php echo $blogRoute; ?>">Curiosidades</a>
            </div>

            <div>
                <a href="<?php echo $sobreRoute; ?>">SOBRE NÓS</a>
                <a class="subLinks" href="<?php echo $sobreRoute; ?>">Preços</a>
                <a class="subLinks" href="<?php echo $sobreRoute; ?>">Valores</a>
                <a class="subLinks" href="<?php echo $sobreRoute; ?>">Colaboradores</a>
            </div>

            <div>
                <a href="<?php echo $contatoRoute; ?>">CONTATO</a>
                <a class="subLinks" href="<?php echo $contatoRoute; ?>">Mensagem</a>
                <a class="subLinks" href="<?php echo $contatoRoute; ?>">Localização</a>
                <a class="subLinks" href="<?php echo $contatoRoute; ?>">Informações</a>
            </div>
            
        </div>

        <div class="redes">
            <img src="../img-estatico/facebook.svg" alt="">
            <img src="../img-estatico/youtube.svg" alt="">
            <img src="../img-estatico/twitter.svg" alt="">
            <img src="../img-estatico/github.svg" alt="">
        </div>

        <p>© 2023 Hamtaro Petshop todos direitos reservados</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

    <script src="../script.js"></script>
    <script src="<?php echo $functionsRoute; ?>"></script>
</body>

</html>
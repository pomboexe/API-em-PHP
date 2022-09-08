<?php
require('db/conexao.php');
    //EXIBIR PRATOS NA TABELA 
    $sql = $pdo->prepare("SELECT * FROM produtos");
    $sql->execute();
    $dados = $sql->fetchAll(); 
    //EXIBIR PEDIDOS NA TAVELA
    $sql_ped = $pdo->prepare("SELECT * FROM pedidos");
    $sql_ped -> execute();
    $dados_ped = $sql_ped->fetchAll();
    //CRIAR TOKEN DE SESSÃO
    $sql_session= $pdo->prepare("SELECT * FROM usuarios WHERE token=?");
    $sql_session->execute(array($_SESSION['TOKEN']));
    $usuario = $sql_session->fetch(PDO::FETCH_ASSOC);
    
    if(!$usuario){
        header('location:index.php');
    }

    //CADASTRAR PRODUTO
    if (isset($_POST['cadastrar'])&& isset($_POST['produto_novo'])&& isset($_POST['price_novo'])){
    
        $produto = limparPost($_POST['produto_novo']);
        $price= limparPost($_POST['price_novo']);

        $sql_cad = $pdo->prepare("INSERT INTO produtos VALUES (null,?,?)");
        $sql_cad->execute(array($produto,$price,));


 }
    //FAZER PEDIDO
    if(isset($_POST['pedir'])&&isset($_POST['select-prod'])&&isset($_POST['qntd'])){
        $pedido= limparPost($_POST['select-prod']);
        $qntd = limparPost($_POST['qntd']);

        $sql_pedir = $pdo ->prepare("INSERT INTO pedidos VALUES (null,?,?)");
        $sql_pedir->execute(array($pedido,$qntd));
    }

    //ATUALIZAR PRODUTO
    if(isset($_POST['atualizar']) && isset($_POST['id_editado']) && isset($_POST['produto_editado']) && isset($_POST['preço_editado'])){
        
        $id=limparPost($_POST['id_editado']);
        $produto=limparPost($_POST['produto_editado']);
        $price=limparPost($_POST['preço_editado']);

    $sql_att_pr = $pdo->prepare("UPDATE produtos SET produto=?,price=? WHERE id=?");
    $sql_att_pr ->execute(array($produto,$price,$id));
    }


    //DELETAR PRODUTO
    if(isset($_POST['deletar']) && isset($_POST['id_deleta']) && isset($_POST['produto_deleta']) && isset($_POST['price_deleta'])){
        
        $id=limparPost($_POST['id_deleta']);
        $produto=limparPost($_POST['produto_deleta']);
        $price=limparPost($_POST['price_deleta']); 

               
        $sql_del = $pdo->prepare("DELETE FROM produtos WHERE id=? AND produto=? AND price=?");
        $sql_del->execute(array($id, $produto, $price));
    }
    //FINALIZAR PEDIDO
    if(isset($_POST['finalizar']) && isset($_POST['id_finaliza']) && isset($_POST['pedido_finaliza']) && isset($_POST['qntd_finaliza'])){
        
        $id=limparPost($_POST['id_finaliza']);
        $pedido=limparPost($_POST['pedido_finaliza']);
        $qntd=limparPost($_POST['qntd_finaliza']); 

               
        $sql_fin = $pdo->prepare("DELETE FROM pedidos WHERE id=? AND pedido=? AND qntd=?");
        $sql_fin->execute(array($id, $pedido, $qntd));
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilo.css">
    <title>Document</title>
</head>
<body>
    <div>
        <button  class="btn-img"><a href="#" class='btn-cadastrar'><img src="img/file-earmark-plus.svg" alt="">Cadastrar Prato</a></button>
        <button class="btn-img"><a href="#" class='btn-pedido'><img src="img/journal-medical.svg" alt="">Anotar Pedido</a></button>
        
    <form class="oculto-forms" id="form_cadastra" method="post">
        <input type="hidden" id="id_novo" name="id_novo" placeholder="ID" required>
        <input type="text" id="produto_novo" name="produto_novo" placeholder="Insira o Nome do Produto" required>
        <input type="number" id="preço_novo" name="price_novo" placeholder="Insira o Preço do Produto" required>
        <button class ="btn-forms"type="submit" name="cadastrar">cadastrar</button>
        <button class ="btn-forms"type="button" id="cancelar_cadastro" name="cancelar">Cancelar</button>
    </form>

    <form class="oculto-forms"method="post" id="form_pedido">
        <select name="select-prod" id="">
            <option>SELECIONE UM PRATO</option>
            <?php
                $sql_slct = $pdo->prepare("SELECT id, produto FROM produtos ORDER BY produto ASC");
                $sql_slct->execute();
                $dados_slct = $sql_slct->fetchAll();
                foreach($dados_slct as $option){
                    ?>
                    <option value="<?php echo $option['produto']?>"><?php echo $option['produto'] ?></option>
                <?php
                }
                ?>
        </select>
        <input type="number"  id="qntd" name="qntd" placeholder="qntd">
        <button class ="btn-forms"type="submit" name="pedir">fazer pedido</button>
        <button class ="btn-forms"type="button" id="cancelar_pedido" name="cancelar_pedido">cancelar</button>
    </form>

    <form class="oculto-forms" id="form_atualiza" method="post">
        <input type="hidden" id="id_editado" name="id_editado" placeholder="ID" required>
        <input type="text" id="produto_editado" name="produto_editado" placeholder="Editar produto" required>
        <input type="number" id="preço_editado" name="preço_editado" placeholder="Editar preço" required>
        <button class ="btn-forms"type="submit" name="atualizar">Atualizar</button>
        <button class ="btn-forms"type="button" id="cancelar" name="cancelar">Cancelar</button>
    </form>
    
    <form class="oculto-forms" id="form_finaliza" method="post">
        <input type="hidden" id="id_finaliza" name="id_finaliza" placeholder="ID" required>
        <input type="hidden" id="pedido_finaliza" name="pedido_finaliza" placeholder="Editar produto" required>
        <input type="hidden" id="qntd_finaliza" name="qntd_finaliza" placeholder="Editar preço" required>
        <b>finalizar pedido <span id="pedido"></span></b>
        <button class ="btn-forms"type="submit" name="finalizar">finalizar</button>
        <button class ="btn-forms"type="button" id="cancelar_finaliza" name="finalizar">Cancelar</button>
    </form>

    <form class="oculto-forms" id="form_deleta" method="post">
        <input type="hidden" id="id_deleta" name="id_deleta" placeholder="ID" required>
        <input type="hidden" id="produto_deleta" name="produto_deleta" placeholder="Editar produto" required>
        <input type="hidden" id="price_deleta" name="price_deleta" placeholder="Editar preço" required>
        <b>tem certeza que quer deletar produto <span id='produto'></span></a>
        <button class ="btn-forms"type="submit" name="deletar">deletar</button>
        <button class ="btn-forms"type="button" id="cancelar_delete" name="cancelar">Cancelar</button>
    </form>
    
    </div>
    <div>
    <h2>Pratos</h2>
        <div class="table-wrapper">
            <table class="fl-table">
                <thead>
                <tr>
                 <th>#</th>
                    <th>Prato</th>
                    <th>Preço</th>
                    <th>...</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                 if(count($dados) > 0){
                        foreach($dados as $chaves => $valor){
                          echo "
                               <tr>
                              <td>".$valor['id']."</td>
                              <td>".$valor['produto']."</td>
                             <td>".$valor['price']."</td>
                             <td><button><a href='#' class='btn-atualizar' data-id='".$valor['id']."' data-produto='".$valor['produto']."' data-price='".$valor['price']."'><img src='img/pencil-square.svg'><a></button>|<button><a href='#'class='btn-deletar' data-id='".$valor['id']."' data-produto='".$valor['produto']."' data-price='".$valor['price']."'><img src='img/trash-fill.svg'></a></button></td>
                             </tr>
                         ";
                        }
                    }else{
                      echo "nenhum prato cadastrado";
                  }
                 ?>
                <tbody>
         </table>
            </div>
    </div>
    <div>
    <h2>Pedidos</h2>
        <div class="table-wrapper">
            <table class="fl-table">
                <thead>
                <tr>
                    <th>Numero</th>
                    <th>Pedido</th>
                    <th>QNTD</th>
                    <th>...</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                 if(count($dados_ped) > 0){
                        foreach($dados_ped as $chaves_ped => $valor_ped){
                          echo "
                               <tr>
                              <td>".$valor_ped['id']."</td>
                              <td>".$valor_ped['pedido']."</td>
                              <td>".$valor_ped['qntd']."</td>
                              <td><button><a href='#' class='btn-finalizar' data-id-ped='".$valor_ped['id']."' data-pedido='".$valor_ped['pedido']."' data-qntd='".$valor_ped['qntd']."'><img src='img/check-circle-fill.svg'></button></td>
                             </tr>
                         ";
                        }
                    }else{
                      echo "nenhum pedido pendente";
                  }
                 ?>
                <tbody>
         </table>
            </div>
    </div>
    <button class="btn-forms"><a href="logout.php">SAIR</a></button>
    <script
  src="https://code.jquery.com/jquery-3.6.1.js"
  integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
  crossorigin="anonymous"></script>
    <script> 
    //FAZER OS FORMS APARECEREM AO CLICAR NOS BUTTONS
        $(".btn-cadastrar").click(function(){
            $('#form_cadastra').removeClass('oculto-forms');
            $('#form_atualiza').addClass('oculto-forms');
            $('#form_deleta').addClass('oculto-forms');
            $('#form_pedido').addClass('oculto-forms');
        });
        $(".btn-pedido").click(function(){
            $('#form_pedido').removeClass('oculto-forms');
            $('#form_atualiza').addClass('oculto-forms');
            $('#form_deleta').addClass('oculto-forms');
            $('#form_cadastra').addClass('oculto-forms');
        });
        $(".btn-atualizar").click(function(){
            var id = $(this).attr('data-id');
            var produto = $(this).attr('data-produto');
            var price = $(this).attr('data-price');
            
            $('#form_atualiza').removeClass('oculto-forms');
            $('#form_deleta').addClass('oculto-forms');
            $('#form_cadastra').addClass('oculto-forms');
            $('#form_pedido').addClass('oculto-forms');

            $("#id_editado").val(id);
            $("#produto_editado").val(produto);
            $("#preço_editado").val(price);

        });
        $(".btn-finalizar").click(function(){
            var id = $(this).attr('data-id-ped');
            var pedido = $(this).attr('data-pedido');
            var qntd = $(this).attr('data-qntd');

            $("#id_finaliza").val(id);
            $("#pedido_finaliza").val(pedido);
            $("#qntd_finaliza").val(qntd);
            $("#pedido").html(pedido);

            
            $('#form_finaliza').removeClass('oculto-forms');
            $('#form_deleta').addClass('oculto-forms');
            $('#form_cadastra').addClass('oculto-forms');
            $('#form_pedido').addClass('oculto-forms');
            $('#form_atualiza').addClass('oculto-forms');


        });
        $(".btn-deletar").click(function(){
            var id = $(this).attr('data-id');
            var produto = $(this).attr('data-produto');
            var price = $(this).attr('data-price');

            $("#id_deleta").val(id);
            $("#produto_deleta").val(produto);
            $("#price_deleta").val(price);
            $("#produto").html(produto);

            $('#form_atualiza').addClass('oculto-forms');
            $('#form_deleta').removeClass('oculto-forms');
            $('#form_cadastra').addClass('oculto-forms');
            $('#form_pedido').addClass('oculto-forms');
            $('#form_finaliza').addClass('oculto-forms');
           

        });
        
        $('#cancelar').click(function(){
            $('#form_atualiza').addClass('oculto-forms');
            $('#form_deleta').addClass('oculto-forms')
            $('#form_cadastra').addClass('oculto-forms');
            $('#form_pedido').addClass('oculto-forms');
            $('#form_finaliza').addClass('oculto-forms');
           
        });
        $('#cancelar_delete').click(function(){
            $('#form_atualiza').addClass('oculto-forms');
            $('#form_deleta').addClass('oculto-forms')
            $('#form_cadastra').addClass('oculto-forms');
            $('#form_pedido').addClass('oculto-forms');
            $('#form_finaliza').addClass('oculto-forms');
            
        });
        $('#cancelar_cadastro').click(function(){
            $('#form_atualiza').addClass('oculto-forms');
            $('#form_deleta').addClass('oculto-forms')
            $('#form_cadastra').addClass('oculto-forms');
            $('#form_pedido').addClass('oculto-forms');
            $('#form_finaliza').addClass('oculto-forms');
            
        });
        $('#cancelar_pedido').click(function(){
            $('#form_atualiza').addClass('oculto-forms');
            $('#form_deleta').addClass('oculto-forms')
            $('#form_cadastra').addClass('oculto-forms');
            $('#form_pedido').addClass('oculto-forms');
            $('#form_finaliza').addClass('oculto-forms');
            
        });
        $('#cancelar_finaliza').click(function(){
            $('#form_atualiza').addClass('oculto-forms');
            $('#form_deleta').addClass('oculto-forms')
            $('#form_cadastra').addClass('oculto-forms');
            $('#form_pedido').addClass('oculto-forms');
            $('#form_finaliza').addClass('oculto-forms');
            
        });
    </script>
</body>
</html>
<?php
$bd = new mysqli('localhost', 'root', '', 'estoque');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    echo $id;


    if (isset($_POST['novo_Produto'])) {
        $novoProduto = trim($_POST['novo_Produto']);
        $novoProduto = filter_var($novoProduto, FILTER_SANITIZE_STRING);

        if ($novoProduto === '') {
            echo "Erro: Novo produto não pode ser vazio.";
            exit();
        }

        $sqlUpdateproduto = "UPDATE controle_estoque SET Produto = '$novoProduto' WHERE id = '$id'";
        $resultUpdateProduto = $bd->query($sqlUpdateProduto);

        if ($resultUpdateProduto === false) {
            echo "Erro: Falha ao atualizar o produto.";
            exit();
        }
    }

    // Atualizar o telefone, se o novo telefone foi recebido
    if (isset($_POST['novo_Preco'])) {
        $novoPreco = trim($_POST['novo_Preco']);
        $novoPreco = filter_var($novoPreco, FILTER_SANITIZE_STRING);

        if ($novoPreco === '') {
            echo "Erro: Novo preço não pode ser vazio.";
            exit();
        }

        $sqlUpdatePreco = "UPDATE controle_estoque SET Preco = '$novoPreco' WHERE id = '$id'";
        $resultUpdatePreco = $bd->query($sqlUpdatePreco);

        if ($resultUpdatePreco === false) {
            echo "Erro: Falha ao atualizar o preço.";
            exit();
        }
    }

    // Atualizar o email, se o novo email foi recebido
    if (isset($_POST['novo_tipo'])) {
        $novoTipo = trim($_POST['novo_tipo']);
        $novoTipo = filter_var($novoTipo, FILTER_SANITIZE_EMAIL);

        if (!filter_var($novoTipo, FILTER_VALIDATE_EMAIL)) {
            echo "Erro: Novo tipo não pode ser vazio.";
            exit();
        }

        $sqlUpdateTipo = "UPDATE controle_estoque SET Tipo = '$novoTipo' WHERE id = '$id'";
        $resultUpdateTipo = $bd->query($sqlUpdateTipo);

        if ($resultUpdateTipo === false) {
            echo "Erro: Falha ao atualizar o tipo.";
            exit();
        }
    }

    // Redirecionar após a atualização
    header('Location: estoque.php');
    exit();
}

if ($bd->connect_error) {
    echo "Erro: Falha ao conectar ao banco de dados. " . $bd->connect_error;
    exit();
}

// Verificar se o ID foi recebido e validar como inteiro
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    echo "Erro: ID nao recebido.";
    exit();
}

// Buscar o nome, telefone e email atuais do contato
$sqlProduto = "SELECT Produto, Preco, Tipo FROM controle_estoque WHERE id = $id";
$resultProduto = $bd->query($sqlProduto);

if ($resultProduto->num_rows == 0) {
    echo "Erro: Estoque não encontrado no banco de dados.";
    exit();
}

 

$produto = $resultProduto->fetch_assoc();
$produtoOriginal = $produto['produto'];
$precoOriginal = $produto['preco'];
$tipoOriginal = $produto['tipo'];

// Fechar a conexão com o banco de dados
$bd->close();

echo $id;
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
            crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <title>Editar Contato</title>
    </head>
    <body>
        <div class="conteudo">
            <h1 class="text-info bg-dark">Editar Produto</h1>
            <form method="post" novalidate="novalidate">
                <input style="display: none" name="id" id="id" value="<?php echo $id; ?>">
                <div class="form-floating mb-3">
                    <input
                        class="form-control"
                        type="text"
                        id="novo_nome"
                        name="novo_nome"
                        value="<?php echo ($nomeOriginal); ?>"
                        required="required">
                    <label for="novo_nome" class="lbl_titulo">Produto:</label>
                </div>
                <div class="form-floating mb-3">
                    <input
                        class="form-control"
                        type="text"
                        id="novo_telefone"
                        name="novo_telefone"
                        value="<?php echo ($telefoneOriginal); ?>"
                        required="required">
                    <label for="novo_telefone" class="lbl_titulo">Preço:</label>
                </div>
                <div class="form-floating mb-3">
                    <input
                        class="form-control"
                        type="email"
                        id="novo_email"
                        name="novo_email"
                        value="<?php echo($emailOriginal); ?>"
                        required="required">
                    <label for="novo_email" class="lbl_titulo">Tipo:</label>
                </div>
                <div class="col-md-4">
                <button type="submit" class="btn btn-primary" id="botao_atualizar">Atualizar</button>
                </div>
            </form>
        </div>
    </body>
</html>
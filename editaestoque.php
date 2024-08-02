<?php
$bd = new mysqli('localhost', 'root', '', 'estoque');

if ($bd->connect_error) {
    echo "Erro: Falha ao conectar ao banco de dados. " . $bd->connect_error;
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;

    if (isset($_POST['novo_produto'])) {
        $novoProduto = trim($_POST['novo_produto']);
        $novoProduto = filter_var($novoProduto, FILTER_SANITIZE_STRING);

        if ($novoProduto === '') {
            echo "Erro: Novo produto não pode ser vazio.";
            exit();
        }

        $sqlUpdateProduto = "UPDATE estoque SET produto = ? WHERE id = ?";
        $stmtUpdateProduto = $bd->prepare($sqlUpdateProduto);
        $stmtUpdateProduto->bind_param('si', $novoProduto, $id);
        $stmtUpdateProduto->execute();

        if ($stmtUpdateProduto->affected_rows === 0) {
            echo "Erro: Falha ao atualizar o produto.";
            exit();
        }
    }

    if (isset($_POST['novo_preco'])) {
        $novoPreco = trim($_POST['novo_preco']);
        $novoPreco = filter_var($novoPreco, FILTER_SANITIZE_STRING);

        if ($novoPreco === '') {
            echo "Erro: Novo preço não pode ser vazio.";
            exit();
        }

        $sqlUpdatePreco = "UPDATE estoque SET preco = ? WHERE id = ?";
        $stmtUpdatePreco = $bd->prepare($sqlUpdatePreco);
        $stmtUpdatePreco->bind_param('si', $novoPreco, $id);
        $stmtUpdatePreco->execute();

        if ($stmtUpdatePreco->affected_rows === 0) {
            echo "Erro: Falha ao atualizar o preço.";
            exit();
        }
    }

    if (isset($_POST['novo_tipo'])) {
        $novoTipo = trim($_POST['novo_tipo']);
        $novoTipo = filter_var($novoTipo, FILTER_SANITIZE_STRING);

        if ($novoTipo === '') {
            echo "Erro: Novo tipo não pode ser vazio.";
            exit();
        }

        $sqlUpdateTipo = "UPDATE estoque SET tipo = ? WHERE id = ?";
        $stmtUpdateTipo = $bd->prepare($sqlUpdateTipo);
        $stmtUpdateTipo->bind_param('si', $novoTipo, $id);
        $stmtUpdateTipo->execute();

        if ($stmtUpdateTipo->affected_rows === 0) {
            echo "Erro: Falha ao atualizar o tipo do produto.";
            exit();
        }
    }

    // Redirecionar após a atualização
    header('Location: estoque.php');
    exit();
}

// Verificar se o ID foi recebido e validar como inteiro
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($id === null) {
    echo "Erro: ID não recebido.";
    exit();
}

// Buscar o nome, telefone e email atuais do contato
$sqlEstoque = "SELECT produto, preco, tipo FROM estoque WHERE id = ?";
$stmtEstoque = $bd->prepare($sqlEstoque);
$stmtEstoque->bind_param('i', $id);
$stmtEstoque->execute();
$resultEstoque = $stmtEstoque->get_result();

if ($resultEstoque->num_rows === 0) {
    echo "Erro: Produto não encontrado no banco de dados.";
    exit();
}

$estoque = $resultEstoque->fetch_assoc();
$produtoOriginal = $estoque['produto'];
$precoOriginal = $estoque['preco'];
$tipoOriginal = $estoque['tipo'];

// Fechar a conexão com o banco de dados
$bd->close();
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
    <title>Editar Produto</title>
</head>
<body>
    <div class="conteudo">
        <h1 class="text-info bg-dark">Editar Produto</h1>
        <form method="post" novalidate="novalidate">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <div class="form-floating mb-3">
                <input
                    class="form-control"
                    type="text"
                    id="novo_produto"
                    name="novo_produto"
                    value="<?php echo htmlspecialchars($produtoOriginal); ?>"
                    required="required">
                <label for="novo_produto" class="lbl_titulo">Produto:</label>
            </div>
            <div class="form-floating mb-3">
                <input
                    class="form-control"
                    type="text"
                    id="novo_preco"
                    name="novo_preco"
                    value="<?php echo htmlspecialchars($precoOriginal); ?>"
                    required="required">
                <label for="novo_preco" class="lbl_titulo">Preço:</label>
            </div>
            <div class="form-floating mb-3">
                <input
                    class="form-control"
                    type="text"
                    id="novo_tipo"
                    name="novo_tipo"
                    value="<?php echo htmlspecialchars($tipoOriginal); ?>"
                    required="required">
                <label for="novo_tipo" class="lbl_titulo">Tipo:</label>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Atualizar</button>
            </div>
        </form>
    </div>
</body>
</html>

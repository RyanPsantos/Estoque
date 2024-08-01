<?php
$bd = new mysqli('localhost', 'root', '', 'agenda');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    echo $id;


    if (isset($_POST['novo_Produto'])) {
        $novoProduto = trim($_POST['novo_Produto']);
        $novoProduto = filter_var($novoProduto, FILTER_SANITIZE_STRING);

        if ($novoProduto === '') {
            echo "Erro: Novo nome não pode ser vazio.";
            exit();
        }

        $sqlUpdateproduto = "UPDATE contatos SET nome = '$novoProduto' WHERE id = '$id'";
        $resultUpdateProduto = $bd->query($sqlUpdateProduto);

        if ($resultUpdateProduto === false) {
            echo "Erro: Falha ao atualizar o nome.";
            exit();
        }
    }

    // Atualizar o telefone, se o novo telefone foi recebido
    if (isset($_POST['novo_Preco'])) {
        $novoPreco = trim($_POST['novo_Preco']);
        $novoPreco = filter_var($novoPreco, FILTER_SANITIZE_STRING);

        if ($novoPreco === '') {
            echo "Erro: Novo telefone não pode ser vazio.";
            exit();
        }

        $sqlUpdateTelefone = "UPDATE contatos SET telefone = '$novoTelefone' WHERE id = '$id'";
        $resultUpdateTelefone = $bd->query($sqlUpdateTelefone);

        if ($resultUpdateTelefone === false) {
            echo "Erro: Falha ao atualizar o telefone.";
            exit();
        }
    }

    // Atualizar o email, se o novo email foi recebido
    if (isset($_POST['novo_email'])) {
        $novoEmail = trim($_POST['novo_email']);
        $novoEmail = filter_var($novoEmail, FILTER_SANITIZE_EMAIL);

        if (!filter_var($novoEmail, FILTER_VALIDATE_EMAIL)) {
            echo "Erro: Email inválido.";
            exit();
        }

        $sqlUpdateEmail = "UPDATE contatos SET email = '$novoEmail' WHERE id = '$id'";
        $resultUpdateEmail = $bd->query($sqlUpdateEmail);

        if ($resultUpdateEmail === false) {
            echo "Erro: Falha ao atualizar o email.";
            exit();
        }
    }

    // Redirecionar após a atualização
    header('Location: agenda.php');
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
$sqlContato = "SELECT nome, telefone, email FROM contatos WHERE id = $id";
$resultContato = $bd->query($sqlContato);

if ($resultContato->num_rows == 0) {
    echo "Erro: Contato não encontrado no banco de dados.";
    exit();
}

 

$contato = $resultContato->fetch_assoc();
$nomeOriginal = $contato['nome'];
$telefoneOriginal = $contato['telefone'];
$emailOriginal = $contato['email'];

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
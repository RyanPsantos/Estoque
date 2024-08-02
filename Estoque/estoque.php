<?php
$bd = new mysqli('localhost', 'root', '', 'estoque');

function getProduto() {
    global $bd;
    $sql = "SELECT * FROM controle_estoque";
    $resultado = $bd->query($sql);
    $produtos = [];
    while ($row = $resultado->fetch_assoc()) {
        $produtos[] = $row;
    }
    return $produtos;
}
// criei uma nova function adicionarContato
function adicionarProduto($produto, $preco, $tipo) {
    global $bd;
    $sql = "INSERT INTO controle_estoque (produto, preco, tipo) VALUES ('$produto', '$preco', '$tipo')"; 
    $bd->query($sql);
}

// Criei uma nova function excluirContato
function excluirProduto($id) {
    global $bd;
    $sql = "DELETE FROM controle_estoque WHERE id = $id";
    $bd->query($sql);
}

// Criei uma nova function editarProduto
function editarProduto($id, $produto, $preco, $tipo) {
    global $bd;
    $sql = "UPDATE controle_estoque SET produto = '$produto', preco = '$preco', tipo = '$tipo' WHERE id = $id";
    $bd->query($sql);
}

$acao = isset($_GET['acao']) ? $_GET['acao'] : null;
$id = isset($_GET['id']) ? intval($_GET['id']) : 10;
$produto = isset($_POST['produto']) ? $_POST['produto'] : '';
$preco = isset($_POST['preco']) ? $_POST['preco'] : '';
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';

if ($acao === 'adicionar') {
    adicionarProduto($produto, $preco, $tipo); //utilizei a nova function criada 
    header('Location: estoque.php'); // mudei para estoque.php
    exit();
} elseif ($acao === 'editar') {
    $id = intval($_GET['id']);
    $produto = $_POST['produto'];
    $preco = $_POST['preco'];
    $tipo = $_POST['tipo'];
    // editarProduto($id, $nome, $telefone, $email); // utilizei a nova function criada
    header("Location: editarestoque.php?id=$id&produto=" . urlencode($produto) . "&preco=" . urlencode($preco) . "&tipo=" . urlencode($tipo));
    exit();
} elseif ($acao === 'excluir') {
    excluirProduto($id); // utilizei a nova function criada 
    header('Location: estoque.php');
    exit();
}

$produtos = getProduto();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Estoque</title>
</head>
<body>
<div class="conteudo">
    <h1 class="text-info bg-dark">Controle de Estoque</h1>
    
    <form action="?acao=adicionar" method="post" novalidate="novalidate">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="produto" name="produto" placeholder="Nome do produto" required="required">
            <label for="produto" class="lbl_titulo">Nome do Produto</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="preco" name="preco" placeholder="Preço" required="required">
            <label for="preco" class="lbl_titulo">Precificação:</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="tipo" name="tipo" placeholder="Tipo" required="required">
            <label for="tipo" class="lbl_titulo">Tipo do Produto</label>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary" id="botao">Adicionar produto</button>
        </div>
    </form>
    <br>
    <div id="style_table">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Produto</th>
                <th scope="col">Preço</th>
                <th scope="col">Tipo</th>
                <th scope="col">Editar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $produto): ?>
            <tr>
                <td><?php echo $produto['id']; ?></td>
                <td><?php echo $produto['Produto']; ?></td>
                <td><?php echo $produto['Preco']; ?></td>
                <td><?php echo $produto['Tipo']; ?></td>
                <td>
                    <a href="?acao=editar&id=<?php echo $produto['id']; ?>&Produto=<?php echo urlencode($produto['Produto']); ?>&Preco=<?php echo urlencode($produto['Preco']); ?>&Tipo=<?php echo urlencode($produto['Tipo']); ?>" class="btn btn-primary">Editar</a>
                    <a href="?acao=excluir&id=<?php echo $produto['id']; ?>" class="btn btn-danger">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
</body>
</html>
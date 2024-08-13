<?php
$bd = new mysqli('localhost', 'root', '', 'estoque');

function getEstoque() {
    global $bd;
    $sql = "SELECT * FROM controle_estoque";
    $resultado = $bd->query($sql);
    $estoque = [];
    while ($row = $resultado->fetch_assoc()) {
        $estoque[] = $row;
    }
    return $estoque;
}

// Função para adicionar um item ao estoque
function adicionarEstoque($produto, $preco, $tipo) {
    global $bd;
    $sql = "INSERT INTO controle_estoque (produto, preco, tipo) VALUES ('$produto', '$preco', '$tipo')";
    $bd->query($sql);
}

// Função para excluir um item do estoque
function excluirEstoque($id) {
    global $bd;
    $sql = "DELETE FROM controle_estoque WHERE Id = $id";
    $bd->query($sql);
}

// Função para editar um item do estoque
function editarEstoque($id, $produto, $preco, $tipo) {
    global $bd;
    $sql = "UPDATE controle_estoque SET Produto = '$produto', Preco = '$preco', Tipo = '$tipo' WHERE Id = $id";
    $bd->query($sql);
}

$acao = isset($_GET['acao']) ? $_GET['acao'] : null;
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$produto = isset($_POST['produto']) ? $_POST['produto'] : '';
$preco = isset($_POST['preco']) ? $_POST['preco'] : '';
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';

if ($acao === 'adicionar') {
    adicionarEstoque($produto, $preco, $tipo);
    header('Location: estoque.php');
    exit();
} elseif ($acao === 'editar') {
    if ($id = intval($_GET['id'])) {
        $produto = $_POST['produto'];
        $preco = $_POST['preco'];
        $tipo = $_POST['tipo'];
        //editarEstoque($id, $produto, $preco, $tipo);
        header("Location: editaestoque.php?id=$id&produto=" . urlencode($produto) . "&preco=" . urlencode($preco) . "&tipo=" . urlencode($tipo));
        exit();
    }
} elseif ($acao === 'excluir') {
        excluirEstoque($id);
        header('Location: estoque.php');
        exit();
}

$estoque = getEstoque();
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
            <input type="text" class="form-control" id="preco" name="preco" placeholder="Preco" required="required">
            <label for="preco" class="lbl_titulo">Preço:</label>
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
        <?php foreach ($estoque as $item): ?>
    <tr>
        <td><?php echo $item['Id']; ?></td>
        <td><?php echo $item['Produto']; ?></td>
        <td><?php echo $item['Preco']; ?></td>
        <td><?php echo $item['Tipo']; ?></td>
        <td>
            <a href="?acao=editar&id=<?php echo ($item['Id']); ?>&produto=<?php echo urlencode($item['Produto']); ?>&preco=<?php echo urlencode($item['Preco']); ?>&tipo=<?php echo urlencode($item['Tipo']); ?>" class="btn btn-primary">Editar</a>
            <a href="?acao=excluir&id=<?php echo ($item['Id']); ?>" class="btn btn-danger">Excluir</a>
        </td>
    </tr>
<?php endforeach; ?>

        </tbody>
    </table>
    </div>
</div>
</body>
</html>

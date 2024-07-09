<?php
$servername = "localhost";
$username = "id22321105_emma";
$password = "Char1101#";
$dbname = "id22321105_kawamisinas";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Procesar el formulario de agregar pedido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $table = $_POST['table'];
    $category = $_POST['category'];
    $product = $_POST['product'];
    $price = $_POST['price'];

    $sql = "INSERT INTO orders (table_name, category, product, price) VALUES ('$table', '$category', '$product', '$price')";

    if ($conn->query($sql) === TRUE) {
        // Actualizar el total en la tabla 'totals'
        $updateTotalSql = "INSERT INTO totals (table_name, total) VALUES ('$table', '$price')
                            ON DUPLICATE KEY UPDATE total = total + $price";
        $conn->query($updateTotalSql);

        // Redireccionar con éxito
        header("Location: admin.php?success=1");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Eliminar un producto específico
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $deleteId = $_GET['id'];
    $deleteSql = "DELETE FROM orders WHERE id=$deleteId";
    
    if ($conn->query($deleteSql) === TRUE) {
        // Redireccionar después de eliminar
        header("Location: admin.php");
        exit();
    } else {
        echo "Error al eliminar el producto: " . $conn->error;
    }
}

// Pagar la cuenta de una mesa específica
if (isset($_GET['pay']) && isset($_GET['table'])) {
    $tableToPay = $_GET['table'];
    $paySql = "DELETE FROM orders WHERE table_name='$tableToPay'; 
               DELETE FROM totals WHERE table_name='$tableToPay'";

    if ($conn->multi_query($paySql) === TRUE) {
        // Redireccionar después de pagar
        header("Location: admin.php");
        exit();
    } else {
        echo "Error al pagar la cuenta: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración - Kawamisinas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Administración de Pedidos</h1>
    </header>
    <main>
        <form id="order-form" method="post" action="admin.php">
            <label for="table">Mesa:</label>
            <select id="table" name="table" required>
                <option value="">Selecciona la mesa...</option>
                <option value="Mesa 1">Mesa 1</option>
                <option value="Mesa 2">Mesa 2</option>
                <option value="Mesa 3">Mesa 3</option>
                <!-- Agrega más opciones según tus mesas -->
            </select>

            <label for="category">Categoría:</label>
            <select id="category" name="category" required>
                <option value="">Selecciona la categoría...</option>
                <option value="cervezas">Cervezas</option>
                <option value="caguamas">Caguamas</option>
                <option value="micheladas">Micheladas</option>
                <option value="azulitos">Azulitos</option>
                <option value="cantaritos">Cantaritos</option>
                <option value="refrescos">Refrescos</option>
                <option value="comida">Comida</option>
            </select>

            <label for="beverage">Producto:</label>
            <select id="beverage" name="product" required>
                <!-- Las opciones serán agregadas dinámicamente -->
            </select>
            <br><br><br>
            <input type="hidden" id="price" name="price">
            <button type="submit">Agregar Pedido</button>
        </form>

        <?php
        if (isset($_GET['success'])) {
            echo "<p>Pedido agregado con éxito.</p>";
        }

        $tables = ["Mesa 1", "Mesa 2", "Mesa 3"];
        foreach ($tables as $table) {
            echo "<div class='order-section'>";
            echo "<h3>$table</h3>";
            echo "<ul>";
            $result = $conn->query("SELECT * FROM orders WHERE table_name='$table'");
            while ($row = $result->fetch_assoc()) {
                echo "<li>{$row['product']} - {$row['price']} 
                        <a href='admin.php?delete=true&id={$row['id']}'>Eliminar</a></li>";
            }
            echo "</ul>";
            echo "<p>Total: $" . number_format(getTotalForTable($conn, $table), 2) . "</p>";
            echo "<a href='admin.php?pay=true&table={$table}'>Pagar Cuenta</a>";
            echo "</div>";
        }

        function getTotalForTable($conn, $table) {
            $totalResult = $conn->query("SELECT total FROM totals WHERE table_name='$table'");
            return $totalResult->fetch_assoc()['total'];
        }
        ?>
        <br><br><br>
    </main>
    <footer>
        <p>&copy; 2024 Kawamisinas. Todos los derechos reservados.</p>
    </footer>
    <script src="data.js"></script>
    <script src="admin.js"></script>
</body>
</html>

<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'seller') {
    echo "Access denied.";
    exit();
}

$seller_id = $_SESSION['user']['id'];
$message = "";


if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);

    //making sure the product belongs to this seller before deleting
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ? AND seller_id = ?");
    $stmt->bind_param("ii", $delete_id, $seller_id);
    if ($stmt->execute()) {
        $message = "Product deleted successfully.";
    } else {
        $message = "Failed to delete product.";
    }
    $stmt->close();
}

// Handling form submission for new product being uploaded
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $barter_option = $_POST['barter_option'];
    $quantity = $_POST['quantity'];
    $area = $_POST['area'];
    $category = $_POST['category'];

    $target_dir = "assets/uploads/";
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . time() . "_" . $image_name;
    $image_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $valid_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($image_type, $valid_types)) {
        $message = "Invalid image format. Only JPG, JPEG, PNG, GIF allowed.";
    } elseif (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO products 
            (seller_id, title, description, image, price, quantity, area, barter_option, category) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("isssdisss", $seller_id, $title, $description, $target_file, $price, $quantity, $area, $barter_option, $category);

        if ($stmt->execute()) {
            $message = "Product uploaded successfully.";
        } else {
            $message = "Database error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Failed to upload image.";
    }
}

// Fetch all products for this seller
$stmt = $conn->prepare("SELECT * FROM products WHERE seller_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Seller Dashboard</title>
    <style>
        body { 
			font-family: sans-serif; 
			margin: 40px; 
		}
			
		form {
			max-width: 600px;
			margin-bottom: 30px;
		}
		
		input, textarea, select { 
			width: 100%; margin-bottom: 10px; 
			padding: 10px; 
		}
		
		button { 
			padding: 10px 20px; 
			background: green; 
			color: white; 
			border: none; 
			cursor: pointer; 
		}
		
		.message {
			padding: 10px; 
			margin-bottom: 20px; 
			background: #f3f3f3; 
			border-left: 5px solid green; 
		}
		
		table { 
			border-collapse: collapse; 
			width: 100%; 
			margin-top: 40px; 
			}
			
		th, td { 
			border: 1px solid #ddd;
			padding: 8px; 
			text-align: center; 
		}
		
        th { 
			background-color: #4CAF50; 
			color: white; 
		}
		
		img { 
			max-width: 100px; 
			height: auto; 
		}
		
        a.delete-link { 
			color: red;
			text-decoration: none;
		}

    </style>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']['username']); ?> (Seller)</h1>
    <p><a href="logout.php">Logout</a></p>

    <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <h2>Upload New Product</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Title" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="number" name="price" step="0.01" placeholder="Price (R)" required>
        <select name="barter_option" required>
            <option value="cash">Cash/Card</option>
            <option value="barter">Barter</option>
            <option value="both">Both</option>
        </select>
        <input type="number" name="quantity" placeholder="Quantity" required>
        <input type="text" name="area" placeholder="Area/Location" required>
        <input type="text" name="category" placeholder="Category (e.g., electronics, fashion)" required>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit">Upload Product</button>
    </form>

    <h2>Your Products</h2>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Price (R)</th>
                <th>Quantity</th>
                <th>Area</th>
                <th>Barter Option</th>
                <th>Category</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>"></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo (int)$row['quantity']; ?></td>
                    <td><?php echo htmlspecialchars($row['area']); ?></td>
                    <td><?php echo htmlspecialchars($row['barter_option']); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td>
                        <a href="?delete=<?php echo (int)$row['id']; ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No products listed yet.</p>
    <?php endif; ?>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Product Images</title>
</head>
<body>
    <h1>Upload Product Images</h1>
    <form action="uploadImages.php" method="POST" enctype="multipart/form-data">
        <label for="product_id">Product ID:</label>
        <input type="number" name="product_id" required><br><br>

        <label for="images">Upload Images:</label>
        <input type="file" name="images[]" multiple accept="image/*" required><br><br>

        <button type="submit">Upload Images</button>
    </form>
</body>
</html>

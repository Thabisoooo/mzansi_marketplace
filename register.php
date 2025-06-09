<!DOCTYPE html>
<html>
<head>
   <title>Register</title>
   <style>
    body {
        margin: 0;
        padding: 0;
        background: #f4f4f4;
        font-family: Arial, sans-serif;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .container {
        background-color: white;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        width: 100%;
        max-width: 400px;
    }

    h2 {
        margin-bottom: 20px;
        color: #333;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    select {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 6px;
        background-color: #eef4ff;
        font-size: 16px;
    }

    button {
        width: 100%;
        padding: 12px;
        background-color: green;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
    }

    button:hover {
        background-color: darkgreen;
    }

    .form-footer {
        margin-top: 15px;
        font-size: 14px;
        color: #555;
    }

    .form-footer a {
        color: #007BFF;
        text-decoration: none;
        font-weight: 600;
    }

    .form-footer a:hover {
        text-decoration: underline;
    }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form method="post">
            <input type="text" name="username" required placeholder="Username"><br>
            <input type="email" name="email" required placeholder="Email"><br>
            <input type="password" name="password" required placeholder="Password"><br>
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="buyer">Buyer</option>
                <option value="seller">Seller</option>
            </select><br>
            <button type="submit">Register</button>
        </form>
        <div class="form-footer">
            Already have an account? <a href="login.php">Login here</a>.
        </div>
    </div>
</body>
</html>

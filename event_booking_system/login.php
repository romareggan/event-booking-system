<?php
require_once "config.php";
$page_title = "Login";
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
        session_regenerate_id(true);
        $_SESSION["user_id"] = (int)$user["user_id"];
        $_SESSION["full_name"] = $user["full_name"];
        $_SESSION["role"] = $user["role"];
        header("Location: " . ($user["role"] === "admin" ? "admin_dashboard.php" : "index.php"));
        exit;
    }
    $error = "Incorrect email or password.";
}
include "header.php";
?>
<div class="form-card">
    <h1>Welcome back</h1>
    <p class="muted">Login to your EventBook account.</p>
    <?php if (isset($_GET["registered"])): ?><div class="success">Registration successful. You can now log in.</div><?php endif; ?>
    <?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
        <label>Email address<input type="email" name="email" autocomplete="email" required></label>
        <label>Password<input type="password" name="password" autocomplete="current-password" required></label>
        <button class="button full-width" type="submit">Login</button>
    </form>
    <p class="muted center">No account? <a href="register.php">Register here</a></p>
</div>
<?php include "footer.php"; ?>

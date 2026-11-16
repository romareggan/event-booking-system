<?php
require_once "config.php";
$page_title = "Register";
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($name === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
        $error = "Enter your name, a valid email, and a password of at least 6 characters.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            header("Location: login.php?registered=1");
            exit;
        } catch (PDOException $ex) {
            $error = "That email address is already registered.";
        }
    }
}
include "header.php";
?>
<div class="form-card">
    <h1>Create an account</h1>
    <p class="muted">Register to book events.</p>
    <?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
    <form method="post" class="validate-form">
        <label>Full name<input type="text" name="full_name" autocomplete="name" required maxlength="100"></label>
        <label>Email address<input type="email" name="email" autocomplete="email" required></label>
        <label>Password<input type="password" name="password" autocomplete="new-password" minlength="6" required></label>
        <button class="button full-width" type="submit">Register</button>
    </form>
    <p class="muted center">Already registered? <a href="login.php">Login</a></p>
</div>
<?php include "footer.php"; ?>

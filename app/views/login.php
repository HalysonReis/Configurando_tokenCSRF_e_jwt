<?php
include "../../app/helpers/csrf.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post" id="logar">
        <label>Email</label>
        <input type="email" name="email" id="email">
        <label>Senha</label>
        <input type="text" name="senha" id="senha">
        <?php echo getTolkenCsrf() ?>
        <input type="submit" name="login" value="logar">
    </form>
    <script src="../../js/login.js"></script>
</body>
</html>
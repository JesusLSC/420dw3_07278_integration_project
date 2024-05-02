<?php
declare(strict_types=1);

use Services\UserService;
use Services\LoginService;


if (LoginService::isUserLoggedIn()) {
    header("Location: " . WEB_ROOT_DIR);
    http_response_code(302);
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "bootstrap.min.css" ?>">
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "standard.css" ?>">
    <script type="text/javascript">

        const API_LOGIN_URL = "<?= WEB_ROOT_DIR . "api/login" ?>";

    </script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "jquery-3.7.1.min.js" ?>" defer></script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "standard.js" ?>" defer></script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "page.login.js" ?>" defer></script>
</head>
<body>
<header id="header">
    <?php
    include "standard.page.header.php";
    ?>
</header>
<main id="main">
    <div class="container">
        <div class="row justify-content-center">
            <h3 class="fullwidth text-center">Tests for ExampleDTOs</h3>
        </div>
        <form id="loginForm" class="row">
            <div class="row justify-content-center">
                <h3 class="fullwidth text-center">Select a user to login with</h3>
            </div>
            <?php
            $from = "";
            if (!empty($_REQUEST["from"])) {
                $from = $_REQUEST["from"];
            }
            ?>
            <input type="hidden" name="from" value="<?= $from ?>">
            <?php
            $user_service = new UserService();
            $all_users = $user_service->getAllUsers();

            foreach ($all_users as $user) {
                $user_id = $user->getId();
                $username = $user->getUsername();
                $element_id = $user_id . "_id";
                echo <<< HTDOC
            <div class="form-check">
                <input type="radio" id="$element_id" class="form-check-input" name="userId" value="$user_id" required>
                <label for="$element_id" class="form-check-label" >$username</label>
            </div>
HTDOC;
            }
            ?>
            <div class="row d-flex justify-content-center">
                <button id="loginButton" type="button" class="btn btn-primary col-12 col-md-4">Login</button>
            </div>
        </form>
    </div>
</main>
<footer id="footer">
    <?php
    include "standard.page.footer.php";
    ?>
</footer>
</body>
</html>
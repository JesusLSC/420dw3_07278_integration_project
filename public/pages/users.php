<?php
declare(strict_types=1);

require_once __DIR__ . "/../../private/helpers/init.php";


use Services\UserService;

$user_service = new UserService();
$all_user_instances = $user_service->getAllUsers();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Page</title>
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "bootstrap.min.css" ?>">
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "standard.css" ?>">
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "user.css" ?>">
    <script type="text/javascript">
        
        const USER_API_URL = "<?= WEB_ROOT_DIR . "api/UserDto" ?>";
    
    </script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "jquery-3.7.1.min.js" ?>" defer></script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "standard.js" ?>" defer></script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "page.users.js" ?>" defer></script>
</head>
<body>
<header id="header" class="header">
    <?php
    include PRJ_FRAGMENTS_DIR .  DIRECTORY_SEPARATOR .
        "standard.page.header.php";
    ?>
</header>
<main id="main" class="main">
    <div class="container">
        <div class="row justify-content-center">
            <h3 class="fullwidth text-center">Tests for UserDTOs</h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-4 row align-items-end align-items-md-center justify-content-center justify-content-md-end">
                <label class="col-12 text-start text-md-end align-items-md-center"
                       for="user-selector">Select a user record:</label>
            </div>
            <div class="col-12 col-md-4 row justify-content-center">

            </div>
            <div class="col-12 col-md-4 row justify-content-center justify-content-md-start py-2 py-md-0 px-4">
                <button id="view-instance-button" class="btn btn-primary col-9 col-sm-5 col-md-9 col-lg-7 text-uppercase"
                        type="button">Load user
                </button>
            </div>
        </div>
        <div class="row">
        
        </div>
        <div class="error-display hidden">
            <h1 id="error-class" class="col-12 error-text"></h1>
            <h3 id="error-message" class="col-12 error-text"></h3>
            <div id="error-previous" class="col-12"></div>
            <pre id="error-stacktrace" class="col-12"></pre>
        </div>
    </div>
    <br/>
    <div class="container">
        <form id="user-form" class="row">
            <div class="col-12">
                <label class="form-label" for="user-id">Id: </label>
                <input class="form-control form-control-sm" id="user-id" type="number" name="id" readonly disabled>
            </div>
            <div class="col-12">
                <label class="form-label" for="user-username">Username:</label>
            </div>
            <div class="col-12">
                <label class="form-label" for="user-email">Email: </label>
            </div>
            <div class="col-12">
                <label class="form-label" for="user-created_at">Creation date: </label>
                <input class="form-control form-control-sm" id="user-created_at" type="datetime-local" name="creationDate" readonly disabled>
            </div>
            <div class="col-12">
                <label class="form-label" for="user-modificated_at">Last modification date: </label>
                <input class="form-control form-control-sm" id="user-modificated_at" type="datetime-local" name="lastModificationDate"
                       readonly disabled>
            </div>
        </form>
        <div class="col-12 d-flex flex-wrap justify-content-around button-row">
            <button id="create-button" type="button" class="btn btn-primary col-12 col-md-2 my-1 my-md-0 text-uppercase">Create</button>
            <button id="clear-button" type="button" class="btn btn-info col-12 col-md-2 my-1 my-md-0 text-uppercase" disabled>Clear Form</button>
            <button id="update-button" type="button" class="btn btn-success col-12 col-md-2 my-1 my-md-0 text-uppercase" disabled>Update</button>
            <button id="delete-button" type="button" class="btn btn-danger col-12 col-md-2 my-1 my-md-0 text-uppercase" disabled>Delete</button>
        </div>
    </div>
</main>
<footer>
    <?php
    include PRJ_FRAGMENTS_DIR .  DIRECTORY_SEPARATOR .
        "standard.page.footer.php";
    ?>
</footer>
</body>
</html>

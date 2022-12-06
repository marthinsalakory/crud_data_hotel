<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOTEL</title>
    <script src="assets/js/jquery.min.js"></script>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <script src="assets/fontawesome/js/all.min.js"></script>
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-primary navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">HOTEL</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <?php if (isset($_SESSION['login'])) : ?>
                            <a onclick="return confirm('Logout?')" class="nav-link" href="logout.php">Logout</a>
                        <?php else : ?>
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#modal_login">Login</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-3">
        <?= flash(); ?>

        <!-- Modal Login -->
        <div class="modal fade" id="modal_login" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" class="modal-content">
                    <div class="modal-header bg-primary text-light">
                        <h1 class="modal-title fs-5 mx-auto" id="staticBackdropLabel">LOGIN</h1>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <input required class="form-control" type="text" name="username" id="username" placeholder="Enter Username">
                            </div>
                            <div class="col-12 mt-3">
                                <input required class="form-control" type="password" name="password" id="password" placeholder="Enter Password">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="login" class="btn btn-primary">Login Now</button>
                    </div>
                </form>
            </div>
        </div>
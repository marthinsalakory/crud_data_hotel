<?php
include 'function.php';

// if is login
if (isset($_SESSION['login'])) {
    header('Location: admin.php');
    exit;
}

// if click login
if (isset($_POST['login'])) {
    if ($_POST['username'] == 'admin' && $_POST['password'] == 'admin') {
        echo "<script>alert('Login success')</script>";
        $_SESSION['login'] = true;
        header('Location: admin.php');
        exit;
    } else {
        echo "<script>alert('Login failed')</script>";
    }
}

include 'header.php';
?>
<div class="row">
    <div class="col-lg-4 col-sm-12 mx-auto">
        <form method="POST" class="input-group mb-3">
            <input value="<?= old_value('cari'); ?>" name="cari" type="text" class="form-control" placeholder="Enter hotel name">
            <button type="submit" class="btn btn-primary" id="button-addon2">Cari</button>
        </form>
    </div>
</div>
<div class="row mt-3">
    <?php if (!isset($_POST['cari'])) : ?>
        <?php foreach (db_findAll('data') as $dt) : ?>
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <img src="assets/img/hotel1.jpg" style="border-radius: 10px;" alt="Gambar Hotel" class="img-fluid">
                        <h3 class="text-center text-uppercase"><?= $dt['name']; ?></h3>
                        <p><?= $dt['description']; ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    <?php else : ?>
        <?php foreach (db_findAll('data', ['name' => $_POST['cari']]) as $dt) : ?>
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <img src="assets/img/hotel1.jpg" style="border-radius: 10px;" alt="Gambar Hotel" class="img-fluid">
                        <h3 class="text-center text-uppercase"><?= $dt['name']; ?></h3>
                        <p><?= $dt['description']; ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    <?php endif ?>
</div>
<?php include 'footer.php' ?>
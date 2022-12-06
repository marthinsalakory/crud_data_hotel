<?php
include 'function.php';

// if no login
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit;
}

// if add data
if (isset($_POST['hotel'])) {
    $ext = explode('.', $_FILES['image']['name']);
    $ext = strtolower(end($ext));
    $filename = uniqid() . '.' . $ext;
    if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png') {
        move_uploaded_file($_FILES['image']["tmp_name"], 'assets/img/' . $filename);
        db_insert('data', [
            'name' => $_POST['name'],
            'address' => $_POST['address'],
            'no_hp' => $_POST['no_hp'],
            'image' => $filename,
            'latlng' => $_POST['latlng'],
            'description' => $_POST['Description'],
        ]);
        setFlash('Success add data');
        header('Location: admin.php');
        exit;
    } else {
        setFlash('Extensi file tidak didukung');
    }
}

// if edit data
if (isset($_POST['hotel_edit'])) {
    if ($_FILES['image']["error"] > 0) {
        $filename = $_POST['old_image'];
    } else {
        $ext = explode('.', $_FILES['image']['name']);
        $ext = strtolower(end($ext));
        $filename = uniqid() . '.' . $ext;
        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png') {
            unlink('assets/img/' . $_POST['old_image']);
            move_uploaded_file($_FILES['image']["tmp_name"], 'assets/img/' . $filename);
        } else {
            setFlash('Extensi file tidak didukung');
        }
    }

    if (!isFlash()) {
        db_update('data', ['image' => $_POST['old_image']], [
            'name' => $_POST['name'],
            'address' => $_POST['address'],
            'no_hp' => $_POST['no_hp'],
            'image' => $filename,
            'latlng' => $_POST['latlng'],
            'description' => $_POST['Description'],
        ]);
        setFlash('Success update data');
        header('Location: admin.php');
        exit;
    }
}

// if delete onclick
if (isset($_GET['hapus'])) {
    if (db_delete('data', ['image' => $_GET['hapus']])) {
        unlink('assets/img/' . $_GET['hapus']);
        setFlash('Success delete');
        header('Location: admin.php');
        exit;
    } else {
        setFlash('Failed delete');
        header('Location: admin.php');
        exit;
    }
}

include 'header.php';
?>
<div class="row">
    <div class="col-12 d-flex justify-content-between">
        <h3>Data Hotel</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add"><i class="fa fa-add"></i> Add</button>
    </div>
</div>
<!-- Modal Add -->
<div class="modal fade" id="modal_add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <form method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header bg-primary text-light">
                <h1 class="modal-title fs-5 mx-auto" id="staticBackdropLabel">FORM HOTEL</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mt-3 col-lg-4 col-md-6 col-sm-12">
                        <input value="<?= old_value('name'); ?>" required maxlength="50" required class="form-control" type="text" name="name" placeholder="Enter Name">
                    </div>
                    <div class="mt-3 col-lg-4 col-md-6 col-sm-12">
                        <input value="<?= old_value('address'); ?>" required maxlength="50" required class="form-control" type="text" name="address" placeholder="Enter Address">
                    </div>
                    <div class="mt-3 col-lg-4 col-md-6 col-sm-12">
                        <input value="<?= old_value('no_hp'); ?>" required maxlength="50" required class="form-control" type="number" name="no_hp" placeholder="Enter Phone Number">
                    </div>
                    <div class="mt-3 col-lg-4 col-md-6 col-sm-12">
                        <input value="<?= old_value('image'); ?>" required required class="form-control" type="file" name="image">
                    </div>
                    <div class="mt-3 col-lg-4 col-md-6 col-sm-12">
                        <input value="<?= old_value('latlng'); ?>" required maxlength="50" required class="form-control" type="text" name="latlng" placeholder="Enter Latitude, Longitude">
                    </div>
                    <div class="mt-3 col-lg-4 col-md-6 col-sm-12">
                        <textarea maxlength="100" class="form-control" name="Description" rows="10" placeholder="Enter Description"><?= old_value('description'); ?></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="hotel" class="btn btn-primary">Add Now</button>
            </div>
        </form>
    </div>
</div>
<div class="row mt-3 justify-content-end">
    <div class="col-lg-4 col-sm-12">
        <form method="POST" class="input-group mb-3">
            <input value="<?= old_value('cari'); ?>" name="cari" type="text" class="form-control" placeholder="Enter hotel name">
            <button type="submit" class="btn btn-primary" id="button-addon2">Cari</button>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-12 table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Name</th>
                    <th scope="col">Address</th>
                    <th scope="col">Phone Number</th>
                    <th scope="col">Image</th>
                    <th scope="col">Latitude Longitude</th>
                    <th scope="col">Description</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!isset($_POST['cari'])) : ?>
                    <?php foreach (db_findAll('data') as $dt) : ?>
                        <tr>
                            <th scope="row">1</th>
                            <td class="text-uppercase"><?= $dt['name']; ?></td>
                            <td><?= $dt['address']; ?></td>
                            <td><?= $dt['no_hp']; ?></td>
                            <td><img class="img-fluid" style="border-radius: 10px;" src="assets/img/<?= $dt['image']; ?>" alt="Gambar Hotel"></td>
                            <td><?= $dt['latlng']; ?></td>
                            <td><?= $dt['description']; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm m-1" data-bs-toggle="modal" data-bs-target="#modal_edit" onclick="$('#modal_edit #name').val('<?= $dt['name']; ?>'); $('#modal_edit #address').val('<?= $dt['address']; ?>'); $('#modal_edit #no_hp').val('<?= $dt['no_hp']; ?>'); $('#modal_edit #latlng').val('<?= $dt['latlng']; ?>'); $('#modal_edit #description').val('<?= $dt['description']; ?>'); $('#modal_edit #old_image').val('<?= $dt['image']; ?>');"><i class="fa fa-edit"></i></button>
                                <a href="?hapus=<?= $dt['image']; ?>" class="btn btn-danger btn-sm m-1"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <?php foreach (db_findAll('data', ['name' => $_POST['cari']]) as $dt) : ?>
                        <tr>
                            <th scope="row">1</th>
                            <td class="text-uppercase"><?= $dt['name']; ?></td>
                            <td><?= $dt['address']; ?></td>
                            <td><?= $dt['no_hp']; ?></td>
                            <td><img class="img-fluid" style="border-radius: 10px;" src="assets/img/<?= $dt['image']; ?>" alt="Gambar Hotel"></td>
                            <td><?= $dt['latlng']; ?></td>
                            <td><?= $dt['description']; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm m-1" data-bs-toggle="modal" data-bs-target="#modal_edit" onclick="$('#modal_edit #name').val('<?= $dt['name']; ?>'); $('#modal_edit #address').val('<?= $dt['address']; ?>'); $('#modal_edit #no_hp').val('<?= $dt['no_hp']; ?>'); $('#modal_edit #latlng').val('<?= $dt['latlng']; ?>'); $('#modal_edit #description').val('<?= $dt['description']; ?>'); $('#modal_edit #old_image').val('<?= $dt['image']; ?>');"><i class="fa fa-edit"></i></button>
                                <a href="?hapus=<?= $dt['image']; ?>" class="btn btn-danger btn-sm m-1"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Modal Add -->
<div class="modal fade" id="modal_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <form method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header bg-warning text-light">
                <h1 class="modal-title fs-5 mx-auto" id="staticBackdropLabel">EDIT HOTEL</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mt-3 col-lg-4 col-md-6 col-sm-12">
                        <input required maxlength="50" required class="form-control" type="text" name="name" id="name" placeholder="Enter Name">
                    </div>
                    <div class="mt-3 col-lg-4 col-md-6 col-sm-12">
                        <input required maxlength="50" required class="form-control" type="text" name="address" id="address" placeholder="Enter Address">
                    </div>
                    <div class="mt-3 col-lg-4 col-md-6 col-sm-12">
                        <input required maxlength="50" required class="form-control" type="number" name="no_hp" id="no_hp" placeholder="Enter Phone Number">
                    </div>
                    <div class="mt-3 col-lg-4 col-md-6 col-sm-12">
                        <input class="form-control" type="hidden" name="old_image" id="old_image">
                        <input class="form-control" type="file" name="image" id="image">
                    </div>
                    <div class="mt-3 col-lg-4 col-md-6 col-sm-12">
                        <input required maxlength="50" required class="form-control" type="text" name="latlng" id="latlng" placeholder="Enter Latitude, Longitude">
                    </div>
                    <div class="mt-3 col-lg-4 col-md-6 col-sm-12">
                        <textarea required maxlength="100" class="form-control" name="Description" id="description" rows="10" placeholder="Enter Description"><?= old_value('description'); ?></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="hotel_edit" class="btn btn-warning">Edit Now</button>
            </div>
        </form>
    </div>
</div>
<?php include 'footer.php' ?>
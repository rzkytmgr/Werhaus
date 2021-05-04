<?php

class ControllerCategories extends Controller
{
    protected $_pk = 'CategoryId';
    protected $_model = 'Categories';

    public $_hak = array();

    public function index()
    {

        $data = $this->_model::loads();
        if (!isset($_SESSION['role'])) {
            header('Location: index.php');
        }
?>

        <section style="max-width: 666px; margin: auto">
            <a class="button is-warning is-small" href="<?= $this->createUrl('create') ?>">
                <ion-icon name="add-circle-outline" style="margin-right: 5px;"></ion-icon>
                Add Category
            </a>
            <section style="margin: 20px 0;">
                <table style="width: 100%;">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($data as $k => $v) : ?>
                        <tr>
                            <td><?= ($k + 1) ?></td>
                            <td><?= $v->CategoryName ?></td>
                            <td><?= $v->CategoryCode ?></td>
                            <td class="table-action">
                                <a href="<?= $this->createUrl('view&id=' . $v->CategoryId) ?>" class="button is-small is-warning is-light">
                                    <ion-icon name="eye-outline"></ion-icon>
                                </a>
                                <a href="<?= $this->createUrl('update&id=' . $v->CategoryId) ?>" class="button is-small is-warning is-light">
                                    <ion-icon name="build-outline"></ion-icon>
                                </a>
                                <a href="<?= $this->createUrl('delete&id=' . $v->CategoryId) ?>" class="button is-small is-danger is-light">
                                    <ion-icon name="trash-outline"></ion-icon>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </section>
        </section>

    <?php
    }

    protected function _load()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        if ($id == '') {                             // kalau tidak ada ID yang mau dihapus 
            header('Location: ' . $this->createUrl('index')); // tampilkan pesan
            return;
        }
        // simpan ke sebuah variabel nama primary keynya
        $pk = $this->_pk;
        // load object model 
        $categoryModel = new Categories();
        $categoryModel->CategoryId = $id;
        $categoryModel->load();
        return $categoryModel;
    }

    public function create()
    {

        if (isset($_POST[$this->_model])) {
            $data = $_POST[$this->_model];
            $data['CategoryCode'] = $_POST['FCode'] . '-' . $_POST['SCode'];
            $categoryModel = new Categories();

            $categoryModel->CategoryName = $data['CategoryName'];
            $categoryModel->CategoryCode = $data['CategoryCode'];

            $categoryModel->load(
                'SELECT * FROM ' . $categoryModel->tablename .
                    ' WHERE CategoryName="' . $categoryModel->CategoryName . '"' .
                    ' OR CategoryCode="' . $categoryModel->CategoryCode . '"'
            );

            if ($categoryModel->CategoryId != '') {
                header('Location: ' . $this->createUrl('create&err=1'));
            } else {
                $categoryModel->_isfetch = false;
                $categoryModel->CategoryId = '';
                $categoryModel->save();
                header('Location: ' . $this->createUrl('create&success=1'));
            }
        }


    ?>
        <section style="max-width: 666px; margin: auto">
            <?= $this->createUrl('index', 'Home') ?>
            <section class="form-box" style="max-width: 600px; margin: 20px auto">
                <?php
                if ($_GET['err'] == '1') {
                ?>
                    <div class="notification is-danger">
                        <button class="delete" onclick="notificationHider(this)"></button>
                        <small>Category Name or Category Code already listed on Categories!</small>
                    </div>
                <?php
                }
                ?>

                <?php
                if ($_GET['success'] == '1') {
                ?>
                    <div class="notification is-warning">
                        <button class="delete" onclick="notificationHider(this)"></button>
                        <small>Successfully add a category!</small>
                    </div>
                <?php
                }
                ?>

                <form action="" method="POST" class="box">
                    <div class="field">
                        <label class="label">Category Name</label>
                        <div class="control">
                            <input class="input" name="Categories[CategoryName]" type="text" placeholder="Makanan">
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Category Code</label>
                        <div class="control">
                            <input class="input" name="FCode" style="max-width: 10%; text-align: center;" type="text" placeholder="MA">
                            <input class="input" name="SCode" style="max-width: 20%;" type="text" placeholder="001">
                        </div>
                    </div>
                    <button class="button is-warning has-text-white">
                        <ion-icon name="add-circle" style="margin-right: 10px;"></ion-icon>Add Category
                    </button>
                </form>
            </section>
        </section>
    <?php
    }

    public function view()
    {
        $categoryModel = $this->_load();

        echo $this->createUrl('index', 'Kembali');
    ?>

        <section style="margin: 20px;">
            <table>
                <tr>
                    <th style="padding: 20px; text-align: left !important">Category Id</th>
                    <td style="padding: 20px; text-align: left !important"><?= $categoryModel->CategoryId ?></td>
                </tr>
                <tr>
                    <th style="padding: 20px; text-align: left !important">Category Name</th>
                    <td style="padding: 20px; text-align: left !important"><?= $categoryModel->CategoryName ?></td>
                </tr>
                <tr>
                    <th style="padding: 20px; text-align: left !important">Category Code</th>
                    <td style="padding: 20px; text-align: left !important"><?= $categoryModel->CategoryCode ?></td>
                </tr>
            </table>
        </section>

        <?php
    }

    public function update()
    {
        $categoryModel = $this->_load();

        if (isset($_POST[$this->_model])) {
            $data = $_POST[$this->_model];
            $data['CategoryCode'] = $_POST['FCode'] . '-' . $_POST['SCode'];
            $categoryModel->CategoryName = $data['CategoryName'];
            $categoryModel->CategoryCode = $data['CategoryCode'];
            $categoryModel->save();
            header('Location: ' . $this->createUrl('index'));
        } else {
        ?>
            <section style="max-width: 666px; margin: auto">
                <?= $this->createUrl('index', 'Kembali') ?>
                <section class="form-box" style="max-width: 600px; margin: 20px auto">


                    <form action="" method="POST" class="box">
                        <div class="field">
                            <label class="label">Category Name</label>
                            <div class="control">
                                <input class="input" name="Categories[CategoryName]" type="text" value=<?= $categoryModel->CategoryName ?>>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Category Code</label>
                            <div class="control">
                                <input class="input" name="FCode" style="max-width: 10%; text-align: center;" type="text" value="<?= explode('-', $categoryModel->CategoryCode)[0] ?>">
                                <input class="input" name="SCode" style="max-width: 20%;" type="text" value=<?= explode('-', $categoryModel->CategoryCode)[1] ?>>
                            </div>
                        </div>
                        <button class="button is-warning has-text-white">
                            <ion-icon name="add-circle" style="margin-right: 10px;"></ion-icon>Update Category
                        </button>
                    </form>
                </section>
            </section>
<?php
        }
    }

    public function test()
    {
    }

    public function search()
    {
        echo 'test';
    }
}

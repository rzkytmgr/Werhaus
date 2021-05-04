<?php
class Controller
{

    protected $_url;
    protected $_pk;
    protected $_model;
    protected $_hak = array('create', 'update', 'insert', 'delete', 'index');
    protected $_action;

    public function __construct($url = '', $model = '', $pk = '')
    {
        $this->_model    = ($model != '' ? $model : $this->_model);
        $this->_pk        = ($pk != '' ? $pk : $this->_pk);
        $this->_url        = ($url != '' ? $url : $this->_url);
        $this->_action();
    }

    protected function _action()
    {
        // ambil action, jika tidak diisi, arahkan ke index
        $action = isset($_GET['act']) ? $_GET['act'] : 'index';
        $page  = isset($_GET['page']) ? $_GET['page'] : '';
        // dari pada pakai action, kita pakai methode saja
        // cek apakah class ini ada action yang dipanggil 
        // dengan fungsi: method_exists
        if (method_exists($this, $action) && $this->canAccess($action, $page)) {
            // kalau ada action, simpan action, panggil action
            $this->_action = $action;
            $this->$action();
        } else {
            header('Location: index.php?page=user&msg=Silahkan Login untuk melanjutkan');
        }
    }

    protected function canAccess($action, $modul)
    {
        if (in_array($action, $this->_hak)) {
            if (!isset($_SESSION)) {
                session_start();
            }

            if (
                isset($_SESSION['access'][$modul]) &&
                in_array($action, $_SESSION['access'][$modul])
            ) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

    // dapat ditimpa/override 
    // digunakan untuk mengambil data
    protected function _load()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        if ($id == '') {                             // kalau tidak ada ID yang mau dihapus 
            echo 'Tidak ada Barang yang akan di-' . $this->_action; // tampilkan pesan
            return;
        }
        // simpan ke sebuah variabel nama primary keynya
        $pk = $this->_pk;
        // load object model 
        $objModel = new $this->_model();
        $objModel->$pk = $id;
        $objModel->load();
        return $objModel;
    }

    // dapat ditulis ulang untuk menampilkan tabel
    // data dalam bentuk object 
    // field_label dalam bentuk array dengan index adalah attribute
    // 										 value adalah labelnya
    protected function table($data, $field_label)
    {
        // simpan ke sebuah variabel nama primary keynya
        $pk = $this->_pk;

        // buat tabel dan header dulu
        echo  '<table class="table">'
            . '<tr>'
            . '<th>No.</th>';

        foreach ($field_label as $nama_field => $label) {
            echo '<th>' . $label . '</th>';
        }
        echo '<th>Aksi</th></tr>';
        $modul  = isset($_GET['page']) ? $_GET['page'] : '';
        // buat data 
        foreach ($data as $k => $y) {
            echo '<tr>';
            echo      '<td>' . ($k + 1) . '</td>';
            foreach ($field_label as $nama_field => $label) {
                echo '<td>' . $y->$nama_field . '</td>';
            }
            echo '<td>';
            if ($this->canAccess('view', $modul)) {
                echo '<a class="btn btn-primary" href="' . $this->createUrl('view') . '&id=' . $y->$pk . '">View</a>';
            }
            if ($this->canAccess('update', $modul)) {
                echo '&nbsp;&nbsp;'
                    . '<a class="btn btn-primary" href="' . $this->createUrl('update') . '&id=' . $y->$pk . '">Update</a>';
            }
            if ($this->canAccess('delete', $modul)) {
                echo '&nbsp;&nbsp;'
                    . '<a class="btn btn-primary" href="' . $this->createUrl('delete') . '&id=' . $y->$pk . '">Delete</a>';
            }
            echo '</td>';
            echo '</tr>';
        }
        echo  '</table>';
    }

    // dapat ditulis ulang untuk menampilkan url
    // kalau label diisi, akan muncul a 
    // kalau tidak hanya urlnya 
    public function createUrl($action = 'index', $label = '')
    {
        // susun urlnya
        $url = $this->_url . '&act=' . $action;
        // kalau ada label yang mau dimasukkan, 
        // berarti harus buat a 
        if ($label != '') {
            return '<a class="url button is-warning is-light is-small" href="' . $url . '"><ion-icon style="margin-right: 10px" name="chevron-back-outline"></ion-icon>' . $label . '</a>';
        }
        return $url;
    }

    // dapat ditimpa/override
    // menghapus data 
    public function delete()
    {
        $this->_load()->delete();    // load model & hapus data
        header("Location:" . $this->createUrl('index'));
    }

    // harus ditimpa/override
    // menampilkan data, harap buat tampilan HTMLnya 
    public function view()
    {
        $data = $this->_load();        // load model

        // tampilkan view disini

        /* Siapkan jalan kembali */
        echo $this->createUrl('index', 'Kembali');
    }

    // harus ditimpa/override
    // menampilkan form create dan simpan, harap buat tampilan HTMLnya 
    public function create()
    {
        /* 
			kalau ada post, tetapi tidak berhasil simpan, maka 		
			cek apakah $this->_model sama dengan di $_POST
			karena indexnya case sensitive
		*/
        if (isset($_POST[$this->_model])) {                    // kalau ada post
            $objModel = new $this->_model();                // buat var tipe 
            $objModel->_attributes = $_POST[$this->_model];      // ambil datanya
            $objModel->save();                                // simpan
            // arahkan kembali ke index
            header("Location:" . $this->createUrl('index'));
        } else {
            // kalau tidak ada post, maka user baru klik create
            // tampilkan form disini

            /* Siapkan jalan kembali */
            echo $this->createUrl('index', 'Kembali');
        }
    }

    // harus ditimpa/override
    // menampilkan form update dan simpan, harap buat tampilan HTMLnya
    public function update()
    {
        $objModel = $this->_load();                            // load model 
        /* 
			kalau ada post, tetapi tidak berhasil simpan, maka 		
			cek apakah $this->_model sama dengan di $_POST
			karena indexnya case sensitive
		*/
        if (isset($_POST[$this->_model])) {                    // kalau ada post
            $objModel->_attributes = $_POST[$this->_model];      // ambil datanya
            $objModel->save();                                // simpan
            // arahkan kembali ke index
            header("Location:" . $this->createUrl('index'));
        } else {
            // kalau tidak ada post, maka user baru klik update 
            // tampilkan form disini

            /* Siapkan jalan kembali */
            echo $this->createUrl('index', 'Kembali');
        }
    }

    // dapat ditimpa/override
    // menampilkan tabel tidak perlu ditimpa, boleh default
    public function index()
    {
        // tampilkan tombol tambah
        echo $this->createUrl('create', 'Create ' . $this->_model);
        // load data;
        $data = $this->_model::loads();
        // susun dalam tabel 
        $this->table($data, array(
            'BarangNama' => 'Nama Barang',
            'BarangHarga' => 'Harga',
            'BarangTipe' => 'Tipe',
        ));
    }
}

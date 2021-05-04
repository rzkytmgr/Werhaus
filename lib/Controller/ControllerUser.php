<?php

class ControllerUser extends Controller
{
    protected $_pk  = 'UserLogin';
    protected $_model = 'User';
    public $_hak = array();
    private $role = array();

    public function index()
    {
        if (!isset($_SESSION['role'])) {
            header('Location: ' . $this->createUrl('login'));
        }

?>

        <span class="is-size-3">Selamat Datang <?php echo $_SESSION['fullname'] ?></span>

        <?php
    }

    // login method
    public function login()
    {
        if (isset($_SESSION['role'])) {
            header('Location: ' . $this->createUrl('index'));
        }

        if (isset($_POST[$this->_model])) {
            $this->role = $GLOBALS['role'];
            $data = $_POST[$this->_model];
            $userModel = new User();
            $userModel->UserEmail = $data['UserEmail'];
            $userModel->load();

            if ($userModel->UserPassword == $data['UserPassword']) {
                if (!isset($_SESSION)) session_start();
                $_SESSION['role'] = $this->role[$userModel->UserRole];
                $_SESSION['fullname'] = $userModel->UserFullname;
                $_SESSION['email'] = $userModel->UserEmail;
                header('Location: ' . $this->createUrl('index'));
            } else {
                header('Location: ' . $this->createUrl('login&err=1'));
            }
        } else {
        ?>

            <div class="box form-box" style="max-width: 500px; margin: auto">

                <?php
                if ($_GET['err'] == 1) {
                ?>
                    <div class="notification is-danger">
                        <button class="delete" onclick="notificationHider(this)"></button>
                        <small>Email or Password incorrect!</small>
                    </div>
                <?php
                }
                ?>
                <form class="" method="POST" action="<?php $this->createUrl('index') ?>">
                    <div class="field">
                        <label class="label">Email</label>
                        <div class="control">
                            <input class="input" name="User[UserEmail]" type="email" placeholder="admin@werhaus.com">
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Password</label>
                        <div class="control">
                            <input class="input" name="User[UserPassword]" type="password" placeholder="●●●●●">
                        </div>
                    </div>
                    <button class="button is-warning has-text-white">Sign in</button>
                </form>
            </div>

        <?php

        }
    }

    public function register()
    {
        if (isset($_SESSION['role'])) {
            header('Location: ' . $this->createUrl('index'));
        }

        if (isset($_POST[$this->_model])) {
            $this->role = 2;
            $data = $_POST[$this->_model];
            $userModel = new User();

            $userModel->UserEmail = $data['UserEmail'];
            $userModel->load();


            if ($userModel->UserRole != '') {
                header('Location: ' . $this->createUrl('register&err=1'));
            }

            $userModel->_isfetch = false;
            $userModel->UserPassword = $data['UserPassword'];
            $userModel->UserFullname = $data['UserFullname'];
            $userModel->UserRole = 2;
            $userModel->save();
            header('Location: ' . $this->createUrl('register&success=1'));
        } else {
        ?>
            <div style="max-width: 500px; margin:auto">
                <div class="box form-box">
                    <?php
                    if ($_GET['success'] == 1) {
                    ?>
                        <div class="notification is-warning">
                            <button class="delete" onclick="notificationHider(this)"></button>
                            <small>Success, Your account has been created! You can <?= $this->createUrl('login', 'Login') ?> now!</small>
                        </div>
                    <?php
                    }

                    if ($_GET['err'] == 1) {
                    ?>
                        <div class="notification is-danger">
                            <button class="delete" onclick="notificationHider(this)"></button>
                            <small>Email already used by other user! choose another email</small>
                        </div>
                    <?php
                    }
                    ?>
                    <form class="" method="POST" action="<?php $this->createUrl('index') ?>">
                        <div class="field">
                            <label class="label">Full Name</label>
                            <div class="control">
                                <input class="input" name="User[UserFullname]" type="text" placeholder="Alexander Graham Bell">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Email</label>
                            <div class="control">
                                <input class="input" name="User[UserEmail]" type="email" placeholder="graham@werhaus.com" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Password</label>
                            <div class="control">
                                <input id="password" class="input" name="User[UserPassword]" type="password" placeholder="●●●●●" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Confirm Password</label>
                            <div class="control">
                                <input id="confirmPassword" class="input" name="User[UserCPassword]" type="password" placeholder="●●●●●" required>
                            </div>
                        </div>
                        <button class="button is-warning has-text-white">Register</button>
                    </form>
                </div>
            </div>
<?php
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: ' . $this->createUrl('index'));
    }
}

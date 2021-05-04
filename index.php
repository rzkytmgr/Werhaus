<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Werewerehaus</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <nav class="navbar py-3 px-6" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href=".">
                <span class="is-size-3 ff-poppins has-text-weight-bold has-text-warning">Werha<span class="has-text-grey">us</span></span>
            </a>

            <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="navbarBasicExample" class="navbar-menu">
            <div class="navbar-start px-5">
                <?php
                if (isset($_SESSION['role']) && $_SESSION['role'] != '') {
                ?>
                    <a class="navbar-item mx-5" href="?page=categories">
                        <ion-icon name="grid-outline"></ion-icon>
                        <span style="margin-left: 10px">Categories</span>
                    </a>

                    <a class="navbar-item mx-5" href="?page=inventory">
                        <ion-icon name="cube-outline"></ion-icon>
                        <span style="margin-left: 10px">Inventory</span>
                    </a>

                    <?php
                    if (count($_SESSION['role']) > 2) {
                    ?>

                        <a class="navbar-item mx-5" href="?page=employee">
                            <ion-icon name="people-outline"></ion-icon>
                            <span style="margin-left: 10px">Employee</span>
                        </a>

                        <a class="navbar-item mx-5" href="?page=users">
                            <ion-icon name="person-outline"></ion-icon>
                            <span style="margin-left: 10px">User</span>
                        </a>

                <?php
                    }
                }
                ?>
            </div>

            <div class="navbar-end">
                <div class="navbar-item">
                    <div class="buttons">
                        <?php
                        if (isset($_SESSION['role']) && isset($_SESSION['email'])) {
                        ?>
                            <div style="display: flex; align-items: center">
                                <div style="background-image: linear-gradient(to bottom left, orange, yellow); height: 25px; width: 25px; border-radius: 100%; margin: 0 10px"></div>
                                <div style="display: inherit; flex-direction: column">
                                    <small style="display: block;margin: -3px 0"><?= $_SESSION['fullname'] ?></small>
                                    <small style="display: block;margin: -3px 0;font-size: 10px;"><?= $_SESSION['email'] ?></small>
                                </div>
                                <a href="?page=user&act=logout" style="font-size: 20px; margin: 0 20px">
                                    <ion-icon name="log-out-outline"></ion-icon>
                                </a>
                            </div>
                        <?php
                        } else {
                        ?>
                            <a class="button is-warning has-text-white" href="?page=user&act=register">
                                <strong>Sign up</strong>
                            </a>
                            <a class="button is-light" href="?page=user&act=login">
                                Log in
                            </a>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <main id="content" class="has-background-white">
        <?php

        include_once('lib/class/config.php');
        if (isset($_GET['page'])) {
            $controller = 'Controller' . ucfirst(strtolower($_GET['page']));
            new $controller('?page=' . $_GET['page']);
        } else {
            new ControllerUser('?page=user');
        }
        ?>
    </main>
    <script src="js/script.js"></script>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>

</html>
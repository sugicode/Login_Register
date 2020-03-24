<?php

require_once("config.php");

if(isset($_POST['register'])){

    // filter data yang diinputkan
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    // enkripsi password
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);


    // menyiapkan query
    $sql = "INSERT INTO users (name, username, email, password)
            VALUES (:name, :username, :email, :password)";
    $stmt = $db->prepare($sql);

    // bind parameter ke query
    $params = array(
        ":name" => $name,
        ":username" => $username,
        ":password" => $password,
        ":email" => $email
    );

    // eksekusi query untuk menyimpan ke database
    $saved = $stmt->execute($params);

    // jika query simpan berhasil, maka user sudah terdaftar
    // maka alihkan ke halaman login
    if($saved) header("Location: login.php");
}

?><!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>SCode</title>
  <link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel="stylesheet" href="./stylee.css">

</head>
<body>
<!-- partial:indexnya -->
<div class="form">

      <ul class="tab-group">
        <li class="tab active"><a href="#signup">Daftar Account</a></li>
        <li class="tab"><a  href="#login">Login</a></li>
      </ul>

      <div class="tab-content">
        <div id="signup">
          <h1>Register Account</h1>

          <form action="/" method="post">

          <div class="top-row">
            <div class="field-wrap">
              <label>
                Nama Depan Anda<span class="req">*</span>
              </label>
              <input type="text" required autocomplete="off" />
            </div>

            <div class="field-wrap">
              <label>
                Nama Belakang Anda<span class="req">*</span>
              </label>
              <input type="text"required autocomplete="off"/>
            </div>
          </div>

          <div class="field-wrap">
            <label>
              Email Anda<span class="req">*</span>
            </label>
            <input type="email"required autocomplete="off"/>
          </div>

          <div class="field-wrap">
            <label>
              Masukkan Password Anda<span class="req">*</span>
            </label>
            <input type="password"required autocomplete="off"/>
          </div>

          <button type="submit" class="button button-block"/>Register</button>

          </form>

        </div>
        <?php

        require_once("config.php");

        if(isset($_POST['login'])){

            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

            $sql = "SELECT * FROM users WHERE username=:username OR email=:email";
            $stmt = $db->prepare($sql);

            // bind parameter ke query
            $params = array(
                ":username" => $username,
                ":email" => $username
            );

            $stmt->execute($params);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // jika user terdaftar
            if($user){
                // verifikasi password
                if(password_verify($password, $user["password"])){
                    // buat Session
                    session_start();
                    $_SESSION["user"] = $user;
                    // login sukses, alihkan ke halaman timeline
                    header("Location: beranda.php");
                }
            }
        }
        ?>
        <div id="login">
          <h1>Selamat Datang!</h1>

          <form action="/" method="post">

            <div class="field-wrap">
            <label>
              Email Anda<span class="req">*</span>
            </label>
            <input type="email"required autocomplete="off"/>
          </div>

          <div class="field-wrap">
            <label>
              Password Anda<span class="req">*</span>
            </label>
            <input type="password"required autocomplete="off"/>
          </div>

          <p class="forgot"><a href="#">Forgot Password?</a></p>

          <button class="button button-block"/>Login</button>

          </form>

        </div>

      </div><!-- tab-content -->

</div> <!-- /form -->
<!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script><script  src="./script.js"></script>

</body>
</html>

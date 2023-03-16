<form method="post" action="">
  <label for="username">Ho va ten:</label>
  <input type="text" id="username" name="username"><br>
  <label for="email">Email:</label>
  <input type="text" id="email" name="email"><br>
  <label for="phone">Phone:</label>
  <input type="text" id="phone" name="phone"><br>
  <label for="password">Password:</label>
  <input type="password" id="password" name="password"><br>
  <label for="cf_password">Cf_Password:</label>
  <input type="cf_password" id="cf_password" name="cf_password"><br>
  <input type="submit" name="submit" value="Register">
</form>

<?php
session_start();
if(isset($_POST['submit'])) {
    //kết nối sql
    $conn = mysqli_connect('localhost', 'username', 'password', 'QUANLY_NN');
    if (!$conn) {
        die("Kết nối thất bại: " . mysqli_connect_error());
    }
    if(!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['cf_password']) || !isset($_POST['phone']) || !isset($_POST['email']) ) {
      echo "Cac truong khong duoc de trong";
    }
    //gán tk mk chống sql
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cf_password = mysqli_real_escape_string($conn, $_POST['cf_password']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    //gán tk mk chống xss
    $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');
    $cf_password = htmlspecialchars($cf_password, ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $ktra = 0;
    //ktra chuỗi có ký tự đặc biệt hay không
    if (preg_match("/[!@#$%^&*()_+\-={}\[\]\|\\:;\"'<>,.?\/]/", $password)) {
        $ktra++;
    } 
    //ktra chuỗi có chữ số hay không
    if (preg_match("/[0-9]/", $password)) {
        $ktra++;
    } 
    //ktra chuỗi có chữ thường hay không
    if (preg_match("/[a-z]/", $password)) {
        $ktra++;
    }
    //ktra chuỗi có chữ hoa hay không
    if (preg_match("/[A-Z]/", $password)) {
        $ktra++;
    }
    if (strlen($password)<8 or $ktra < 2){
        echo "mk sai định dạng";
        return;
    }
    if ($password != $cf_password) {
      echo "mk sai định dạng";
      return;
    }
    //kiem tra email
    if (preg_match("/^\\S+@\\S+\\.\\S+$/", $email)) {
      echo "email khong dung dinh dang";
      return;
    }
    if (preg_match("/^\\+?[1-9][0-9]{7,14}$/", $phone)) {
      echo "dien thoai khong dung dinh dang";
      return;
    }

    // kiem tra email da ton tai
    $sql = "SELECT * FROM user WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 1) {
      echo "email da co nguoi su dung";
      return;
    }
    // kiem tra sdt da ton tai
    $sql = "SELECT * FROM user WHERE phone='$phone'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 1) {
      echo "sdt da co nguoi su dung";
      return;
    }
    $hash_password = md5($password);
    $sql = "INSERT INTO USER (USERNAME, EMAIL, PHONE, PASS)
    VALUES ('".$_POST["username"]."', '".$_POST["email"]."', '".$_POST["phone"]."', '".$hash_password."')";
    if (mysqli_query($conn, $sql)) {
      echo "dang ki thanh cong";
      header("location: login.php");
    } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>
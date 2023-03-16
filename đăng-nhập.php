<?php
session_start();
if(isset($_POST['submit'])) {
    //kết nối sql
    $conn = mysqli_connect('localhost', 'username', 'password', 'database_name');
    if (!$conn) {
        die("Kết nối thất bại: " . mysqli_connect_error());
    }
    //gán tk mk chống sql
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    //gán tk mk chống xss
    $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');
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
    } else{
        $hash_password = md5($password);
        echo $hash_password;
        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 1) {
            // Đăng nhập thành công
            session_start();
            echo "đăng nhập thành công";
        } else {
            // Đăng nhập thất bại
            echo "Sai tên đăng nhập hoặc mật khẩu";
        }
    }
}
?>
<form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br>
    <input type="submit" name="submit" value="Login">
</form>
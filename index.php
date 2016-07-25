<?php
require_once("functions.php");

$new_table = "new_tasks";
$errors = array();
if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $memo = $_POST['memo'];
    $name = htmlspecialchars($name, ENT_QUOTES);
    $memo = htmlspecialchars($memo, ENT_QUOTES);
    if($name === ''){
        $errors['name'] = 'タスク名が入力されていません。';
    }
    if($memo === ''){
        $errors['memo'] = 'メモが入力されていません。';
    }
    if(count($errors) === 0){



    	$conn3 = postgresql_db_connect("new_todolist");
    	$sql3 = "INSERT INTO {$new_table} (name, memo,done) VALUES('{$name}','{$memo}',0)";
		$stmt3 = pg_query($conn3,$sql3);
    	$conn3 = null;
    	unset($name, $memo);
    }
}
if(isset($_POST['method']) && ($_POST['method'] === 'put')){

	//データベースが存在しない場合作成する
	if(postgresql_db_connect("new_todolist") == false){
		$conn1 = postgresql_db_connect("postgres");
		$sql1 = "CREATE DATABASE new_todolist";
		$stmt1 = pg_query($conn1,$sql1);
		$conn1 = null;
	}

	//テーブルが存在しない場合作成する
		$conn2 = postgresql_db_connect("new_todolist");
		$sql2 = "CREATE TABLE IF NOT EXISTS {$new_table}(
		id serial PRIMARY KEY,
		name character varying(20),
		memo text,
		done int)";
		$stmt2 = pg_query($conn2,$sql2);
		$conn2 = null;

	$id = $_POST["id"];
    $id = htmlspecialchars($id, ENT_QUOTES);
    $id = (int)$id;
    $conn = postgresql_db_connect("new_todolist");
    $sql = "UPDATE {$new_table} SET done = 1  WHERE id = {$id}";
    $stmt = pg_query($conn,$sql);
    $conn = null;
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Todoリスト</title>
</head>
<body>
<h1>Todoリスト</h1>
<?php
if(isset($errors)){
	print("<ul>");
	foreach ($errors as $value){
		print("<li>");
		print ($value);
		print("</li>");
	}
	print("</ul>");
}
?>
<form action="index.php" method="post">
<ul>
    <li><span>タスク名</span><input type="text" name="name" value="<?php if (isset($name)) { print($name); } ?>"></li>
    <li><span>メモ</span><textarea name="memo"><?php if (isset($memo)) { print($memo); } ?></textarea></li>
    <li><input type="submit" name="submit"></li>
</ul>
</form>

<?php
//データベースが存在しない場合作成する
if(postgresql_db_connect("new_todolist") == false){
	$conn1 = postgresql_db_connect("postgres");
	$sql1 = "CREATE DATABASE new_todolist";
	$stmt1 = pg_query($conn1,$sql1);
	$conn1 = null;
}

//テーブルが存在しない場合作成する
	$conn2 = postgresql_db_connect("new_todolist");
	$sql2 = "CREATE TABLE IF NOT EXISTS {$new_table}(
	id serial PRIMARY KEY,
	name character varying(20),
	memo text,
	done int)";
	$stmt2 = pg_query($conn2,$sql2);
	$conn2 = null;


$conn = postgresql_db_connect("new_todolist");
$sql = "SELECT id, name, memo, done FROM {$new_table} WHERE done = 0 ORDER BY id DESC";
$stmt = pg_query($conn,$sql);
if(!$stmt){
	echo "An error occurred.\n";
    		exit;
}
pg_close($conn);
print('<dl>');
while ($task = pg_fetch_row($stmt)){
    print '<dt>';
    print $task[1];
    print '</dt>';
    print '<dd>';
    print $task[2];
    print '</dd>';
	print '<dd>';
    print '
            <form action="index.php" method="post">
            <input type="hidden" name="method" value="put">
            <input type="hidden" name="id" value="' . $task[0] . '">
            <button type="submit">完了</button>
            </form>
          ' ;
    print '</dd>';
}
print('</dl>');
?>
</ul>
</body>
</html>
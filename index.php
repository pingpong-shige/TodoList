
<?php

require_once("functions.php");

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

    	$conn = postgresql_db_connect();

    	$sql = "INSERT INTO tasks (name, memo,done) VALUES('{$name}','{$memo}',0)";
//     	$stmt = $conn->prepare($sql);
		$stmt = pg_query($conn,$sql);

//     	$stmt->bindValue(1, $name, PDO::PARAM_STR);
//     	$stmt->bindValue(2, $memo, PDO::PARAM_STR);
//     	$stmt->execute();

    	$conn = null;

    	unset($name, $memo);

    }
}

if(isset($_POST['method']) && ($_POST['method'] === 'put')){


// echo "<pre>";
// var_dump($_POST);
// echo "</pre>";


	$id = $_POST["id"];
    $id = htmlspecialchars($id, ENT_QUOTES);
    $id = (int)$id;

    $conn = postgresql_db_connect();

    $sql = "UPDATE tasks SET done = 1  WHERE id = {$id}";
//     $stmt = $conn->prepare($sql);


//      $stmt->bindValue(1, $id, PDO::PARAM_INT);
//     $stmt->execute();

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
$conn = postgresql_db_connect();

$sql = 'SELECT id, name, memo, done FROM tasks WHERE done = 0 ORDER BY id DESC';
//$stmt = $conn->prepare($sql);
//$stmt->execute();

$stmt = pg_query($conn,$sql);
if(!$stmt){
	echo "An error occurred.\n";
    		exit;
}
pg_close($conn);

print('<dl>');


//while($task = $stmt->fetch(PDO::FETCH_ASSOC)){
while ($task = pg_fetch_row($stmt)){



// 	echo "<pre>";
// 	echo var_dump($task);
// 	echo "</pre>";


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
            <button type="submit">済んだ</button>
            </form>
          ' ;
    print '</dd>';

}

print('</dl>');

?>
</ul>
</body>
</html>

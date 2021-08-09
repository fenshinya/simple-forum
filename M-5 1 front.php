<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
<?php
//【データベースに接続】
$dsn ='データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//【編集選択機能】前の名前とコメントを取得→フォームのvalueに反映
if(!empty($_POST["edit"]) && !empty($_POST["e_password"])){
    $edit_num=$_POST["edit"];
    $edit_pass=$_POST["e_password"];
    $sql="SELECT name,comment,password FROM forum where id=$edit_num";
    $stmt=$pdo->query($sql);
    $results=$stmt->fetchAll();
    //パスワードが一致したときだけ前の名前とコメント,パスワードを取得
    foreach($results as $row){
        if($row["password"]==$edit_pass){
            $prename=$row["name"];
            $precomment=$row["comment"];
            $prepass=$row["password"];
        }
    }
}

?>
    <form action="M-5 1.php" method="post">
        <p>【投稿フォーム】</p>
        <input type="text" name="name" placeholder="名前を入力してください" 
        value="<?php 
        if(!empty($prename)){
            echo $prename;
        }
        ?>"
        >
        <br>
        <input type="text" name="comment" placeholder="コメントしてください"
        value="<?php 
        if(!empty($precomment)){
            echo $precomment;
        }
        ?>">
        <br>
        <input type="password" name="new_password" placeholder="パスワードを入力">
        <br>
        <!--inputタグにPHPを組み込み、編集番号を表示-->
        <input type="hidden" name="edit_selected" 
        value="<?php
        //編集番号とパスワードが入力され、
        if(!empty($_POST["edit"]) && !empty($_POST["e_password"])){
            $edit_num=$_POST["edit"];
            $edit_pass=$_POST["e_password"];
            //パスワードが一致したとき（＝$prepassが値を持つ)ときだけ隠しフォームに数字を送る
            if(!empty($prepass)){
                echo $edit_num;
            }
        }
        ?>">
        <input type="submit" name="normal_form" value="送信">
    </form>
    <form action="M-5 1.php" method="post">
        <p>【削除フォーム】</p>
        <input type="number" name="delete_num" placeholder="削除する番号を指定">
        <br>
        <input type="password" name="d_password" placeholder="パスワードを入力">
        <br>
        <input type="submit" name="delete_form" value="削除">
    </form>
    <form action="" method="post">
        <p>【編集フォーム】</p>
        <input type="number" name="edit" placeholder="編集する番号を指定">
        <br>
        <input type="password" name="e_password" placeholder="パスワードを入力">
        <br>
        <input type="submit" name="edit_form" value="編集">
    </form>
    <p>＊必ずパスワードを設定してください（空欄だと投稿/編集/削除ができません）</p>
    <p>＊編集の際、パスワードを変更しない場合でも、改めて同じパスワードを入力してください</p>
    <p>【投稿一覧】</p>
<?php
//編集フォームの空欄処理
if(!empty($_POST["edit_form"])){
    if(empty($_POST["e_password"])){
        echo "パスワードを入力してください"."<br>";
    }
    if(empty($_POST["edit_num"])){
        echo "番号を指定してください"."<br>";
    }
}
//【表示機能】
$sql="SELECT * FROM forum ";
$stmt=$pdo->query($sql);
$results=$stmt->fetchAll();
foreach($results as $row){
    echo $row["id"].",";
    echo $row["name"].",";
    echo $row["comment"].",";
    //本来パスワードは後で隠す
    echo $row["password"].",";
    echo $row["date"]."<br>";
    echo "<hr>";
} 
?>
</body>
</html>
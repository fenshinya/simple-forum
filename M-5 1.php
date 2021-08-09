<?php
//【データベースに接続】
$dsn ='データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//【DB上にテーブルを作る】
$sql = "CREATE TABLE IF NOT EXISTS forum"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "password TEXT,"
    . "date datetime"
    .");";
$stmt = $pdo->query($sql);
/*
//テーブルの存在確認
$sql='SHOW CREATE TABLE forum';
$result = $pdo -> query($sql);
foreach ($result as $row){
        echo $row[1];
    }
echo "<hr>";
*/
//【編集選択機能】→フロントへ
//空欄処理
if(!empty($_POST["normal_form"])){
    if(empty($_POST["name"])){
        echo "名前を入力してください"."<br>";
    }
    if(empty($_POST["comment"])){
        echo "コメントを入力してください"."<br>";
    }
    if(empty($_POST["new_password"])){
        echo "パスワードを入力してください"."<br>";
    }
}
if(!empty($_POST["delete_form"])){
    if(empty($_POST["d_password"])){
        echo "パスワードを入力してください"."<br>";
    }
    if(empty($_POST["delete_num"])){
        echo "番号を指定してください"."<br>";
    }
}
//【編集機能】
if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["edit_selected"]) && !empty($_POST["new_password"])){
    $edit_num=$_POST["edit_selected"];
    $name=$_POST["name"];
    $comment=$_POST["comment"];
    $new_password=$_POST["new_password"];
    $Posted_date=date("Y/m/d H:i:s");
    //編集
    $sql="UPDATE forum SET name=:name,comment=:comment,password=:password,date=:date WHERE id=$edit_num ";
    $stmt=$pdo->prepare($sql);
    $stmt-> bindParam(':name', $name, PDO::PARAM_STR);
    $stmt-> bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt-> bindParam(':password', $new_password, PDO::PARAM_STR);
    $stmt-> bindParam(':date',$Posted_date, PDO::PARAM_STR);
    $stmt->execute();
    echo "編集に成功しました！"."<br>";
}
//【投稿機能】
elseif(!empty($_POST["name"]) && !empty($_POST["comment"])&& !empty($_POST["new_password"])){
    $name=$_POST["name"];
    $comment=$_POST["comment"];
    $new_password=$_POST["new_password"];
    $Posted_date=date("Y/m/d H:i:s");
     //書き込み
     $sql = $pdo -> prepare("INSERT INTO forum (name, comment, password, date) VALUES (:name, :comment,:password,:date)");
     //bindParam('プレースホルダ名', '実際にバインドするデータ', 'データの型');
     $sql -> bindParam(':name', $name, PDO::PARAM_STR);
     $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
     $sql -> bindParam(':password', $new_password, PDO::PARAM_STR);
     $sql -> bindParam(':date', $Posted_date, PDO::PARAM_STR);
     //実行
     $sql -> execute();
     echo "投稿が完了しました！"."<br>";
}
//【削除機能】
if(!empty($_POST["delete_num"])&& !empty($_POST["d_password"])){
    $delete_num=$_POST["delete_num"];
    $delete_pass=$_POST["d_password"];
    //指定行のパスワードを取得
    $sql="SELECT password FROM forum WHERE id=$delete_num";
    $stmt=$pdo->query($sql);
    $result=$stmt->fetchAll();
    foreach($result as $row){
        if($row["password"]==$delete_pass){
            //パスワード一致で削除実行
            $sql="delete from forum where id=:id";
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(':id',$delete_num, PDO::PARAM_STR);
            $stmt->execute();
            echo "削除に成功しました！"."<br>";
        }
        else{
            //パスワードが違かったら
            echo"パスワードが違います"."<br>";
        }
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
    //パスワードは後で隠す
    echo $row["password"].",";
    echo $row["date"]."<br>";
    echo "<hr>";
} 
//フォームに戻る
echo "<a href='https://tech-base.net/tb-230158/M-5%201%20front.php'>フォームに戻る</a>"
?>
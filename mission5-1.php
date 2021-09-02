<?php

$dsn = 'mysql:dbname=********db;host=localhost';
$user = 'euglenaaa';
$password = 'PASSWORD';
$pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

  //データベース内にテーブルを作成
  $sql = "CREATE TABLE IF NOT EXISTS m5_1_2"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32) NOT NULL,"
    . "comment TEXT NOT NULL,"
    . "pass TEXT NOT NULL,"
    . "date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    .");";
    $stmt = $pdo->query($sql);
    
    
//新規投稿
//echo "新規投稿デバッグ用<br>";
 if(!empty($_POST["name"])&&!empty($_POST["comment"])&&!empty($_POST["pass"])&&!empty($_POST["submit"])&&empty($_POST["edit_n"]))

{
    //echo "新規投稿分岐に入りました"."<br>";//デバッグ用
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $pass = $_POST["pass"];
    $date = date("Y/m/d/ H:i:s");
    
    
    $sql = $pdo -> prepare("INSERT INTO m5_1_2(name, comment, pass, date) VALUES (:name, :comment, :pass, :date)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
    $sql -> execute();
    

    
//bindParam => 与えられた変数を文字列としてパラメータに入れる
//PDO::PARAM_STR ->変数の値を文字列として扱う
//:nameなど -> パラメータ(:の後に任意の文字
 //execute() -> 準備したprepareに入っているSQL文を実行
 
}
//削除機能
//echo "削除デバッグ用"."<br>"; //デバッグ用
if(!empty($_POST["delno"])&&!empty($_POST["削除"])&&!empty($_POST["delpass"])){
    
    //echo "削除分岐に入りました"."<br>"; //デバッグ用
    $sql = ' SELECT * FROM m5_1_2 WHERE id=:id';
    //WHERE句 -> 演算子を使って検索条件を指定
    $delete = $_POST["delno"];

    $delpass=$_POST["delpass"];
    
    $stmt = $pdo -> prepare($sql);
//prepare ->値部分にパラメータをつけて実行待ち
$stmt -> bindParam(':id',$delete,PDO::PARAM_INT);
//bindParam => 与えられた変数を文字列としてパラメータに入れる
//PDO::PARAM_STR ->変数の値を文字列として扱う
//:nameなど -> パラメータ(:の後に任意の文字)
    $stmt->execute();
    //execute() -> 準備したprepareに入っているSQL文を実行
$results = $stmt -> fetchAll();
//fetchAll -> 結果データを全件まとめて配列で取得する
    
    foreach($results as $row){
        if($delpass == $row['pass']){
            $sql = 'DELETE FROM m5_1_2 WHERE id=:id';
            $stmt = $pdo -> prepare($sql);
            //prepare ->値部分にパラメータをつけて実行待ち
            $stmt -> bindParam(':id',$delete,PDO::PARAM_INT);
            $stmt -> execute();
        }else{
            echo "パスワードが違います<br>";
        }
    }
    
}


//編集用フォームに元の内容を表示
if(!empty($_POST["edit"])){

$sql = ' SELECT * FROM m5_1_2 WHERE id=:id';
//WHERE句 -> 演算子を使って検索条件を指定
$number = $_POST["editnumber"];
//編集対象番号の定義
$editpass = $_POST["editpass"];
//パスワードの定義

$stmt = $pdo->prepare($sql);
//prepare ->値部分にパラメータをつけて実行待ち
$stmt->bindParam(':id', $number,PDO::PARAM_INT);
$stmt->execute();
$results = $stmt -> fetchAll();
//fetchAll -> 結果データを全件まとめて配列で取得する

foreach($results as $row) {
      if($editpass == $row['pass'] && $number == $row['id']) {
          //編集パスとパス、編集対象番号とidが一致の時
        
        $newname = $row['name'];
        $newcomment = $row['comment'];
        //formにechoしてある
      } else {

     echo "パスワードが違います"."<br>";
      }
    }
  }



//編集処理
//echo "checkif_before編集"."<br>"; //デバッグ用
if (!empty($_POST["name"])&&!empty($_POST["comment"])&&!empty($_POST["edit_n"])&&!empty($_POST["pass"])){
    
    //echo "編集分岐に入りました"."<br>"; //デバッグ用
    
    $id = $_POST["edit_n"];
    $pass = $_POST["pass"];
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $date = date("Y/m/d H:i:s");
    //変数の定義
    
    
        
        //echo "編集アップデート分岐に入りました"."<br>";//デバッグ用
    
    
    $sql = 'UPDATE m5_1_2 SET name=:name, comment=:comment, pass=:pass, date=:date WHERE id=:id';
    //アップデート
    $stmt = $pdo->prepare($sql);
    $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
    $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
    $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
    $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
    $stmt -> execute();
    
    //echo $name."<br>";//デバッグ
    //echo $comment."<br>";//デバッグ
    //echo $pass."<br>";//デバッグ
    //echo $date."<br>";//デバッグ
    //echo $id."<br>";//デバッグ
    
    
    //echo "編集アップデート分岐終了";//デバッグ
        
      
      
    }
  

  
?>

<form action="" method="post">
        <!--名前の入力フォーム-->
        <input type="text" name="name" placeholder="名前" value="<?php if (isset($newname)){echo $newname;}?>">
        <br>
        <?php if(empty($_POST["name"])&&!empty($_POST["submit"])){echo "名前を入力してください"."<br>";} ?>
        <!--コメントの入力フォーム-->
        <input type="text"  name="comment" placeholder="コメント" value="<?php if (isset($newcomment)){echo $newcomment;}?>">
        <br>
        <?php if(empty($_POST["comment"])&&!empty($_POST["submit"])){echo "コメントを入力してください"."<br>";} ?>
        <!--パスワードの入力フォーム-->
        <input type="text"  name="pass" placeholder="パスワード">
        <br>
        <?php if(empty($_POST["pass"])&&!empty($_POST["submit"])){echo "パスワードを入力してください"."<br>";} ?>
        <input type="submit" name="submit" value = "送信">
        <br>
        <br>
        <!--削除フォーム-->
        <input type="text" name="delno" placeholder="削除対象番号">
        <br>
        <?php if(empty($_POST["delno"])&&!empty($_POST["削除"])){echo "削除対象番号を入力してください"."<br>";} ?>
        <input type= "text"  name="delpass" placeholder="パスワード">
        <br>
        <?php if(empty($_POST["delpass"])&&!empty($_POST["削除"])){echo "パスワードを入力してください"."<br>";} ?>
        <input type="submit" name="削除" value="削除">
        <br>
        
        <!--編集番号指定用フォーム-->
        <input type="hidden" name="edit_n" placeholder="ダミー" value="<?php if(isset($number)) {echo $number;}?>">
        <br>
        <input type="text" name="editnumber" placeholder="編集対象番号">
        <br>
        <?php if(empty($_POST["editnumber"])&&!empty($_POST["edit"])){echo "編集対象番号を入力してください"."<br>";} ?>
        <input type="text"  name="editpass" placeholder="パスワード">
        <br>
        <?php if(empty($_POST["editpass"])&&!empty($_POST["edit"])){echo "パスワードを入力してください"."<br>";} ?>
        <input type="submit" name="edit" value="編集">
        <br>
        <br>
        </form>
        <!--formタグ:htmlのメールフォーム-->
<!--action属性:必ず指定、送信先を指定-->
<!--method="post"はユーザー側の情報送信に使う-->
<!--input submitは送信ボタン-->
<!--input text は一行テキストボックス-->
<!--placeholderはテキストボックスに元々書いてある文字-->
<!-- input type:形式、name:名前-->

<?php
$sql = 'SELECT * FROM m5_1_2';
//SELECT -> どの項目(列)のデータを検索するかを指定
//FROM -> どの表から検索するかを指定する
$stmt = $pdo -> query($sql);
$results = $stmt -> fetchAll();
//fetchAll -> 結果データを全件まとめて配列で取得する
foreach ($results as $row){
    echo $row['id'].',';
    echo $row['name'].',';
    echo $row['comment'].'<br>';
    echo $row['date'].'<br>';
    echo "<hr>";
    //hr->横向きの罫線を引く
}

?>
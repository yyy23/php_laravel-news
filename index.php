<?php 
//変数の定義
$title =(isset($_POST["title"]) === true) ? $_POST["title"]: ""; //true:の左、 false:右
$article =(isset($_POST["article"]) === true) ? trim($_POST["article"]) : "";  //true:の左、 false:右    trim:両端のスペースを消す

$error_message = [];
$success_message = null;

if(isset ($_POST["send_submit"]) === true) { //ボタンが押された時の実行処理


  if(empty($_POST["title"]) ) { //タイトルが未入力の時
      $error_message[] = "※ タイトルを入力してください"; 
  } elseif (30 < mb_strlen($_POST["title"]) ) { //タイトルが30字以上の時
      $error_message[] = "※ タイトルは30字以下で入力してください";
  }  

  if(empty($_POST["article"]) ) { //記事内容が未入力の時
      $error_message[] = "※ 記事を入力してください";
  }   
  

  $fp = fopen("toukou.txt", 'a'); //ファイルポインタ：ファイル追記モードで開く
  fwrite($fp, $title . "\t" . $article . "\n"); //ファイル書き込み($title タブ $article 改行)
  fclose($fp); //ファイルを閉じる
}


//変数の初期化
$data = null;
$split_data = null;
$res = array();
$res_array = array();


$fp = fopen("toukou.txt", "r"); //ファイルを読み込みモードで開く

if($fp) {
  while($data = fgets($fp) ) {
    $split_data = preg_split("/[\t]/", $data);  //タブで文字列を分割する

    $res = array( //連想配列で代入 
      "title" => $split_data[0],  //投稿されたタイトル
      "article" => $split_data[1]   //投稿された記事
    );
    array_unshift($res_array, $res);  //$resの内容を$res_arrayに先頭に追加して配列する

  }  
  fclose($fp); //ファイルを閉じる
}  

?>


<!DOCTYPE html>
<html lang="ja">
<head>  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scal=1"> <!--表示領域設定：端末画面の幅、初期ズーム倍率-->

  <title>Laravel-News</title>
  <link rel="stylesheet" type="text/css" href="laravel-news.css"/>
</head> 

<header>
  <div class="nav-bar">
  <a href="http://localhost/index.php">Laravel-News</a> <!--TOP画面へのリンク-->
</div>
</header>

<body>
  <h2>皆さんのトレンドニュースを教えてください★</h2>
  
  <section>
    <?php foreach($error_message as $value): ?>
      <?php echo $value; ?>
    <?php endforeach; ?>
  </section>

  <form action= "index.php" method= "POST">  <!--ファイル、methodの指定-->
    <p>タイトル： <input type= "text" name= "title"></p><br>  <!--タイトル入力部分の作成-->
    <p>記事： <textarea name= "article" cols= "40" rows= "10"></textarea></p><br>  <!--記事入力部分の作成-->
    <input type= "submit" name= "send_submit" value= "投稿">  <!--投稿ボタンの作成-->
  </form>
  <hr>
    <?php foreach($res_array as $value): ?>  <!--foreach文で投稿件数分を表示する-->
    <?php echo $value["title"]; ?>  <!--タイトルの出力-->
    <p><?php echo $value["article"]; ?></p> <!--記事内容の出力-->
    <a href="http://localhost/comment.php">記事全文・コメントを読む</a>
  <hr>
    <?php endforeach; ?>  
</body>
</html>
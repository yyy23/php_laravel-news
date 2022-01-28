<!-- ＜表示機能  作成 >  -->
<?php 
//変数の定義
$is_title = isset($_POST["title"]); //$_POST["title"]が未定義もしくはnullの場合、$is_titleにfalseが格納される・違う場合はtrueが格納される
if($is_title === true) { //$is_titleがtrueだったらifの処理、falseだったらelseの処理
  $title = $_POST["title"];  //$_POST["title"]が$titleに格納される
}else { 
  $title =  ""; //$titleに空文字が格納される 
}


$is_article = isset($_POST["article"]);  //$_POST["article"]が未定義もしくはnullの場合、$is_articleにfalseが格納される・違う場合はtrueが格納される
if($is_article === true) {  //$is_articleがtrueだったらifの処理、falseだったらelseの処理
  $article = trim($_POST["article"]); //$_POST["article"]が両端のスペースを消して$articleに格納される
}else {
  $article = "";  //$articleに空文字が格納される 
}

//$error_messageに空の配列を代入する＝初期化
// $error_message = [];

$is_sent = isset($_POST["send_submit"]);  //$_POST["send_submit"]が未定義もしくはnullの場合、・違う場合はtrueが格納される
if($is_sent === true) { //$is_sentがtrueだったら、toukou.txtを開いて、$title,$article,$idをファイルの書き込む


  // if(empty($_POST["title"]) ) { //$_POST["title"]が空だったら、$error_messageの処理
  //     $error_message1 [] = "※タイトルを入力してください"; 
  // } elseif (30 < mb_strlen($_POST["title"]) ) { //$_POST["title"]が30文字より多かったとき、$error_messageの処理
  //     $error_message2 [] = "※タイトルは30字以下で入力してください";
  // }  

  // if(empty($_POST["article"]) ) { //$_POST["article"]が空だったら、$error_messageの処理
  //     $error_message3 [] = "※記事を入力してください";
  // }   
  
  // if(empty($error_message) ) {  //$error_messageが空でなかったら、”toukou.txt”を開いて、ID取得、ファイルに書き込み処理をする
    
      $fp = fopen("toukou.txt", 'r'); //IDを読み込むために、"toukou.txt"を開く（ファイルポインタ：読み込みモード）

      $id_list = array();  //まず、$id_listを配列として宣言する

      //"toukou.txt"に書かれている全てのIDを取得
      if($fp) {  //"toukou.txt"を開いたら、whileの処理
        while($data = fgets($fp) ) {  //fgetsで"toukou.txt"に書かれている内容を一行ずつ$dataに格納する
          $toukou_data = preg_split("/[\t]/", $data); //preg_splitで、$dataをタブで区切って文字列として取得、$toukou_dataに格納
          

          $id = $toukou_data[2]; //$idに各投稿のIDを格納

          array_push($id_list, $id); //array_pushで、$id_listに$idの内容を末尾に追加するよう格納する
          
        }
        fclose($fp); //"toukou.txt"ファイルを閉じる
      }  

      //上記で取得したIDの一番大きいい値を取得する
      $i = 1;  //添字変数を宣言、1を$iに格納する
      $max = $id_list[0];  //$id_list 0番目を$max(最大値)に格納する
      $id_count = count($id_list);  //$id_listの要素数を格納した$id_countを宣言する
     
      //最大値を求めるために配列id_listをループする
      while($id_count > $i) {  //whileでループしている中での条件分岐：$id_countが$iより大きかったらifの処理、そうでなければ何もしない
        if($id_list[$i] > $max) {  //$id_list[$i]と$maxを比較し、$id_list[$i]が大きかったら
          $max = $id_list[$i];  //$max(最大値)に$id_list[$i]を格納する
        } 
        $i = $i + 1;  //$iは$iに+1ずつしていく
      }
      $id = $max + 1;  //$idは上記で取得した$max(最大値)に+1した値を格納する
      // var_dump($id);
    

      $fp = fopen("toukou.txt", 'a');  //"toukou.txt"を開く（ファイルポインタ：追記モード）
      fwrite($fp, $title . "\t" . $article . "\t" . $id ."\n"); //"toukou.txt"ファイルに$title,$article,$idを書き込む
      fclose($fp); //"toukou.txt"を閉じる
  // }  
}


//  ＜表示機能  作成 >

//変数の初期化
// $data = null;
// $toukou_data = null;
$toukou = array();  //$toukouを配列として宣言
$toukou_array = array();   //$toukou_arrayを配列として宣言


$fp = fopen("toukou.txt", "r"); //"toukou.txt"を読み込みモードで開く

if($fp) {  //"toukou.txt"を開いたら、
  while($data = fgets($fp) ) {  //fgetsで"toukou.txt"に書かれている内容を一行ずつ$dataに格納する
    $toukou_data = preg_split("/[\t]/", $data); //$dataをタブで区切り文字列として取得、$toukou_dataに格納
    $toukou = array(    //2次元配列として、$toukouに$toukou_dataの配列を格納する
      "title"   => $toukou_data[0],  //投稿されたタイトルを$toukou_data 0番目
      "article" => $toukou_data[1],  //投稿された記事$toukou_data 1番目
    );

    array_unshift($toukou_array, $toukou); //array_unshiftで、$toukou_arrayに$toukouの内容を先頭に追加していく
    
  }  
  fclose($fp); //"toukou.txt"ファイルを閉じる
}  
?>

<!--以下、HTMLの書き込み-->

<!DOCTYPE html>
<html lang="ja">
<head>  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scal=1.0"> <!--表示領域設定：端末画面の幅、初期ズーム倍率-->

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

  <form action= "index.php" method= "POST">  <!--ファイル、methodの指定-->
    <p>タイトル： <input type= "text" name= "title"></p><br>  <!--タイトル入力部分の作成-->
    <p>記事： <textarea name= "article" cols= "30" rows= "10"></textarea></p><br>  <!--記事入力部分の作成-->
    <input type= "submit" name= "send_submit" value= "投稿">  <!--投稿ボタンの作成-->
  </form>
  <hr>
    <?php
    $i = 0;  //添字変数$iを宣言、0を$iに格納
    $toukou_count = count($toukou_array);  //$toukou_arrayの要素数を格納した$toukou_countを宣言
    
    while( $i < $toukou_count) {  //$toukou_count数の分だけループさせる
      $toukou = $toukou_array[$i];  //$toukouに$toukou_array[$i]を格納  [$i]はループ数を表す
    ?>
    <p><?php echo $toukou["title"]; ?></p>  <!-- $toukou["title"]を表示 -->
    <p><?php echo $toukou["article"]; ?></p>  <!-- $toukou["article"]を表示 -->
    <a href="http://localhost/comment">記事全文・コメントを読む</a>  <!--コメントページへのリンク作成 -->
    <hr>
    <?php 
    $i = $i + 1;  //$iは$iに１プラスする
    }
    ?>
</body>
</html>
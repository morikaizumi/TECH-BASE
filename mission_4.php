<html>
  <head>
    <title>mission4</title>
    <meta http-equiv="content-type" charset="utf-8">
  </head>

  <body>

    
    <?php
      //データベース接続
      $dsn = 'データベース名';
      $user = 'ユーザ名';
      $password = 'パスワード';
      $pdo = new PDO($dsn,$user,$password);

      
    ?>
  
    <?php
      if(isset($_POST["send"])&&(empty($_POST["hid_num"]))){

        if(isset($_POST["name"]) && ($_POST["name"] != "")){
          $name = $_POST['name'];
          $a +=1;
        }else{
          echo "名前を入力してください。";
        }

        if(isset($_POST["comment"]) && ($_POST["comment"] != "")){
          $comment = $_POST["comment"];
          $a +=1;
        }else{
          echo "コメントを入力してください。";
        }

        if(isset($_POST["password"]) && ($_POST["password"] != "")){
          $password = $_POST["password"];
          $a += 1;
        }else{
          echo "パスワードを入力してください。";
        }

        $date = date("Y/m/d H:i:s");

        //データ入力(3-5)
        if($a == 3){
          $sql = $pdo ->prepare("INSERT INTO keiziban(name,comment,date,password)VALUES(:name,:comment,:date,:password)");
          $sql -> bindParam(':name',$name,PDO::PARAM_STR);
          $sql -> bindParam(':comment',$comment,PDO::PARAM_STR);
          $sql -> bindParam(':date',$date,PDO::PARAM_STR);
          $sql -> bindParam(':password',$password,PDO::PARAM_STR);
          $sql -> execute();
          $a = 0; 
        }
       
      }elseif(isset($_POST["send"])&&(!empty($_POST["hid_num"]))){
        $ei=$_POST["hid_num"];
        $nm=$_POST["name"];
        $cm=$_POST["comment"];
        $ps=$_POST["password"];
        $dt=date("Y/m/d H:i:s");
        $sql="UPDATE keiziban set name='$nm', comment='$cm', password='$ps', date='$dt' WHERE id='$ei'";
        $result=$pdo->query($sql);
      }

       //削除機能実装
       if(isset($_POST["delete"])){
         $i = $_POST["del_num"];
         $p = $_POST["del_pass"];
         $sql="SELECT * FROM keiziban WHERE id=$i AND password=\"$p\";";
         $results = $pdo ->query($sql);
         foreach($results as $row){
           $delpass = $row["password"];
	 }
         if($delpass==""){
           echo "【削除エラー】パスワードが間違っています。";
         }else{
           $delid=$_POST["del_num"];
           $sql="DELETE FROM keiziban WHERE id=$delid";
           $result = $pdo->query($sql);
         }
       }

       //編集機能実装
       if(isset($_POST["edit"])){
         $i2=$_POST["edit_num"];
         $p2=$_POST["edit_pass"];
       
         $sql="SELECT * FROM keiziban WHERE id=$i2 AND password=\"$p2\";";
         $results = $pdo ->query($sql);
         foreach($results as $row){
           $editpass = $row["password"];
           $editname = $row["name"];
           $editcomment = $row["comment"];
         }
         if($editpass==""){
           echo "【編集エラー】パスワードが間違っています。";
         }else{
           $n = $editname;
           $c = $editcomment;
         }
       }
    ?>

    <form method="post" action="">
    <p>名前:<input type="text" name="name" value="<?php echo $n; ?>"></p>
    <p>コメント:<input type="text" name="comment" value="<?php echo $c; ?>"></p>
    <p>パスワード:<input type="text" name="password" value="">
    <input type="submit" name="send" value="送信"></p>
    <p>削除対象番号:<input type="text" name="del_num"></p>
    <p>パスワード:<input type="text" name="del_pass" value="">
    <input type="submit" name="delete" value="削除"></p>
    <p>編集対象番号:<input type="text" name="edit_num" value=""></p>
    <p>パスワード:<input type="text" name="edit_pass" value="">
    <input type="submit" name="edit" value="編集"></p>
    <p><input type="hidden" name="hid_num" value="<?php echo $i2; ?>"></p>
    </form>

    <?php
      //入力したデータをselectで表示(3-6)
      $sql = 'SELECT * FROM keiziban ORDER BY id ASC';
      $results = $pdo ->query($sql);
      foreach($results as $row){
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'<br>';
      }
    ?>
  </body>
</html>
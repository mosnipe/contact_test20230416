<?php
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $inquiry = $_POST['inquiry']; //提出された値の定義＋取り出し
    $name    = $_POST['name'];
    $email   = $_POST['email'];

    header('Location: thanks.html');//フォーム提出後のサンクスページへの遷移記述
    exit;
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>お問い合わせフォーム</title>
</head>
<body>
    <h1>お問い合わせフォーム</h1>
    <form action="" method="post">
        <p>お問い合わせ内容 ※必須</p>
        <p><textarea name="inquiry" rows="10" cols="100"></textarea></p>
        <p>お名前 ※必須</p>
        <p><input type="text" name="name" vale=""></p>
        <p>ご連絡用Email ※必須</p>
        <p><input type="email" name="email" vale=""></p>
        <p><input type="submit" value="送信"></p>
    </form>
</body>
</html>

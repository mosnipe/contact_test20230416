<?php
session_start();

//CSRF対策

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    //CSRF対策
    if (!isset($_POST['token'] || $_POST['token'] !== getToken())) {
        exit('処理を正常に完了できませんでした');
    }
    
    // バリデーション
    $inquiry = $_POST['inquiry']; //提出された値の定義＋取り出し
    $name    = $_POST['name'];
    $email   = $_POST['email'];

    $error = array();

    if (empty($inquiry)) {
        $error['inquiry'] = '必ずご記入ください';
    }

    if (empty($name)) {
        $error['name'] = '必ずご記入ください';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = 'メールアドレスの形式が正しくありません';
    }

    if (empty($error)) {
        $to = 'schoo.iwata@gmail.com';
        $subject = 'お問い合わせ: ' . $name . 'さんより';
        $message = "email: \n" . $email . "\nお問い合わせ文:\n"
        mb_language('Japanese');
        mb_internal_encoding('UTF-8');
        $flg = mb_send_mail($to, $subject, $message);

        if($flg) {
            header('Location: thanks.html');//フォーム提出後のサンクスページへの遷移記述
            exit;
        }

        exit('お問い合わせの受付に失敗しました');

    }


}

/*
 * CSRF対策用の IDを作る
 */
function getToken() {
    return hash('sha256', session_id());
}

/*
 * HTMLの特殊文字をエスケープします
 */
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
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
        <input type="hidden" name="token" value="<?php echo getToken(); ?>">
        <p>お問い合わせ内容 ※必須</p>
        <?php 
            if (isset($error['inquiry'])) echo h($error['inquiry']);
        ?>
        <p><textarea name="inquiry" rows="10" cols="100"></textarea></p>
        <p>お名前 ※必須</p>
        <?php 
            if (isset($error['name'])) echo h($error['name']);
        ?>
        <p><input type="text" name="name" vale=""></p>
        <p>ご連絡用Email ※必須</p>
        <?php 
            if (isset($error['email'])) echo h($error['email']);
        ?>
        <p><input type="email" name="email" vale=""></p>
        <p><input type="submit" value="送信"></p>
    </form>
</body>
</html>

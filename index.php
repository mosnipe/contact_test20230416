<?php
define('MAIL_INQUIRY', 'sniya.pagone824055@outlook.jp');
session_start();

//CSRF対策

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    // CSRF対策
    if (!isset($_POST['token']) || $_POST['token'] !== getToken()) {
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
        // 注意: FILTER_VALIDATE_EMAIL は一部の有効なメールも弾きます。
        // 案件次第では正規表現の使用も必要です。
        $error['email'] = 'メールアドレスの形式が正しくありません';
    }

        // バリデーションエラーが無い場合お問い合わせを受付
    if (empty($error)) {
        $to      = MAIL_INQUIRY;
        $subject = "お問い合わせ: " . $name . '様より';
        $message = "email:\n" . $email . "\n問合せ本文:\n" . $inquiry;
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
 * HTMLの特殊文字をエスケープします
 */
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/*
 * CSRF対策用の IDを作る
 */
function getToken() {
    return hash('sha256', session_id());
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>お問い合わせフォーム</title>
</head>
<body>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせフォーム</title>
    <link rel="stylesheet" href="style.css"> <!-- style.cssを読み込む -->
</head>

<body>
    <div class="form-container">
        <h1>お問い合わせフォーム</h1>
        <form action="" method="post">
            <input type="hidden" name="token" value="<?php echo getToken(); ?>">

            <p>お問い合わせ内容 ※必須</p>
            <?php if (isset($error['inquiry'])) echo h($error['inquiry']); ?>
            <textarea class="form-input" name="inquiry" required rows="10" cols="100" maxlength="1000" minlength="10"
                placeholder="できるだけ詳しく入力して下さい (10文字以上 1000文字以内)"><?php if (isset($inquiry)) echo h($inquiry); ?></textarea>

            <p>お名前 ※必須</p>
            <?php if (isset($error['name'])) echo h($error['name']); ?>
            <input class="form-input" type="text" name="name" required value="<?php if (isset($name)) echo h($name); ?>"
                placeholder="お名前">

            <p>ご連絡用Email ※必須</p>
            <?php if (isset($error['email'])) echo h($error['email']); ?>
            <input class="form-input" type="email" name="email" required value="<?php if (isset($email)) echo h($email); ?>"
                placeholder="email@example.com">

            <input class="form-button" type="submit" value="送信">
        </form>
    </div>
</body>

</html>

</body>
</html>

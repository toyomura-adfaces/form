<?php

// 変数の初期化
$page_flag = 0;
$error = array();

if (!empty($_POST['btn_confirm'])) {
    $page_flag = 1;

    // セッションの書き込み
    session_start();
    $_SESSION['page'] = true;
} elseif (!empty($_POST['btn_submit'])) {

    session_start();
    if (!empty($_SESSION['page']) && $_SESSION['page'] === true) {

        // セッションの削除
        unset($_SESSION['page']);

        $page_flag = 2;

        // 変数とタイムゾーンを初期化
        $header01 = null;
        $header02 = null;
        $body01 = null;
        $body02 = null;
        $auto_reply_subject = null;
        $auto_reply_text = null;
        $admin_reply_subject = null;
        $admin_reply_text = null;
        date_default_timezone_set('Asia/Tokyo');

        //日本語の使用宣言
        mb_language("ja");
        mb_internal_encoding("UTF-8");

        $contact = preg_replace("/\{(.*)\}/", '', $_POST['contact']);

        ////////////////////////////////////////////////////////////申込者用////////////////////////////////////////////////////////////


        // 送信元
        $from01 = "株式会社アドフェイス <noreply@adfaces.co.jp>";

        // 送信元メールアドレス
        $from_mail01 = "toyomura@adfaces.co.jp";

        // 送信者名
        $from_name01 = "株式会社アドフェイス";

        $param01 = "-ftoyomura@adfaces.co.jp";
        $header01 = "MIME-Version: 1.0\n";
        $header01 .= "Content-Type: text/plain; charset=ISO-2022-JP\r\n";
        //添付ファイルが有る場合
        // $header01 = "Content-Type: multipart/mixed;boundary=\"__BOUNDARY__\"\n";
        // $header01 = "Content-Type: text/plain \r\n";
        $header01 .= "Return-Path: " . $from_mail01 . " \r\n";
        $header01 .= "From: " . $from01 . " \r\n";
        $header01 .= "Sender: " . $from01 . " \r\n";
        $header01 .= "Reply-To: " . $from_mail01 . " \r\n";
        $header01 .= "Organization: " . $from_name01 . " \r\n";
        $header01 .= "X-Sender: " . $from_mail01 . " \r\n";
        $header01 .= "X-Priority: 3 \r\n";
        // 件名を設定
        $auto_reply_subject = '【申込者用フォームテスト】';

        // 本文を設定
        $auto_reply_text .= "これは申込者用テスト送信のサンプルです" . "\n\n\n";

        $auto_reply_text .= "お名前[漢字]：" . h($_POST['name01']) . "\n";
        $auto_reply_text .= "お名前[かな]：" . h($_POST['name02']) . "\n";
        $auto_reply_text .= "メールアドレス：" . h($_POST['email']) . "\n";
        $auto_reply_text .= "電話番号：" . h($_POST['tel']) . "\n";

        if (h($_POST['gender']) === "男性") {
            $auto_reply_text .= "性別：男性\n";
        } elseif (h($_POST['gender']) === "女性") {
            $auto_reply_text .= "性別：女性\n";
        } else {
            $auto_reply_text .= "その他\n";
        }

        $auto_reply_text .= "ご住所：" . "〒" . h($_POST['zip']) . "\n" . h($_POST['address1'])  . h($_POST['address2']) . h($_POST['address3']) . "\n";

        if (($_POST['how'])) {
            $auto_reply_text .= "【セレクトテスト】" . "\n" . h($_POST['how']) . "\n\n";
        }

        if ($_POST['function']) {
            $auto_reply_text .= "【チェックテスト】\n";
        }
        foreach ($_POST['function'] as $function_val) {
            $auto_reply_text .=  h($function_val) . ",";
        }

        $auto_reply_text .= "\n";

        if (!empty($_POST['contact'])) {
            $auto_reply_text .= "テキストテスト：" . h($contact)  . "\n\n\n";
        }

        // テキストメッセージをセット
        // $body = "--__BOUNDARY__\n";
        // $body .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n\n";
        $body01 = $auto_reply_text . "\n";
        // $body .= "--__BOUNDARY__\n";

        //メール本文エンコーディング
        // $body = mb_convert_encoding($body, 'ISO-2022-JP-MS');

        // 自動返信メール送信
        mb_send_mail($_POST['email'], $auto_reply_subject, $body01, $header01, $param01);


        ////////////////////////////////////////////////////////////管理者用////////////////////////////////////////////////////////////

        // 送信元
        $from02 = "株式会社アドフェイス <noreply@adfaces.co.jp>";

        // 送信元メールアドレス
        $from_mail02 = "<toyomura@adfaces.co.jp>";

        // 送信者名
        $from_name02 = "株式会社アドフェイス";

        $param02 = "-ftoyomura@adfaces.co.jp";
        $header02 = "MIME-Version: 1.0\n";
        $header02 .= "Content-Type: text/plain; charset=ISO-2022-JP\r\n";
        //添付ファイルが有る場合
        // $header02 = "Content-Type: multipart/mixed;boundary=\"__BOUNDARY__\"\n";
        $header02 .= "Return-Path: " . $from_mail02 . " \r\n";
        $header02 .= "From: " . $from02 . " \r\n";
        $header02 .= "Sender: " . $from02 . " \r\n";
        $header02 .= "Reply-To: " . $from_mail02 . " \r\n";
        $header02 .= "Organization: " . $from_name02 . " \r\n";
        $header02 .= "X-Sender: " . $from_mail02 . " \r\n";
        $header02 .= "X-Priority: 3 \r\n";

        // 運営側へ送るメールの件名
        $admin_reply_subject = "【管理者用フォームテスト】";

        // 本文を設定
        $admin_reply_text = "これは管理者用テスト送信のサンプルです。\n\n\n";
        $admin_reply_text .= "お名前[漢字]：" . h($_POST['name01']) . "\n";
        $admin_reply_text .= "お名前[かな]：" . h($_POST['name02']) . "\n";
        $admin_reply_text .= "メールアドレス：" . h($_POST['email']) . "\n";
        $admin_reply_text .= "電話番号：" . h($_POST['tel']) . "\n";

        if (h($_POST['gender']) === "男性") {
            $admin_reply_text .= "性別：男性\n";
        } elseif (h($_POST['gender']) === "女性") {
            $admin_reply_text .= "性別：女性\n";
        } else {
            $admin_reply_text .= "その他\n";
        }

        $admin_reply_text .= "ご住所：" . "〒" . h($_POST['zip']) . "\n" . h($_POST['address1'])  . h($_POST['address2']) . h($_POST['address3']) . "\n";

        if (($_POST['how'])) {
            $admin_reply_text .= "【セレクトテスト】" . "\n" . h($_POST['how']) . "\n\n";
        }

        if ($_POST['function']) {
            $admin_reply_text .= "【チェックテスト】\n";
        }
        foreach ($_POST['function'] as $function_val) {
            $admin_reply_text .=  h($function_val) . ",";
        }

        $admin_reply_text .= "\n";

        if (!empty($_POST['contact'])) {
            $admin_reply_text .= "テキストテスト：" . h($contact) . "\n\n";
        }

        // テキストメッセージをセット
        // $body = "--__BOUNDARY__\n";
        // $body .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n\n";
        $body02 = $admin_reply_text . "\n";
        // $body .= "--__BOUNDARY__\n";

        //メール本文エンコーディング
        // $body = mb_convert_encoding($body, 'ISO-2022-JP-MS');

        // 管理者へメール送信
        mb_send_mail('toyomura@adfaces.co.jp', $admin_reply_subject, $body02, $header02, $param02);

        $tel = $_POST['tel'];
        $list = array(
            $_POST['name01'],
            $_POST['name02'],
            $_POST['email'],
            '="' . $tel . '"',
            $_POST['gender'],
            $_POST['zip'],
            $_POST['address1'],
            $_POST['address2'],
            $_POST['address3'],
            $_POST['how'],
            implode('`, `', (array) $_POST['function']),
            $_POST['contact'],
            $_POST['agreement'],
            date("Y-m-d H:i:s"),
        );
        mb_convert_variables('SJIS', 'UTF-8', $list); //文字コードをUTF-8からShiftJISに変更
        $csv = fopen('file.csv', 'a'); //csvファイルと書き込みモードを指定
        fputcsv($csv, $list); //変換した配列をcsvファイルに書き込み実行
        fclose($csv); //csvファイルを閉じる

    }
} else {
    $page_flag = 0;
}



function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, "UTF-8");
}

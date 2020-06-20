<?php
$token = 'TOKEN';
$admin = 'ADMIN';
ob_start();
define('API_KEY', "$token");
function bot($method, $datas = [])
{
    $url = "https://api.telegram.org/bot" . API_KEY . "/" . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);
    if (curl_error($ch)) {
        var_dump(curl_error($ch));
    } else {
        return json_decode($res);
    }
}
function SendMessage($chatid, $text, $parsmde, $keyboard)
{
    bot('sendMessage', [
        'chat_id' => $chatid,
        'text' => $text,
        'parse_mode' => $parsmde,
        'reply_markup' => $keyboard,
    ]);
}
function GetChatMember($chatid, $userid)
{
    $collect = json_decode(file_get_contents('https://api.telegram.org/bot' . API_KEY . "/getChatMember?chat_id=$chatid&user_id=" . $userid));
    return $collect->result->status;
}
$update = json_decode(file_get_contents('php://input'));
$chat_id = $update->message->chat->id;
$from_id = $update->message->from->id;
$first = $update->message->from->first_name;
$last = $update->message->from->last_name;
$text = $update->message->text;
$channel = json_encode(['inline_keyboard' => [[['text' => "Join Channel", 'url' => 'https://t.me/ic_mods']]], 'resize_keyboard' => true]);
if ($text == "/Start" or $text == "/start") {
    if ($chat_id == $admin) {
        SendMessage($chat_id, "Hi father", "html", null);
    } else {
        SendMessage($chat_id, "Hey $first $last\nWelcome to WARP+ Charger.\nSend your cloudflare id to me to get 2GB data per minute.", "html", null);
    }
} elseif ($text == $text && GetChatMember("@IC_MODS", $chat_id) != "member" && GetChatMember("@IC_MODS", $chat_id) != "administrator" && $chat_id != $admin) {
    SendMessage($chat_id, "We found out that you are not a member of our channel.To continue, you must first become a member of the channel and repeat the /start command", "html", $channel);
} elseif ($text == $text) {
    file_get_contents("https://imseyyed.ir/API/CF-API.php?id=$text");
    file_get_contents("https://imseyyed.ir/API/CF-API.php?id=$text");
    SendMessage($chat_id, "Please wait ...\nCharging your account", "html", null);
    sleep(5);
    SendMessage($chat_id, "Done. 2GB added to you account.\nGet more data at : " . date('H:i', strtotime('+30 minutes')) . "UTC", "html", null);
}
unlink('error_log');

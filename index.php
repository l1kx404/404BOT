<?php
$token = "8847470052:AAFJaKJlK3jTLigPL3yIBUbWldNMqm5gbbU";
$update = json_decode(file_get_contents('php://input'), true);
$uid = $update['message']['from']['id']?? $update['callback_query']['from']['id']?? 0;
$chat_id = $update['message']['chat']['id']?? $update['callback_query']['message']['chat']['id']?? 0;
$text = $update['message']['text']?? '';
$data = $update['callback_query']['data']?? '';

if($uid) @file_get_contents('https://app.adsgalaxy.online/api/bot/integration/599/8NSvUN5c4pn1ngiWRGevFXe3QiJ437ul68hR9eWsp_0?user_id='.$uid);

function s($token,$c,$t,$k=null){
  $p=['chat_id'=>$c,'text'=>$t,'parse_mode'=>'HTML'];
  if($k) $p['reply_markup']=json_encode($k);
  @file_get_contents("https://api.telegram.org/bot$token/sendMessage?".http_build_query($p));
}
function back(){ return [['text'=>'🔙 Kembali','callback_data'=>'kembali']]; }
$main=['inline_keyboard'=>[
  [['text'=>'🎮 Top Up Game','callback_data'=>'topup'],['text'=>'📱 Pulsa & Data','callback_data'=>'pulsa']],
  [['text'=>'💰 SALDO','callback_data'=>'saldo'],['text'=>'👥 Referral','callback_data'=>'ref']],
  [['text'=>'🎁 Bonus Harian','callback_data'=>'bonus'],['text'=>'🏆 Leaderboard','callback_data'=>'lead']],
  [['text'=>'🌐 Roblox','callback_data'=>'roblox'],['text'=>'💬 CS','callback_data'=>'cs']]
]];
if($text=='/start'||$data=='kembali') s($token,$chat_id,"🎭 <b>PROJECT TEAM 404</b> 🎭\nPilih layanan:",$main);
if($data=='topup') s($token,$chat_id,"🎮 <b>Top Up Game</b>\nML / FF / PUBG / Roblox\n\nPilih nominal:\n• ML/FF/PUBG: 86 Diamond 20k\n• Roblox: 80 Robux 20k / 400 Robux 80k",['inline_keyboard'=>[[back()[0]]]]);
if($data=='roblox') s($token,$chat_id,"🌐 <b>Roblox</b>\n• 80 Robux - 20k\n• 400 Robux - 80k\n• 800 Robux - 150k\n\nKetik /bayar untuk lanjut",['inline_keyboard'=>[[back()[0]]]]);
if($data=='pulsa') s($token,$chat_id,"📱 <b>Pulsa & Data</b>\n10k / 25k / 50k / 100k",['inline_keyboard'=>[[back()[0]]]]);
if($data=='saldo') s($token,$chat_id,"💰 <b>SALDO</b>\nRp0\nIsi via QRIS / DANA / GoPay / SeaBank",['inline_keyboard'=>[[back()[0]]]]);
if($data=='ref') s($token,$chat_id,"👥 Referral:\nhttps://t.me/THEREALTEAM404BOT?start=$uid\nDapat 5k/teman",['inline_keyboard'=>[[back()[0]]]]);
if($data=='bonus') s($token,$chat_id,"🎁 Bonus harian 2k diklaim!",['inline_keyboard'=>[[back()[0]]]]);
if($data=='lead') s($token,$chat_id,"🏆 <b>Leaderboard</b>\nBelum ada data",['inline_keyboard'=>[[back()[0]]]]);
if($data=='cs') s($token,$chat_id,"💬 <b>CS PROJECT TEAM 404</b>\n@Komandan404",['inline_keyboard'=>[[back()[0]]]]);

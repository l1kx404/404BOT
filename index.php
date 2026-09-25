<?php
$TOKEN = "TOKEN_BARU_LU";
$ADMIN = 1404465764;
$DOMAIN = "https://404bot-production.up.railway.app";
define('API',"https://api.telegram.org/bot$TOKEN/");
$dataDir=__DIR__."/data"; @mkdir($dataDir);
function db($id){global $dataDir;$f="$dataDir/$id.json";return file_exists($f)?json_decode(file_get_contents($f),true):['saldo'=>0,'last'=>0,'pending'=>0];}
function save($id,$d){global $dataDir;file_put_contents("$dataDir/$id.json",json_encode($d),LOCK_EX);}
function api($m,$p=[]){$c=curl_init(API.$m);curl_setopt_array($c,[CURLOPT_POST=>1,CURLOPT_RETURNTRANSFER=>1,CURLOPT_POSTFIELDS=>$p,CURLOPT_TIMEOUT=>5]);$r=curl_exec($c);curl_close($c);return $r;}
function send($c,$t,$k=null){$p=['chat_id'=>$c,'text'=>$t,'parse_mode'=>'HTML'];if($k)$p['reply_markup']=json_encode($k);api('sendMessage',$p);}
function answer($id){api('answerCallbackQuery',['callback_query_id'=>$id]);}
function menuUtama(){return['inline_keyboard'=>[[['text'=>'🎮 Top Up Game','callback_data'=>'topup_game'],['text'=>'📱 Pulsa & Data','callback_data'=>'pulsa']],[['text'=>'💰 SALDO','callback_data'=>'saldo'],['text'=>'🎁 Bonus Harian','callback_data'=>'bonus']],[['text'=>'🔙 Kembali','callback_data'=>'back']]]];}

$u=json_decode(file_get_contents("php://input"),true);
// === CALLBACK ===
if(isset($u['callback_query'])){$q=$u['callback_query'];$cid=$q['message']['chat']['id'];$d=$q['data'];$mid=$q['message']['message_id'];answer($q['id']);$db=db($cid);
if($d=='back'||$d=='saldo'&&false){}
if($d=='back'){api('editMessageText',['chat_id'=>$cid,'message_id'=>$mid,'text'=>"🎭 <b>PROJECT TEAM 404</b>",'parse_mode'=>'HTML','reply_markup'=>json_encode(menuUtama())]);}
elseif($d=='saldo'){api('editMessageText',['chat_id'=>$cid,'message_id'=>$mid,'text'=>"💰 <b>Top Up Saldo</b>\nPilih nominal:",'parse_mode'=>'HTML','reply_markup'=>json_encode(['inline_keyboard'=>[[['text'=>'10k','callback_data'=>'dep_10000'],['text'=>'20k','callback_data'=>'dep_20000']],[['text'=>'50k','callback_data'=>'dep_50000'],['text'=>'100k','callback_data'=>'dep_100000']],[['text'=>'🔙','callback_data'=>'back']]]])]);}
elseif(strpos($d,'dep_')===0){$nom=str_replace('dep_','',$d);$db['pending']=$nom;save($cid,$db);
$GLOBALS['DOMAIN']; send($cid,"Scan QRIS ini Rp".number_format($nom).":\n".$GLOBALS['DOMAIN']."/qris.jpg\n\nAbis bayar, <b>kirim foto bukti</b> ke sini.");}
elseif($d=='bonus'){$t=time();if($t-$db['last']<86400){send($cid,"⏳ Besok lagi");}else{$db['saldo']+=2000;$db['last']=$t;save($cid,$db);send($cid,"🎁 2k masuk! Saldo: Rp".number_format($db['saldo']));}}
elseif($d=='topup_game'){send($cid,"Ketik: <code>ML 86diamond ID</code>");}
elseif($d=='pulsa'){send($cid,"Ketik: <code>PULSA 25000 0812xxx</code>");}
exit;}

// === MESSAGE ===
if(isset($u['message'])){$m=$u['message'];$cid=$m['chat']['id'];$txt=$m['text']??'';
// admin ACC
if($cid==$ADMIN && strpos($txt,'/acc')===0){$p=explode(' ',$txt);$uid=$p[1];$db=db($uid);$db['saldo']+=$db['pending'];$db['pending']=0;save($uid,$db);send($uid,"✅ Saldo Rp".number_format($db['saldo'])." masuk!");send($ADMIN,"Done ACC $uid");exit;}
if(strpos($txt,'/start')===0){send($cid,"🎭 <b>PROJECT TEAM 404</b>\nPilih:",menuUtama());exit;}
// terima bukti foto
if(isset($m['photo'])){$db=db($cid);$nom=$db['pending']?:'unknown';$fid=end($m['photo'])['file_id'];
api('forwardMessage',['chat_id'=>$ADMIN,'from_chat_id'=>$cid,'message_id'=>$m['message_id']]);
send($ADMIN,"🧾 Bukti dari <code>$cid</code>\nNominal: Rp$nom\nACC: <code>/acc $cid</code>");
send($cid,"⏳ Bukti diterima, nunggu admin cek 1-5 menit.");exit;}
if(preg_match('/^(ML|PULSA|RBX)\s/i',$txt)){send($cid,"✅ Order <code>$txt</code> diterima (demo)");}
}

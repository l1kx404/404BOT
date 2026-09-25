const { Telegraf } = require('telegraf');
const http = require('http');

const bot = new Telegraf(process.env.BOT_TOKEN);
const INTEGRATION_URL = process.env.INTEGRATION_URL;

bot.start(async (ctx) => {
  try {
    await fetch(`${INTEGRATION_URL}?user_id=${ctx.from.id}`);
  } catch(e){}
  ctx.reply(`Halo ${ctx.from.first_name}! Bot aktif.`);
});

bot.launch();
http.createServer((req,res)=>res.end('ok')).listen(process.env.PORT || 3000);
console.log('Bot jalan...');

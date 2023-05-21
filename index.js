// express
// wa web
// wa qrcode

const qrcode = require('qrcode-terminal');

const { MessageMedia } = require('whatsapp-web.js');

const { Client } = require('whatsapp-web.js');
const client = new Client();


const express = require('express');


const app = express();

const port = 3000; 


client.on('qr', qr => {
    qrcode.generate(qr, {small: true});
});


client.on('authenticated', () => {
  console.log('Authenticated!');
});

// client.on('message', message => {
//   if(message.body === '!ping') {
//     client.sendMessage(message.from, 'pong');
//   }
// });

app.use(express.json());

const checkRegisteredNumber = async function(number) {
  const isRegistered = await client.isRegisteredUser(number);
  return isRegistered;
}


app.post('/api/send-message', async (req, res) => {

  // waktu yg dibutuhkan sampai terkirim 2 detik

  const { number, message } = req.body;
  
  console.log('Received number:', number);

  // console.log('Received numer list:', listNumber);

  console.log('Received message:', message);

  chatId = `${number}@c.us`;

  console.log(chatId);

  // sent file
  // const media = MessageMedia.fromFilePath('profile.png');
  // client.sendMessage(chatId, media);

  // sent multiple contact
  // listNumber.forEach(item => {
  //   client.sendMessage(`${item}@c.us`, message)
  // });
  
  // send one kontact
  client.sendMessage(chatId, message)

 //  check number
  // const isRegisteredNumber = await checkRegisteredNumber(number);

  // if (isRegisteredNumber) {
  //   console.log("Terdaftar");
  // } else {
  //   console.log("Tidak terdaftar");
  // }

  
  // Return a response
  res.json({ success: true, message: 'Message sent successfully' });
});

app.listen(port, () => {
  console.log(`Server is running on http://localhost:${port}`);
});


client.initialize();
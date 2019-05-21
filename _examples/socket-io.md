```js
// server.js
var Redis = require("ioredis"),
  redis = new Redis(),
  io = require("socket.io")(6001, {
    origins: "ezyparts.local:*" // защита на уровен сервера
  }),
  request = require("request");

io.use(function(socket, next) {
  // защита на уровне соеденения
  request.get(
    {
      url: "http://ezyparts.local/ws/auth",
      headers: { cookie: socket.request.headers.cookie },
      json: true
    },
    function(error, response, json) {
      console.log(json);
      return json.auth ? next() : next(new Error("Auth error"));
    }
  );
});

io.on("connection", function(socket) {
  // защита подписки на канал
  socket.on("subscribe", function(chanel) {
    console.log(chanel);
    request.get(
      {
        url: "http://ezyparts.local/ws/auth/" + chanel,
        headers: { cookie: socket.request.headers.cookie },
        json: true
      },
      function(error, response, json) {
        console.log(json);
        if (json.can) {
          socket.join(chanel, function(error) {
            socket.send("join to chanel: " + chanel);
          });
          return;
        }
      }
    );
  });
});

redis.psubscribe("*", function(err, count) {});

redis.on("pmessage", function(pattern, chanel, message) {
  message = JSON.parse(message);
  io.emit(chanel + ":" + message.event, message.data);
  console.log(chanel, message);
});
```

```js
var socket = io(":6001"),
  chanel = "private-test-chanel:test";

socket.on("error", data => {
  console.warn("Error", data);
});

// просит подключиться
socket.on("connect", function() {
  socket.emit("subscribe", chanel);
});

// сообщение socket.send()
socket.on("message", function(message) {
  console.info(message);
});

// event from chanel
socket.on(chanel, data => {
  this.messages.push(data.data.message);
});
```

```js
// отправить текущему сокету сформировавшему запрос (туда откуда пришла)
socket.emit("eventClient", "this is a test");

// отправить всем пользователям, включая отправителя
io.sockets.emit("eventClient", "this is a test");

// отправить всем, кроме отправителя
socket.broadcast.emit("eventClient", "this is a test");

// отправить всем клиентам в комнате (канале) 'game', кроме отправителя
socket.broadcast.to("game").emit("eventClient", "nice game");

// отправить всем клиентам в комнате (канале) 'game', включая отправителя
io.sockets.in("game").emit("eventClient", "cool game");

// отправить конкретному сокету, по socketid
io.sockets.socket(socketid).emit("eventClient", "for your eyes only");
```

In laravel, redis

1. https://medium.com/@dennissmink/laravel-echo-server-how-to-24d5778ece8b
2.

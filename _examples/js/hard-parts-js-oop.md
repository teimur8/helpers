https://coursehunters.net/course/javascript-zhestkie-chasti-obektno-orientirovannogo-programmirovaniya
https://static.frontendmasters.com/resources/2018-10-03-javascript-hard-parts-oop/javascript-hard-parts-oop.pdf

## The Hard Parts on Object-Oriented Programming

- OOP - an enormously popular paradigm for structuring our complex code

### Варианты создания объекта

```js
const user1 = {
  name: "Phil",
  score: 4,
  increment: function() {
    user1.score++;
  }
};
// or
const user2 = {}; //create an empty object
user2.name = "Julia"; //assign properties to that object
user2.score = 5;
user2.increment = function() {
  user2.score++;
};
// or
const user3 = Object.create(null);
user3.name = "Eva";
user3.score = 9;
user3.increment = function() {
  user3.score++;
};
```

- this don't DRY

### Решение 1 DRY

```js
function userCreator(name, score) {
  const newUser = {};
  newUser.name = name;
  newUser.score = score;
  newUser.increment = function() {
    newUser.score++;
  };
  return newUser;
}
const user1 = userCreator("Phil", 4);
const user2 = userCreator("Julia", 5);
user1.increment();
```

- functions are just copies in each object, how create link?

### Using the prototype chain

```js
const functionStore = {
  increment: function() {
    this.score++;
  },
  login: function() {
    console.log("You're loggedin");
  }
};
const user1 = {
  name: "Phil",
  score: 4
};
user1.name; // name is a property of user1 object
user1.increment; // Error! increment is not!

const user1 = Object.create(functionStore); // create link
user1; // {}
user1.increment; // function....
```

### Решение 2

```js
function userCreator(name, score) {
  const newUser = Object.create(userFunctionStore);
  newUser.name = name;
  newUser.score = score;
  return newUser;
}
const userFunctionStore = {
  increment: function() {
    this.score++;
  },
  login: function() {
    console.log("You're loggedin");
  }
};
const user1 = userCreator("Phil", 4);
user1.increment();
/*
1.create function userCreator
2.create object userFunctionStore
    - add functions to propertys login,increment
3.invoke userCreator
    - set params to local memory: name = 'Phil', score = 4
    - create empty object
        - __proto__ create link to userFunctionStore 
        - add props name and score
    - return newUser object
4.invoke user1.increment()
    - js search increment function in object
    - search in __proto__

__proto__ is hidden property of object
*/
```

### Function and object combo

```js
function multiplyBy2(num) {
  return num * 2;
}
multiplyBy2.stored = 5;
multiplyBy2(3); // 6

multiplyBy2.stored; // 5
// prototype it's regular property function object combo
multiplyBy2.prototype;
```

### Решение 3 new keyword

```js
function UserCreator(name, score){
 this.name = name;
 this.score = score;
}
UserCreator.prototype.increment = function(){
 this.score++;
};
UserCreator.prototype.login = function(){
 console.log("login");
};
const user1 = new UserCreator(“Eva”, 9)
user1.increment()

/*
1.create function UserCreator. it's a function object compo with big empty propert 'prototype'
2.add to prototype functions increment and login
3.keyword 'new' doing same things how in ### Решение 2
4. __proto__ link with function prototype object
5.все это происходит при вызове new Class
/*
```

### Расширение функционала

- this ссылается на объект перед точкой object.run(), this = object

```js
UserCreator.prototype.increment = function() {
  // add some big function
  function add1() {
    this.score++;
  }
  // const add1 = function(){this.score++;}
  add1(); // don't work
};
// solution 1
UserCreator.prototype.increment = function() {
  let that = this; // add that variable
  function add1() {
    that.score++;
  }
  add1();
};
// solution 2
UserCreator.prototype.increment = function() {
  // add arrow function, which bind this lexically
  const add1 = () => {
    this.score++;
  };
  add1();
};
```

### Решение 4 class ‘syntactic sugar’

![image](https://raw.githubusercontent.com/teimur8/helpers/master/image.png

```js
class UserCreator {
  constructor(name, score) {
    this.name = name;
    this.score = score;
  }
  increment() {
    this.score++;
  }
  login() {
    console.log("login");
  }
}
const user1 = new UserCreator("Eva", 9);
user1.increment();
```

### proto link

```js
/*
- __proto__ - объект для поиска методов в цепочке
- prototype - объект функции для создание __proto__  при выззове через new
*/

const obj = {
  num: 3
};
obj.num; // 3
obj.hasOwnProperty("num"); // ? Where's this method?
Object.prototype; // {hasOwnProperty: FUNCTION}
```

```js
function userCreator(name, score) {
  const newUser = Object.create(functionStore);
  return newUser;
}
const functionStore = {
  increment: function() {
    this.score++;
  }
};
/*
1.Object.create we override the default __proto__ reference to Object.prototype and replace with functionStore
2.functionStore is an object so it has a __proto__ reference to Object.prototype - we just intercede in the chain
*/
```

```js
function multiplyBy2(num) {
  return num * 2;
}
multiplyBy2.toString(); //Where is this method?
Function.prototype; // {toString : FUNCTION, call : FUNCTION, bind : FUNCTION}
multiplyBy2.hasOwnProperty("score"); // Where's this function?
Function.prototype.__proto__; // Object.prototype {hasOwnProperty: FUNCTION}
```

### Prototype

```js
var o = {
  a: 2,
  m: function() {
    return this.a + 1;
  }
};

console.log(o.m()); // 3
// в этом случае при вызове 'o.m' this указывает на 'o'

var p = Object.create(o);
// 'p' - наследник 'o'
```

```js
var o = { a: 1 };
// o ---> Object.prototype ---> null

var a = ["yo", "whadup", "?"];
// a ---> Array.prototype ---> Object.prototype ---> null

function f() {
  return 2;
}
// f ---> Function.prototype ---> Object.prototype ---> null
```

ECMAScript 5

```js
var a = { a: 1 };
// a ---> Object.prototype ---> null

var b = Object.create(a);
// b ---> a ---> Object.prototype ---> null

var c = Object.create(b);
// c ---> b ---> a ---> Object.prototype ---> null

var d = Object.create(null);
// d ---> null

console.log(d.hasOwnProperty);
// undefined, т.к. 'd' не наследуется от Object.prototype
```

### Наследование

```js
// --------- Класс-Родитель ------------
// Конструктор родителя пишет свойства конкретного объекта
function Animal(name) {
  this.name = name;
  this.speed = 0;
}

// Методы хранятся в прототипе
Animal.prototype.run = function() {
  alert(this.name + " бежит!");
};

// --------- Класс-потомок -----------
// Конструктор потомка
// Animal.call(this, name), но apply надёжнее, так как работает с любым количеством аргументов.
function Rabbit(name) {
  Animal.apply(this, arguments);
}

// Унаследовать
Rabbit.prototype = Object.create(Animal.prototype);

// Желательно и constructor сохранить
Rabbit.prototype.constructor = Rabbit;

// Методы потомка
Rabbit.prototype.run = function() {
  // Вызов метода родителя внутри своего
  Animal.prototype.run.apply(this);
  alert(this.name + " подпрыгивает!");
};

// Готово, можно создавать объекты
var rabbit = new Rabbit("Кроль");
rabbit.run();
```

```js
class Animal {
  constructor(name) {
    this.name = name;
    this.speed = 0;
  }
  run() {
    alert(this.name + " бежит!");
  }
}

class Rabbit extends Animal {
  constructor(name) {
    super(name);
  }
  run() {
    super.run();
    alert(this.name + " бежит 2!");
  }
}

// Готово, можно создавать объекты
var rabbit = new Rabbit("Кроль");
rabbit.run();
```

### call,apply, bind

- this – это текущий объект при вызове «через точку» и новый объект при конструировании через new.
- call и apply явно указывают this
- func.call(context, arg1, arg2) идентичен вызову func.apply(context, [arg1, arg2])

```js
// При вызове функции как метода:
obj.func(...)    // this = obj
obj["func"](...)

// При обычном вызове:
func(...) // this = window (ES3) /undefined (ES5)

// В new:
new func() // this = {} (новый объект)

// Явное указание:
func.apply(context, args) // this = context (явная передача)
func.call(context, arg1, arg2, ...)
```

- Методы call/apply вызывают функцию с заданным контекстом и аргументами.
- А bind не вызывает функцию. Он только возвращает «обёртку», которую мы можем вызвать позже, и которая передаст вызов в исходную функцию, с привязанным контекстом.

```js
function bind(func, context) {
  return function() { // (*)
    return func.apply(context, arguments);
  };
}

var wrapper = func.bind(context[, arg1, arg2...]);
```

## Модули

- AMD - Asynchronous module definition
- CommonJS
- UMD - Universal Module Definition

```js
// es6
export default ...
export function func(){}, func(){}, let one = 1, class User(){}, {one, two}
export let one; export let two;
import {one, two}|{one as item1, two as item2}|one|* as obj  from "./nums"
// common js
module.exports = VARIABLE;
const VARIABLE = require('./file');
exports.NAME = VARIABLE;
import {NAME} from './file';
```

### js функции

```js
// конвертация в Boolean
!!"", !!0; // false
!!"ф", !!1, !!-1, !![], !!{}; // true

// конвертация в число
+"123", +"123.01", +new Date(); // 123, 123,01, 1461288164385
+"123ф", +"a", +"123,01"; // NaN
parseInt("3.14"), "10px", "15*3", "m17p"; // 3 , 10, 15, NaN
Number.parseInt() == parseInt();
parseFloat("3.14"), "3,14", "3*3", "e3"; // 3.14, 3, 3, NaN

// коневертация в строку
123 + [], true + []; // "123", "true"
// есть ли свойсвто в объекте
"a" in { a: "a" }, "b" in { a: "a" }; // true, false
// последний элемента массива
[1, 2, 3].slice(-1); // 3
//cлияние массивов
[1, 2, 3].concat([1, 2, 3]); // [1, 2, 3, 1, 2, 3]
// перемешать массив
[1, 2, 3].sort(() => Math.random() - 0.5); // [2, 1, 3]
// удаляем елемент по индексу
array.splice(index, 1); // [item]
delete array[i];

Array.from(Array(10).keys()) // [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
[...Array(10).keys()]
```

### ВСТРОЕНЫЕ ОБЪЕКТЫ В JAVASCRIPT

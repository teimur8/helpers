https://metanit.com/web/typescript/3.3.php

Строго типизированный язык который компилиться в js. Есть штуки для ООП. Все ошибки типов выдаются на этапе компиляции в js. Можно комп. через cli или добавить конфиг tsconfig.json.

```js
{
    "compileOnSave": true,
    "compilerOptions": {
        "target": "es5",
        "removeComments": true,
        "outDir": "js",
        "sourceMap": true
        "outFile": "main.js"
    },
	"files":[] // что надо
	"exclude":[] // что не надо
}
```

Типы: boolean,number,string,array,tuple: кортежи,enum: перечисления,any: произвольный,null и undefined, void.

Установка типов у свойств, методов. У переменных тип можно динамически.

```js
let x: number = 10;
let x; // тип any
let names: number[] // массив цифр
let names: Array<number>
let userInfo: [string, number]; // tuple, кортеж. только два элемента с заданным типами
enum Season { Winter, Spring, Summer, Autumn };
enum Season { Winter=10, Spring=true,Autumn="123123" };
let id : number | string; // union, может хранить два типа или больше
class User{
    name : string;
    constructor(_name:string){
        this.name = _name;
    }
}

// Создаем псевдоним или свой тип:
type stringOrNumberType = number | string;
let sum: stringOrNumberType = 36.6;

let add = function (
    a: number,
    b: number = 10, //по умолч.
    c?: string, // не обязательно
    d:string=funcReturnString() // функ. по умолч.
    ...numberArray: number[] // типа args
) : number {
    return a + b;
}
```

перегруз функций, определение функции будет только одно, в которой уже нужно проверять параметры. Фигня.

```js
function mathOp(
  x: number,
  y: number,
  operation: (a: number, b: number) => number // callback
): number {
  let result = operation(x, y);
  return result;
}
```

ооп

```js
// Class prop and methods.
class User {
    id: number;
    constructor(userId: number) {
        this.id = userId;
    }
    getInfo(): string {
        return 'id:' + this.id;
    }
    // статические методы и св-ва
    static PI: number = 3.14;
    static getSquare(radius: number): number {
        return Operation.PI _ radius _ radius;
    }

// модификаторы св-в и методов
public name: string;
public year: number;
private \_name: string;
private \_year: number;
protected age: number;
readonly age: number;
private setYear(age: number): number {
return new Date().getFullYear() - age;
}
}

// Определение свойств через конструктор
class User {
private \_name: string;
constructor(name: string) {
this.\_name = name;
}
// вместо этого можно написать
constructor(private name:string){}
}

```

Наследование через extend. Родитель - super.
Абстрактный класс к в php. Он только для наследования.
Интерфейсы

### Общие типы generic

С помощью generic можно указать что входит, или ts сам определит и на основе типа можно делать что то дальше.

```ts
function getId<T>(id: T): T {
  return id;
}
getId<number>(5);
getId<string>("abc");
```

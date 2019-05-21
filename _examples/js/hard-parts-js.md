https://coursehunters.net/course/javascript-novye-zhestkie-chasti
https://static.frontendmasters.com/resources/2018-05-23-javascript-new-hard-parts/new-hard-parts-slides.pdf

## Foundations of JavaScript

```js
const num = 3;
function multiplyBy2(inputNumber) {
  const result = inputNumber * 2;
  return result;
}
const name = "Will";
/*
1.Declaring const num and set 3
2.In variable multiplyBy2 set object clouser with functionality
    - function say "save to the memory this particular functionality"
    - we not go to the fucntion body until it's call
3.like step 1
*/
```

## Running/calling/invoking a function

```js
function multiplyBy2(inputNumber) {
  const result = inputNumber * 2;
  return result;
}
const output = multiplyBy2(4);
/* 
When you execute a function you create a new execution context
- set inputNumber, set result and return result to global scope 
*/
```

## Asynchronicity

### Web Browser APIs/Node background threads

- JavaScript is single threaded and has a synchronous execution model

```js
function printHello(){
 console.log(“Hello”);
}
setTimeout(printHello,1000);
console.log(“Me first!”);
/*
1. save to printHello fucntion
2. call setTimeout with printHello [f] and timeout
3. run log
4.
*/
```

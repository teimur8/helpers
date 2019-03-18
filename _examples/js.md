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

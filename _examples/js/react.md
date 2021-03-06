# NUXT

#### Exports & Imports

```js
// default
export default ...;
import someNameOfYourChoice from './path/to/file.js';
// named
export const someData = ...;
import { someData } from './path/to/file.js';
import * as upToYou from './path/to/file.js';
```

#### Classes

```js
class Person {
  constructor() {
    this.name = "Max";
  }
}
const person = new Person();
console.log(person.name);
//or
class Person {
  name = "Max";
}
const person = new Person();
console.log(person.name); // prints 'Max'
// or
class Person {
  name = "Max";
  printMyName() {
    console.log(this.name); // this is required to refer to the class!
  }
}
const person = new Person();
person.printMyName();
// or
class Person {
  name = "Max";
  printMyName = () => {
    console.log(this.name);
  };
}
// inheritanc
class Human {
  species = "human";
}
class Person extends Human {
  name = "Max";
  printMyName = () => {
    console.log(this.name);
  };
}
const person = new Person();
person.printMyName();
console.log(person.species); // prints 'human'
```

#### Spread & Rest Operator

### HOC - higher-order component

Обычная обертка которая возвращает компонент, можно вставить пропсы, функции или
условно изменить рендеринг

```js
// withAuth.js
import React from "react";
export default function(Component) {
  return class withAuth extends React.Component {
    funct1() {
      alert(1);
    }

    render() {
      let var1 = "1";
      return <Component var1={var1} funct1={this.funct1} {...this.props} />;
    }
  };
}
```

```js
const Secret = props => {
  return <h1>Secret page</h1>;
};

export default withAuth(Secret);
```

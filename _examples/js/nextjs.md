### Components type

```js
// Functional Component
// props = {prop1, prop2 : { prop3 }}
const Sell = props => <p>Sell</p>;
export default Sell;
```

```js
// Class Component
import React from "react";
class Home extends React.Component {
  render() {
    return <p>Hey</p>;
  }
}
export default Home;
```

### Layout

```js
// Layout.js
// <> == <React.Fragment>
const Layout = props => (
  <>
    <h1>Layout</h1>
    <React.Fragment>{props.childred}</React.Fragment>
  </>
);
export default Layout;
```

### Lifecicle functions

```js
// http://projects.wojtekmaj.pl/react-lifecycle-methods-diagram/
export default class Home extends React.Component {
  constructor() {
    super();
  }
  componentDidMount() {}
  componentDidUpdate() {}
  componentWillUnmount() {}
  getSnapshotBeforeUpdate(prevProps, prevState) {}
  render() {} // required
}
```

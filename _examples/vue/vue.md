### Building Controlled Components

[link](https://codesandbox.io/s/oxxlx055xy?from-embed)

```html
<input :value="email" @input="event = $event.target.value" />
```

```js
props:['value']
this.$emit("input", !this.value);

v-model="input"
//same
:value="input"
@input="(e) => input = e.target.value"
```

### Customizing Controlled Component Bindings

[link](https://codesandbox.io/s/mqnzm84plx?from-embed)

Change value to toggled and input to toggle

```html
<span
  @click="toggle"
  @keydown.space.prevent="toggle"
  :aria-checked="toggled.toString()"
></span>
<input :toggled="email" @toggle="event = $event.target.value" />
```

```js
export default {
  model: {
    prop: "toggled",
    event: "toggle"
  },
  props: ["toggled"],
  methods: {
    toggle() {
      this.$emit("toggle", !this.toggled);
    }
  }
};
```

### Wrapping External Libraries as Vue Components

[link](https://codesandbox.io/s/n4qolyr42m?from-embed)
js component to vue

```html
<input :value="value" ref="tima1" placeholder="YYYY-MM-DD" />
```

```js
import Pikaday from "pikaday";
import "pikaday/css/pikaday.css";

export default {
  props: {
    value: { required: true },
    format: { default: "YYYY-MM-DD" },
    options: { default: {} }
  },
  mounted() {
    const picker = new Pikaday({
      field: this.$refs.input,
      format: this.format,
      onSelect: () => {
        this.$emit("input", picker.toString());
      },
      ...this.options
    });
  }
};
```

### Encapsulating External Behavior: Closing on Escape

[link](https://codesandbox.io/s/1v1o4lvp9j?from-embed)

```html
<!-- can focus like input, add behavior added tabindex=0 -->
<div tabindex="0"></div>
```

```js
export default {
  created() {
    const escapeHandler = e => {
      if (e.key === "Escape" && this.show) {
        this.dismiss()
      }
    }
    document.addEventListener("keydown", escapeHandler)
    this.$once("hook:destroyed", () => {
      document.removeEventListener("keydown", escapeHandler)
    })
  },
}
// or
  watch:{
    show(show){
        this.$nextTick(() =>{
            this.$refs.modal.focus() // focus to element
        })
    }
  }
```

### Run immediatly

```js
  watch:{
      show:{
          immediate: true,
          handler: (show) => {
              show != show;
          }
      }
  }
```

```js
$emit('open:confirm-delete-modal')
@open:confirm-delete-modal="'event run'"
```

### Native-Style Buttons Using Slots and Class Merging

**Component.vue**

```html
<div>
  <header>
    <slot name="header"></slot>
  </header>
  <section>
    <slot></slot>
  </section>
  <footer>
    <slot name="footer">
      Default content. Delete after insert slot with name 'footer'
    </slot>
  </footer>
  <slot name="img"></slot>
</div>
```

```html
<component>
    <template slot="header">Header</template>
    section slot
    <div slot="footer">footer slot with div</div>
    <img slot="img" src=""/>
</componnent>
```

### slot inheriting

[link](https://codesandbox.io/s/jj8vjjxlk9?from-embed)

**ModalDialog.vue**

```html
<template>
  <portal to="modals" v-if="show">
    <div class="modal-backdrop" v-show="show">
      <div class="modal">
        <slot></slot>
      </div>
    </div>
  </portal>
</template>
```

```js
<script>
export default {
  props: ["show"],
  methods: {
    dismiss() {
      this.$emit("close")
    }
  },
  watch: {
    show: {
      immediate: true,
      handler: show => {
        if (show) {
          document.body.style.setProperty("overflow", "hidden")
        } else {
          document.body.style.removeProperty("overflow")
        }
      }
    }
  },
  created() {
    const escapeHandler = e => {
      if (e.key === "Escape" && this.show) {
        this.dismiss()
      }
    }
    document.addEventListener("keydown", escapeHandler)
    this.$once("hook:destroyed", () => {
      document.removeEventListener("keydown", escapeHandler)
    })
  }
}
</script>
```

// ConfirmDeleteModal

```html
<template>
  <modal-dialog :show="show" @close="cancel">
    <h1 class="text-center text-2xl font-bold mb-4">
      Are you sure?
    </h1>
  </modal-dialog>
</template>
```

```js
import ModalDialog from "./ModalDialog.vue";

export default {
  components: {
    ModalDialog
  },
  props: ["show", "accountId"],
  methods: {
    cancel() {
      this.$emit("close");
    },
    confirmDelete() {
      console.log(`Deleting account ${this.accountId}...`);
      this.$emit("close");
    }
  }
};
```

### Passing Data Up Using Scoped Slots

[link](https://codesandbox.io/s/nwz1xpkyl0?from-embed)

**ContactList.vue**

```html
<div v-for="contact in contacts" :key="contact.id">
  <div class="font-bold">
    {{ pseudoSlot({ contact: contact }) }}
  </div>
  <slot :contact="contact"></slot>
</div>
```

```js
  props: ["pseudoSlot"],
```

**App.vue**

```html
<contact-list :pseudo-slot="({ contact }) => contact.name.first">
  <a slot-scope="{ contact }" :href="`/contacts/${contact.id}`">
    {{ contact.name.first }}
  </a>
</contact-list>
```

#### JS Distructor

[link](https://learn.javascript.ru/destructuring)

```js
let options = {
  title: "Меню",
  width: 100,
  height: 200
};

let { title, width, height } = options;
```

### Render Functions 101

[link](https://codesandbox.io/s/5vxlz052px?from-embed)

```html
<template>
  <span class="text-xl">
    Hello World
  </span>
</template>
```

```js
export default {
  // render function create element like in template. We can delete template, and all works
  render(createElement) {
    return createElement(span,{
        attrs: {
          class: "text-xl"
        },
    }, 'Hello World')
}
```

### Render Functions and Components

[link](https://codesandbox.io/s/k05o3npx25?from-embed)

```js
// HelloWorld.vue
export default {
  render(createElement) {
    return createElement(span,{
        attrs: {
          class: "text-xl"
        },
    }, 'Hello World')
}
```

```js
  import HelloWorld from "./components/HelloWorld.vue"

  render(createElement) {
    // dose't need to register fo component object, when we render component from render function
    return createElement(HelloWorld,{
      on:{
        click: () => {} // custom event run $emit('click')
      },
      nativeOn:{
        click: () => {}  // browser event
      },
      props:{
        value: true // send props
      }
    }, 'Hello World')
  }
```

```js
  data:{
    return{
      toggled: true
    }
  },
  render(createElement) {
    // v-model implemetation in render function
    return createElement(ToggleInput,{
      on:{
      input: (newValue) => this.toggled = newValue
      },
      props:{
        value: this.toggled
      }
    })
  }
```

### Render Functions and Children

[link](https://codesandbox.io/s/7w1pr58p6x?from-embed)

```js
render(createElement) {
  return createElement("div", {}, [
    createElement("h1", {}, "Your Contacts"),
    createElement("ul",{}, this.contacts.map(contact => {
        return createElement("li",{},`${contact.name.first} ${contact.name.last}`)
      })
    )
  ])
}
```

### Render Functions and Slots

[link](https://codesandbox.io/s/z2k1j94o8m?from-embed)

**App.vue**

```html
<hello-world>
  <h1 slot-scope="{ subject }">
    Hello {{ subject }}!
  </h1>
</hello-world>
```

**HelloWorld.vue**

```js
render(createElement) {
  // this.$slots.default // VNode inside
  // this.$slots.name
  // this.$scopedSlots.default() // function, return VNode
  return createElement("div", {}, [
    // this.$slot.default,
    this.$scopedSlots.default({
      subject: 'world'
    })
  ])
}
// or
render(createElement) {
  this.$scopedSlots.default({
    subject: 'world'
  })
}
```

### Data Provider Components

[link](https://codesandbox.io/s/nk9qr8yz0p?from-embed)

**FetchJson.vue**

```js
export default {
  data() {
    return {
      response: null,
      loading: true
    };
  },
  render() {
    return this.$scopedSlots.default({
      response: this.response,
      loading: this.loading
    });
  }
};
```

**App.vue**

```html
<fetch-json>
  <div class="card" slot-scope="{ response: albums, loading }">
    <div v-if="loading">
      Loading...
    </div>
    <div v-else>
      {{ album.artist }}
    </div>
  </div>
</fetch-json>
```

### Renderless UI Components

[link](https://codesandbox.io/s/kn1nv6ypv?from-embed)

**RenderlessTagInput.vue**

```js
export default {
  model: {
    prop: "tags",
    event: "update"
  },
  props: {
    tags: { required: true },
    addOnEnter: true // options for different functional
  },
  data() {
    return {
      input: ""
    };
  },
  render() {
    return this.$scopedSlots.default({
      // send data
      tags: this.tags,
      removeTag: this.removeTag,

      // bind event with context, return functinon, after evaluate
      // return object with wnwvt object and bined context
      removeButtonEvents: tag => ({
        click: () => {
          this.removeTag(tag);
        }
      }),

      // bind props
      inputProps: {
        value: this.input
      },
      // bind events
      inputEvents: {
        input: e => (this.input = e.target.value),
        keydown: e => {
          if (e.key === "Enter" && this.addOnEnter) {
            e.preventDefault();
            this.addTag();
          }
        }
      }
    });
  }
};
```

**App.vue**

```html
<renderless-tag-input v-model="tags" :add-on-enter="false">
  <div
    slot-scope="{ tags, removeTag, inputProps, inputEvents, removeButtonEvents }"
  >
    <span v-for="tag in tags" :key="tag">
      <span>{{ tag }}</span>
      <button type="button" @click="removeTag(tag)">&times;</button>
      <button type="button" v-on="removeButtonEvents(tag)">&times;</button>
    </span>
    <input placeholder="Add tag..." v-bind="inputProps" v-on="inputEvents" />
  </div>
</renderless-tag-input>
```

```js
// value bindings
:value="inputValue"
v-bind:value="inputValue"
v-bind="{ value: inputValue }"

@input="onInput"
v-on:input="onInput"
v-on="{ input: onInput }"

@keydown.backpase
@keydown.enter.prevent
{
  keydown: e =>{
    if(e.key === 'Backpase')
    if(e.key === 'Enter'){
      e.preventDefault();
    }
  }
}
```

### Wrapping Renderless Components

[link](https://codesandbox.io/s/5z5056yoq4?from-embed)

**Wrapper.vue**

```html
<renderless-tag-input
  :tags="tags"
  @update="(newTags) => $emit('update', newTags)"
>
  ...
</renderless-tag-input>
```

```js
export default {
  components: {
    RenderlessTagInput
  },
  model: {
    prop: "tags",
    event: "update"
  },
  props: {
    tags: { required: true }
  }
};
```

### Element Queries as a Data Provider Component

[link](https://codesandbox.io/s/20r8wnx44r?from-embed)

**WithDimensions.vue**

```js
import elementResizeDetectorMaker from "element-resize-detector";
const erd = elementResizeDetectorMaker({ strategy: "scroll" });
export default {
  mounted() {
    erd.listenTo(this.$el, el => {
      this.width = el.offsetWidth;
      this.height = el.offsetHeight;
    });
  },
  render() {
    return this.$scopedSlots.default({
      width: this.width,
      height: this.height
    });
  }
};
```

```html
<with-dimensions>
  <div
    slot-scope="{ width }"
    :class="{ 'profile-card--horizontal': width >= 400 }"
  ></div>
</with-dimensions>
```

### Building Compound Components with Provide/Inject

[link](https://codesandbox.io/s/jl6pz69ox3?from-embed)

```js
```

```html

```

[link](https://codesandbox.io/s/o98y1l735y?from-embed)
[link](https://codesandbox.io/s/ykypmk03xj?from-embed)
[link](https://codesandbox.io/s/oozwlvk36?from-embed)
[link](https://codesandbox.io/s/o95oq681l6?from-embed)
[link](https://codesandbox.io/s/8n0mnm2v70?from-embed)
[link](https://codesandbox.io/s/n7mw5871v0?from-embed)
[link](https://codesandbox.io/s/w66mzknr27?from-embed)
[link](https://codesandbox.io/s/vyxl1z5pp5?from-embed)

### Event Bus

```js
// event-bus.js
import Vue from "vue";
export const EventBus = new Vue();
```

```js
// event-mixin.js
import EventBus from "./event-bus";
export const eventMixin = {
  methods: {
    busEmit(event, data) {
      return EventBus.$emit(`${event}-${this.eventBusId}`, data);
    },
    busListen(event, callback) {
      return EventBus.$on(`${event}-${this.eventBusId}`, callback);
    },
    busListenOff(event, callback) {
      return EventBus.$off(`${event}-${this.eventBusId}`, callback);
    },
    busListenOnce(event, callback) {
      return EventBus.$once(`${event}-${this.eventBusId}`, callback);
    }
  }
};
```

```js
import { eventMixin } from "./event-mixin";

export default {
  mixins: [eventMixin],

  mounted() {
    this.$el.addEventListener("click", () =>
      this.busEmit("optionSelected", this.value)
    );
    this.busListen("activeOptionIndexChange", value => {
      // do somthing
    });
  }
};
```

### Provide / Inject

**The provide/inject binding are NOT reactive**
https://medium.com/@znck/provide-inject-in-vue-2-2-b6473a7f7816

```js
// Parent
export default {
  data() {
    return {
      eventBusId: Math.random()
        .toString(36)
        .substring(7),
      bar: "bar"
    };
  },

  provide() {
    const foo = {};
    Object.defineProperty(foo, "bar", {
      enumerable: true,
      get: () => this.bar
    });
    return {
      eventBusId: this.eventBusId,
      disableDefaultSearch: falses,
      foo
    };
  }
};
```

```js
// Child
export default {
  methods: {
    onInput() {
      this.$emit("input", this.query);

      if (!this.disableDefaultSearch) {
        this.busEmit("searchInput", this.query);
      }
    }
  },
  inject: ["eventBusId", "disableDefaultSearch"]
};
```

### File Upload

```html
<input type="file" @change="atChange" />
```

```js
atChange(event)
{
    this.file = event.target.files[0];

    let reader = new FileReader();
    reader.readAsDataURL(this.file );

    reader.onload = event => {
        this.imageUrl = event.target.result;
    }
},
```

```js
// upload to server
upload(event)
{
    let form = new FormData();
    form.append(`file`, this.file);
    form.set(`name`, this.name);

    axios({
      method: 'post',
      url: this.url,
      data: form,
      config: { headers: {'Content-Type': 'multipart/form-data' }}
    })
    .then(response => {
    })
    .catch(error => {
    });
},
```

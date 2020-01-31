

## Вариант монтирование

```js

/**
 *
 * @param selector
 * @param elementId
 */
const mountPickerModal = (selector: string, elementId: string) => {

  if (!document.getElementById(`${elementId}`)) {
    return;
  }

  if (!document.querySelector(`[${selector}]`)) {
    return;
  }

  // монтируем модалку
  const el = document.getElementById(`${elementId}`) as HTMLHtmlElement;
  const type = typeof el.dataset.type;

  new PickerModal({
    store,
    propsData: {
      type
    },
  }).$mount(el);

  // вешаем обработчки на клик, что бы вызвать кастомный event с параметрами
  [...document.querySelectorAll(`[${selector}]`)]
    .map((el: any) => {
      el.addEventListener('click', function(this: any) {
        document.dispatchEvent(new CustomEvent('picker-modal-event', {
          detail: this.dataset,
        }));
      });
    });

};

mountPickerModal('js-picker-modal', 'pickerModal');


```

## [Colors](https://getbootstrap.com/docs/4.3/utilities/colors/)

- text-{color}
- bg-{color}

## [Buttons](https://getbootstrap.com/docs/4.3/components/buttons/)

- стили: btn, btn-{color}, btn-outline-{color}, btn-link
- можно использовать с: a,button, input[type="button,submit,reset"]
- размеры: btn-lg, btn-sm, btn-block(во всю длину)
- активность: для ссылки - a.active, a.disabled aria-disabled="true", для button - button disabled
- checkbox: label.btn > input[type="checkbox,radio"], но состояние вручную

## [Typography](https://getbootstrap.com/docs/4.3/content/typography/)

- .h[1-6]
- огромный заголовок: display-{1-4}
- <mark> - выделяет, <del>,<s> - зачеркивают, <ins>,<u> - подчеркивают, <small>, <strong>,<b>, <em>,<i> - italic
- .mark, .small
- цитаты: blockquote.blockquote > blockquote-footer
- список: горизонтальный - ul.list-inline->li.liist-inline-item

## [Text](https://getbootstrap.com/docs/4.3/utilities/text/)

- text-justify
- выравнивание: .text-{left,center,right}, .text-{size}-{position} - .text-md-left - в зависимости от экрана
- text-wrap, text-nowrap - перенос текста
- обрезка: text-truncate
- перенос букв: text-break
- .text-{lowercase,uppercase,capitalize}
- text-decoration-none

## [Jumbotron](https://getbootstrap.com/docs/4.3/components/jumbotron/)

- классаня штука, можно изменить цвет

## [Media](https://getbootstrap.com/docs/4.0/layout/overview/)

- **min-width** - минимальная ширина при которой будет активны стили, если меньше, то перестают работать
- **max-width** - максимальная ширина при которой будет активны стили, если больше, то перестают работать

```css
// активен пока screen больше 576px
@media (min-width: 576px) {
}
// активен пока screen меньше 768px
@media (max-width: 768px) {
}
// активен пока screen меньше 768px и больше 576px
@media (min-width: 576px) and (max-width: 768px) {
}
```

```css
// аналог min-width
@include media-breakpoint-up(xs, sm, md, lg, xl) {
}
// аналог max-width
@include media-breakpoint-down(xs, sm, md, lg, xl) {
}
// аналог  min-width и max-width
@include media-breakpoint-between(md, xl) {
}
// или, менее гибкий
@include media-breakpoint-only(xs) {
}
```

```scss
$zindex-{dropdown,sticky,fixed,modal-backdrop,modal,popover,tooltip];
```

## Utilites Display

https://getbootstrap.com/docs/4.0/utilities/display/

- display для xs: .d-{none,inline,inline-block,block,table,table-cell,table-row,flex,inline-flex}
- display для остальных: .d-{sm, md, lg, xl}-{value}
- скрыть элемент: .d-none, .d-sm-none

## Utilites Border,Shadows

https://getbootstrap.com/docs/4.1/utilities/borders/

- добавить: .border, .border-{direction}
- убрать: .border-0, .border-{direction}-0
- цвет: .border.border-{color}
- radius: .rounded, .rounded-{direction,circle,0}

https://getbootstrap.com/docs/4.1/utilities/shadows/

- тени: .shadow,.shadow-{none,sm,lg}

## Utilites Spacing

https://getbootstrap.com/docs/4.1/utilities/spacing/

- margin для xs: m{t,b,l,r,x,y}-{0,1,2,3,4,5,auto}, m-1 круговой
- padding для xs: m{t,b,l,r,x,y}-{0,1,2,3,4,5}
- margin для остальных: m{t,b,l,r,x,y}-{breakpoint}-{0,1,2,3,4,5,auto}, m-md-1 круговой
- padding для остальных: m{t,b,l,r,x,y}-{breakpoint}-{0,1,2,3,4,5}
- горизонтальный центр: .mx-auto

## forms

https://getbootstrap.com/docs/4.1/components/forms/

- <fieldset disabled> - отключает поля формы, или группировка полей
- some structure to forms: .form-group > .form-control + label
- стили для полей: .form-control, .form-control-file - для файла
- размеры: .form-control..form-control-{sm,lg}
- только чтение: readonly, .form-control-plaintext и readonly - будет как строка
- в строку: form.form-inline
- чекбоксы и радио: .form-check > input.form-check-input + label.form-check-label
- в строку: .form-check.form-check-inline
- без label: .form-check-input.position-static
- сетка: .row > .col > input.form-control, .form-row

## custom forms

https://getbootstrap.com/docs/4.1/components/forms/#custom-forms
https://getbootstrap.com/docs/4.1/components/forms/#translating-or-customizing-the-strings

- .custom-control.custom-checkbox или .custom-radio > input.custom-control-input#id1 + label.custom-control-label[for="id1"], без id не будет работать
- в строку: .custom-control.custom-control-inline.custom-checkbox
- range: input[type="range",min="0",max="5",step="0.5"].custom-range#id1 + label[for="id1"]
- file: .custom-file > input[type="file"].custom-file-input#id1 + label[for="id1"].custom-file-label

## navs

https://getbootstrap.com/docs/4.1/components/navs/

- default: ul.nav > li.nav-item > a.nav-link.{active,disabled}
- short: .nav > a.nav-link.{active,disabled}
- tabs or pills: .nav.{nav-tabs,nav-pills}, short: .nav.nav-tabs > a.nav-link.nav-item.{active,disabled}

-

## flexbox utilites

https://getbootstrap.com/docs/4.0/utilities/flex

- direction: .flex-row, .flex-lg-row - row,row-reverse,column,column-reverse;
- justify-content: .justify-content-start, .justify-content-sm-start - start,center,end, around, between
- align-items: .align-items-center, .align-items-sm-center - start,center,end,stretch,baseline;
- align-self: .align-self-center, .align-self-md-center - start,center,end,stretch,baseline;
- flex-wrap: .flex-nowrap, .flex-wrap
- align-content + flex-wrap: .align-content-start, .align-content-sm-start - start,center,end,stretch;
- order: .order-0, .order-sm-0 - 0 - 12;
- margin justify-content: .mr-auto, .ml-auto
- margin .align-items-start.flex-column: .mb-auto, .mt-auto

##

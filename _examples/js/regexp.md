https://learn.javascript.ru/regexp-introduction
https://code.tutsplus.com/tutorials/a-simple-regex-cheat-sheet--cms-31278

Flags
g — global.
i — insensitive.
m — This flag will make sure that the ^ and \$ tokens look for a match at the beginning or end of each line instead of the whole string.
u — This flag will enable you to use Unicode code point escapes in your regular expression.
y — This will tell JavaScript to only look for a match at the current position in the target string.

\w — all the words characters
\W — non-word characters
\d — digit characters.
\D — non-digit characters
\s — whitespace characters
\S — all other characters except whitespace.
. — any character except line breaks.
[A-Z] — characters in a rang
[ABC] — a character in the given set
[^abc] — all the characters not present in the given set

```js
// create
regexp = new RegExp("шаблон", "флаги");
regexp = /шаблон/; // без флагов
regexp = /шаблон/gim; // с флагами gmi
```

```js
// match()
"asd".match(/.{1,2}/g); // ["as", "d"]
"asd".match(/.{1,2}/); // ["as", index: 0, input: "asd", groups: undefined]
```

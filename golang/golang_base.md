
# Типы

- basic types: numbers, strings, and Booleans;
- aggregate types: arrays and structs;
- reference types: pointers, slices, maps, functions, and channels;
- interface types.


```go
// Базовые: numbers, string, bool
int,uint(|8|16|32|64),
byte = uint8 // одно и тоже
rune = int32, // Unicode code point
float(32|64),complex(64|128)
string: “” `` // одинарных нет
```

# Variables (Declaration & Initialization)

```go
// default init
var name type
var i int
// explicit
var name type = expression
var i int = 12
// short
name := expression
i := 12
func main(){
    a := func(){}
    b := 10
}
// := - is a declaration
// = - is an assignment
```

## Zero value

- 0 : numbers-0 : numbers
- false: bool-false: bool
- “” : string-“” : string
- nil : reference type-nil : reference type



```go



# Scope

// группировать типы аргументов
// возвращать несколько значений
func add(x,y int ) (int, int){
    return x,y;
}
func main (){
    a,b := add(10,20)
}

```

# Указатели

```go
// & - оператор взятия ссылки
// * - оператор взятия значения

func main(){
    a := 10
    b = &a
    c = &b

    a // 10
    *b //10
    **c //10

    *b += 10
    a // 20
}

```

# Приведение типа - Type assertion

Conveersion - change one type to another
Assertion - used for interfases

```go
// пока пропущю
// не понимаю как это рабоатет, как я понял немного в го статсическая типизация
// а с помощью этого мы можем делать ее динимической
func main(){}

```

# Структуры

```go
package main

import "fmt"

// Создаем структуру
type Food struct {
	name string // Aggregated field
}
// добавляем метод к ней
func (this *Food) eat() {
	fmt.Println("Eat: ", this.name)
}

// Пример встраивания и агрегации
// Новая структура, внутри встраиваем другую
type Recipe struct {
	Food               // Embedded field
	Ingredients []Food // Aggregated field
}

// Добавляем метод
func (this *Recipe) eat() {
	fmt.Println("Eat: ", this.name, "\n----------------------")
	for _, ingredient := range this.Ingredients {
		ingredient.eat()
	}
}

func main() {
    // простой вызов
    fries := Food{
		name: "fries",
	}
	fries.eat()

	fmt.Println("\n------------------------------------------------------------------\n")

    // создаем структуру рецепт и зполняем ее
	cheeseburger := Recipe{
		Ingredients: []Food{
                {name: "bun"},
                {name: "cucumber"},
                {name: "cheese"},
                {name: "cutlet"},
                {name: "bun"}
            },
	}
	cheeseburger.Food.name = "Small cheeseburger"
	cheeseburger.Food.eat()

	fmt.Println("\n------------------------------------------------------------------\n")

    cheeseburger.name = "Big cheeseburger"
    // если у рецепта не добавить свою функцию eat
    // то так будет вызываться функция _s Food
	cheeseburger.eat()
}

```

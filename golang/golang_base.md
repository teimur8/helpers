

```go
// внутри функции можно использовать оператор :=
func main(){
    a := func(){}
    b := 10
}


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

# Routines
[repo](https://github.com/GoesToEleven/GolangTraining/tree/master/22_go-routines)


## Councurrency
[no-go](https://github.com/GoesToEleven/GolangTraining/blob/master/22_go-routines/01_no-go/main.go)

Main thread don't wory about routines, so we need tell him to wait.Use `WaitGroup` for that. Init WG `var wg sync.WaitGroup`, say that we have two rutines `wg.Add(2)`, run them and wait `wg.Wait()`
[wait-group](https://github.com/GoesToEleven/GolangTraining/blob/master/22_go-routines/03_wait-group/main.go)

## Concurrency vs. Parallelism

Concurrency is about dealing with lots of things at once. Много вещей в одном потоке.
Parallelism is about doing lots of things at once. Много в паралельных потоках.

![img1](http://i.imgur.com/9Dahh6U.png)
![img2](http://i.imgur.com/r1mM72i.png)

По умолчанию go испольузет одно ядро, в runtime.GOMAXPROCS(runtime.NumCPU()) мы говрим чтобы использовать все для паралелизма.
init() спец функция для иницилизации
[gomaxprocs_parallelism](https://github.com/GoesToEleven/GolangTraining/blob/master/22_go-routines/05_gomaxprocs_parallelism/main.go)


## Race Condition
Использование одной переменной в нескольких потоках может создавать баги.Для выявления используют флаг -race.

[Diagram](https://github.com/ardanlabs/gotraining/blob/master/topics/go/concurrency/data_race/README.md#diagram)

При запуске с -race будут показывать предупреждения
[race-condition](https://github.com/GoesToEleven/GolangTraining/blob/master/22_go-routines/06_race-condition/main.go)

Иструмент для решение это задачи - mutex

## Mutex
mutex, от mutual exclusion — «взаимное исключение», for prevent race condition.

создаем `var mutex sync.Mutex`, закрываем перед `mutex.Lock()` изменением и открываем после `mutex.Unlock()`

[mutex](https://github.com/GoesToEleven/GolangTraining/blob/master/22_go-routines/07_mutex/main.go)
[mutex_ex_2](https://github.com/ardanlabs/gotraining/blob/master/topics/go/concurrency/data_race/example3/example3.go)

## Atomic

Package atomic provides low-level atomic memory primitives useful for implementing synchronization algorithms.

[doc](https://godoc.org/sync/atomic)
[atomicity](https://github.com/GoesToEleven/GolangTraining/blob/master/22_go-routines/08_atomicity/main.go)

## Channels

[README](https://github.com/ardanlabs/gotraining/blob/master/topics/go/concurrency/channels/README.md)
[channels](https://github.com/GoesToEleven/GolangTraining/tree/master/22_go-routines/09_channels)





# Routines

## Councurrency
```go
// гл проц не следит за рутинами, и нужно сказать ему что бы
// он ждал. Для этого испольузем WaitGroup
var wg sync.WaitGroup

func main() {
	wg.Add(2)
	go f1()
	go f2()
	wg.Wait()
}

func f1() {
	for i := 0; i < 10; i++ {
		fmt.Println("f1:", i)
		time.Sleep(time.Duration(3 * time.Millisecond))
	}
	wg.Done()
}

func f2() {
	for i := 0; i < 10; i++ {
		fmt.Println("f2:", i)
		time.Sleep(time.Duration(3 * time.Millisecond))
	}
	wg.Done()
}
```

## Concurrency vs. Parallelism

Concurrency is about dealing with lots of things at once. Много вещей в одном потоке.
Parallelism is about doing lots of things at once. Много в паралельных потоках.

![img1](http://i.imgur.com/9Dahh6U.png)
![img2](http://i.imgur.com/r1mM72i.png)


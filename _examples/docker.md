## Error unauthorized: incorrect username or password
docker logout

## List Docker CLI commands
docker
docker container --help

## Display Docker version and info
docker --version
docker version
docker info

## Execute Docker image
docker run hello-world

## List Docker images
docker image ls

## List Docker containers (running, all, all in quiet mode)
docker container ls
docker container ls --all
docker container ls -aq


```sh
cat services_\(1\).sql | dc exec -T db /usr/bin/mysql -u 'user' --password=pass dbName
```

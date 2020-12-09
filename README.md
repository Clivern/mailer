## Mailer

A Transactional Email Microservice.


## Development

Run `make` command to get all available commands. you can use `make serve` to run the application and `make ci` to run sanity checks

```bash
$ make

 Choose a command run in Mailer:

  fix        Fix code style issues
  fix-diff   Get code style issues
  test       Run test cases
  serve      Run the application
  lint       Lint PHP files
  outdated   Get list of outdated packages
  ci         Run all sanity checks

# For laravel commands
$  /artisan
```


## Deployment

### With docker-compose

```bash
$ docker-compose up -d
```


## Scaling


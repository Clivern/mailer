## Mailer

A Transactional Email Microservice.


### Getting Started

Clone and create the application configs

```
$ cp .env.example .env
```

You need to provide the following configs

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mailer
DB_USERNAME=root
DB_PASSWORD=root
DB_FOREIGN_KEYS=true

MAIL_FROM_ADDRESS=from@example.com
MAIL_FROM_NAME="Mailer"

SENDGRID_API_KEY=
MAILJET_PUBLIC_API_KEY=
MAILJET_PRIVATE_API_KEY=
```

Then run the database migrations

```
$ ./artisan migrate
```

To run the web service

```
$ ./artisan serve
```

To run the queue consumer

```
$ ./artisan queue:work --tries=10
```

To send email from the command line

```
# Text Email
$  ./artisan message:send --to_email hello@example.com --subject "Hello" --type text --body "Hello World" --to_name Somebody

# HTML Email
$  ./artisan message:send --to_email hello@example.com --subject "Hello" --type html --body "<h3>Hello World</h3>" --to_name Somebody

# Markdown Email
$  ./artisan message:send --to_email hello@example.com --subject "Hello" --type markdown --body "## Hello World" --to_name Somebody
```

To send email through API

```
# Text Email
$ curl -X POST http://127.0.0.1:8000/api/v1/message \
    -d '{"to": [{"email": "hello@example.com", "name": "Somebody"}], "subject":"Hello", "content":{"type":"text", "value": "Hello World"}}'

# HTML Email
$ curl -X POST http://127.0.0.1:8000/api/v1/message \
    -d '{"to": [{"email": "hello@example.com", "name": "Somebody"}], "subject":"Hello", "content":{"type":"html", "value": "<h3>Hello World<h3>"}}'

# Markdown Email
$ curl -X POST http://127.0.0.1:8000/api/v1/message \
    -d '{"to": [{"email": "hello@example.com", "name": "Somebody"}], "subject":"Hello", "content":{"type":"markdown", "value": "## Hello World"}}'
```

### Deployment

#### With docker-compose

Create `.env` file from `.env.docker`

```bash
$ cp .env.docker .env
```

Update `.env` file with your sendgrid and mailjet credentials

```
MAIL_FROM_ADDRESS=from@example.com
MAIL_FROM_NAME="Mailer"

SENDGRID_API_KEY=
MAILJET_PUBLIC_API_KEY=
MAILJET_PRIVATE_API_KEY=
```

Build docker images and run containers

```bash
$ docker-compose up -d
```

Send test emails

```
# Test Email
$ curl -X POST http://127.0.0.1:8000/api/v1/message \
    -d '{"to": [{"email": "hello@example.com", "name": "Somebody"}], "subject":"Hello", "content":{"type":"text", "value": "Hello World"}}'

$ curl -X POST http://127.0.0.1:8000/api/v1/message \
    -d '{"to": [{"email": "hello@example.com", "name": "Somebody"}], "subject":"Hello", "content":{"type":"html", "value": "<h3>Hello World<h3>"}}'

$ curl -X POST http://127.0.0.1:8000/api/v1/message \
    -d '{"to": [{"email": "hello@example.com", "name": "Somebody"}], "subject":"Hello", "content":{"type":"markdown", "value": "## Hello World"}}'
```


### Scaling



### Development

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

# DEMO chat with Symfony and RabbitMQ

## Description

This project is a prototype of a chat on Symfony and RabbitMQ.

The aim of the project is to consolidate theoretical knowledge about:
* RabbitMQ
* AMQP
* STOMP

## Requirements

* PHP >= 8.0
* Postgres *

## Installation

1. Clone a project
```
git clone https://github.com/eduardevdokimov746/symfony_rabbitmq_chat.git 
```
2. Go to the project folder
```
cd symfony_rabbitmq_chat
```
3. Install dependencies
```
composer intall
```
4. Copy the env file and redefine the environment variables
```
cp .env .env.local
```
5. Create a database
```
bin/console doctrine:database:create
```
6. Perform migrations
```
bin/console doctrine:migrations:migrate -n
```
7. Upload initial data
```
bin/console doctrine:fixture:load -n
```
## Using

The initial data contains 2 users:
* Bob Bobovich:
> Login: bob
> 
> Password: 123
* Max Maxovich:
> Login: max
> 
> Password: 123

Before you start using the chat, you need to run several queue handler consumers:

1. The consumer responsible for creating the exchange for the chat:
```
bin/console rabbitmq:consumer connect_to_chat
```
2. The consumer responsible for storing messages in the database:
```
bin/console rabbitmq:consumer save_db
```
3. The consumer responsible for logging rejected messages:
```
bin/console rabbitmq:consumer dlx_save_db
```

The interaction diagram can be viewed in [Draw.io](https://app.diagrams.net) by opening the 'chat' file in the root of the project.
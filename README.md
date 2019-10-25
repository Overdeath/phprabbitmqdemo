# PHP RabbitMQ usage demo

These scripts area meant to showcase a basic usecase of RabbitMQ in PHP. 
Running the publisher will publish 1000 messages on the queue and the consumer will consume those messages and output their contents. 
Make sure to edit the files before running and set the parameters of the rabbitmq server (hostname, user, password), the exchange for the publisher, the exchange and the queue for the consumer.
The scripts will create the exchange and queue if they do not exist

## Use case
Install RabbitMQ
Start up the management UI of rabbit mq in one tab.
Run `php composer.phar install` to install the phpamqp library.
Run `php publisher.php` and open the "Queues" tab in the UI notice the messages on the queue. You can use the UI to retrieve a message and either requeue it or discard it.
Run `php worker.php` and notice the messages being consumed appear in the output of the terminal. You can click on the queue in the management UI and notice the rate at which are being consumed.
Open another terminal and open another consumer by running `php worker.php`. You will now notice the rate in the UI has doubled. But also notice that not all messages are processed in order
Install supervisor and manage the workers via a supervisor config

### Caution
The consumer has a usleep command in it to fake processing time, you can change this to increase or decrease the speed of one consumer. 

### Using supervisor to manage consumers 
Included in the repo is a sample configuration file for supervisor that would allow you to run a variable number of workers and configure supervisor to make sure they are always running.
For instructions on how to install supervisor check [the installation guide](http://supervisord.org/installing.html)

### Setting up RabbitMQ on debian/Ubuntu
This is just a sample for ubuntu 18.04, use the instructions on the [official RabbitMQ instalation page](https://www.rabbitmq.com/download.html) instead

```sh
#install erlang
wget -O - 'https://dl.bintray.com/rabbitmq/Keys/rabbitmq-release-signing-key.asc' | sudo apt-key add -
echo "deb http://dl.bintray.com/rabbitmq/debian bionic erlang" | sudo tee /etc/apt/sources.list.d/bintray.erlang.list
sudo apt-get update
sudo apt-get install erlang-nox

# install rabbitmq
wget -O - 'https://dl.bintray.com/rabbitmq/Keys/rabbitmq-release-signing-key.asc' | sudo apt-key add -
echo "deb https://dl.bintray.com/rabbitmq/debian bionic main" | sudo tee /etc/apt/sources.list.d/bintray.rabbitmq.list
sudo apt-get update
sudo apt-get install rabbitmq-server

# enable rabbitmq management ui
sudo rabbitmq-plugins enable rabbitmq_management

# create a test admin user
sudo rabbitmqctl add_user test test
sudo rabbitmqctl set_user_tags test administrator
sudo rabbitmqctl set_permissions -p / test ".*" ".*" ".*"

```


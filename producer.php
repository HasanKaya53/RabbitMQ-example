<?php
require_once(__DIR__ . '/vendor/autoload.php');

const HOST = "localhost";
const PORT = 5672;
const USERNAME = "guest";
const PASSWORD = "guest";
const QUEUE = "message_queue";

$connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(HOST, PORT, USERNAME, PASSWORD);

$channel = $connection->channel();

// create a queue if not exists.
$channel->queue_declare(QUEUE, false, true, false, false, false, null, null);

$jobId = 0;

while (true) {
    // create a job
    $jobData = [
        "id"        => ++$jobId,
        "task"      => "sleep",
        "time"    => rand(0, 3),
        "mail"      => rand(0,9999)."@gmail.com"
    ];
    $job = json_encode($jobData, JSON_UNESCAPED_SLASHES);

    // One of the delivery options. The 'delivery_mode' property specifies the durability of messages. 2 indicates that messages are persistent. In other words, even if the RabbitMQ server is restarted, these messages will not be lost.
    $message = new PhpAmqpLib\Message\AMQPMessage($job,  [
        'delivery_mode'     => 2,
    ]);
    $channel->basic_publish($message, '', RABBITMQ_QUEUE);
    echo '[' . date("Y-m-d H:i:s") . '] Job created!' . PHP_EOL;
    sleep(1);
}
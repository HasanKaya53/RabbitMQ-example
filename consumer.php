<?php
require_once(__DIR__ . '/vendor/autoload.php');

const HOST = "localhost";
const PORT = 5672;
const USERNAME = "guest";
const PASSWORD = "guest";
const QUEUE = "message_queue";

$connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(HOST, PORT, USERNAME, PASSWORD);


$channel = $connection->channel();
$channel->queue_declare(RABBITMQ_QUEUE, false, true, false, false, false, null, null);


echo 'Mesajlar bekleniyor. Çıkmak için CTRL+C' . PHP_EOL;

$worker = function (object $message) {
    try {
        $job = json_decode($message->body, true);
        echo '[-] process... #' . $job['id']." => ".$job["mail"] . PHP_EOL;
        sleep($job['period']);
        //Send mail..

        echo '[+] Finish #' . $job['id']  . PHP_EOL;

        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
    } catch (\Throwable $e) {
        echo '[x] Failed #' . $job['id'] . PHP_EOL;
        $message->delivery_info['channel']->basic_nack($message->delivery_info['delivery_tag']);
    }
};

$channel->basic_qos(null, 1, null);
$channel->basic_consume(RABBITMQ_QUEUE, '', false, false, false, false, $worker);

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();
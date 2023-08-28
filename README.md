<h2>Basic RabbitMQ Usege</h2>
<h4>Installation on Docker</h4>



<h5> First, Install docker image </h5>
<code> docker pull rabbitmq </code>
<h5>Creating and Running the RabbitMQ Container</h5>
<code>docker run -d --name rabbitmqContainer -p 5672:5672 -p 15672:15672 rabbitmq</code>

<h6>If you get the error Ports are not available:</h6>
<b>Checking Port Usage: sudo lsof -i :5672</b>
<p><b>kill -9 PID_NUMBER</b></p>



<h5> Install Composer Package </h5>
<code> composer require php-amqplib/php-amqplib </code>


<h5> Running Consumer and Producer </h5>
<code> php consumer.php </code>
<code> php producer.php </code>




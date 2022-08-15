<?php

use App\Models\User;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Route;
use App\Mail\ApproveLegalRepresentative;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/publish', function () {
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

    $channel = $connection->channel();
    $channel->queue_declare('emails', false, true, false, false);

    $message = new AMQPMessage('mateusgalieta@gmail.com');
    $channel->basic_publish($message, '', 'emails');

    $channel->close();
    $connection->close();
});

Route::get('/consumer', function () {
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

    $channel = $connection->channel();

    $callback = function ($message) {
        Mail::to(User::find(1))
            ->send(new ApproveLegalRepresentative());
    };

    $channel->basic_consume('emails', '', false, true, false, false, $callback);

    while ($channel->is_consuming()) {
        $channel->wait();
    }

    $channel->close();
    $connection->close();
});

<?php



require "vendor/autoload.php";

$access_token = '+yvfpllsF0x8JZDsNrF+PlMOsXi4rj/SU5TIpVfBuXebs9C7hrEiEoc+Ws9V+W/wZsCyNAZNGkH54W9lpTUZ81H1N/90TRmIama6KDuq682PGdN0gwKgc0BaeoyEPBZ8xh4XJtkZ0RySUdhuMWAFKwdB04t89/1O/w1cDnyilFU=';

$channelSecret = '369c8c5d03cea66d8d167ed377f61598';

$pushID = 'U3fc5b043ff752d5b78c9b2f5eb093084';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);

$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('hello world');
$response = $bot->pushMessage($pushID, $textMessageBuilder);

echo $response->getHTTPStatus() . ' ' . $response->getRawBody();








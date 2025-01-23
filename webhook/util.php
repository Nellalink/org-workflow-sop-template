<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

class Util
{
  private $SlackBotToken;
  public $ChannelID;

  public function __construct()
  {
    $environment = 'dev';
    $dotenv = Dotenv::createImmutable(__DIR__, ".env.$environment");
    $dotenv->load();
    $this->SlackBotToken = $_ENV['SLACK_BOT_TOKEN'];
    $this->ChannelID = $_ENV['CHANNEL_ID'];
  }


  public function PayLoad()
  {
    $rst = json_decode(file_get_contents("php://input"));
    $pl = array_merge((array)$rst, $_REQUEST);
    return json_decode(json_encode($pl), true);
  }


  function curlPost($endpoint, $postData, $headers = [])
  {
    $url = "https://slack.com/api/$endpoint";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($headers,['Authorization: Bearer ' . $this->SlackBotToken,'Content-Type: application/json']));
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);

    return [$response,$this->SlackBotToken];
  }




}

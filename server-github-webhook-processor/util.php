<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

class Util
{
  private $SlackBotToken;
  public $ChannelID;

  public function __construct()
  {
    $secrets = $this->ReadConfig();
    $this->SlackBotToken = $secrets['SlackBotToken'];
    $this->ChannelID = $secrets['ChannelID'];
  }


  public function PayLoad()
  {
    $rst = json_decode(file_get_contents("php://input"));
    $pl = array_merge((array)$rst, $_REQUEST);
    return json_decode(json_encode($pl), true);
  }

  public function ReadConfig()
  {
    try {
      $read = file_get_contents(__DIR__ . '/config.json');
      if ($read === false) {
        die("Error reading config.json");
      }
      return json_decode($read, true);
    } catch (\Throwable $th) {
      die($th->getMessage());
    }
  }


  function curlPost($endpoint, $postData, $headers = [])
  {
    $url = "https://slack.com/api/$endpoint";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($headers, ['Authorization: Bearer ' . $this->SlackBotToken, 'Content-Type: application/json']));
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);

    return [$response, $this->SlackBotToken];
  }
}

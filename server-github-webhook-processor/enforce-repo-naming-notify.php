<?php
include "./access.php";
include "./util.php";

class WebHooks extends Util
{
    function __construct()
    {
        parent::__construct();
    }

    public function AuthenticateCall()
    {
        $payload = $this->PayLoad();
        if (isset($payload['data']['text'])) {
            $this->sendMessage($payload);
        }
    }
    public function sendMessage($data)
    {
        try {
            $title = $data['data']['title'] ?? "Failed Test";
            $msg = $data['data']['text'] ?? "new incoming message";
            $repo = $data['repo'] ?? "<missing repository>";
            $format = $data['format'] ?? "`[SUFFIX]-[PROJECT]-[MODULE]`";

            $response = $this->curlPost("chat.postMessage", [
                'channel' => $this->ChannelID,
                'text' => ":x: *{$title}* \n Error: {$msg} \n Repository: `{$repo}` \n Required format: {$format}"
            ]);

            // die(json_encode($response));

        } catch (\Throwable $th) {
            die($th->getMessage());
        }
    }
}

(new WebHooks())->AuthenticateCall();

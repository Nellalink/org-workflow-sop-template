<?php
include "./access.php";
include "./util.php";

class WebHooks extends Util
{
    public $https  = null;

    function __construct()
    {
        parent::__construct();
    }

    public function AuthenticateCall()
    {
        $HOOK_TOKEN = '4a2985c1cbabda189fcafa37ab517827';
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
            $repo = explode('/',$data['repository'])[1] ?? "<missing repository>";
            $format = $data['format'] ?? "`[SUFFIX]-[PROJECT]-[MODULE]`";

            $response = $this->curlPost("chat.postMessage", [
                'channel' => $this->ChannelID,
                'text' => ":warning: {$title} \n Error: {$msg} \n Repository: `{$repo}` \n Required format: {$format}"
            ]);

            // die(json_encode($response));

        } catch (\Throwable $th) {
            die($th->getMessage());
        }
    }
}

(new WebHooks())->AuthenticateCall();

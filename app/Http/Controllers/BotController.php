<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

use Illuminate\Http\Request;

class BotController extends Controller
{

    public function bot(Request $request)
    {
        $data = $request->all();
        //get the user’s id

        // $webhook_event = $data;
        $id = $data["entry"][0]["messaging"][0]["sender"]["id"];
        $msg = $data["entry"][0]["messaging"][0];
        //  $senderMessage = $data["entry"][0]["messaging"][0]["message"];
        $command = "";

        // When bot receive message from user
        if (!empty($msg["message"])) {
            $command = $msg["message"]["text"];
            $this->sendSeen($id);

            $this->handlePostBack($id, "GET_STARTED");
            // When bot receive button click from user
        } else if (!empty($msg["postback"])) {
            $command = $msg["postback"]["payload"];
            $this->sendSeen($id);
            $this->sendTypingOn($id);
            $this->handlePostBack($id, $command);
        }

        /*if (!empty($msg["postback"])) {
            $command = $msg["postback"]["payload"];
        }*/

        // File::append('fb.txt', $msg["postback"]);


    }

    public function handlePostBack($recipientId, $recievedPb)
    {
        $payload = $recievedPb;

        switch ($payload) {
            case "GET_STARTED":
                {
                    $user = $this->getUsername($recipientId);
                    $msg = "  مرحبا بك في صفحتنا باربر برو للحلاقة" . $user;
                    $this->sendTextMessage($recipientId, $msg);
                    $this->sendFirstMessage($recipientId);

                }
                break;

            case "RESTART_CONVERSATION":
                $this->sendFirstMessage($recipientId);
                break;
            case "prendre_rdv":
                $this->sendDays($recipientId);
                break;

            case "today":
                $day = 0;
            case "tomorrow":
                $day = 1;
            case "totomorrow":
                $day = 2;

                $this->sendAttachmentMessage($recipientId, $day);
                break;


        }
    }


    public function sendImageMessage($recipientId, $url)
    {

        $messageData = [
            "recipient" => [
                "id" => $recipientId,

            ],
            "message" => [
                "attachment" => [
                    "type" => "image",
                    "payload" => [
                        "url" => $url,
                        "is_reusable" => true

                    ],
                ],
            ],
        ];

        // dd($messageData);

        $ch = curl_init('https://graph.facebook.com/v6.0/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);
        curl_close($ch);

    }


    public function sendAttachmentMessage($recipientId, $day)
    {


        $messageData = [
            "recipient" => [
                "id" => $recipientId,
            ],
            "message"   => [

                "attachment" => [
                    "type"=>"template",
                    "payload"=> [
                        "template_type"=>"generic",

                        "elements"=>[
                            [
                                "title"=>"كيراتين",
                                "image_url"=>"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSj_EN7-0s8FSw0j9sU6pG35rygpdqF4AIvZi6OLRvnZsU2CbDSyrzXPzEDrVYfN4wV9wk&usqp=CAU",
                                "subtitle"=>"Prix : 1000.00 DA",


                                "default_action"=>[
                                    "type"=>"web_url",
                                    "url"=>"https://tik-barberbot.herokuapp.com/users/crenos",
                                    "messenger_extensions"=> true,
                                    "webview_height_ratio"=>"tall"
                                ],

                                "buttons"=> [
                                    [
                                        "title"=> "احجز موعد الان",
                                        "type"=> "web_url",
                                        "url"=> "https://tik-barberbot.herokuapp.com/users/crenos",
                                        "webview_height_ratio"=> "tall"

                                    ],
                                ],
                            ],
                            [
                                "title"=>"ليسار + سيشوار",
                                "image_url"=>"https://www.mycoupe.fr/wp-content/uploads/2019/07/resultat-naturel-seche-cheveux-pour-homme-1-300x300.jpg",
                                "subtitle"=>"150.00 DA",


                                "default_action"=>[
                                    "type"=>"web_url",
                                    "url"=>"https://tik-barberbot.herokuapp.com/users/crenos",
                                    "messenger_extensions"=> true,
                                    "webview_height_ratio"=>"tall"
                                ],

                                "buttons" => [
                                    [
                                        "title"=> "احجز موعد الان",
                                        "type"=> "web_url",
                                        "url"=> "https://tik-barberbot.herokuapp.com/users/crenos",
                                        "webview_height_ratio"=> "tall"
                                    ],
                                ],
                            ],
                        ],

                    ],
                ],
            ],
        ];

        //dd($messageData);

        $ch = curl_init('https://graph.facebook.com/v6.0/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);
        curl_close($ch);

    }

    public function sendDays($recipientId)
    {

        $messageData = [
            "recipient" => [
                "id" => $recipientId,
            ],
            "message" => [
                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "button",
                        "text" => "اختر يوم موعدك",

                        "buttons" => [
                            [
                                "title" => "اليوم",
                                "type" => "postback",
                                "payload" => "today"
                            ],
                            [
                                "title" => "غدا",
                                "type" => "postback",
                                "payload" => "tomrrow"
                            ],
                            [
                                "title" => "بعد غد",
                                "type" => "postback",
                                "payload" => "totomorrow"
                            ],
                        ],

                    ],
                ],
            ],
        ];

        //dd($messageData);

        $ch = curl_init('https://graph.facebook.com/v6.0/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);
        curl_close($ch);
    }

    public function sendFirstMessage($recipientId)
    {

        $messageData = [
            "recipient" => [
                "id" => $recipientId,
            ],
            "message" => [
                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "button",
                        "text" => "ان كنت لا تعرف كيف تحجز موعد اضغط على طريقة حجز موعد",

                        "buttons" => [
                            [
                                "title" => "حجز موعد",
                                "type" => "postback",
                                "payload" => "prendre_rdv"
                            ],
                            [
                                "title" => "طريقة حجز موعد",
                                "type" => "postback",
                                "payload" => "show_how"
                            ],

                        ],

                    ],
                ],
            ],
        ];

        //dd($messageData);

        $ch = curl_init('https://graph.facebook.com/v6.0/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);
        curl_close($ch);
    }

    public function sendTextMessage($recipientId, $messageText)
    {

        $messageData = [
            "recipient" => [
                "id" => $recipientId,

            ],
            "message" => [
                "text" => $messageText,
            ],
        ];

        //dd($messageData);
        $ch = curl_init('https://graph.facebook.com/v6.0/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);
        curl_close($ch);

    }

    public function sendSuggestedMessage($recipientId)
    {

        $messageData = [
            "recipient" => [
                "id" => $recipientId,

            ],
            "message" => [
                "text" => "i love you",
                "quick_replies" => [
                    [
                        "content_type" => "text",
                        "title" => "red",
                        "payload" => "prendre_rdv",
                    ],
                ],
            ],
        ];

        // dd($messageData);

        $ch = curl_init('https://graph.facebook.com/v6.0/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);
        curl_close($ch);

    }

    private function sendTypingOn($recipientId)
    {
        $messageData = [
            "recipient" => [
                "id" => $recipientId,
            ],
            "sender_action" => "typing_on"
        ];
        $ch = curl_init('https://graph.facebook.com/v6.0/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);
        curl_close($ch);

    }

    private function sendSeen($recipientId)
    {
        $messageData = [
            "recipient" => [
                "id" => $recipientId,
            ],
            "sender_action" => "mark_seen"
        ];
        $ch = curl_init('https://graph.facebook.com/v6.0/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);
        curl_close($ch);

    }


    public function getProfilePicture($recipientId)
    {

        $cURLConnection = curl_init();
        curl_setopt($cURLConnection, CURLOPT_URL, 'https://graph.facebook.com/' . $recipientId . '?fields=profile_pic&access_token=' . env("PAGE_ACCESS_TOKEN"));
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        $usernameJson = curl_exec($cURLConnection);
        curl_close($cURLConnection);
        $jsonArrayResponse = json_decode($usernameJson);
        $profile_picture = $jsonArrayResponse->profile_pic;
        return ($profile_picture);

    }


    public function getUsername($recipientId)
    {

        $cURLConnection = curl_init();
        curl_setopt($cURLConnection, CURLOPT_URL, 'https://graph.facebook.com/' . $recipientId . '?fields=name&access_token=' . env("PAGE_ACCESS_TOKEN"));
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        $usernameJson = curl_exec($cURLConnection);
        curl_close($cURLConnection);
        $jsonArrayResponse = json_decode($usernameJson);
        $name = $jsonArrayResponse->name;
        return ($name);

    }


    public function persistantMenu()
    {
        $message_data = [
            "get_started" => [
                "payload" => "GET_STARTED",

            ],
            "persistent_menu" => [
                [
                    "locale" => "default",
                    "composer_input_disabled" => false,
                    "call_to_actions" => [
                        [
                            "type" => "postback",
                            "title" => "احجز موعد",
                            "payload" => "prendre_rdv"],
                        [
                            "type" => "postback",
                            "title" => "رصيدي و مواعيدي",
                            "payload" => "myrdvs"],
                        [
                            "type" => "postback",
                            "title" => "اتصل بالمبرمج",
                            "payload" => "call_dev"],
                        [
                            "type" => "postback",
                            "title" => "كيف احجز موعد",
                            "payload" => "show_how"],
                    ]
                ],

            ]];


        //dd($messageData);
        $ch = curl_init('https://graph.facebook.com/v10.0/me/messenger_profile?access_token=' . env("PAGE_ACCESS_TOKEN"));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message_data));
        curl_exec($ch);
        curl_close($ch);
    }

    // or when your server returns json
    // $content = json_decode($response->getBody(), true);

}

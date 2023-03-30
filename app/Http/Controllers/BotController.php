<?php

namespace App\Http\Controllers;

use App\Coiffure;
use App\Settings;
use App\Weekday;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Object_;

class BotController extends Controller
{
    public function __construct()
    {
    }




    public function bot(Request $request)
    {

        $data = $request->all();
        //get the user’s id

        // $webhook_event = $data;
        $id = $data["entry"][0]["messaging"][0]["sender"]["id"];
        $msg = $data["entry"][0]["messaging"][0];
        $this->persistantMenu();
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
            $this->handlePostBack($id,$command);

        }

    }

    public function checkWeekend($day)
    {
       
    }

    public function handlePostBack($recipientId, $recievedPb)
    {
        $payload = $recievedPb;

        switch ($payload) {
            case "GET_STARTED":
                {
                    $user = $this->getUsername($recipientId);
                    $msg = "\n 💈 💈 ✂ ⚜ مرحبا بك في صفحتنا باربر فينتاج للحلاقة العصرية";
                    $this->sendTextMessage($recipientId, $msg);
                    $this->sendTextMessage($recipientId, $user . " 😍");
                    $this->sendFirstMessage($recipientId);

                }
                break;

            case "RESTART_CONVERSATION":
                $this->sendFirstMessage($recipientId);
                break;
            case "prendre_rdv":
                $this->send3Days($recipientId);
                break;

            case "today":
                {
                    $day = 0;
                    $this->sendAttachmentMessage($recipientId, $day);
                }
                break;
            case "tmrw":
                {
                    $day = 1;
                   // $this->sendTextMessage($recipientId,"hi");
                    $this->sendAttachmentMessage($recipientId, $day);
                }
                break;
            case "ttmrw":
                {
                    $day = 2;
                    $this->sendAttachmentMessage($recipientId, $day);
                    //$this->sendTextMessage($recipientId,"hi");
                }
                break;

            case "myrdv":
                {
                    //$this->sendTextMessage($recipientId,"hi");
                    $this->sendRdv($recipientId);
                }
                break;
            case "show_how":
            {
                $this->sendTextMessage($recipientId,"https://www.youtube.com/watch?v=PFc2zzQERqU");
            }
            break;

            case "call_dev":
            {
                $this->sendTextMessage($recipientId,"https://facebook.com/taki729");
            }break;

        }
    }


    public function filterDays($recipientId){
        /*
        $client = new ClientController();
        $params = $client->getParams();


        $weekends = Weekday::all();

       if( ! $weekends == null){


           $date = Carbon::now()->format("Y-m-d");
           $day = $this->translateDay(Carbon::createFromFormat('Y-m-d',$date)->format('l'));

           $todayWeek = DB::select('select distinct H_debut,H_fin from weekdays where day ='.$day.' or date='.$date);
           if(! $todayWeek == null ){
               if($todayWeek[0]->H_debut<=$params->H_but || $todayWeek[0]->H_fin>=$params->H_fin){
                   $this->send2days($recipientId);
               }else{
                   $this->send3Days($recipientId);
               }
           }else{
               $this->send3Days($recipientId);
           }


           $date = Carbon::tomorrow()->format("Y-m-d");
           $day = $this->translateDay(Carbon::createFromFormat('Y-m-d',$date)->format('l'));
           $tomorrowWeek = DB::select('select distinct H_debut,H_fin from weekdays where day ='.$day.' or date='.$date);
           if(! $tomorrowWeek == null ){
               if($tomorrowWeek[0]->H_debut<=$params->H_but || $tomorrowWeek[0]->H_fin>=$params->H_fin){
                   $this->send2day2toMorrow($recipientId);
               }else{
                   $this->send3Days($recipientId);
               }
           }else{
               $this->send3Days($recipientId);
           }


           $date = Carbon::tomorrow()->addDay(1)->format("Y-m-d");
           $day = $this->translateDay(Carbon::createFromFormat('Y-m-d',$date)->format('l'));
           $totomorrow = DB::select('select distinct H_debut,H_fin from weekdays where day ='.$day.' or date='.$date);
           if(! $totomorrow == null ){
               if($totomorrow[0]->H_debut<=$params->H_but || $totomorrow[0]->H_fin>=$params->H_fin){
                   $this->send2daysTodayTomorrow($recipientId);
               }else{
                   $this->send3Days($recipientId);
               }
           }else{
               $this->send3Days($recipientId);
           }

       }else{
           $this->send3Days($recipientId);
       }


      */

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

        $ch = curl_init('https://graph.facebook.com/v11.0/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);
        curl_close($ch);

    }

    public function sendVideoMessage($recipientId, $url)
    {

        $messageData = [
            "recipient" => [
                "id" => $recipientId,

            ],
            "message" => [
                "attachment" => [
                    "type" => "video",
                    "payload" => [
                        "url" => 'https://web.facebook.com/fox.ta3loub/videos/346543706826764',
                        "is_reusable" => true

                    ],
                ],
            ],
        ];

        // dd($messageData);

        $ch = curl_init('https://graph.facebook.com/v11.0/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);
        curl_close($ch);

    }

    public function sendAttachmentMessage($recipientId,$day)
    {

        $elements = $this->getCoiffures($recipientId,$day);

        $messageData = [
            "recipient" => [
                "id" => $recipientId,
            ],
            "message" => [

                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "generic",

                        "elements" =>$elements /*[
                            [
                                "title" => "ديقرادي",
                                "image_url" => "https://taki.berrehal.xyz/coiffures/1.jpg",
                                "subtitle" => "250.00 DA",


                                "default_action" => [
                                    "type" => "web_url",
                                    "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/1/$day",
                                    "messenger_extensions" => true,
                                    "webview_height_ratio" => "tall",
                                    "webview_share_button"=>"hide"

                                ],

                                "buttons" => [
                                    [
                                        "title" => "احجز موعد الان",
                                        "type" => "web_url",
                                        "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/1/$day",
                                        "webview_height_ratio" => "tall",
                                    "webview_share_button"=>"hide"
                                    ],
                                ],
                            ],
                            [
                                "title" => "ليسار + قصة شعر",
                                "image_url" => "https://taki.berrehal.xyz/coiffures/2.jpg",
                                "subtitle" => "400.00 DA",


                                "default_action" => [
                                    "type" => "web_url",
                                    "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/2/$day", //"https://taki.berrehal.xyz/users/crenos/$recipientId/2/$day",
                                    "messenger_extensions" => true,
                                    "webview_height_ratio" => "tall",
                                    "webview_share_button"=>"hide"
                                ],

                                "buttons" => [
                                    [
                                        "title" => "احجز موعد الان",
                                        "type" => "web_url",
                                        "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/2/$day",
                                        "webview_height_ratio" => "tall",
                                    "webview_share_button"=>"hide"
                                    ],
                                ],
                            ],
                            [
                                "title" => "كيراتين + قصة شعر",
                                "image_url" => "https://taki.berrehal.xyz/coiffures/3.jpg",
                                "subtitle" => "3000.00 DA",


                                "default_action" => [
                                    "type" => "web_url",
                                    "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/3/$day",
                                    "messenger_extensions" => true,
                                    "webview_height_ratio" => "tall",
                                    "webview_share_button"=>"hide"
                                ],

                                "buttons" => [
                                    [
                                        "title" => "احجز موعد الان",
                                        "type" => "web_url",
                                        "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/3/$day",
                                        "webview_height_ratio" => "tall",
                                    "webview_share_button"=>"hide"
                                    ],
                                ],
                            ],
                            [
                                "title" => "لحية",
                                "image_url" => "https://taki.berrehal.xyz/coiffures/4.jpg",
                                "subtitle" => "150.00 DA",


                                "default_action" => [
                                    "type" => "web_url",
                                    "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/4/$day",
                                    "messenger_extensions" => true,
                                    "webview_height_ratio" => "tall",
                                    "webview_share_button"=>"hide"
                                ],

                                "buttons" => [
                                    [
                                        "title" => "احجز موعد الان",
                                        "type" => "web_url",
                                        "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/4/$day",
                                        "webview_height_ratio" => "tall",
                                    "webview_share_button"=>"hide"
                                    ],
                                ],
                            ],
                            [
                                "title" => "كيراتين",
                                "image_url" => "https://taki.berrehal.xyz/coiffures/5.jpg",
                                "subtitle" => "1500.00 DA",


                                "default_action" => [
                                    "type" => "web_url",
                                    "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/5/$day",
                                    "messenger_extensions" => true,
                                    "webview_height_ratio" => "tall",
                                    "webview_share_button"=>"hide"
                                ],

                                "buttons" => [
                                    [
                                        "title" => "احجز موعد الان",
                                        "type" => "web_url",
                                        "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/5/$day",
                                        "webview_height_ratio" => "tall",
                                    "webview_share_button"=>"hide"
                                    ],
                                ],
                            ],
                            [
                                "title" => "VIP تسريحة",
                                "image_url" => "https://taki.berrehal.xyz/coiffures/6.jpg",
                                "subtitle" => "3500.00 DA",


                                "default_action" => [
                                    "type" => "web_url",
                                    "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/6/$day",
                                    "messenger_extensions" => true,
                                    "webview_height_ratio" => "tall",
                                    "webview_share_button"=>"hide"
                                ],

                                "buttons" => [
                                    [
                                        "title" => "احجز موعد الان",
                                        "type" => "web_url",
                                        "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/6/$day",
                                        "webview_height_ratio" => "tall",
                                    "webview_share_button"=>"hide"
                                    ],
                                ],
                            ],
                            [
                                "title" => "ليسار + سيشوار",
                                "image_url" => "https://taki.berrehal.xyz/coiffures/7.jpg",
                                "subtitle" => "150.00 DA",


                                "default_action" => [
                                    "type" => "web_url",
                                    "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/7/$day",
                                    "messenger_extensions" => true,
                                    "webview_height_ratio" => "tall",
                                    "webview_share_button"=>"hide"
                                ],

                                "buttons" => [
                                    [
                                        "title" => "احجز موعد الان",
                                        "type" => "web_url",
                                        "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/7/$day",
                                        "webview_height_ratio" => "tall",
                                    "webview_share_button"=>"hide"
                                    ],
                                ],
                            ],
                        ],*/

                    ],
                ],
            ],
        ];

        //dd($messageData);

        $ch = curl_init('https://graph.facebook.com/v11.0/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);
        curl_close($ch);

    }


    public function sendRdv($recipientId)
    {
        $url = env('APP_URL').'/myrdv/'.$recipientId;


        $messageData = [
            "recipient" => [
                "id" => $recipientId,
            ],
            "message" => [

                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "generic",

                        "elements" => [
                            [
                                "title" => "🏆 رصيدي و مواعيدي",
                                "image_url" => $this->getProfilePicture($recipientId),
                                "subtitle" => "",


                                "default_action" => [
                                    "type" => "web_url",
                                    "url" => $url,
                                    "messenger_extensions" => true,
                                    "webview_height_ratio" => "tall",
                                    "webview_share_button"=>"hide"

                                ],

                                "buttons" => [
                                    [
                                        "title" => "🎁 تفقد مواعيدك - رصيدك 🕔",
                                        "type" => "web_url",
                                        "url" => "https://taki.berrehal.xyz/myrdv/$recipientId",
                                        "webview_height_ratio" => "tall",
                                        "webview_share_button"=>"hide"
                                    ],
                                ],
                            ],

                        ],

                    ],
                ],
            ],
        ];

        //dd($messageData);

        $ch = curl_init('https://graph.facebook.com/v11.0/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);
        curl_close($ch);

    }

    public function sendTest($reciepientId){
        $attachment_url = "https://tikbarber.herokuapp.com/img.jpg";
        $messageData = [
            "recipient" => [
                "id" => $reciepientId,
            ],
            "message" => [
                "attachment"=>[
                    "type"=>"template",
                    "payload"=>[
                        "template_type"=>"generic",
                        "elements"=>[
                            [
                                "title"=>"Is this the right picture?",
                                "subtitle"=>"Tap a button to answer.",
                                "image_url"=>$attachment_url,
                                "buttons"=>[
                                    [
                                        "type"=>"postback",
                                        "title"=> "Yes!",
                                        "payload"=> "yes",
                                    ]
                                ]

                            ]
                        ]
                    ]
                ]
            ],
        ];

        //dd($messageData);

        $ch = curl_init('https://graph.facebook.com/v11.0/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);
        curl_close($ch);
    }


    public function send3Days($recipientId)
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
                        "text" => "📅 اختر يوم موعدك",

                        "buttons" => [
                            [
                                "title" => " ⏰ اليوم",
                                "type" => "postback",
                                "payload" => "today"
                            ],
                            [
                                "title" => "⏰ غدا",
                                "type" => "postback",
                                "payload" => "tmrw"
                            ],
                             [
                                "title" => "⏰ بعد غد",
                                "type" => "postback",
                                "payload" => "ttmrw"
                            ],
                           
                        ],

                    ],
                ],
            ],
        ];

        //dd($messageData);

        $ch = curl_init('https://graph.facebook.com/v11.0/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);
        curl_close($ch);
    }

    public function sendCancelMsg($recipientId)
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
                        "text" => "✅ مبروك لقد تم حجز موعدك بنجاح شكرا على ثقتك 🙏",

                        "buttons" => [
                            [
                                "title" => "تصفح مواعيدي",
                                "type" => "web_url",
                                "url" => "https://taki.berrehal.xyz/myrdv/$recipientId",
                                "webview_height_ratio" => "tall",
                                "webview_share_button"=>"hide"
                            ],

                        ],

                    ],
                ],
            ],
        ];

        //dd($messageData);

        $ch = curl_init('https://graph.facebook.com/v11.0/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
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
                        "text" => "📅 💈 ان كنت لا تعرف كيف تحجز موعد اضغط على طريقة حجز موعد",

                        "buttons" => [
                            [
                                "title" => "📅 حجز موعد",
                                "type" => "postback",
                                "payload" => "prendre_rdv"
                            ],
                            [
                                "title" => "🎥 طريقة حجز موعد",
                                "type" => "postback",
                                "payload" => "show_how"
                            ],

                        ],

                    ],
                ],
            ],
        ];

        //dd($messageData);

        $ch = curl_init('https://graph.facebook.com/v11.0/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
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
        $ch = curl_init('https://graph.facebook.com/v11.0/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
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

        $ch = curl_init('https://graph.facebook.com/v11.0/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
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
        $ch = curl_init('https://graph.facebook.com/v11.0/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
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
        $ch = curl_init('https://graph.facebook.com/v11.0/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
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

    public function myrdv($recipientId){

        $messageData = [
            "recipient" => [
                "id" => $recipientId,
            ],
            "message" => [

                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "generic",

                        "elements" => [

                            [
                                "title" => "🏆 رصيدي و مواعيدي",
                                "image_url" => "",
                                "subtitle" => "",


                                "default_action" => [
                                    "type" => "web_url",
                                    "url" => "https://taki.berrehal.xyz/myrdv/$recipientId", //"https://taki.berrehal.xyz/users/crenos/$recipientId/2/$day",
                                    "messenger_extensions" => true,
                                    "webview_height_ratio" => "tall",
                                    "webview_share_button"=>"hide"
                                ],

                                "buttons" => [
                                    [
                                        "title" => "🎁 تفقد مواعيدك - رصيدك 🕔",
                                        "type" => "web_url",
                                        "url" => "https://taki.berrehal.xyz/myrdv/$recipientId",
                                        "webview_height_ratio" => "tall",
                                        "webview_share_button"=>"hide"
                                    ],
                                ],
                            ],

                        ],

                    ],
                ],
            ],
        ];
        $ch = curl_init('https://graph.facebook.com/v11.0/me/messenger_profile?access_token=' . env("PAGE_ACCESS_TOKEN"));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);
        curl_close($ch);

    }

    public function persistantMenu()
    {
        $appurl = env('APP_URL');
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
                            "title" => "📅 احجز موعد",
                            "payload" => "prendre_rdv"],
                        [
                            "type" => "postback",
                            "title" => "🏆 رصيدي و مواعيدي",
                            "payload" => "myrdv"],
                        [
                            "type" => "postback",
                            "title" => "📲 اتصل بالمبرمج",
                            "payload" => "call_dev"],
                        [
                            "type" => "postback",
                            "title" => "🎥 كيف احجز موعد",
                            "payload" => "show_how"],
                    ]
                ],

            ],
            "whitelisted_domains"=> [
                "https://www.google.com/",
                $appurl
    ]
        ];


        //dd($messageData);
        $ch = curl_init('https://graph.facebook.com/v11.0/me/messenger_profile?access_token=' . env("PAGE_ACCESS_TOKEN"));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message_data));
        curl_exec($ch);
        curl_close($ch);
    }

    public function getCoiffures($recipientId,$day){

        $coiffures = Coiffure::all();
        $elements = [];
        foreach ($coiffures as $c){
            $elem = [
                "title" => $c->nom,
                "image_url" => env('APP_URL').'/storage/product/'.$c->photo ,
                "subtitle" => $c->prix .'.00DA',
                "default_action" => [
                    "type" => "web_url",
                    "url" => env('APP_URL')."/users/crenos/".$recipientId.'/'.$c->id.'/'.$day,
                    "messenger_extensions" => true,
                    "webview_height_ratio" => "tall",
                    "webview_share_button"=>"hide"

                ]
            ];
            array_push($elements,$elem);
        }

        return $elements;
    }




// or when your server returns json
    // $content = json_decode($response->getBody(), true);

}

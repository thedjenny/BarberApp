<?php

namespace App\Http\Controllers;

use App\Settings;
use App\Weekday;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use Illuminate\Http\Request;

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
            $this->sendAttachmentMessage($id);

            $this->sendTextMessage($id,"hi");
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
                    $msg = "\n 💈 💈 ✂ ⚜ مرحبا بك في صفحتنا باربر برو للحلاقة";
                    $this->sendTextMessage($recipientId, $msg);
                    $this->sendTextMessage($recipientId, $user . " 😍");
                    $this->sendFirstMessage($recipientId);

                }
                break;

            case "RESTART_CONVERSATION":
                $this->sendFirstMessage($recipientId);
                break;
            case "prendre_rdv":
                $this->filterDays($recipientId);
                break;

            case "today":
                {
                    $day = 0;
                    $this->sendAttachmentMessage($recipientId, $day);
                }
                break;
            case "tomorrow":
                {
                    $day = 1;
                    $this->sendAttachmentMessage($recipientId, $day);
                }
                break;
            case "totomorrow":
                {
                    $day = 2;
                    $this->sendAttachmentMessage($recipientId, $day);
                }
                break;

            case "myrdv":
                {
                    $this->myrdv($recipientId);
                }
                break;
            case "show_how":
            {
                $this->sendVideoMessage($recipientId,"https://youtube.com/watch?v=cHCUomfPHZM");
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
    public function send2days($id){

    }
    public function send2daysTodayTomorrow($id){

    }

    public function translateDay($day){
        switch ($day){
            case "Saturday":
                $day="Samedi";break;
            case "Sunday":
                $day="Dimanche";break;
            case "Monday":
                $day="Lundi";break;
            case "Tuesday":
                $day = "Mardi";break;
            case "Wednesday":
                $day = "Mercredi";break;
            case "Thursday":
                $day = "Jeudi";break;
            case "Friday":
                $day = "Vendredi";break;
            default:
                return view('error');
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
                        "url" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSuRBLJ4t1pihoYjY4ozK0cJzTIV2GqPlWT2i7Kkghu5zS5NtyRtWY4QniVWOJS28e_NHM&usqp=CAU",
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

        $ch = curl_init('https://graph.facebook.com/v6.0/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);
        curl_close($ch);

    }

    public function sendAttachmentMessage($recipientId)
    {

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
                                "title" => "ديقرادي",
                                "image_url" => "https://tikbarber.herokuapp.com/img.jpg",
                                "subtitle" => "250.00 DA",


                                "default_action" => [
                                    "type" => "web_url",
                                    "url" => "https://tikbarber.herokuapp.com",
                                    "messenger_extensions" => true,
                                    "webview_height_ratio" => "tall"
                                ],

                                "buttons" => [
                                    [
                                        "title" => "احجز موعد الان",
                                        "type" => "web_url",
                                        "url" => "https://tikbarber.herokuapp.com",
                                        "webview_height_ratio" => "tall"
                                    ],
                                ],
                            ],/*
                            [
                                "title" => "ليسار + قصة شعر",
                                "image_url" => "https://www.biblond.com/wp-content/uploads/2015/09/55f67d62979f5.jpg",
                                "subtitle" => "400.00 DA",


                                "default_action" => [
                                    "type" => "web_url",
                                    "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/2/$day",
                                    "messenger_extensions" => true,
                                    "webview_height_ratio" => "tall"
                                ],

                                "buttons" => [
                                    [
                                        "title" => "احجز موعد الان",
                                        "type" => "web_url",
                                        "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/2/$day",
                                        "webview_height_ratio" => "tall"
                                    ],
                                ],
                            ],
                            [
                                "title" => "كيراتين + قصة شعر",
                                "image_url" => "https://blogscdn.thehut.net/wp-content/uploads/sites/32/2018/06/15172422/1200x672_240517197_MC_MK_Blog_Imagery_June_Image13_1200x672_acf_cropped.jpg",
                                "subtitle" => "3000.00 DA",


                                "default_action" => [
                                    "type" => "web_url",
                                    "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/3/$day",
                                    "messenger_extensions" => true,
                                    "webview_height_ratio" => "tall"
                                ],

                                "buttons" => [
                                    [
                                        "title" => "احجز موعد الان",
                                        "type" => "web_url",
                                        "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/3/$day",
                                        "webview_height_ratio" => "tall"
                                    ],
                                ],
                            ],
                            [
                                "title" => "لحية",
                                "image_url" => "https://www.barb-art.fr/blog/wp-content/uploads/2019/02/brosse-barbe-1.jpg",
                                "subtitle" => "150.00 DA",


                                "default_action" => [
                                    "type" => "web_url",
                                    "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/4/$day",
                                    "messenger_extensions" => true,
                                    "webview_height_ratio" => "tall"
                                ],

                                "buttons" => [
                                    [
                                        "title" => "احجز موعد الان",
                                        "type" => "web_url",
                                        "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/4/$day",
                                        "webview_height_ratio" => "tall"
                                    ],
                                ],
                            ],
                            [
                                "title" => "كيراتين",
                                "image_url" => "https://rukminim1.flixcart.com/image/416/416/kmz7qfk0/hair-treatment/c/u/w/1000-keratin-nourishing-hair-mask-tmt-original-imagfrbpacvz5e3d.jpeg",
                                "subtitle" => "1500.00 DA",


                                "default_action" => [
                                    "type" => "web_url",
                                    "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/5/$day",
                                    "messenger_extensions" => true,
                                    "webview_height_ratio" => "tall"
                                ],

                                "buttons" => [
                                    [
                                        "title" => "احجز موعد الان",
                                        "type" => "web_url",
                                        "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/5/$day",
                                        "webview_height_ratio" => "tall"
                                    ],
                                ],
                            ],
                            [
                                "title" => "VIP تسريحة",
                                "image_url" => "https://d2zdpiztbgorvt.cloudfront.net/region1/us/105344/biz_photo/67f9a2cbc5084feaa3f31dd277400c-vip-barber-lounge-llc-biz-photo-98f1fc544ec5478eb7165636ed69f8-booksy.jpeg",
                                "subtitle" => "3500.00 DA",


                                "default_action" => [
                                    "type" => "web_url",
                                    "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/6/$day",
                                    "messenger_extensions" => true,
                                    "webview_height_ratio" => "tall"
                                ],

                                "buttons" => [
                                    [
                                        "title" => "احجز موعد الان",
                                        "type" => "web_url",
                                        "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/6/$day",
                                        "webview_height_ratio" => "tall"
                                    ],
                                ],
                            ],
                            [
                                "title" => "ليسار + سيشوار",
                                "image_url" => "https://www.anoos.com/assets/img/hair-straight.jpg",
                                "subtitle" => "150.00 DA",


                                "default_action" => [
                                    "type" => "web_url",
                                    "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/7/$day",
                                    "messenger_extensions" => true,
                                    "webview_height_ratio" => "tall"
                                ],

                                "buttons" => [
                                    [
                                        "title" => "احجز موعد الان",
                                        "type" => "web_url",
                                        "url" => "https://taki.berrehal.xyz/users/crenos/$recipientId/7/$day",
                                        "webview_height_ratio" => "tall"
                                    ],
                                ],
                            ],*/
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

    public function sendTestAtt($recipientId){

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
                                "payload" => "tomrrow"
                            ],
                            [
                                "title" => "⏰ بعد غد",
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
                        "text" => "مبروك لقد تم حجز موعدك بنجاح شكرا على ثقتك 🙏",

                        "buttons" => [
                            [
                                "title" => "تصفح مواعيدي",
                                "type" => "web_url",
                                "url" => "https://5f26fa99f86a.ngrok.io/myrdv/$recipientId",
                                "webview_height_ratio" => "tall"
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

    public function myrdv($recipientId){


        $messageData = [
            "recipient" => [
                "id" => $recipientId,
            ],
            "message" => [
                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "web_url",
                                "title" => "🏆 رصيدي و مواعيدي",
                                "url" => "https://5f26fa99f86a.ngrok.io/myrdv/$recipientId",
                                "webview_height_ratio" => "tall"


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
                            "title" => "📅 22احجز موعد",
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
                "https://www.barbededarwin.fr",
                "https://www.yahoo.com",
                "https://tikbarber.herokuapp.com"
    ]
        ];


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

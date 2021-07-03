<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Client;
use App\Rdv;
use DB;
use App\Http\Controllers\BotController;
use function Sodium\add;


class ClientController extends Controller
{
    public function __construct()
    {
    }

    //traitement RDVS
   public function workTime($param , $begin )
    {
        $CompletTime = array ();// Define output
        if($begin === null)
            $StartTime    = strtotime ("10:00");
        else
            $StartTime    = strtotime ($begin);

        $EndTime      = strtotime ("20:00");
        $lunchTime = strtotime("13:00");
        $backTime = strtotime("14:00");
        $AddMins = $param * 60;
        $next = strtotime("00:00");
    
        while ($StartTime < $lunchTime) //Run loop
        {
            $next = $StartTime + $AddMins;
            if($next<=$lunchTime){
                $CompletTime[] = date ("G:i", $StartTime);
            }

            $StartTime = $StartTime + $AddMins;
        }


        if($backTime>$StartTime){

            while($backTime<$EndTime){
                $next = $backTime + $AddMins;

                if($next<=$EndTime){
                    $CompletTime[] = date ("G:i", $backTime);
                }

                $backTime = $backTime + $AddMins; //Endtime check
            }
        }else{

            while($StartTime<$EndTime){
                    $next = $StartTime + $AddMins;
                    if($next<=$EndTime){
                        $CompletTime[] = date ("G:i", $StartTime);
                    }

                    $StartTime = $StartTime + $AddMins;


                //Endtime check
            }
        }
       
        

       return($CompletTime);
        
    }

    public function timeSpliter($beg , $param)
    {
        $CompletTime = array ();// Define output
        $StartTime    = strtotime ($beg); //Get Timestamp
        $EndTime      = strtotime ($beg) + ($param*60); //Get Timestamp
        $AddMins  = 15 * 60;

      
        while($StartTime<$EndTime){
            $CompletTime[] = date ("G:i", $StartTime);
            $StartTime = $StartTime + $AddMins; //Endtime check
        }

        
      
       return($CompletTime);
    }

    public function getCrenos($id,$type,$day)
    {

        $date = "";
        $takenCr = [];
        $del = [];
        $exist = false;

        switch ($day){
            case "0":{
                $mytime = Carbon::now();
                $date = $mytime->toDateString();
            }break;
            case "1":{
                $tomorrow = Carbon::tomorrow();
                $date = $tomorrow->toDateString();
            }break;
            case "2":{
                $totomorrow = Carbon::now()->addDays(2);
                $date=$totomorrow->toDateString();
            }break;
            default:{
                return view('error');
            }
        }

        if($day == "0"){

            $min = Carbon::now()->minute;
            $beg = Carbon::now();

           switch ($min){
                case ($min > 0 && $min<=15) :
                    $beg->addMinutes(15-$min);break;
                case ($min>15 && $min<=30):
                    $beg->addMinutes(30-$min);break;
                case($min>30 && $min<=45):
                    $beg->addMinutes(45-$min);break;
               case($min>45 && $min<60):
                   $beg->addMinutes(60-$min);break;
               default : $beg;

            }

            switch($type){

                case "1":{
                    $coiffure = "degrade";
                    $creno = $this->workTime(30,$beg->format("H:i"));

                }break;

                case "2":{
                    $coiffure = "lisseur+coiffure";
                    $creno = $this->workTime(45,$beg->format("H:i"));
                }break;

                case "3":{
                    $coiffure = "degrade + kiratin";
                    $creno = $this->workTime(60,$beg->format("H:i"));
                }break;

                case "4":{
                    $coiffure = "barbe";
                    $creno = $this->workTime(15,$beg->format("H:i"));
                }break;

                case "5" :{
                    $coiffure = "kiratin simple";
                    $creno = $this->workTime(60,$beg->format("H:i"));
                }break;

                case "6":{
                    $coiffure = "VIP";
                    $creno = $this->workTime(90,$beg->format("H:i"));

                }break;

                case "7":{
                    $coiffure = "Lisseur/Sechoir";
                    $creno = $this->workTime(15,$beg->format("H:i"));
                }break;

                default: {
                    return view('error');
                }


            }
        }else
            {          switch($type){

                case "1":{
                    $coiffure = "degrade";
                            $creno = $this->workTime(30,null);

                        }break;

                        case "2":{
                            $coiffure = "lisseur+coiffure";
                            $creno = $this->workTime(45,null);
                        }break;

                        case "3":{
                            $coiffure = "degrade + kiratin";
                            $creno = $this->workTime(60,null);
                        }break;

                        case "4":{
                            $coiffure = "barbe";
                            $creno = $this->workTime(15,null);
                        }break;

                        case "5" :{
                            $coiffure = "kiratin simple";
                            $creno = $this->workTime(60,null);
                        }break;

                        case "6":{
                            $coiffure = "VIP";
                            $creno = $this->workTime(90,null);
                        }break;

                        case "7":{
                            $coiffure = "Lisseur/Sechoir";
                            $creno = $this->workTime(15,null);
                        }break;

                        default : {
                            return view('error');
                        }

                    }
                    }


        $takenCrSQL = Rdv::select('time','idClient')
                        -> where('date','=',$date)->get();


        foreach ($takenCrSQL as $tk){

            if($tk["idClient"]===$id){
                $exist = true;
            }
           // $myt = Carbon::createFromTime($tk["time"])->format("H:i");
            $myt = strtotime($tk["time"]);

            for ($i = 0; $i < count($creno); $i++) {
             if($myt >= strtotime($creno[$i])  &&  $myt< strtotime($creno[$i+1])){
                    array_push($del,date("G:i",strtotime($creno[$i])));
                }

        }

        }


        $crs = array_diff($creno,$del);
        $crs = array_diff($crs,$takenCr);
      //rani hna
        return view('crenos')->with('data',[
            'crns'=>$crs,
            'id'=>$id,
            'type'=>$type,
            'date'=>$date,
            'exist'=>$exist]);

    }
    ////////////////////////////////////////////

    public function  reserver(Request $request) //Reservation Min time
    {
        $id = $request->userid;
        $day = $request->date;
        $type = $request->type;

        $bot = new BotController();

        $user = Client::where('idClient', '=', $id)->first();
        if ($user === null) {
            Client::create(array(
                'idClient' => $id,
                'username'=> $bot->getUsername($id),
                 'profile_picture' => $bot->getProfilePicture($id) ,
                 'points'=>0
            ));
            $user = Client::where('idClient', '=', $id)->first();
        
        }


        $beg = $request ->creno;


        $creno [] = array();
        $coiffure = "";
       // $points = $user->points;


        switch($type){

            case "1":{
                $coiffure = "degrade";
               // $points = $points + 30;
                $creno = $this->timeSpliter($beg,30);
            }break;
            
            case "2":{
                $coiffure = "lisseur+coiffure";
               // $points = $points + 40;
                $creno = $this->timeSpliter($beg,45);
            }break;

            case "3":{
                $coiffure = "degrade + kiratin";
              //  $points = $points + 100;
                $creno = $this->timeSpliter($beg,60);
            }break;
            
            case "4":{
                $coiffure = "barbe";
              //  $points = $points + 10;
                $creno = $this->timeSpliter($beg,15);
            }break;

            case "5" :{
                $coiffure = "kiratin simple";
               // $points = $points + 80;
                $creno = $this->timeSpliter($beg,60);
            }break;

            case "6":{
                $coiffure = "VIP";
              //  $points = $points + 150;
                $creno = $this->timeSpliter($beg,90);
            }break;

            case "7":{
                $coiffure = "Lisseur/Sechoir";
              //  $points = $points + 15;
                $creno = $this->timeSpliter($beg,15);
            }break;

            default :{
                return view('error');
            }
            
        }
/*
        Client::where('idClient','=', $id)
            ->update([
                'points' => $points
            ]);*/

        
        foreach($creno as $cr){
        
            Rdv::create(array(
                'idClient' => $id,
                'date'=>$day,
                 'coiffure' => $coiffure,
                 'time'=>$cr
            ));

        }

        $mycreno = $creno[0];

       $bot->sendTextMessage($id,"مبروك لقد تم حجز موعدك بنجاح");
        $bot->handlePostBack($id,"myrdv");
        return redirect(route('myrdv',$id));
//         return view('users.myrdv')->with('data',[
//            'mycreno'=>$mycreno,
//            'user'=>$user]);

    }


    public function getParams(){
       $params = DB::select('select H_debut,H_pause,H_retour,H_fin from settings');
       return $params;
    }

    public function myRdv($id)
    {
        $today = Carbon::now()->format("Y-m-d");
        $time = Carbon::now()->format("H:i");
        $bool = false;
        $user = Client::where('idClient', '=', $id)->first();


        if($user === null)
        {
            return redirect()->back();
        }else{
            $rdv = Rdv::where('idClient','=',$id)->first();

            if(! $rdv == null){
            if($today <= strtotime($rdv["date"]) && $time<strtotime($rdv["time"])){
                $bool = true;
            }
            }
            return view('users.myrdv')->with('data',[
                'rdv'=>$rdv,
                'bool'=>$bool,
                'user'=>$user
            ]);

        }
    }

    public function cancelRdv(Request $request){
       $id = $request->id;
       $date = $request->date;
       $pts = 0;
        $client = Client::select('points')-> where('idClient','=',$id)->get();
        $coiffure = Rdv::select('coiffure')->where('idClient',"=",$id)->get();
        $coiffure = $coiffure[0]['coiffure'];
        switch($coiffure){
            case "degrade":
                $pts = 30;break;
            case "lisseur+coiffure" :
                $pts = 40;break;
            case "degrade + kiratin" :
                $pts = 100;break;
            case "barbe" :
                $pts = 10;break;
            case "kiratin simple":
                $pts = 80;break;
            case "VIP":
                $pts = 150;break;
            case "Lisseur/Sechoir":
                $pts = 15;break;
            default :
                $pts = 0;break;

        }

        $newP = $client[0]["points"] - $pts;

        Client::where('idClient','=', $id)
            ->update([
                'points' => $newP
            ]);

        DB::table('Rdvs')->where('idClient', $id)->where('date',$date)->delete();
        echo "تم حدف الموعد بنجاح";
        sleep(3);

        return redirect()->back();

    }


}

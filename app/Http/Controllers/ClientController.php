<?php

namespace App\Http\Controllers;

use App\Coiffure;
use App\Http\Controllers\BotController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Client;
use App\Rdv;
use DB;
use phpDocumentor\Reflection\Types\Object_;
use function Sodium\add;


class ClientController extends Controller
{
    public function __construct()
    {
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
        return $day;
    }

    public function timeTrait($day,$type,$beg,$end,$H_pause,$H_retour){
        $co = Coiffure::find($type);
        if($co !=null) {


            if ($day == "0") {

                //$min = Carbon::now()->minute;
                // dd(strtotime($beg) < Carbon::createFromFormat("H:i","00:12")->timestamp);

                if (strtotime($beg) <= Carbon::now()->timestamp) {

                    $min = Carbon::now()->minute;
                    $beg = Carbon::now();
                    switch ($min) {
                        case ($min > 0 && $min <= 15) :
                            $beg->addMinutes(15 - $min);
                            break;
                        case ($min > 15 && $min <= 30):
                            $beg->addMinutes(30 - $min);
                            break;
                        case($min > 30 && $min <= 45):
                            $beg->addMinutes(45 - $min);
                            break;
                        case($min > 45 && $min < 60):
                            $beg->addMinutes(60 - $min);
                            break;
                        default :
                            $beg;

                    }

                }

                $beg = Carbon::parse($beg);
                    $coiffure = $co->nom;
                    $creno = $this->workTime($co->temps, $beg->format("H:i"), $end , $H_pause,$H_retour);
                return $creno;
            } else {
                $coiffure = $co->nom;
                $creno = $this->workTime($co->temps, $beg, $end,$H_pause,$H_retour);
                return $creno;
            }
        }else{
            return view('error');
        }
    }

    //traitement RDVS
    public function workTime($param , $begin ,$end , $H_pause,$H_retour)
    {
        $CompletTime = array ();// Define output

        $times = DB::select('select H_debut,H_pause,H_retour,H_fin from settings');

        if($begin === null)
            $StartTime    = strtotime ($times[0]->H_debut);
        else
            $StartTime    = strtotime ($begin);

        $EndTime      = strtotime($end); //strtotime ($times[0]->H_fin);
        if($H_pause != null && $H_retour != null){
           $lunchTime = strtotime($H_pause);
         $backTime = strtotime($H_retour); 
        }else{
             $lunchTime = strtotime($times[0]->H_pause);
             $backTime = strtotime($times[0]->H_retour);
        }
       
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

    public function getCrenos($id,$type,$day,$idCoiffeur)
    {

        $date = "";
        $takenCr = [];
        $del = [];
        $exist = false;
        $isOff = false;
        $H_pause = null;
        $H_retour = null;

        switch ($day){
            case "0":{
                 $mytime = Carbon::now();
               // $mytime = Carbon::createFromFormat('d-m-Y H:i:s',  '13-07-2021 00:12:00');
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

        $jour = Carbon::createFromFormat('Y-m-d',$date)->format('l');
        $jour = $this->translateDay($jour);
        $weekends = DB::select('select distinct H_debut, H_fin FROM weekdays where date='.$date.' OR day= "'. $jour.'"');
        $params = $this->getParams();
        
        
        if(! $weekends == null){
            
            if($weekends[0]->H_debut<=$params[0]->H_debut && $weekends[0]->H_fin>=$params[0]->H_fin){
                $isOff = true;
                return view('crenos')->with('data',['isOff'=>true]);
            }else{
                
                $beg = $params[0]->H_debut;
                $end = $params[0]->H_fin;
                
                
                if($weekends[0]->H_debut>=$params[0]->H_debut && $weekends[0]->H_fin>$params[0]->H_fin){
                    $beg = $params[0]->H_debut;
                    $end = $weekends[0]->H_debut;
                    // dd("if");

                }else{
                    
                    
                    if($weekends[0]->H_debut<=$params[0]->H_debut && $weekends[0]->H_fin<=$params[0]->H_fin){
                        $beg = $weekends[0]->H_fin;
                        $end = $params[0]->H_fin;

                    }else{
                       
                        if($weekends[0]->H_debut>=$params[0]->H_debut && $weekends[0]->H_fin<=$params[0]->H_fin){
                             $beg = $params[0]->H_debut;
                             $end = $params[0]->H_fin;
                             $H_pause = $weekends[0]->H_debut;
                             $H_retour = $weekends[0]->H_fin;
                            
                        }else{
                            $beg = $end;
                        }
                        

                    }


                }


                /*
                if($weekends[0]->H_fin>=$params[0]->H_fin){
                    $end = $params[0]->H_fin;
                    dd($weekends[0]->H_fin , $params[0]->H_fin);
                }else{
                    $beg = $weekends[0]->H_fin;
                    $end = $params[0]->H_fin;

                }*/
                $creno = $this->timeTrait($day,$type,$beg,$end,$H_pause,$H_retour);

            }

        }else{

            $creno = $this->timeTrait($day,$type,$params[0]->H_debut,$params[0]->H_fin,null,null);


        }

        $takenCrSQL = Rdv::select('time','idClient')
            -> where('date','=',$date)
            ->where('idCoiffeur','=',$idCoiffeur)->get();

        $rdvExist = DB::select('select distinct idClient from rdvs where idClient ='.$id.' AND date = "'.$date.'" OR idClient ='.$id.' AND  date= "'.Carbon::tomorrow()->toDateString().'" or idClient ='.$id.' AND  date ="'.Carbon::now()->addDays(2)->toDateString().'"');

        if (! $rdvExist==null){
            $exist = true;
        }
        foreach ($takenCrSQL as $tk){

            // $myt = Carbon::createFromTime($tk["time"])->format("H:i");
            $myt = strtotime($tk["time"]);

            for ($i = 0; $i < count($creno); $i++) {
                if($myt >= strtotime($creno[$i])  &&  $myt< strtotime($creno[$i+1])){
                    array_push($del,date("G:i",strtotime($creno[$i])));
                }

            }

        }


        if(is_array($creno)){
            $crs = array_diff($creno,$del);
            $crs = array_diff($crs,$takenCr);
        }else{
            return view('error');
        }


        //rani hna
        return view('crenos')->with('data',[
            'crns'=>$crs,
            'id'=>$id,
            'type'=>$type,
            'date'=>$date,
            'exist'=>$exist,
            'isOff'=>$isOff]);

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

        }else{
             if($user->blocked == 1){
            return view('error');
        }
        }

        $co = Coiffure::find($type);
        if($co !=null){
            $beg = $request ->creno;
            $coiffure = $co->nom;
            $creno = $this->timeSpliter($beg,$co->temps);
        }else{
            return view('error');
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

        $bot->sendTextMessage($id,"✅ مبروك لقد تم حجز موعدك بنجاح شكرا على ثقتك 🙏");
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
        $time = Carbon::now()->timestamp;
        $bool = false;
        $user = Client::where('idClient', '=', $id)->first();


        if($user === null)
        {
            return view('error');
        }else{
            $rdv = DB::select('select distinct idClient,max(date) as date,time from rdvs where idClient = '.$id);
            
            //$rdv = Rdv::where('idClient','=',$id)->where('max(date)');


            if(! $rdv == null){
                
               
                if($today < $rdv[0]->date){
                  
                    $bool = true;
                    
                }else{
                    if($today == $rdv[0]->date){
                        if($time<strtotime($rdv[0]->time)){
                        $bool = true;
                    }
                    }
                }
            }
          
            return view('users.myrdv')->with('data',[
                'rdv'=>$rdv[0],
                'bool'=>$bool,
                'user'=>$user
            ]);

        }
    }

    public function cancelRdv(Request $request){
       $id = $request->id;
        $date = $request->date;
        $bot = new BotController();
        $client = Client::select('points')-> where('idClient','=',$id)->get();
        $coiffure = Rdv::select('coiffure')->where('idClient',"=",$id)->orderBy('created_at', 'DESC')->first();
        
        $coiffure = $coiffure->coiffure;
        $pts =  DB::select('select distinct points FROM coiffures where nom= "'. $coiffure.'"');
     
        $pts = $pts[0]->points;

        $newP = $client[0]["points"] - $pts;
        if ($newP<0){
            $newP = 0;
        }
        
        Client::where('idClient','=', $id)
            ->update([
                'points' => $newP
            ]);

        DB::table('rdvs')->where('idClient', $id)->where('date',$date)->delete();

        sleep(3);
        $bot->sendTextMessage($id,"😞 لقد تم حذف موعدك بنجاح ❎");
        return redirect()->back();

    }

    public function deleteWeekday(Request $request){
        $date =$request->date;
        $day = $request->day;

        if( !($date == null && $day == null)){

            DB::table('weekdays')->where('date',$date)->where('day',$day)->delete();
            return redirect()->back();
        }else{
            return view ('error');
        }
    }



}

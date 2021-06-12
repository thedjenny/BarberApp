<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use DB;
use \App\Http\Controllers\BotController;

class ClientController extends Controller
{

    //traitement RDVS
   public function workTime($param)
    {
        $CompletTime = array ();// Define output
        $StartTime    = strtotime ("10:00"); //Get Timestamp
        $EndTime      = strtotime ("20:00"); //Get Timestamp
        $lunchTime = strtotime("13:00");
        $backTime = strtotime("14:00");
        $AddMins  = $param * 60;

    
        while ($StartTime < $lunchTime) //Run loop
        {
            $CompletTime[] = date ("G:i", $StartTime);
            $StartTime = $StartTime + $AddMins; //Endtime check
        }

        while($backTime<$EndTime){
            $CompletTime[] = date ("G:i", $backTime);
            $backTime = $backTime + $AddMins; //Endtime check
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

    public function getCrenos($id,$day,$type)
    {
        
        //$beg = $request ->hour;
        
        switch($type){

            case "1":{
                $coiffure = "degrade";
                $creno = $this->workTime(45);
            }break;
            
            case "2":{
                $coiffure = "simple";
                $creno = $this->workTime(30);
            }break;

            case "3":{
                $coiffure = "degrade + lisseur/sechoir";
                $creno = $this->workTime(60);
            }break;
            
            case "4":{
                $coiffure = "barbe";
                $creno = $this->workTime(15);
            }break;

            case "5" :{
                $coiffure = "kiratin simple";
                $creno = $this->workTime(60);
            }break;

            case "6":{
                $coiffure = "VIP";
                $creno = $this->workTime(90);
            }break;

            case "7":{
                $coiffure = "Lisseur/Sechoir";
                $creno = $this->workTime(15);
            }break;
            
        }
      
        $takenCr = Rdv::all();
        $crs = array_diff($creno,$takenCr);
        return view('crenos')->with('crs',$crs);
   

    }
    ////////////////////////////////////////////

    public function  reserver(Request $request , $id) //Reservation Min time
    {
     
        $user = Client::where('idClient', '=', $id)->first();
        if ($user === null) {
            Client::create(array(
                'idClient' => $id,
                'username'=> BotController::getUsername($id),
                 'profile_picture' => BotController::getProfilePicture($id) ,
                 'points'=>0
            ));
        
        }

        $type = $request->type;
        $date =$request->date;
        $beg = $request ->hour;

        $creno [] = array();
        
        switch($type){

            case "1":{
                $coiffure = "degrade";
                $creno = $this->timeSpliter($beg,45);
            }break;
            
            case "2":{
                $coiffure = "simple";
                $creno = $this->timeSpliter($beg,30);
            }break;

            case "3":{
                $coiffure = "degrade + lisseur/sechoir";
                $creno = $this->timeSpliter($beg,60);
            }break;
            
            case "4":{
                $coiffure = "barbe";
                $creno = $this->timeSpliter($beg,15);
            }break;

            case "5" :{
                $coiffure = "kiratin simple";
                $creno = $this->timeSpliter($beg,60);
            }break;

            case "6":{
                $coiffure = "VIP";
                $creno = $this->timeSpliter($beg,90);
            }break;

            case "7":{
                $coiffure = "Lisseur/Sechoir";
                $creno = $this->timeSpliter($beg,15);
            }break;
            
        }
        
        foreach($creno as $cr){
        
            Rdv::create(array(
                'idClient' => $id,
                'date'=>$date,
                 'coiffure' => $coiffure,
                 'time'=>$cr
            ));
        
        
        }

   

    }

    

    public function myRdv($id)
    {
        $user = Rdv::where('idClient', '=', $id)->first();
        if($user === null)
        {
            echo "there is no rdv";
        }
    }
}

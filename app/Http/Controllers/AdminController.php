<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Settings;
use Carbon\Carbon;
use Carbon\Traits\Week;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Client;
use DateTime;
use App\Rdv;
use App\Weekday;
use DB;

class AdminController extends Controller
{


    public function __construct()
    {
        Carbon::setLocale('fr');
        $this->middleware('auth');
    }
    public function index()
    {

       return view('admin.index');
    }
    public function getClients ()
    {
        $clients = DB::select('select distinct c.idClient,c.profile_picture,username,points,c.created_at,date,blocked from rdvs as r,clients as c where c.idClient= r.idClient GROUP by c.idClient order by created_at desc');

        foreach ($clients as $client){
            $client->created_at = date('Y-m-d', strtotime($client->created_at));

        }
         return view('admin.clients')->with("clients",$clients);
    } //ok
    public function getSchedule($day)
    {
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


        $data = DB::select('select c.idClient,r.isPresent, c.profile_picture,username,r.coiffure,points,min(time) as time from rdvs as r, clients as c where r.date= "'.$date.'" And C.idClient = r.idClient group by c.idClient order by time asc');


        if($data == null){
            return view('admin.schedule')->with('vide',true)->with('date',$date);
        }else{
            return view('admin.schedule')->with('data',$data)->with('vide',false)->with('date',$date);

        }
    } //ok
    public function getPlanning(){

        return view('admin.planning');
    }
    public function cancelDay(Request $request){

        $msg = json_decode(file_get_contents("php://input"));
        Carbon::setLocale('fr');
         $day = Carbon::createFromFormat('Y-m-d',$msg->date)->format('l');

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

        $dayIn = Rdv::where('date', '=', $msg->date)->first();

        if($dayIn == null){

        $params = DB::select('select H_debut,H_fin from settings');

        Weekday::create(array(
            'H_debut'=>$params[0]->H_debut,
            'H_fin'=>$params[0]->H_fin,
            'day'=>$day,
            'date' => $msg->date,
        ));
        }else{
            return redirect()->back();
        }

        return redirect()->route('editworkday') ;

    }
    public function bloquerClient(Request $request){

        $idClient = $request->idClient;
        if(!$idClient == null){
            Client::where('idClient', $idClient)
                ->update([
                    'blocked' => true
                ]);
            sleep(2);
            return redirect()->back();
        }else
            return view ('error');
    }
    public function debloquerClient(Request $request){
        $idClient = $request->idClient;
        if(!$idClient == null){
            Client::where('idClient', $idClient)
                ->update([
                    'blocked' => false
                ]);
            sleep(2);
            return redirect()->back();
        }else
            return view ('error');
    }
    public function isPresent(Request $request){
        $id = $request->id;
        $type = $request->type;
        $points = $request->points;
        $present = false;
        $client = Client::where('idClient', '=', $id)->first();
        $today = Carbon::now()->format('Y-m-d');

        if(! $client==null){
            switch($type){

                case "degrade":{

                    $points = $points + 30;

                }break;

                case "lisseur+coiffure":{

                    $points = $points + 40;

                }break;

                case "degrade + kiratin":{

                    $points = $points + 100;

                }break;

                case "barbe":{

                    $points = $points + 10;

                }break;

                case "kiratin simple" :{

                    $points = $points + 80;
                }break;

                case "VIP":{

                    $points = $points + 150;
                }break;

                case "Lisseur/Sechoir":{

                    $points = $points + 15;

                }break;

                default :{
                    return view('error');
                }
                $present = true;

            }
            Client::where('idClient','=', $id)
                ->update([
                    'points' => $points
                ]);

            Rdv::where('idClient','=', $id)->where('date','=',$today)
                ->update([
                    'isPresent' => 1
                ]);
        }else{

            return  view('error');
        }
        return redirect()->back();
    }
    public function editCancelDay(){
        
        $days = DB::select('select distinct day,date,H_debut,H_fin from weekdays where date != "" ');
        if($days == null){

            return view ('admin.editBreak')->with('vide',true);
        }
        return view ('admin.editBreak')->with('days',$days)->with('vide',false);
    }
    public function editTimeDay(Request $request){
        $date = $request->date;
        $H_debut = $request->H_debut;
        $H_fin = $request->H_fin;
        if(! $date==null ){
            Weekday::where('date', $date)
                ->update([
                    'H_debut' => $H_debut,
                    'H_fin'=>$H_fin
                ]);

            return redirect()->back();
        }else{
            return view('error');
        }

    }
    public function editPoints(Request $request){
        $username = $request->username;
        $points = $request->points;

        Client::where('idClient', $username)
            ->update([
                'points' => $points
            ]);
        return redirect()->back();

    }
    public function registerAdmin(){
        return view('admin.register');
    }
    public function getAdmins(){

        $admins = DB::select('select nom,prenom,email from admins');
        return view('admin.admins')->with('admins',$admins);
    }
    public function getAdminProfil(){
        return view('admin.adminProfile');
    }
    public function getSettings(){

        $weekends= DB::select('select day,H_debut,H_fin from weekdays where date = ""');
        $days = array();

        foreach ($weekends as $row){

         array_push($days,$row->day);
        }


        return view('admin.adminSettings')->with('weekends',$days);/*with('trv',[
            'H_debut'=> $days["H_debut"],
            'H_fin'=>$days['H_fin'],
            'H_pause'=>$days['H_pause'],
            'H_retour'=>$days['H_retour']
        ]);*/
    }
    public function setSettings(Request $request){
        $data = $request->all();
        $weekends = array();
        $beg = $request->beg;
        $end = $request->end;
        $lunch = $request->lunch;
        $back = $request->back;


        for($i=0;$i<8;$i++){
            $str = "checkedDay".$i;

            if(! $request->$str == null){
                array_push($weekends,$request->$str);
            }
        }

        foreach ($weekends as $weekend){
            $we = Weekday::where('day','=',$weekend )->first();

                if($we == null){
                   $we= Weekday::Create(['day' => $weekend , 'H_debut'=>$request->beg, 'H_fin'=>$request->end]);
                }else{
                   $we->day = $weekend;
                   $we->H_debut = $request->beg;
                   $we->H_fin = $request->end;
                   $we->save();
                }


        }
        return redirect()->back();


    


}
    public function getShopSettings(){
        $settings = Settings::all()->first();
        return view('admin.ShopSettings')->with('settings',$settings);
    }
    public function saveTimeSettings(Request $request){

        $beg = $request->beg;
        $end = $request->end;
        $pause = $request->pause;
        $retour = $request->back;

        if(strtotime($pause)<=strtotime($retour) && strtotime($retour)<strtotime($end) && strtotime($beg)<strtotime($end) && strtotime($beg)<strtotime($pause))
        {

            DB::table('settings')->delete();
            Settings::create(array(
                'H_debut'=>$beg,
                'H_fin' => $end,
                'H_pause'=>$pause,
                'H_retour'=>$retour
            ));
        }else{
            return view('error');
        }
        return redirect()->back();
    }
    public function deleteAdmin(Request $request){

        $email = $request->email;

        $count = Admin::all()->count();
        if($count>1){
            if (! $email == null){
                DB::table('admins')->where('email', $email)->delete();
            }else{
              return  "error mail";
            }
        }else{
            return  "error count ".$count;
        }

        return redirect()->back();
    }


}

<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Coiffeur;
use App\Coiffure;
use App\Tresorerie;
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
use Illuminate\Support\Facades\Input;

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

    public function getClients()
    {
        $clients = DB::select('select distinct c.idClient,c.profile_picture,username,points,c.created_at,date,blocked from rdvs as r,clients as c  GROUP by c.idClient order by created_at desc');


        foreach ($clients as $client) {
            $client->created_at = date('Y-m-d', strtotime($client->created_at));

        }

        return view('admin.clients')->with("clients", $clients);
    } //ok





    /*
    public function getSchedule($day)
    {
        switch ($day) {
            case "0":
                {
                    $mytime = Carbon::now();
                    $date = $mytime->toDateString();
                }
                break;
            case "1":
                {
                    $tomorrow = Carbon::tomorrow();
                    $date = $tomorrow->toDateString();
                }
                break;
            case "2":
                {
                    $totomorrow = Carbon::now()->addDays(2);
                    $date = $totomorrow->toDateString();
                }
                break;
            default:
            {
                return view('error');
            }
        }


        $data = DB::select('select c.idClient,r.isPresent, c.profile_picture,username,r.coiffure,points,min(time) as time from rdvs as r, clients as c where r.date= "' . $date . '" And c.idClient = r.idClient group by c.idClient order by time asc');


        if ($data == null) {
            return view('admin.schedule')->with('vide', true)->with('date', $date);
        } else {
            return view('admin.schedule')->with('data', $data)->with('vide', false)->with('date', $date);

        }
    } //ok
*/

    public function getbarberSchedule($day,$idCoiffeur)
    {

        switch ($day) {
            case "0":
                {
                    $mytime = Carbon::now();
                    $date = $mytime->toDateString();
                }
                break;
            case "1":
                {
                    $tomorrow = Carbon::tomorrow();
                    $date = $tomorrow->toDateString();
                }
                break;
            case "2":
                {
                    $totomorrow = Carbon::now()->addDays(2);
                    $date = $totomorrow->toDateString();
                }
                break;
            default:
            {
                return view('error');
            }
        }

        $clients=Client::all();
        $coiffures = Coiffure::all();
        $data = DB::select('select c.idClient,r.isPresent, c.profile_picture,username,r.coiffure,points,min(time) as time from rdvs as r, clients as c where r.idCoiffeur="'.$idCoiffeur.'" AND r.date= "' . $date . '" And c.idClient = r.idClient group by c.idClient order by time asc');



        if ($data == null) {
            return view('admin.schedule')->with('vide', true)->with('date', $date);
        } else {
            return view('admin.schedule')->with('data', $data)
                                                ->with('vide', false)
                                                ->with('date', $date)
                                                ->with('clients',$clients)
                                                ->with('coiffure',$coiffures)
                                                ->with('idCoiffeur',$idCoiffeur);

        }


    }

    public function getSchedule($day)
{

        $barbers = DB::select('select * from coiffeurs where is_blocked=0 ');
        return view('admin.barbersPlan')->with('day',$day)
                                            ->with('coiffeurs',$barbers);
}
    public function getPlanning()
    {

        return view('admin.planning');
    }

    public function cancelDay(Request $request)
    {

        $msg = json_decode(file_get_contents("php://input"));
        Carbon::setLocale('fr');
        $day = Carbon::createFromFormat('Y-m-d', $msg->date)->format('l');

        switch ($day) {
            case "Saturday":
                $day = "Samedi";
                break;
            case "Sunday":
                $day = "Dimanche";
                break;
            case "Monday":
                $day = "Lundi";
                break;
            case "Tuesday":
                $day = "Mardi";
                break;
            case "Wednesday":
                $day = "Mercredi";
                break;
            case "Thursday":
                $day = "Jeudi";
                break;
            case "Friday":
                $day = "Vendredi";
                break;
            default:
                return view('error');
        }

        $dayIn = Rdv::where('date', '=', $msg->date)->first();

        if ($dayIn == null) {

            $params = DB::select('select H_debut,H_fin from settings');

            Weekday::create(array(
                'H_debut' => $params[0]->H_debut,
                'H_fin' => $params[0]->H_fin,
                'day' => $day,
                'date' => $msg->date,
            ));
        }


    }

    public function bloquerClient(Request $request)
    {

        $idClient = $request->idClient;
        if (!$idClient == null) {
            Client::where('idClient', $idClient)
                ->update([
                    'blocked' => true
                ]);
            sleep(2);
            return redirect()->back();
        } else
            return view('error');
    }

    public function debloquerClient(Request $request)
    {
        $idClient = $request->idClient;
        if (!$idClient == null) {
            Client::where('idClient', $idClient)
                ->update([
                    'blocked' => false
                ]);
            sleep(2);
            return redirect()->back();
        } else
            return view('error');
    }

    public function isPresent(Request $request)
    {
        $id = $request->id;
        $coiffure = DB::select('select * from coiffures where nom ="' . $request->type . '"');


        $points = $request->points;
        $present = false;
        $client = Client::where('idClient', '=', $id)->first();
        $today = Carbon::now()->format('Y-m-d');


        if ($client != null && $coiffure != null) {

            $points = $points + $coiffure[0]->points;
            $present = true;

            Client::where('idClient', '=', $id)
                ->update([
                    'points' => $points
                ]);

            Rdv::where('idClient', '=', $id)->where('date', '=', $today)
                ->update([
                    'isPresent' => 1
                ]);
        } else {

            return view('error');
        }
        return redirect()->back();
    }

    public function editCancelDay()
    {

        $days = DB::select('select distinct day,date,H_debut,H_fin from weekdays where date != "" ');
        if ($days == null) {

            return view('admin.editBreak')->with('vide', true);
        }
        return view('admin.editBreak')->with('days', $days)->with('vide', false);
    }

    public function editTimeDay(Request $request)
    {
        $date = $request->date;
        $H_debut = $request->H_debut;
        $H_fin = $request->H_fin;
        if (!$date == null) {
            Weekday::where('date', $date)
                ->update([
                    'H_debut' => $H_debut,
                    'H_fin' => $H_fin
                ]);

            return redirect()->back();
        } else {
            return view('error');
        }

    }

    public function editPoints(Request $request)
    {
        $username = $request->username;
        $points = $request->points;

        Client::where('idClient', $username)
            ->update([
                'points' => $points
            ]);
        return redirect()->back();

    }

    public function registerAdmin()
    {
        return view('admin.register');
    }

    public function getAdmins()
    {

        $admins = DB::select('select nom,prenom,email from admins');
        return view('admin.admins')->with('admins', $admins);
    }

    public function getAdminProfil()
    {
        return view('admin.adminProfile');
    }

    public function getSettings()
    {

        $weekends = DB::select('select day,H_debut,H_fin from weekdays where date = ""');
        $days = array();

        foreach ($weekends as $row) {

            array_push($days, $row->day);
        }


        return view('admin.adminSettings')->with('weekends', $days);/*with('trv',[
            'H_debut'=> $days["H_debut"],
            'H_fin'=>$days['H_fin'],
            'H_pause'=>$days['H_pause'],
            'H_retour'=>$days['H_retour']
        ]);*/
    }

    public function getCoiffures()
    {

        $coiffures = Coiffure::all();
        return view('admin.coiffures')->with('coiffures', $coiffures);
    }

    public function createCoiffure(Request $request)
    {

        // ensure the request has a file before we attempt anything else.
        if ($request->hasFile('image')) {
            $this->validate($request, [
                'image' => 'mimes:jpeg,bmp,png' // Only allow .jpg, .bmp and .png file types.
            ]);
            // Save the file locally in the storage/public/ folder under a new folder named /product
            $request->image->store('product', 'public');
            // Store the record, using the new file hashname which will be it's new filename identity.

            $temps = $request->get('hours') * 60 + $request->get('minutes');

            $product = new Coiffure([
                "nom" => $request->get('nom'),
                "photo" => $request->image->hashName(),
                "points" => $request->get('points'),
                "temps" => $temps,
                "prix" => $request->get('prix')
            ]);
            $product->save(); // Finally, save the record.
            return redirect()->back();
        } else {
            return view('error');
        }


    }

    public function deleteCoiffure(Request $request)
    {
        $msg = json_decode(file_get_contents("php://input"));
        Coiffure::find($msg->id)->delete();
    }

    public function setSettings(Request $request)
    {
        $data = $request->all();

        $weekends = array();
        $beg = $request->beg;
        $end = $request->end;
        $lunch = $request->lunch;
        $back = $request->back;


        for ($i = 0; $i < 8; $i++) {
            $str = "checkedDay" . $i;

            if (!$request->$str == null) {
                array_push($weekends, $request->$str);
            }
        }

        if (sizeof($weekends) == 0) {
            DB::table('weekdays')->delete();
        } else {
            foreach ($weekends as $weekend) {
                $we = Weekday::where('day', '=', $weekend)->first();

                if ($we == null) {
                    $we = Weekday::Create(['day' => $weekend, 'H_debut' => $request->beg, 'H_fin' => $request->end]);
                } else {
                    $we->day = $weekend;
                    $we->H_debut = $request->beg;
                    $we->H_fin = $request->end;
                    $we->save();
                }


            }
        }


        return redirect()->back();


    }

    public function getShopSettings()
    {
        $settings = Settings::all()->first();
        return view('admin.ShopSettings')->with('settings', $settings);
    }

    public function saveTimeSettings(Request $request)
    {

        $beg = $request->beg;
        $end = $request->end;
        $pause = $request->pause;
        $retour = $request->back;

        if (strtotime($pause) <= strtotime($retour) && strtotime($retour) < strtotime($end) && strtotime($beg) < strtotime($end)) {

            DB::table('settings')->delete();
            Settings::create(array(
                'H_debut' => $beg,
                'H_fin' => $end,
                'H_pause' => $pause,
                'H_retour' => $retour
            ));
        } else {
            return view('error');
        }
        return redirect()->back();
    }

    public function deleteAdmin(Request $request)
    {

        $email = $request->email;

        $count = Admin::all()->count();
        if ($count > 1) {
            if (!$email == null) {
                DB::table('admins')->where('email', $email)->delete();
            } else {
                return "error mail";
            }
        } else {
            return "error count " . $count;
        }

        return redirect()->back();
    }

    public function getCoupes()
    {
        $coupes = DB::select('select * from coiffures');
        return view('admin.coiffures')->with('coupes', $coupes);
    }

    public function setCoupes(Request $request)
    {

    }

    public function deleteClient(Request $request)
    {
        $id = $request->idClient;

        if (DB::table('select * from clients where idClient = ' . $id) != null) {
            DB::table('clients')->where('idClient', $id)->delete();
            return redirect()->back();
        } else {
            return view('error');
        }

    }

    public function getCoiffeur()
    {
        $barbers = DB::select('select * from coiffeurs where is_blocked=0 ');

        return view('admin.barbers')->with('coiffeurs', $barbers);
    }


    public function addbarber(Request $request)
    {

        // ensure the request has a file before we attempt anything else.
        if ($request->hasFile('image')) {

            $this->validate($request, [
                'image' => 'mimes:jpeg,bmp,png' // Only allow .jpg, .bmp and .png file types.
            ]);
            // Save the file locally in the storage/public/ folder under a new folder named /product
            $request->image->store('product', 'public');
            // Store the record, using the new file hashname which will be it's new filename identity.


            $product = new Coiffeur([

                "photo" => $request->image->hashName(),
                "nom" => $request->get('nom'),
                "prenom" => $request->get('prenom'),
            ]);
            $product->save(); // Finally, save the record.
            return redirect()->back();
        } else {
            return view('error');
        }

    }

    public function deleteBarber(Request $request)
    {
        $idCoiffeur = $request->id;

            Coiffeur::where('idCoiffeur', $idCoiffeur)
                ->update([
                    'is_blocked' => true
                ]);

    }

    public function addClientOffline(Request $request){

        if ($request->hasFile('image')) {

            $this->validate($request, [
                'image' => 'mimes:jpeg,bmp,png' // Only allow .jpg, .bmp and .png file types.
            ]);
            // Save the file locally in the storage/public/ folder under a new folder named /product
            $request->image->store('product', 'public');
            // Store the record, using the new file hashname which will be it's new filename identity.


            $product = new Client([
                "idClient"=>rand(1000000000000000,9999999999999999),
                "profile_picture" => $request->image->hashName(),
                "username" => $request->get('username'),
                "points" => $request->get('points'),
                "blocked"=>0
            ]);

            $product->save(); // Finally, save the record.
            return redirect()->back();
        } else {
            return view('error');
        }


    }

    public function addRdvOffline(Request $request){
        dd($request->all());
    }

    /* ------------------------- Tresorerie -------------------------- */
    public function getTresorerie(){
        return view('admin.tresorerie')->with('clients',$this->loadClients_Encaissement())
                                            ->with('coiffeurs',$this->loadCoiffeurs_Encaissement())
                                            ->with('coupes',$this->loadCoupes_Encaissement());
    }


    public function loadClients_Encaissement(){
        $date = Carbon::now()->toDateString();

        $clients = DB::select("select distinct c.username from clients as c , rdvs as r where c.idClient=r.idClient AND r.date = '". $date."'");

       return $clients;
    }
    public function loadCoiffeurs_Encaissement(){
        $coiffeurs = DB::select('select idCoiffeur,nom,prenom from coiffeurs');
        return $coiffeurs;
    }
    public function loadCoupes_Encaissement(){
        $coupes = DB::select('select nom , prix from coiffures');
        return $coupes;
    }


    public function validerEncaissement(Request $request){
        $type = "Encaissement";
        $client = $request->client;
        $idCoiffeur = $request->coiffeur;
        $coupe = $request->coiffure;
        $prix = $request->prix;
        //dd($idCoiffeur[0]->idCoiffeur);
        $tresorerie = new Tresorerie([
            'idCoiffeur'=>$idCoiffeur,
            'type' => $type,
            'montant'=>$prix,
            'motif'=>$coupe
        ]);
        $tresorerie->save();

        return redirect()->back()->with('message', 'Encaissement succes');



    }
    public function validerCharge(Request $request){

        $montant = $request->montant;
        if($montant>0){

            $tresorerie = new Tresorerie([
                'type'=>'Charge',
                'motif'=>$request->motif,
            ]);
            $tresorerie->save();
            return redirect()->back()->with('message', 'Debit avec succes');

        }else{
            return view('error');
        }
    }
    public function validerDecaissement(Request $request){
        $montant = $request->montant;
        $idCoiffeur = $request->coiffeur;

        if($montant>0 && $idCoiffeur != null){

            $tresorerie = new Tresorerie([
                'type'=>'Avance ouvrier',
                'idCoiffeur'=>$idCoiffeur,
                'motif'=>$request->motif,
            ]);
            $tresorerie->save();
            return redirect()->back()->with('message', 'Decaissement avec succes');

        }else{
            return view('error');
        }

    }

    



}

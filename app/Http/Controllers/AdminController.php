<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Client;
use DateTime;
use App\Rdv;
use DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view ("admin.index");
    }

    public function getClients (Request $request)
    {
        
        $clients = Client::all();
        
        return view('admin.clients')->with($clients);

    }

    public function getSchedule()
    {
     
        $data = DB::select('select idClient, min(time) from rdvs group by idClient order by time asc'); 
    
        return view ('admin.schedule')->with($data);

    }

    


}

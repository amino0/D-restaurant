<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Commande;
use Illuminate\Support\Facades\DB;

class cuisinecontroller extends Controller
{
    public function index()
    {
        //  $d = ;

        $commande = DB::select("SELECT * FROM commandes where status_serveur = '0' or  status_serveur = '1' order by heure ASC ");
        $deleted = DB::select("SELECT * FROM commandes where status_serveur = '0' order by heure ASC  ");


        return view('cuisine.liste', compact('commande', 'deleted'));
    }
    public function pretcommande($id)
    {
        $affected = DB::table('commandes')
            ->where('id', $id)
            ->update(['status_serveur' => 1]);
        return redirect('/cuisine')->with(
            'success',
            'La commande est prete.'
        );
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Table;
use App\Commande;
use \Carbon\Carbon;

use Illuminate\Http\Request;

class serveurcontroller extends Controller
{
    public function hash()
    {
        $caisse = DB::select(" SELECT *  
        FROM caisses where date_fermer is null and date_ouvert is not null");

        if (!empty($caisse)) {
            foreach ($caisse as $row) {
                if ($row->date_ouvert) {
                    $id = $row->date_ouvert;
                } else {
                    $id = null;
                }
            }
        } else {
            $id = null;
        }
        if ($id == 0) {
            return $id;
        }
        $hash = md5($id);
        return $hash;
    }
    public function index()
    {
        return view('login');
    }
    public function home()
    {
        $table = DB::select('select * from tables ');
        $commandepret  = DB::select("select * from commandes where `status_serveur` = 1	  ");

        return view('serveur.home', compact('table', 'commandepret'));
    }
    public function select($id)
    {
        $article = DB::select("select * from articles,categories where `categories`.`id` = `articles`.`id_categorie` and `categories`.`type_menu` = 1	  ");
        $commandepret  = DB::select("select * from commandes where `status_serveur` = 1	  ");

        return view('serveur.choix', compact('article', 'commandepret'));
    }
    public function ajoutarticle(Request $request)
    {
        $hash = $this->hash();
        if ($hash == null) {
            return back()->with(
                'succe',
                'Desoler les caisses sont fermer'
            );
        } else {
            $date = Carbon::now();
            $d = $date->format('H:i:s');

            $name = $request->input('nom');
            $prix = $request->input('prix');
            $table = $request->input('table');

            $commande = new Commande;
            $commande->nom = $name;
            $commande->prix = $prix;
            $commande->table = $table;
            $commande->suplement = '0';
            $commande->status_serveur = '0';
            $commande->status_caisse = '0';
            $commande->heure = $d;
            $commande->jour_id = $hash;
            $commande->save();
            return back()->with(
                'success',
                'vous avez envoyer la commande !'
            );
        }
    }
    public function livre($id)
    {
        DB::table('commandes')
            ->where('id', $id)
            ->update(['status_serveur' => 2]);
        return back()->with(
            'success',
            "vous avez livre la commande aux clients !"
        );
    }
}

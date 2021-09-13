<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Table;
use App\Commande;
use App\Vente;
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
        $table = DB::select('select * from categories ');
        $commandepret  = DB::select("select * from commandes where `status_serveur` = 1	  ");

        return view('serveur.home', compact('table', 'commandepret'));
    }
    public function select($id)
    {
        $article = DB::select("select * from articles where `articles`.`id_categorie` = $id ");
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
            $tablee  = DB::select("select * from tables where `id` = $table	  ");
            foreach ($tablee as $row) {
                $nom_table = $row->nom_table;
            }

            $commande = new Commande;
            $commande->nom = $name;
            $commande->prix = $prix;
            $commande->table = $nom_table;
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

    public function vendre(Request $request)
    {
        $hash = $this->hash();
        if ($hash == null) {
            return back()->with(
                'succe',
                'Desoler les caisses sont fermer'
            );
        } else {
            $Vente = new Vente();
            $Vente->id_article = $request->input('id_article');
            $Vente->article = $request->input('article');
            $Vente->prix_vendu = $request->input('prix_vendu');
            $Vente->prix_revient = $request->input('prix_revient');
            $Vente->hash = $hash;
            $Vente->save();
            return back()->with(
                'success',
                'vous avez envoyer à la caisse !'
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

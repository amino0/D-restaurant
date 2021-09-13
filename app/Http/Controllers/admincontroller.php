<?php

namespace App\Http\Controllers;

use PDF;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\caissecontroller;
use App\Journal;
use App\Mennue;
use App\Categorie;
use App\Article;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use Illuminate\Http\Request;

class admincontroller extends Controller
{
    public function hash()
    {
        $caisse = DB::select(" SELECT *  
        FROM caisses where date_fermer is null and date_ouvert is not null");

        if (empty($caisse)) {
            foreach ($caisse as $row) {
                if (isset($row->date_ouvert)) {
                    $id = $row->date_ouvert;
                } else {
                    $id = null;
                }
            }
        }
        $id = null;

        if ($id == 0) {
            return $id;
        }
        $hash = md5($id);
        return $hash;
    }
    public function hashvar($var)
    {
        $hash = md5($var);
        return $hash;
    }
    public function index()
    {
        $journals = Journal::all();
        $commandess = DB::select(" SELECT * FROM `ventes` ORDER BY `ventes`.`created_at` DESC limit 10");
        $commandebest = DB::select(" SELECT sum(prix_vendu), article
        from ventes 
        group by article
        order by sum(prix_vendu) desc 
        limit 10");
        // dd($commandebest);
        return view('admin.home', compact('journals', 'commandess', 'commandebest'));
    }
    public function employer()
    {
        return view('admin.employer');
    }
    public function menus()
    {
        $menus = Mennue::all();
        return view('admin.menu.menus', compact('menus'));
    }
    public function menu()
    {
        $categories = Categorie::all();
        return view('admin.menu.menu', compact('categories'));
    }
    public function new_categorie(Request $request)
    {
        $name = $request->input('nom');
        $id = $request->input('idmenu');
        DB::table('categories')->insert([
            'nom' => $name,
            'type_menu' => $id
        ]);
        return back();
    }
    public function desactive($id)
    {
        DB::table('categories')
            ->where('id', $id)
            ->update(['type_menu' => 2]);
        return back();
    }
    public function active($id)
    {
        DB::table('categories')
            ->where('id', $id)
            ->update(['type_menu' => 1]);
        return back();
    }
    public function categorie($id)
    {
        $articles = DB::select('select * from articles where id_categorie = ?', [$id]);
        $categories = DB::select('select * from categories where id = ?', [$id]);

        //  $articles = Article::where('id_categorie', $id);
        return view('admin.menu.categorie', compact('articles', 'categories'));
    }
    public function new_article(Request $request)
    {
        $name = $request->input('nom');
        $prix = $request->input('prix');
        $id = $request->input('idcategorie');
        $prix_revient = $request->input('prix_revient');
        $quantite = $request->input('quantite');

        $article = new Article;
        $article->nom = $name;
        $article->prix = $prix;
        $article->id_categorie = $id;
        $article->nom_categorie = $name;
        $article->prix_revient = $prix_revient;
        $article->quantite = $quantite;
        $article->save();


        return back();
    }
    public function deletearticle($id)
    {
        $deleted = DB::delete("delete from articles where id = $id");
        return back();
    }
    public function deletecategorie($id)
    {
        $deleted = DB::delete("delete from categories where id = $id");
        return back();
    }
    public function caisse()
    {
        $caisse = DB::select('select * from caisses ');

        return view('admin.caisse', compact('caisse'));
    }
    public function ouvrircaisse(Request $request)
    {
        // $hash = $this->hashvar();

        $caisse = DB::select('select * from caisses where date_fermer is null');

        if ($caisse == null) {

            $date = $request->input('date');
            $hash = $this->hashvar($date);
            DB::table('caisses')->insert([
                'date_ouvert' => $date,
                'jour_compte' => $hash
            ]);
            return redirect('/administrateur/caisse');
        } else {
            return redirect('/administrateur/caisse')->with(
                'success',
                "Vous devez tout d'abord fermer la caisse "
            );
        }
    }
    public function fermercaisse(Request $request)
    {

        $id = $request->input('id');
        $dt = date("Y-m-d H:i:s");
        DB::table('caisses')
            ->where('id', $id)
            ->update(['date_fermer' => $dt]);
        return redirect('administrateur/caisse')->with(
            'success',
            'vous avez fermer la caisse'
        );
    }
    public function seeqrcode($id)
    {
        $qrcode = base64_encode(QrCode::format('svg')->size(300)
            ->generate("$id"));
        $article = DB::select("select * from articles where id = $id ");

        $pdf = PDF::loadView('pdf', compact('qrcode', 'article'));
        $pathToFile =  $pdf->stream("$id arriveDJIB" . '.pdf');
        return  $pathToFile;
    }
}

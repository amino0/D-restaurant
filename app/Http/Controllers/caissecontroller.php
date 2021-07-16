<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Commande;
use App\Caiss;
use App\Table;
use Illuminate\Support\Facades\DB;


class caissecontroller extends Controller
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
        $hash = $this->hash();
        $caisse = DB::select(" SELECT * FROM `caisses` WHERE `jour_compte` = '$hash'");
        if (!empty($caisse)) {

            $commande = DB::select(" SELECT * FROM `commandes` WHERE `jour_id` = '$hash'");
            $table = DB::select(" SELECT * FROM `tables` ");
            $tablesum = DB::select(" SELECT * FROM `tables`,`commandes` WHERE `tables`.`id` = `commandes`.`table` and  status_caisse = 0 and `jour_id` = '$hash'");
            $deleted = DB::select(" SELECT * FROM commandes where status_caisse = 0 and `jour_id` = '$hash' ");

            return view('caisse.home', compact('commande', 'deleted', 'caisse', 'table', 'tablesum'));
        }
        $commande = Commande::all();
        $deleted = DB::select(" SELECT *  
        FROM commandes where status_caisse = 0");

        return view('caisse.homefermer', compact('commande', 'deleted'));
    }
    public function fermercaisse(Request $request)

    {
        $id = $request->input('id');
        $dt = date("Y-m-d H:i:s");
        DB::table('caisses')
            ->where('id', $id)
            ->update(['date_fermer' => $dt]);
        return redirect('/caisse')->with(
            'success',
            'vous avez fermer la caisse'
        );
    }
    public function voir_table($id)
    {
        $hash = $this->hash();
        $commande = DB::select(" SELECT * FROM `commandes` WHERE `jour_id` = '$hash' and `commandes`.`table` = $id");
        $deleted = DB::select(" SELECT * FROM commandes where status_caisse = 0 and `jour_id` = '$hash' ");
        $caisse = DB::select(" SELECT * FROM `caisses` WHERE `jour_compte` = '$hash'");
        $table = DB::select(" SELECT * FROM `tables` WHERE `id` = '$id'");

        return view('caisse.facturetable', compact('commande', 'deleted', 'caisse', 'table'));
    }
}

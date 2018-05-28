<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class FinderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   public function __construct(Request $request)
   {
    $this->miinformacion=[];
   }

     public function people(Request $request)
    {
        $this->miinformacion=$request->auth;
        $clave=$request->clave;
        $arrayexplode=explode(' ',$clave);
         $results=DB::table('users')->select('id','first_name', 'last_name')->where(
            function ($query) use($arrayexplode) {
                for ($i = 0; $i < count($arrayexplode); $i++){
                   $query->orwhere('first_name', 'like',  '%' . $arrayexplode[$i] .'%')->whereNotIn('id', [$this->miinformacion->id]);
                } 
           }
         )->orWhere( function ($query) use($arrayexplode) {
            for ($i = 0; $i < count($arrayexplode); $i++){
               $query->orwhere('last_name', 'like',  '%' . $arrayexplode[$i] .'%')->whereNotIn('id', [$this->miinformacion->id]);
            } 
       })->get();
       $datos['datos']=$results;
         return response($datos,200); 
     
    }

    public function mascotas(Request $request)
    {

        $this->miinformacion=$request->auth;

        $clave=$request->clave;
        $arrayexplode=explode(' ',$clave);
         $results=DB::table('mascotas')->where(
            function ($query) use($arrayexplode) {
                for ($i = 0; $i < count($arrayexplode); $i++){
                   $query->orwhere('nombre', 'like',  '%' . $arrayexplode[$i] .'%')->whereNotIn('id_usuario', [$this->miinformacion->id])->where('vender',1);
                } 
           }
         )->orWhere( function ($query) use($arrayexplode) {
            for ($i = 0; $i < count($arrayexplode); $i++){
               $query->orwhere('raza', 'like',  '%' . $arrayexplode[$i] .'%')->whereNotIn('id', [$this->miinformacion->id])->where('vender',1);
            } 
       })->orWhere( function ($query) use($arrayexplode) {
        for ($i = 0; $i < count($arrayexplode); $i++){
           $query->orwhere('color', 'like',  '%' . $arrayexplode[$i] .'%')->whereNotIn('id', [$this->miinformacion->id])->where('vender',1);
        } 
   })->get();


       $datos['datos']=$results;
         return response($datos,200); 
     
    }
    public function mascotasvisitante(Request $request)
    {
        $clave=$request->clave;
        $arrayexplode=explode(' ',$clave);
         $results=DB::table('mascotas')->where(
            function ($query) use($arrayexplode) {
                for ($i = 0; $i < count($arrayexplode); $i++){
                   $query->orwhere('nombre', 'like',  '%' . $arrayexplode[$i] .'%')->where('vender',1);
                } 
           }
         )->orWhere( function ($query) use($arrayexplode) {
            for ($i = 0; $i < count($arrayexplode); $i++){
               $query->orwhere('raza', 'like',  '%' . $arrayexplode[$i] .'%')->where('vender',1);
            } 
       })->orWhere( function ($query) use($arrayexplode) {
        for ($i = 0; $i < count($arrayexplode); $i++){
           $query->orwhere('color', 'like',  '%' . $arrayexplode[$i] .'%')->where('vender',1);
        } 
   })->get();


       $datos['datos']=$results;
         return response($datos,200); 
     
    }

    //
}

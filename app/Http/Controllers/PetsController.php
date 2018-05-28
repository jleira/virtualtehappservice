<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Auth;
use DB;
use App\User;


class PetsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */

   public function __construct(Request $request)
   {
    $this->miinformacion=$request->auth;
   }


public function crearmascota(Request $request)
{
    $this->validate($request, [
        'nombre' => 'required',
        'sexo' => 'required',
        'raza' => 'required',
        'color' => 'required',
        'microchip' => 'required',
        'vender' =>'required',
        'precio'=>'required'
        ]);
        $existemascota=0;
        $existemascota=DB::table('mascotas')->where('id_usuario',$this->miinformacion->id)->
        where('nombre',$request->nombre)->
        where('sexo',$request->sexo)->
        where('raza',$request->raza)->
        where('color',$request->color)->
        where('microchip',$request->microchip)->
        count();

        if($existemascota>0){
            return response('Ya tiene una mascota registrada con esta informacion',422);
        }
        DB::table('mascotas')->insert(
            [
        'id_usuario'=>$request->auth->id,
        'nombre'=>$request->nombre,
        'sexo'=>$request->sexo,
        'raza'=>$request->raza,
        'color'=>$request->color,
        'microchip'=>$request->microchip,
        'vender'=>$request->vender,
        'precio'=>$request->precio
            ]
        ); 
        $macotanueva=DB::table('mascotas')->where('id',DB::table('mascotas')->where('id_usuario',$this->miinformacion->id)->max('id'))->get();
        return response($macotanueva,200);
}
public function mismascotas($id,Request $request)
{
    if($id==0){
        $id=$this->miinformacion->id;
    }
        return response(DB::table('mascotas')->where('id_usuario',$id)->get(),200);  
}



}





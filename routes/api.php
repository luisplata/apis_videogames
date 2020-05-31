<?php

use App\Scores;
use App\Videogames;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//despues le hacemos la seguridad de 
Route::post("/guardarData/{videogame}", function (Request $request, $videogame) {
    //buscamos el videogame y guardamos la data
    $video = Videogames::where('name', $videogame)->firstOrFail();
    //convertimos las opciones del json en una variable php
    $opciones = json_decode($video->options);
    $ranking = Scores::where('nombre',  $request->nombre)->first();
    if ($ranking != null) {
        $ranking->score = $request->score;
        $ranking->save();
    } else {
        $ranking = new Scores();
        $ranking->nombre = $request->nombre;
        $ranking->score = $request->score;
        $ranking->videogames_id = $video->id;
        $ranking->save();
    }
    $ranking->videogame;
    return $ranking;
});
Route::post("/crearVideojuego", function (Request $request) {
    $v = new Videogames();
    $v->name = $request->name;
    $v->save();
    return $v;
});

Route::get("/score/best/{videogame}/{numtop?}", function ($videogame, $muntop = 10) {
    $video = Videogames::where('name', $videogame)->firstOrFail();
    $scores = Scores::where('videogames_id', $video->id)->orderBy('score', 'desc')->limit($muntop)
        ->get();
    return $scores;
});

Route::get("/score/{videogame}/{name}", function ($videogame, $nombre) {
    $video = Videogames::where('name', $videogame)->firstOrFail();
    $opciones = json_decode($video->options);
    $participante = new stdClass();
    foreach ($opciones->scores as $key => $value) {
        if ($value->nombre == $nombre) {
            $participante->nombre = $value->nombre;
            $participante->score = $value->score;
        }
    }
    return json_encode($participante);
});

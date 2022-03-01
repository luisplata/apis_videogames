<?php

use App\Models\Scores;
use App\Models\Videogames;
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
    $ranking = Scores::where('nombre',  $request->nombre)->where('videogames_id', $video->id)->first();
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

Route::get("/score/best/{videogame}/{numtop?}/{descendiente?}", function ($videogame, $muntop = 10, $descendiente = 1) {
    $video = Videogames::where('name', $videogame)->firstOrFail();
    $scores = Scores::where('videogames_id', $video->id)->orderBy('score', $descendiente==1?'desc':'asc')->limit($muntop)
        ->get();
    return $scores;
});

Route::get("/score/{videogame}/{name}", function ($videogame, $nombre) {
    $video = Videogames::where('name', $videogame)->firstOrFail();
    $ranking = Scores::where('nombre',  $nombre)->where('videogames_id', $video->id)->first();
    $ranking->videogame;
    return $ranking;
});

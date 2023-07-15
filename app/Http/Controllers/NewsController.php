<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Http\Response;

class NewsController extends Controller
{

    public function index()
    {
        // Lógica para buscar os dados da base de dados
        $news = News::all();

        // Retornar os dados como resposta
        return response()->json($news);
    }

    
    
}

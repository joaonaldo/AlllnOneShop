<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;


class NewController extends Controller
{
    public function createNews(Request $request)
    {
        // Recupera os valores do formulário
        $descricao = $request->input('description');
        $title = $request->input('title');
        $date = $request->input('date');
        
        // Verifica se uma ou mais imagens foram enviadas
        if ($request->hasFile('image1')) {
            $news = new News();
            $news->description = $descricao;
            $news->title = $title;
            $news->date = $date;
            $news->save();
    
            // Obtém o arquivo enviado
            $uploadedImage = $request->file('image1');
            
            // Define o caminho de destino para salvar o arquivo
            $destinationPath = './uploads/';
    
            // Gera um nome único para o arquivo
            $imageName = 'P-' . time() . '.' . $uploadedImage->getClientOriginalExtension();
    
            // Move o arquivo para o destino
            $uploadedImage->move($destinationPath, $imageName);
            
            // Atribui o nome do arquivo à propriedade image1 da instância News
            $news->image1 = $imageName;
            $news->save();
    
            return response()->json(['News' => $news, 'message' => 'CREATED'], 201);
        }
    
        return response()->json(['message' => 'Erro ao criar a notícia' ], 400);
    }
    public function destroy($id)
    {
        // Lógica para excluir o elemento com o ID fornecido
      
        News::findOrFail($id)->delete();
    
        // Retorne uma resposta adequada, como uma mensagem de sucesso
        return response()->json(['message' => 'Elemento excluído com sucesso'], 200);
    }
    
    public function updatenews(Request $request, $id)
    {
        // Find the news instance based on the ID
        $news = News::find($id);
    
        if ($news) {
            // Retrieve the original values from the database
            $originalTitle = $news->title;
            $originalDate = $news->date;
            $originalDescription = $news->description;
            $originalImages1 = $news->image1;
    
            // Retrieve the updated values from the request
            $title = $request->input('title');
            $date = $request->input('date');
            $descricao = $request->input('description');
    
            // Check if the values have changed
            $hasChanges = false;
            if (
                $originalTitle !== $title ||
                $originalDate !== $date ||
                $originalDescription !== $descricao ||
                ($request->hasFile('image1') && $request->file('image1')->getClientOriginalName() !== $originalImages1)
            ) {
                $hasChanges = true;
            }
    
            // Update the properties of the news instance
            $news->title = $title;
            $news->date = $date;
            $news->description = $descricao;
    
            // Check if a new image file was uploaded
            if ($request->hasFile('image1')) {
                // Obtain the uploaded image
                $uploadedImage = $request->file('image1');
    
                // Define the destination path to save the file
                $destinationPath = base_path('public/uploads');
    
                // Generate a unique name for the file
                $imageName = 'P-' . time() . '.' . $uploadedImage->getClientOriginalExtension();
    
                // Move the file to the destination
                $uploadedImage->move($destinationPath, $imageName);
    
                // Assign the new file name to the images1 property of the news instance
                $news->image1 = $imageName;
    
                $hasChanges = true;
            }
    
            // Save the updated news instance if there are changes
            if ($hasChanges) {
                $news->save();
                return response()->json(['news' => $news, 'message' => 'UPDATED'], 200);
            } else {
                return response()->json(['message' => 'No changes detected'], 200);
            }
        }
    
        return response()->json(['message' => 'news not found'], 404);
    }
    
    public function find($id)
    {
        $news = News::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'news' => $news
        ]);
    }
    
    public function searchNewsByTitle(Request $request)
    {
        $title = $request->query('title');
    
        $query = News::query();
    
        if ($title) {
            $query->where('title', 'LIKE', '%' . $title . '%');
        }
    
        $News = $query->get();
    
        return response()->json($News);
    }

}

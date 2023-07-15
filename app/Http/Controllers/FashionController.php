<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Fashion;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class FashionController extends Controller
{
    public function createFashion(Request $request)
    {
        // Recupera os valores do formulário
        $title = $request->input('title');
        $date = $request->input('date');
        $descricao = $request->input('description');
        
        // Verifica se uma ou mais imagens foram enviadas
        if ($request->hasFile('images1')) {
            $Fashion = new Fashion();
            $Fashion->title = $title;
            $Fashion->date = $date;
            $Fashion->description = $descricao;
            $Fashion->save();
    
            // Obtém o arquivo enviado
            $uploadedImage = $request->file('images1');
            
            // Define o caminho de destino para salvar o arquivo
            $destinationPath = './uploads/';
    
            // Gera um nome único para o arquivo
            $imageName = 'P-' . time() . '.' . $uploadedImage->getClientOriginalExtension();
    
            // Move o arquivo para o destino
            $uploadedImage->move($destinationPath, $imageName);
            
            // Atribui o nome do arquivo à propriedade images1 da instância Fashion
            $Fashion->images1 = $imageName;
            $Fashion->save();
    
            return response()->json(['Fashion' => $Fashion, 'message' => 'CREATED'], 201);
        }
    
        return response()->json(['message' => 'Erro ao criar a notícia'], 400);
    }
    
    public function index()
    {
        // Lógica para buscar os dados da base de dados
        $Fashion = Fashion::all();

        // Retornar os dados como resposta
        return response()->json($Fashion);
    }

    public function destroy($id)
    {
        // Lógica para excluir o elemento com o ID fornecido
        // Exemplo:
        Fashion::findOrFail($id)->delete();
    
        // Retorne uma resposta adequada, como uma mensagem de sucesso
        return response()->json(['message' => 'Elemento excluído com sucesso'], 200);
    }

    public function find($id)
    {
        $Fashion = Fashion::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'fashion' => $Fashion
        ]);
    }

    public function searchFashionByTitle(Request $request)
    {
        $title = $request->query('title');
    
        $query = Fashion::query();
    
        if ($title) {
            $query->where('title', 'LIKE', '%' . $title . '%');
        }
    
        $Fashion = $query->get();
    
        return response()->json($Fashion);
    }


    public function updateFashion(Request $request, $id)
    {
        // Find the Fashion instance based on the ID
        $Fashion = Fashion::find($id);
    
        if ($Fashion) {
            // Retrieve the original values from the database
            $originalTitle = $Fashion->title;
            $originalDate = $Fashion->date;
            $originalDescription = $Fashion->description;
            $originalImages1 = $Fashion->images1;
    
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
                ($request->hasFile('images1') && $request->file('images1')->getClientOriginalName() !== $originalImages1)
            ) {
                $hasChanges = true;
            }
    
            // Update the properties of the Fashion instance
            $Fashion->title = $title;
            $Fashion->date = $date;
            $Fashion->description = $descricao;
    
            // Check if a new image file was uploaded
            if ($request->hasFile('images1')) {
                // Obtain the uploaded image
                $uploadedImage = $request->file('images1');
    
                // Define the destination path to save the file
                $destinationPath = base_path('public/uploads');
    
                // Generate a unique name for the file
                $imageName = 'P-' . time() . '.' . $uploadedImage->getClientOriginalExtension();
    
                // Move the file to the destination
                $uploadedImage->move($destinationPath, $imageName);
    
                // Assign the new file name to the images1 property of the Fashion instance
                $Fashion->images1 = $imageName;
    
                $hasChanges = true;
            }
    
            // Save the updated Fashion instance if there are changes
            if ($hasChanges) {
                $Fashion->save();
                return response()->json(['Fashion' => $Fashion, 'message' => 'UPDATED'], 200);
            } else {
                return response()->json(['message' => 'No changes detected'], 200);
            }
        }
    
        return response()->json(['message' => 'Fashion not found'], 404);
    }
    
    
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Comment;
use App\Models\Image;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function createProduct(Request $request)
    {
        // Recupera os valores do formulário
        $nome = $request->input('name');
        $categoria = $request->input('category');
        $preco = $request->input('price');
        $quantidade = $request->input('quantity');
        $descricao = $request->input('description');
    
        // Verifica se uma ou mais imagens foram enviadas
        if ($request->hasFile('images')) {
            $images = $request->file('images');
    
            // Verifica se a matriz de imagens não está vazia
            if (!empty($images)) {
                $product = new Product();
                $product->name = $nome;
                $product->category = $categoria;
                $product->price = $preco;
                $product->quantity = $quantidade;
                $product->description = $descricao;
                $product->save();
    
                foreach ($images as $imagem) {
                    $original_filename = $imagem->getClientOriginalName();
                    $original_filename_arr = explode('.', $original_filename);
                    $file_ext = end($original_filename_arr);
                    $destination_path = './uploads/';
                    $image = 'P-' . time() . '.' . $file_ext;
    
                    if ($imagem->move($destination_path, $image)) {
                        // Cria um novo registro de imagem associado ao produto
                        $imageModel = new Image();
                        $imageModel->product_id = $product->id;
                        $imageModel->filename = $image;
                        $imageModel->save();
                    }
                }
    
                return response()->json(['product' => $product, 'message' => 'CREATED'], 201);
            }
        }
    
        return response()->json(['message' => 'Nenhuma imagem enviada'], 400);
    }
    
    public function index()
    {
        // Lógica para buscar os dados da base de dados
        $products = Product::with('images')->get();

        // Retornar os dados como resposta
        return response()->json($products);
    }

    public function destroy($id)
    {
        // Exclui os comentários associados ao produto
        Comment::where('product_id', $id)->delete();
    
        // Exclui o produto
        Product::findOrFail($id)->delete();
    
        // Retorna uma resposta adequada, como uma mensagem de sucesso
        return response()->json(['message' => 'Produto, comentários e registros de histórico de compra excluídos com sucesso'], 200);
    }

    
    

    public function find($id)
    {
        
        $product = Product::with('images')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }
    
    public function getProductImages($id)
    {
        // Buscar o produto pelo ID
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Produto não encontrado'], 404);
        }

        // Buscar as imagens associadas ao produto
        $images = Image::where('product_id', $id)->get();

        return response()->json(['images' => $images]);
    }
   
    
    public function updateProducts(Request $request, $id)
    {
        // Find the Fashion instance based on the ID
        $product = Product::find($id);
    
        if ($product) {
            // Retrieve the original values from the database
            $originalname = $product->name;
            $originalquantity = $product->quantity;
            $originalcategory = $product->category;
            $originalprice = $product->price;
            $originalDescription = $product->description;
            $originalImages = $product->images;
    
            // Retrieve the updated values from the request
            $name = $request->input('name');
            $price = $request->input('price');
            $quantity = $request->input('quantity');
            $categoy = $request->input('category');
            $description = $request->input('description');
    
            $hasChanges = false;
            if (
                $originalname !== $name ||
                $originalquantity !== $quantity ||
                $originalcategory !== $category ||
                $originalprice !== $price ||
                $originalDescription !== $description ||
                ($request->hasFile('images') && $request->file('images')->getClientOriginalName() !== $originalImages)
            ) {
                $hasChanges = true;
            }
    
            // Update the properties of the Fashion instance
            $product->name = $name;
            $product->quantity = $quantity;
            $product->price = $price;
            $product->description = $description;
    
            // Check if a new image file was uploaded
            if ($request->hasFile('images')) {
                // Obtain the uploaded image
                $uploadedImage = $request->file('images');
    
                // Define the destination path to save the file
                $destinationPath = base_path('public/uploads');
    
                // Generate a unique name for the file
                $imageName = 'P-' . time() . '.' . $uploadedImage->getClientOriginalExtension();
    
                // Move the file to the destination
                $uploadedImage->move($destinationPath, $imageName);
    
                // Assign the new file name to the images1 property of the Fashion instance
                $product->images = $imageName;
    
                $hasChanges = true;
            }
    
            // Save the updated Fashion instance if there are changes
            if ($hasChanges) {
                $product->save();
                return response()->json(['Product' => $product, 'message' => 'UPDATED'], 200);
            } else {
                return response()->json(['message' => 'No changes detected'], 200);
            }
        }
    
        return response()->json(['message' => 'Product not found'], 404);
    }


    public function searchProductByName(Request $request)
    {
        $name = $request->query('name');
        $category = $request->query('category');
    
        $query = Product::query();
    
        if ($name) {
            $query->where('name', 'LIKE', '%' . $name . '%');
        }
    
        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }
    
        $products = $query->get();
    
        // Buscar as imagens associadas a cada produto
        foreach ($products as $product) {
            $images = Image::where('product_id', $product->id)->get();
            $product->images = $images;
        }
    
        return response()->json($products);
    }
    
}

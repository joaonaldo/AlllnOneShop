<?php
 namespace App\Http\Controllers;

 use App\Models\Comment;
 use App\Models\User;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Validator;
 use Illuminate\Validation\ValidationException;
 
 class CommentController extends Controller
 {
     public function store(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'user_id' => 'required|exists:users,id',
             'content' => 'required|string',
             'rating' => 'required|integer',
             'product_id' => 'required|exists:products,id',
         ]);
 
         if ($validator->fails()) {
             throw new ValidationException($validator);
         }
 
         $user = User::find($request->user_id);
         if (!$user) {
             throw new \Exception('User not found');
         }
 
         $comment = Comment::create([
             'user_id' => $request->user_id,
             'name' => $user->name,
             'email' => $user->email,
             'content' => $request->content,
             'rating' => $request->rating,
             'product_id' => $request->product_id,
         ]);
 
         // Outras ações após a criação do comentário, se necessário
 
         return response()->json([
             'message' => 'Comentário adicionado com sucesso!',
             'comment' => $comment,
         ], 201);
     }
     public function getCommentsByProductId($productId)
     {
         $comments = Comment::with('user:id,name')->where('product_id', $productId)->get();
     
         return response()->json([
             'comments' => $comments,
         ], 200);
     }
     
     public function countCommentsByProductId($productId)
     {
         $commentCount = Comment::where('product_id', $productId)->count();
     
         return response()->json([
             'comment_count' => $commentCount,
         ], 200)->header('Access-Control-Allow-Origin', '*');
     }

     public function destroy($id)
     {
         $comment = Comment::find($id);
     
         if (!$comment) {
             throw new \Exception('Comentário não encontrado');
         }
     
         $comment->delete();
     
         return response()->json(['message' => 'Comentário excluído com sucesso'], 200);
     }
     
     
 }
 
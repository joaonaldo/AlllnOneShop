<?php
namespace App\Http\Controllers;

use App\Models\PurchaseHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PurchaseHistoryController extends Controller
{

public function store(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'user_id' => 'required|exists:users,id',
             'date' => 'required|string',
             'address' => 'required|string',
             'numberOrder' => 'required|integer',
             'product_id' => 'required|exists:products,id',
         ]);
 
         if ($validator->fails()) {
             throw new ValidationException($validator);
         }
 
         $user = User::find($request->user_id);
         if (!$user) {
             throw new \Exception('User not found');
         }
 
         $purchasehistory = purchasehistory::create([
             'user_id' => $request->user_id,
             'name' => $user->name,
             'numberOrder' => $request->numberOrder,
             'date' => $request->date,
             'address' => $request->address,
             'product_id' => $request->product_id,
         ]);
 
         return response()->json([
             'message' => 'Comentário adicionado com sucesso!',
             'purchasehistory' => $purchasehistory,
         ], 201);
     }
    }
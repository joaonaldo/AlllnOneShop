<?php
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CommentController;


/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['middleware' => 'cors'], function () use ($router) {
    //All the routes you want to allow CORS for
  
    $router->options('/{any:.*}', function (Request $req) {
      return;
    });

    // API route group
    $router->group(['prefix' => 'api'], function () use ($router) {
        // Matches "/api/register
    $router->post('register', 'AuthController@register');

        // Matches "/api/login
        $router->post('login', 'AuthController@login');

        // Matches "/api/profile
        $router->get('profile', 'UserController@profile');

        // Matches "/api/users/1 
        //get one user by id
        $router->get('users/{id}', 'UserController@singleUser');

        // Matches "/api/users
        $router->get('users', 'UserController@allUsers');

         // Matches "/api/products
         $router->post('products', 'ProductController@createProduct');

         // Matches "/api/News
         $router->post('News', 'NewController@createNews');

         // Matches "/api/News
         $router->post('Fashion', 'FashionController@createFashion');

         $router->get('news', 'NewsController@index');

         $router->get('fashion', 'FashionController@index');

         $router->get('product', 'ProductController@index');
        
         $router->delete('news/{id}', 'NewController@destroy');

         $router->delete('fashion/{id}', 'FashionController@destroy');

         $router->delete('product/{id}', 'ProductController@destroy');


         $router->delete('comment/{id}', 'CommentController@destroy');

         $router->get('product/{id}', 'ProductController@find');

         $router->get('moda/{id}', 'FashionController@find');

         $router->get('products/{id}/images', 'ProductController@getProductImages');

         $router->get('products/{category}', 'ProductController@getProductsByCategory');

         $router->post('logout', 'AuthController@logout');

         $router->post('product/{id}', 'ProductController@updateProducts');

         $router->put('news/{id}', 'NewController@edit');
         
         $router->get('news/{id}', 'NewController@find');

         $router->post('comments', 'CommentController@store');

         $router->get('/products/{productId}/comments', 'CommentController@getCommentsByProductId');

         $router->get('username/{id}', 'UserController@getUsernameById');

         $router->get('username', 'UserController@getAuthenticatedUserName');
         
         $router->get('comments/count/{productId}', 'CommentController@countCommentsByProductId');

         $router->get('produto/search-by-name', 'ProductController@searchProductByName');

         $router->get('fashion/search-by-title', 'FashionController@searchFashionByTitle');

         $router->get('News/search-by-title', 'NewController@searchNewsByTitle');
        
         $router->post('purchasehistory', 'PurchaseHistoryController@store');

         $router->post('fashion/{id}', 'FashionController@updateFashion');

         $router->post('news/{id}', 'newController@updatenews');
    });
});
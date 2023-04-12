<?php

use App\Http\Livewire\Expense\ExpenseCreate;
use App\Http\Livewire\Expense\ExpenseEdit;
use App\Http\Livewire\Expense\ExpenseList;
use Illuminate\Support\Facades\Route;
use \Illuminate\Support\Facades\{Storage, File};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::prefix('expenses')->name('expenses.')->group(function(){

        Route::get('/', ExpenseList::class)->name('index');
        Route::get('/create', ExpenseCreate::class)->name('create');
        Route::get('/edit/{expense}', ExpenseEdit::class)->name('edit');

        Route::get('/{expense}/photo', function($expense) {
            $expense = auth()->user()->expenses()->find($expense);

            //Pegar a imagem
            if(is_null($expense->photo) || !Storage::disk('public')->exists($expense->photo))
                return abort(404, 'Image not found');

            $image = Storage::disk('public')->get($expense->photo);
            $mimeType = File::mimeType(storage_path('app/public/'. $expense->photo));
            
            return response($image)->header('Content-Type', $mimeType);
            //Retornar como imagem
        })->name('photo');

    });
});

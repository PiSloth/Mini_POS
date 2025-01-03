<?php

use App\Http\Controllers\Pdf\InvoiceController;
use App\Livewire\Config\Accounting\PaymentMethod;
use App\Livewire\Config\ProductLocation;
use App\Livewire\Config\ProductSetting\Branch;
use App\Livewire\Config\ProductSetting\Category;
use App\Livewire\Config\ProductSetting\Product;
use App\Livewire\Config\ProductSetting\SubCategory;
use App\Livewire\Crm\Contact;
use App\Livewire\Inventory\Balance;
use App\Livewire\Sale\DailyInvoice;
use App\Livewire\Sale\InvoiceDetail;
use App\Livewire\Sale\SaleInvoice;
use App\Models\BranchProductLocation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Spatie\Browsershot\Browsershot;

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

Route::view('/', 'welcome');
// Route::get('/', function () {

//     $new = ['name' => "မောင်ပိုင်", "nick name", "ကဘူးကီ"];

//     $data = ['Pos', 'God of Men'];
//     $pdf = Pdf::loadView('livewire.sale.invoice-test', ['new' => $new]);
//     return $pdf->download('example.pdf');
// });

Route::get('/test', function () {

    $new = ['name' => "မောင်ပိုင်", "nick name", "ကဘူးကီ"];

    $html = view('livewire.sale.invoice-test', ['new' => $new])->render();

    $pdf = Browsershot::html($html)
        ->pdf();


    return Response($pdf, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="example.pdf"',
        'Content-Length' => strlen($pdf)
    ]);
});

Route::middleware(['auth'])->prefix('config')->group(function () {
    Route::get('/branch', Branch::class)->name('branch');
    Route::get('/category', Category::class)->name('category');
    Route::get('/sub-category', SubCategory::class)->name('sub-category');
    Route::get('/product', Product::class)->name('product');
    Route::get('/item-location', ProductLocation::class)->name('item-location');
    Route::get('/payment-method', PaymentMethod::class)->name('payment-method');
});

Route::middleware(['auth'])->prefix('inventory')->group(function () {
    Route::get('/balance', Balance::class)->name('stock-balance');
});

Route::middleware(['auth'])->prefix('sale')->group(function () {
    Route::get('/invoice', SaleInvoice::class)->name('invoice');
    Route::get('/daily-invoice', DailyInvoice::class)->name('daily-invoice');
    Route::get('/invoice-detail', InvoiceDetail::class)->name('invoice-detail');
});

Route::get('/generate/{id}', [InvoiceController::class, 'generateInvoice']);

Route::middleware(['auth'])->prefix('crm')->group(function () {
    Route::get('/contacts', Contact::class)->name('contact');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';

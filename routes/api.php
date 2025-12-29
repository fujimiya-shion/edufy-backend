<?php

use App\Http\Controllers\Api\V1\Course\CourseController;
use App\Http\Controllers\Api\V1\Course\CourseMediaController;
use App\Http\Controllers\Api\V1\Course\CourseScheduleController;
use App\Http\Controllers\Api\V1\Lesson\LessonController;
use App\Http\Controllers\Api\V1\Lesson\LessonMediaController;
use App\Http\Controllers\Api\V1\Ribbon\RibbonController;
use App\Http\Controllers\Api\V1\Teacher\TeacherController;
use App\Http\Controllers\Api\V1\TrainingCenter\TrainingCenterController;
use App\Http\Controllers\Api\V1\Cart\CartController;
use App\Http\Controllers\Api\V1\Cart\CartItemController;
use App\Http\Controllers\Api\V1\Order\OrderController;
use App\Http\Controllers\Api\V1\Order\OrderItemController;
use App\Http\Controllers\Api\V1\Payment\PaymentController;
use App\Http\Controllers\Api\V1\Payment\PaymentLogController;
use Illuminate\Support\Facades\Route;

Route::prefix('/api/v1')->group(function () {

    Route::controller(RibbonController::class)
        ->prefix('/ribbons')
        ->group(function () {
            Route::get('/', 'index');
        });

    Route::controller(CourseController::class)
        ->prefix('/courses')
        ->group(function () {
            Route::get('/filter', 'filter');
            
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
        });
    
    Route::apiResource('/course-schedules', CourseScheduleController::class)
        ->names('course-schedules')
        ->except(['create', 'edit']);

    Route::apiResource('/course-media', CourseMediaController::class)
        ->names('course-media')
        ->except(['create', 'edit']);

    Route::apiResource('/lessons', LessonController::class)
        ->names('lessons')
        ->except(['create', 'edit']);

    Route::apiResource('/lesson-media', LessonMediaController::class)
        ->names('lesson-media')
        ->except(['create', 'edit']);

    Route::prefix('/teachers')->group(function () {
        Route::get('/search', [TeacherController::class, 'search'])->name('teachers.search');
    });
    
    Route::apiResource('/teachers', TeacherController::class)
        ->names('teachers')
        ->except(['create', 'edit']);

    Route::prefix("/training-centers")->group(function () {
        Route::get('/search', [TrainingCenterController::class, 'search']);
    });

    Route::apiResource('/training-centers', TrainingCenterController::class)
        ->names('training-centers')
        ->except(['create', 'edit']);
});

Route::prefix('/api/v1')->middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/carts', CartController::class)
        ->names('carts')
        ->except(['create', 'edit']);

    Route::apiResource('/cart-items', CartItemController::class)
        ->names('cart-items')
        ->except(['create', 'edit']);

    Route::prefix('/cart')->group(function () {
        Route::post('/add-item', [CartController::class, 'addItem'])->name('cart.add-item');
        Route::delete('/remove-item/{id}', [CartController::class, 'removeItem'])->name('cart.remove-item');
        Route::delete('/clear', [CartController::class, 'clear'])->name('cart.clear');
    });

    Route::apiResource('/orders', OrderController::class)
        ->names('orders')
        ->except(['create', 'edit']);

    Route::apiResource('/order-items', OrderItemController::class)
        ->names('order-items')
        ->except(['create', 'edit']);

    Route::prefix('/orders')->group(function () {
        Route::post('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
        Route::get('/my', [OrderController::class, 'myOrders'])->name('orders.my');
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    });

    Route::apiResource('/payments', PaymentController::class)
        ->names('payments')
        ->except(['create', 'edit']);

    Route::prefix('/payments')->group(function () {
        Route::post('/create-intent', [PaymentController::class, 'createIntent'])->name('payments.create-intent');
        Route::post('/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
        Route::post('/webhook', [PaymentController::class, 'webhook'])->withoutMiddleware('auth:sanctum')->name('payments.webhook');
    });

    Route::apiResource('/payment-logs', PaymentLogController::class)
        ->names('payment-logs')
        ->only(['index', 'show']);
});

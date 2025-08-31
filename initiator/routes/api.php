<?php

use Illuminate\Support\Facades\Route;
use Src\Presentation\Http\Controller\Sync;

Route::put('/sync', Sync::class)->name('api.sync');

<?php

use Illuminate\Support\Facades\Route;
use Src\Presentation\Http\Controller\Command;
use Src\Presentation\Http\Controller\Query;
use Src\Presentation\Http\Controller\Sync;

Route::put('/sync', Sync::class)->name('api.sync');

Route::put('/details/{detailId}/version/{versionId}', [Command::class, 'details']);

Route::get('/brands', [Query::class, 'brands']);
Route::get('/brands/{brandId}', [Query::class, 'details']);

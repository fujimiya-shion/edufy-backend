<?php
namespace App\Http\Controllers\Contracts;

use Illuminate\Http\Request;
interface Searchable {
    public function search(Request $request);
}
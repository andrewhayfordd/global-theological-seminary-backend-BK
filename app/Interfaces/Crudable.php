<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

interface Crudable
{
    public function  create(Request $request, ): Model;
    public function update(Request $request, $value): bool;
    public function delete( ?Request $request, $value): bool;
}

<?php

namespace App\Repositories\Products;

use Illuminate\Support\Str;
use App\Models\Product as Model;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Product extends BaseRepository
{
    protected static $model = Model::class;
    protected static $disk = 'public';

    public static function save($data): array
    {
        static::$rules['save'] = [
            'name' => 'required|unique:products,name,NULL,id,deleted_at,NULL',
            'user_id' => 'required|exists:users,id',
            'manufacture_year' => 'required|integer|between:1900,'.date('Y'),
        ];
        
        if (isset($data['photo']) && $data['photo'] != 'null') {
            $file = $data['photo'];
            $name = uniqid() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $extenion = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $filename = $name . '.' . $extenion;
            // Store data
            Storage::disk(static::$disk)->put("uploads/products/{$data['user_id']}/{$filename}", File::get($file));
            $data['photo'] = $filename;
        } 
        else {
            unset($data['photo']);
        }

        return parent::save($data);
    }

    public static function update($id, $data): array
    {
        static::$rules['update'] = [
            'name' => "required|unique:products,name,{$id},id,deleted_at,NULL",
            'user_id' => 'required|exists:users,id',
            'manufacture_year' => 'required|integer|between:1900,'.date('Y')
        ];

        if (isset($data['photo']) && $data['photo'] != 'null') {
            $file = $data['photo'];
            $name = uniqid() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $extenion = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $filename = $name . '.' . $extenion;
            // Store data
            Storage::disk(static::$disk)->put("uploads/products/{$data['user_id']}/{$filename}", File::get($file));
            $data['photo'] = $filename;
        }
        else {
            unset($data['photo']);
        }

        return parent::update($id, $data);
    }
}
<?php

namespace App\Repositories\Products;

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
            'manufacture_year' => 'required|integer|between:1900,'.date('Y')
        ];
        
        if (isset($data['photo'])) {
            $file = $data['photo'];
            $name = uniqid() . '_' . $file->getClientOriginalName();
            // Store data
            Storage::disk(static::$disk)->put("uploads/activity/{$data['user_id']}/{$name}", File::get($file));
            $data['photo'] = $name;
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

        if (isset($data['photo'])) {
            $file = $data['photo'];
            $name = uniqid() . '_' . $file->getClientOriginalName();
            // Store data
            Storage::disk(static::$disk)->put("uploads/activity/{$data['user_id']}/{$name}", File::get($file));
            $data['photo'] = $name;
        }

        return parent::update($id, $data);
    }
}
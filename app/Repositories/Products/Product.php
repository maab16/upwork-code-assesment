<?php

namespace App\Repositories\Products;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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
            'name' => [
                'required',
                Rule::unique('products')->where(function($query) use($data) {
                    return $query->where('name', $data['name'])->where('user_id', $data['user_id'])->whereNull('deleted_at');
                })
            ],
            'user_id' => 'required|exists:users,id',
            'manufacture_year' => 'required|integer|between:1900,'.date('Y'),
        ];
        
        if (isset($data['photo']) && $data['photo'] != 'null') {
            $path = 'uploads/products';

            if (!is_dir($path)) {
                File::makeDirectory($path, 0777,true);
            }

            $file = $data['photo'];
            $name = uniqid() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $extenion = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $filename = $name . '.' . $extenion;
            // Store data
            Storage::disk(static::$disk)->put("{$path}/{$data['user_id']}/{$filename}", File::get($file));
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
            'name' => [
                'required',
                Rule::unique('products')->where(function($query) use($data) {
                    return $query->where('name', $data['name'])->where('user_id', $data['user_id'])->whereNull('deleted_at');
                })->ignore($id)
            ],
            'user_id' => 'required|exists:users,id',
            'manufacture_year' => 'required|integer|between:1900,'.date('Y')
        ];

        if (isset($data['photo']) && $data['photo'] != 'null') {
            $path = 'uploads/products';

            if (!is_dir($path)) {
                File::makeDirectory($path, 0777,true);
            }
            $file = $data['photo'];
            $name = uniqid() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $extenion = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $filename = $name . '.' . $extenion;
            // Store data
            Storage::disk(static::$disk)->put("{$path}/{$data['user_id']}/{$filename}", File::get($file));
            $data['photo'] = $filename;
        }
        else {
            unset($data['photo']);
        }

        return parent::update($id, $data);
    }
}
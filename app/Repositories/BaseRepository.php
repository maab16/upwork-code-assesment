<?php

namespace App\Repositories;

/**
 * BaseRepository class
 * 
 * @author Md Abu Ahsan Basir <maab.career@gmail.com>
 * @package App\Repositories
 *
 */

use Exception;
use App\Contracts\Repository;
use Illuminate\Support\Facades\Validator;

abstract class BaseRepository implements Repository
{
    protected static $rules = [];

    protected static $exactSearchFields = ['id'];

    /**
     * Get all models.
     * 
     * @param   array $options
     * @param   array $relations
     * @param   array $fields
     * 
     * @return array
     * 
     */
    public static function all($options = [], $relations = [], $fields = []): array
    {
        return static::get($relations, $options, $fields);
    }

    /**
     * Get models with selected columns/fields.
     * 
     * @param   array $fields
     * @param   array $options
     * @param   array $relations
     * 
     * @return array
     * 
     */
    public static function select($fields = [], $options = [], $relations = []): array
    {
        return static::get($relations, $options, $fields);
    }
    
    /**
     * Get models.
     * 
     * @param   array $relations
     * @param   array $options
     * @param   array $fields
     * 
     * @return array
     * 
     */
    public static function get($relations = [], $options = [], $fields = []): array
    {
        try {
            // Get Model query instance.
            $query = static::$model::query();
            // Set options.
            foreach ($options as $option => $value) {
                if (is_array($value)) {
                    $query->{$option}(...$value);
                } else {
                    $query->{$option}($value);
                }
            }

            if (is_array($fields) && sizeof($fields) > 0) {
                $query = $query->selectRaw(implode(',', $fields));
            }
            else if (is_string($fields) && !empty($fields)) {
                $query = $query->selectRaw($fields);
            }

            $models = $query->with($relations)->get();
 
            return [
                'status' => 'success',
                'data' => $models
            ];
        } catch (Exception $ex) {
            return [
                'status' => 'error',
                'data' => $ex->getMessage()
            ];
        }
    }
    

    /**
     * Get a model.
     * 
     * @param   integer $id
     * @param   array $realtions
     * 
     * @return array
     * 
     */
    public static function first($value, $field = 'id', $realtions = []): array
    {
        try {
            // Get first result.
            $model = static::$model::where($field, $value)->with($realtions)->first();
 
            // Return model if data fetched successfully.
            if ($model) {
                return [
                    'status' => 'success',
                    'data' => $model
                ];
            }
            // Return error if model doesn't find.
            return [
                'status' => 'error',
                'data' => "Illuminate\Database\Eloquent\ModelNotFoundException with message No query results for model " . Model::class . " " . $value
            ];
        } catch (Exception $ex) {
            return [
                'status' => 'error',
                'data' => $ex->getMessage()
            ];
        }
    }

    /**
     * Search model.
     * 
     * @param   array $data
     * @param   array $options
     * @param   array $relations
     * 
     * @return array
     * 
     */
    public static function search($data = [], $options = [], $relations = []): array
    {
        try {
            // Get Model query instance.
            $query = static::$model::query();
            // Set fields
            foreach ($data as $field => $value) {
                switch ($field) {
                    case in_array($field, static::$exactSearchFields):
                        $query->where($field, $value);
                        break;
                    default:
                        $query->where($field, 'LIKE', "%{$value}%");
                        break;
                }
            }
            // Set options.
            foreach ($options as $option => $value) {
                if (is_array($value)) {
                    $query->{$option}(...$value);
                } else {
                    $query->{$option}($value);
                }
            }

            $models = $query->with($relations)->get();
 
            return [
                'status' => 'success',
                'data' => $models
            ];
        } catch (Exception $ex) {
            return [
                'status' => 'error',
                'data' => $ex->getMessage()
            ];
        }
    }
    
    /**
     * Create a model.
     * 
     * @param   array|object $data
     * 
     * @return array
     * 
     */
    public static function save($data): array
    {
        try {
            // Check $data either array nor object otherwise return the error.
            if (! is_array($data) && ! is_object($data)) {
                return [
                    'status' => 'error',
                    'data' => ['You must provide data as associative array or object.']
                ];
            }
            // If $data is object then convert into array.
            if (is_object($data)) {
                $data = (array) $data;
            }
            // Check validation.
            $validator = Validator::make($data, static::$rules['save']);
            // If validation failed then return the error.
            if ($validator->fails()) {
                return [
                    'status' => 'error',
                    'data' => $validator->errors()->toArray()
                ];
            }
            // Create new Model instance.
            $model = new static::$model;

            // Bind all fields and values into model instance.
            foreach ($data as $field => $value) {
                $model->{$field} = $value;
            }
            // Save the model.
            $model->save();
            // Return model if saved successfully.
            if ($model) {
                return [
                    'status' => 'success',
                    'data' => $model
                ];
            }
            // Return error if model not created successfully.
            return [
                'status' => 'error',
                'data' => "There is a problem to create new model please check manually."
            ];

        } catch (Exception $ex) {
            // Return exception error if someything happen wrong.
            return [
                'status' => 'error',
                'data' => $ex->getMessage()
            ];
        }
    }

    /**
     * Update a model.
     * 
     * @param   integer $id
     * @param   array|object $data
     * 
     * @return array
     * 
     */
    public static function update($id, $data): array
    {
        try {
            // Check $data either array nor object otherwise return the error.
            if (! is_array($data) && ! is_object($data)) {
                return [
                    'status' => 'error',
                    'data' => ['You must provide data as associative array or object.']
                ];
            }
            // If $data is object then convert into array.
            if (is_object($data)) {
                $data = (array) $data;
            }
            // Check validation.
            $validator = Validator::make($data, static::$rules['update']);
            // If validation failed then return the error.
            if ($validator->fails()) {
                return [
                    'status' => 'error',
                    'data' => $validator->errors()->toArray()
                ];
            }
            // Create new Model instance.
            $model =  static::$model::find($id);
            // Bind all fields and values into model instance.
            foreach ($data as $field => $value) {
                $model->{$field} = $value;
            }
            // Save the model.
            $model->save();
            // Return model if saved successfully.
            if ($model) {
                return [
                    'status' => 'success',
                    'data' => $model
                ];
            }
            // Return error if model not created successfully.
            return [
                'status' => 'error',
                'data' => "Illuminate\Database\Eloquent\ModelNotFoundException with message No query results for model " . Model::class . " " . $id
            ];

        } catch (Exception $ex) {
            // Return exception error if someything happen wrong.
            return [
                'status' => 'error',
                'data' => $ex->getMessage()
            ];
        }
    }

    /**
     * Delete a model.
     * 
     * @param   integer $id
     * 
     * @return array
     * 
     */
    public static function delete($id): array
    {
        try {
    
            // Get Model instance by $id.
            $model = static::$model::find($id);
 
            // Return model if saved successfully.
            if ($model) {
                $model->delete();
                return [
                    'status' => 'success',
                    'data' => $model
                ];
            }
            // Return error if model not created successfully.
            return [
                'status' => 'error',
                'data' => "Illuminate\Database\Eloquent\ModelNotFoundException with message No query results for model " . Model::class . " " . $id
            ];

        } catch (Exception $ex) {
            // Return exception error if someything happen wrong.
            return [
                'status' => 'error',
                'data' => $ex->getMessage()
            ];
        }
    }
}
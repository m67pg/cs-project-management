<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;

/**
 * ベースリポジトリ
 */
abstract class BaseRepository
{
    protected $model;

    /**
     * 新しいインスタンスの生成
     */
    public function __construct()
    {
        Log::debug('BaseRepository::__construct()');

        $this->getModel();
    }

    /**
     * モデルの取得
     */
    abstract protected function getModel();

    /**
     * モデル名の取得
     */
    public function getModelName() {
        $model_name = '';
        $model_name_split = mb_str_split(array_slice(explode('\\', get_class($this->model)), -1)[0]);

        foreach ($model_name_split as $key => $value) {
            if (ctype_upper($value)) {
                if ($key > 0) {
                    $model_name .= '_';
                }
                $model_name .= mb_strtolower($value);
            } else {
                $model_name .= $value;
            }
        }

        return $model_name;
    }

    /**
     * テーブル名の取得
     */
    public function getTableName() {
        return $this->model->getTable();
    }

    /**
     * 一覧の取得
     *
     * @param  array  $input
     * @return array
     */
    abstract public function get($input = []);

    /**
     * モデルの取得
     *
     * @param  int     $id
     * @return mixed
     */
    public function find($id)
    {
        Log::debug('BaseRepository::find()');

        return $this->model->find($id);
    }

    /**
     * モデルの保存
     *
     * @param  array   $input
     * @param  int     $id
     * @return mixed
     */
    public function save($input, $id = 0)
    {
        Log::debug('BaseRepository::save()');

        if ($id == 0) {
            $base = $this->model;
        } else {
            $base = $this->model->find($id);
        }
        $base->fill($input)->save();

        return $base;
    }
}

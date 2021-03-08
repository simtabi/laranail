<?php

namespace Simtabi\Laranail\Repositories\Eloquent;



use Simtabi\Laranail\Repositories\Interfaces\CommonFunctionsInterface;

class CommonFunctionsRepository extends RepositoriesAbstract implements CommonFunctionsInterface
{

    protected $table = null;

    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    public function setScreen(string $screen)
    {
        $this->screen = $screen;
        return $this;
    }

    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    public function getTable()
    {
        return !empty($this->table) ? $this->table : parent::getTable();
    }


    //===============================================================**
    // General
    //==============================================================**/

    public function getDataSiteMap(array $conditions = [], array $orderBy = [], $limit = 10, $paginate = 0)
    {
        $data = $this->model;

        if (is_array($conditions) && !empty($conditions)){
            $data->where($conditions);
        }
        else{
            $data->where($this->getTable() . '.status', '=', 1);
        }

        if (is_array($orderBy) && !empty($orderBy)){
            foreach ($orderBy as $by => $direction) {
                $data->orderBy($by, $direction);
            }
        }
        else{
            $data->orderBy($this->getTable() . '.created_at', 'desc');
        }

         $data->select($this->getTable() . '.*');

        if ($limit) {
            $data->limit($limit);
        }

        if ($paginate) {
            return $this->applyBeforeExecuteQuery($data, $this->screen)->paginate($paginate);
        }
        return $this->applyBeforeExecuteQuery($data, $this->screen)->get();
    }


    //===============================================================**
    // Search & filter Queries
    //==============================================================**/

    public function getSearch($query, array $conditions = [], array $orderBy = [], $limit = 10, $paginate = 0){

        $data = $this->model;
        if (is_array($conditions) && !empty($conditions)){
            $data->where($conditions);
        }

        if (is_array($orderBy) && !empty($orderBy)){
            foreach ($orderBy as $by => $direction) {
                $data->orderBy($by, $direction);
            }
        }
        else{
            $data->orderBy($this->getTable() . '.created_at', 'desc');
        }


        foreach (explode(' ', $query) as $term) {
            $data->where('name', 'LIKE', '%' . $term . '%');
        }

        $data->select($this->getTable() . '.*');

        if ($limit) {
            $data->limit($limit);
        }

        if ($paginate) {
            return $this->applyBeforeExecuteQuery($data, $this->screen)->paginate($paginate);
        }
        return $this->applyBeforeExecuteQuery($data, $this->screen)->get();
    }



    //===============================================================**
    // Query get many
    //==============================================================**/

    public function getAll(array $select = [], array $conditions = [], array $orderBy = [], $limit = 10, $paginate = 0){

        $data = $this->model;

        if (is_array($select) && !empty($select)){
            $data->select($select);
        }else{
            $data->select($this->getTable() . '.*');
        }


        if (is_array($conditions) && !empty($conditions)){
             $data->where($conditions);
        }

        if (is_array($orderBy) && !empty($orderBy)){
            foreach ($orderBy as $by => $direction) {
                $data->orderBy($by, $direction);
            }
        }
        else{
            $data->orderBy($this->getTable() . '.created_at', 'desc');
        }

        if ($limit) {
            $data->limit($limit);
        }

        if ($paginate) {
            return $this->applyBeforeExecuteQuery($data, $this->screen)->paginate($paginate);
        }
        return $this->applyBeforeExecuteQuery($data, $this->screen)->get();
    }


    public function getAllCreatedAt($time, array $conditions = [],array $select = [],  array $orderBy = [], $limit = 10, $paginate = 0){
        $args = ['created_at' => $time];
        if (!empty($conditions) && is_array($conditions)){
            $args = array_merge($conditions, $args);
        }
        return $this->getAll( $select, $args, $orderBy, $limit, $paginate);
    }

    public function getAllUpdatedAt($time, array $conditions = [],array $select = [],  array $orderBy = [], $limit = 10, $paginate = 0){
        $args = ['updated_at' => $time];
        if (!empty($conditions) && is_array($conditions)){
            $args = array_merge($conditions, $args);
        }
        return $this->getAll( $select, $args, $orderBy, $limit, $paginate);
    }

    public function getAllCreatedAtInRange($from, $to, array $conditions = [], array $select = [], array $orderBy = [], $limit = 10, $paginate = 0){

        $data = $this->model;

        $data
            ->where([$this->getTable() . '.created_at' => $from])
            ->where([$this->getTable() . '.created_at' => $to]);

        if (is_array($select) && !empty($select)){
            $data->select($select);
        }else{
            $data->select($this->getTable() . '.*');
        }


        if (is_array($conditions) && !empty($conditions)){
            $data->where($conditions);
        }

        if (is_array($orderBy) && !empty($orderBy)){
            foreach ($orderBy as $by => $direction) {
                $data->orderBy($by, $direction);
            }
        }
        else{
            $data->orderBy($this->getTable() . '.id', 'desc');
        }

        if ($limit) {
            $data->limit($limit);
        }

        if ($paginate) {
            return $this->applyBeforeExecuteQuery($data, $this->screen)->paginate($paginate);
        }
        return $this->applyBeforeExecuteQuery($data, $this->screen)->get();

    }

    public function getAllUpdatedAtInRange($from, $to, array $conditions = [], array $select = [], array $orderBy = [], $limit = 10, $paginate = 0){

        $data = $this->model;

        $data
            ->where([$this->getTable() . '.updated_at' => $from])
            ->where([$this->getTable() . '.updated_at' => $to]);

        if (is_array($select) && !empty($select)){
            $data->select($select);
        }else{
            $data->select($this->getTable() . '.*');
        }


        if (is_array($conditions) && !empty($conditions)){
            $data->where($conditions);
        }

        if (is_array($orderBy) && !empty($orderBy)){
            foreach ($orderBy as $by => $direction) {
                $data->orderBy($by, $direction);
            }
        }
        else{
            $data->orderBy($this->getTable() . '.id', 'desc');
        }

        if ($limit) {
            $data->limit($limit);
        }

        if ($paginate) {
            return $this->applyBeforeExecuteQuery($data, $this->screen)->paginate($paginate);
        }
        return $this->applyBeforeExecuteQuery($data, $this->screen)->get();

    }



    //===============================================================**
    // Query get related
    //==============================================================**/

    public function getRelated(array $conditions = [], $limit = 10, $paginate = 0){
    }

    public function getRelatedIds($model){
    }

    public function getRelatedByIds($model){
    }



    //===============================================================**
    // Query get popular
    //==============================================================**/

    public function getPopular(array $conditions = [], $limit = 10, $paginate = 0){
    }



    //===============================================================**
    // Query get Featured
    //==============================================================**/

    public function getFeatured(array $conditions = [], $limit = 10, $paginate = 0){
    }



    //===============================================================**
    // Query Next & Previous records
    //==============================================================**/

    public function getNextAndPrevious($request, $value, array $select = [], array $orderBy = [], $take = 10, $paginate = 0){

        // init model
        $model = $this->model;

        // if select
        if (is_array($select) && !empty($select)){
            $model->select($select);
        }else{
            $model->select($this->getTable() . '.*');
        }

        // if orderby
        if (is_array($orderBy) && !empty($orderBy)){
            foreach ($orderBy as $by => $direction) {
                $model->orderBy($by, strtoupper($direction));
            }
        }
        else{
            $model->orderBy($this->getTable() . '.id', 'desc');
        }

        // if limit
        if ($take && is_integer($take)) {
            $previous = $model::where($request, '<', $value)->take($take)->get();
            $next     = $model::where($request, '>', $value)->take($take)->get();

            // if paginate
            if ($paginate) {
                $previous = $this->applyBeforeExecuteQuery($previous, $this->screen)->paginate($paginate);
                $next     = $this->applyBeforeExecuteQuery($next, $this->screen)->paginate($paginate);
            }
            else{
                $previous = $this->applyBeforeExecuteQuery($previous, $this->screen)->get();
                $next     = $this->applyBeforeExecuteQuery($next, $this->screen)->get();
            }
        }
        else{
            $previous = $model::where($request, '<', $value)->first();
            $next     = $model::where($request, '>', $value)->first();
        }

        return [
            'previous' => $previous,
            'next'     => $next,
        ];

    }


}

<?php

namespace Simtabi\Laranail\Repositories\Interfaces;

interface CommonFunctionsInterface
{



    //===============================================================**
    // General
    //==============================================================**/

    public function getDataSiteMap(array $conditions = [], array $orderBy = [], $limit = 10, $paginate = 0);



    //===============================================================**
    // Search & filter Queries
    //==============================================================**/

    public function getSearch($query, array $conditions = [], array $orderBy = [], $limit = 10, $paginate = 0);



    //===============================================================**
    // Query get many
    //==============================================================**/

    public function getAll(array $select = [], array $conditions = [], array $orderBy = [], $limit = 10, $paginate = 0);

    public function getAllCreatedAt($time, array $conditions = [],array $select = [],  array $orderBy = [], $limit = 10, $paginate = 0);

    public function getAllUpdatedAt($time, array $conditions = [],array $select = [],  array $orderBy = [], $limit = 10, $paginate = 0);

    public function getAllCreatedAtInRange($from, $to, array $conditions = [], array $select = [], array $orderBy = [], $limit = 10, $paginate = 0);

    public function getAllUpdatedAtInRange($from, $to, array $conditions = [], array $select = [], array $orderBy = [], $limit = 10, $paginate = 0);



    //===============================================================**
    // Query get related
    //==============================================================**/

    public function getRelated(array $conditions = [], $limit = 10, $paginate = 0);

    public function getRelatedIds($model);

    public function getRelatedByIds($model);



    //===============================================================**
    // Query get popular
    //==============================================================**/

    public function getPopular(array $conditions = [], $limit = 10, $paginate = 0);


    //===============================================================**
    // Query get Featured
    //==============================================================**/

    public function getFeatured(array $conditions = [], $limit = 10, $paginate = 0);


    //===============================================================**
    // Query Next & Previous records
    //==============================================================**/

    public function getNextAndPrevious($request, $value, array $select = [], array $orderBy = [], $take = 10, $paginate = 0);

}

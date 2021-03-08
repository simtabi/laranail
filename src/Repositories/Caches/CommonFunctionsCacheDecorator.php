<?php

namespace Simtabi\Laranail\Repositories\Caches;


use Simtabi\Laranail\Repositories\Interfaces\CommonFunctionsInterface;

class CommonFunctionsCacheDecorator extends CacheAbstractDecorator implements CommonFunctionsInterface
{
    /**
     * @var CommonFunctionsInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * CategoryCacheDecorator constructor.
     * @param CommonFunctionsInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(CommonFunctionsInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }



    //===============================================================**
    // General
    //==============================================================**/

    public function getDataSiteMap(array $conditions = [], array $orderBy = [], $limit = 10, $paginate = 0)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }



    //===============================================================**
    // Search & filter Queries
    //==============================================================**/

    public function getSearch($query, array $conditions = [], array $orderBy = [], $limit = 10, $paginate = 0)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }



    //===============================================================**
    // Query get many
    //==============================================================**/

    public function getAll(array $select = [], array $conditions = [], array $orderBy = [], $limit = 10, $paginate = 0)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getAllCreatedAt($time, array $conditions = [], array $select = [], array $orderBy = [], $limit = 10, $paginate = 0)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getAllUpdatedAt($time, array $conditions = [], array $select = [], array $orderBy = [], $limit = 10, $paginate = 0)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getAllCreatedAtInRange($from, $to, array $conditions = [], array $select = [], array $orderBy = [], $limit = 10, $paginate = 0)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getAllUpdatedAtInRange($from, $to, array $conditions = [], array $select = [], array $orderBy = [], $limit = 10, $paginate = 0)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }



    //===============================================================**
    // Query get related
    //==============================================================**/

    public function getRelated(array $conditions = [], $limit = 10, $paginate = 0)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getRelatedIds($model)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getRelatedByIds($model)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }



    //===============================================================**
    // Query get popular
    //==============================================================**/

    public function getPopular(array $conditions = [], $limit = 10, $paginate = 0)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }



    //===============================================================**
    // Query get Featured
    //==============================================================**/

    public function getFeatured(array $conditions = [], $limit = 10, $paginate = 0)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }


    //===============================================================**
    // Query Next & Previous records
    //==============================================================**/

    public function getNextAndPrevious($request, $value, array $select = [], array $orderBy = [], $take = 10, $paginate = 0)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

}

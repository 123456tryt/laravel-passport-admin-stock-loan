<?php

namespace App\Http\Controllers\Load;

trait ShowBaseTrait
{
    //常用过滤参数
    static protected $base_params = ['field', 'where', 'whereIn', 'has', 'count', 'order', 'offset', 'limit', 'load'];
    //load懒惰式加载路径参数
    static protected $load_paths = [];
    //已处理的load_path;
    static protected $loaded_paths = [];

    //缓存数据，没有则通过_run_orm函数获取
    private static function _cache_run_orm($params = [], $id = false, $extra = [])
    {
        //设置缓存标签
        $cache_tags = explode('/', $_SERVER['REQUEST_URI']);
        array_shift($cache_tags);
        //设置缓存名字（为加密参数）
        $cache_key = md5(json_encode($params));
        //随机缓存时间，让缓存不致于同时消失，提高页面访问速度
        $rs = \Cache::tags($cache_tags)->remember($cache_key, mt_rand(1, 3), function () use ($params, $id, $extra) {
            return static::_run_orm($params, $id, $extra);
        });
        //返回缓存后的数据
        return $rs;
    }

    //关联模型查询
    private static function _run_orm($params = [], $id = false)
    {
        //获取模型名字
        $model_name = static::$model_name;
        //获取模型路径
        $model_path = 'App\Http\Model\\' . $model_name;
        //自动创造load参数（有此参数需load懒惰式加载）
        static::_get_prams_load($params, $id);
        //很关键 query方法返回一个EloquentBuilder模型 可以连贯查询操作
        $Model = $model_path::query();
        //with预加载（相对的是load懒惰式加载）
        $Model = static::_orm_with($params, $Model, $model_name, true);
        //if详情else列表
        if ($id !== false) {
            $rs = $Model->first();
            //若有load参数 则load懒惰式加载
            if (static::$load_paths) {
                static::_orm_load($rs, $params, static::$load_paths);
            }
        } else {
            $rs['list'] = $Model->get();
            //若有load参数 则load懒惰式加载
            if (static::$load_paths) {
                static::_orm_load($rs['list'], $params, static::$load_paths);
            }
            //若有count参数 则增加count字段
            $rs['count'] = empty($params['count']) ? '' : $Model->count;
        }
        //返回数据
        return $rs;
    }

    //递归执行 自动给params增加load=>true字段 同时赋值$load_paths静态变量（load懒惰式加载路径参数）
    private static function _get_prams_load(&$params, $first_id = false, $limit_lever = 1, $path = [], &$load = [])
    {
        //循环分析参数
        foreach ($params as $k => &$v) {
            //若不为数组或者参数为常用过滤参数
            if (!is_array($v) || in_array($v, static::$base_params)) continue;
            //头一层循环根据是否含limit初始化$limit_lever 此参数是判断是否需要load加载的关键
            if ($first_id) {
                $limit_lever--;
            }
            //将路径存入新变量v_path 避免污染下次循环的path变量
            $v_path = $path;
            array_push($v_path, $k);
            //若limit在一个链条上出现1次以上 则增加load字段（因为只有1次的话 直接能用with加载解决）
            if (isset($v['limit'])) {
                $limit_lever++;
                if ($limit_lever > 1) {
                    //保存需load的模型路径以及load参数
                    $v['load'] = 'true';
                    $load[] = $v_path;
                }
            }
            //引用自己递归执行
            static::_get_prams_load($v, false, $limit_lever, $v_path, $load);
        }
        //排序（应为load执行有顺序要求）
        if ($load) {
            asort($load);
            static::$load_paths = $load;
        }
        //终止递归继续执行；
        return;
    }

    //递归执行 orm with预加载
    private static function _orm_with($params = [], $Model, $model_name, $first_count = false)
    {
        //两层过滤 第一层为数量过滤 第二层为字段、排序等其他过滤
        $Model = static::_filter_orm_1($params, $Model, $model_name);
        //若是第一次循环且需要count 则添加count属性
        if ($first_count && !empty($params['count'])) $Model->count = $Model->count();
        $Model = static::_filter_orm_2($params, $Model, $model_name);
        //过滤掉params中的常用过滤参数
        $params = array_filter($params, function ($key) {
            return !in_array($key, static::$base_params);
        }, ARRAY_FILTER_USE_KEY);
        //循环分析参数
        foreach ($params as $k_model_name => $v_params) {
            //若有count 直接withCount增加 model_count字段
            if (isset($v_params['count']) && $v_params['count']) {
                $Model->withCount([$k_model_name => function ($k_model) use ($v_params, $k_model_name) {
                    static::_filter_orm_1($v_params, $k_model, $k_model_name);
                }]);
                unset($v_params['count']);
            }
            //若存在load字段 则放弃（需load加载处理）
            if (isset($v_params['load'])) continue;//表明只能通过_orm_load加载
            //若排除掉count字段 load字段 还有其它字段 则with加载
            if ($v_params) {
                $Model->with([$k_model_name => function ($k_model) use ($v_params, $k_model_name) {
                    //引用自己 递归执行
                    static::_orm_with($v_params, $k_model, $k_model_name);
                }]);
            }


        }
        //返回with加载结果 同时终止递归
        return $Model;
    }

    //递归执行 递归load懒惰式加载（递归需特别注意 能放到循环外就放到循环执行 可以大大加快执行速度）
    private static function _orm_load($rs, $load_params, $load_paths, $parent_path = [])
    {
        //对每一个需要load的参数 依次进行处理
        foreach ($load_paths as $path) {
            //若在递归中已经处理的 则不去处理
            if (in_array($path, static::$loaded_paths)) continue;
            //递归时，一个链条上，接着上个节点的参数进行处理
            $new_path = array_diff($path, $parent_path);
            //获取要加载的模型名
            $model_name = end($new_path);
            //避免参数在下个循环中被污染
            $params = $load_params;
            foreach ($new_path as $v_new_path) {
                $params = $params[$v_new_path];
            }
            //将加载路径存入全局静态变量
            static::$loaded_paths[] = $path;
            //删除已经加载的path 减少循环次数
            unset($load_paths[array_search($path, $load_paths)]);
            //对需要处理的每一个结果 进行load加载
            foreach ($rs as $v) {
                //循环只是为了获取加载的位置
                foreach ($new_path as $v_new_path) {
                    if ($v_new_path != $model_name) {
                        $v = $v->$v_new_path;
                    }
                }
                //正式加载
                $new_prams = $params;
                $v->load([$model_name => function ($Model) use ($new_prams, $model_name) {
                    //调用with加载嵌套方法
                    static::_orm_with($new_prams, $Model, $model_name);
                }]);
                //提取仅在一个链条上 还未加载的路径
                foreach ($load_paths as $v_path) {
                    if ($path != array_intersect($path, $v_path)) continue;
                    $new_load_paths[] = $v_path;
                }
                //若没有可加载 则无需递归 直接进入下一个循环
                if (empty($new_load_paths)) continue;
                //新的递归
                static::_orm_load($v->$model_name, $new_prams, $new_load_paths, $path);
            }
        }
    }

    //model 第一层数量过滤 withCount使用正好
    private static function _filter_orm_1($params, $Model, $model_name)
    {
        //where过滤
        if (!empty($params['where'])) $Model->where($params['where']);
        //whereIn过滤
        if (!empty($params['whereIn'])) $Model->whereIn($params['whereIn'][0], $params['whereIn'][1]);
        //whereMust过滤
        if (!empty(config('select.' . $model_name . '.whereMust'))) $Model->whereRaw(config('select.' . $model_name . '.whereMust'));
        //has过滤
        if (!empty($params['has'])) {
            foreach ($params['has'] as $has) {
                $Model->has($has);
            }
        }
        return $Model;
    }

    //model 第二层为字段、排序等其他过滤
    private static function _filter_orm_2($params, $Model, $model_name)
    {
        //字段过滤 必须有 若无 则根据配置决定
        $select = empty($params['field']) ? config('select.' . $model_name . '.field') : $params['field'];
        $select = $select ? implode(',', $select) : '*';
        $Model->selectRaw($select);
        //排序并限制数量
        if (!empty($params['order'])) $Model->orderByRaw($params['order']);
        if (!empty($params['offset'])) $Model->offset($params['offset']);
        //limit字段若无 则根据配置决定（limit=>1）
        if (!isset($params['limit'])) $limit = config('select.limit_default');
        //若想设置无限制 则limit需设置为0
        else $limit = $params['limit'] == 0 ? config('select.limit_max') : $params['limit'];
        $Model->limit($limit);
        return $Model;
    }
}
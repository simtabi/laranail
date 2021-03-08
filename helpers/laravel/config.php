<?php


if (!function_exists('buildFormInputSelectionData')) {
    /**
     * @param $request
     * @return array
     */
    function buildFormInputSelectionData($request){
        $data = Helpers::getFromArray($request, [
            'bool_yes' => [
                0 => trans('patch.base::forms.fields.no.label'),
                1 => trans('patch.base::forms.fields.yes.label'),
            ],
            'bool_activate' => [
                0 => trans('patch.base::forms.fields.deactivated.label'),
                1 => trans('patch.base::forms.fields.activated.label'),
            ],
            'publish_mode' => [
                'published' => trans('patch.base::forms.fields.published.label'),
                'unpublished' => trans('patch.base::forms.fields.unpublished.label'),
                'scheduled' => trans('patch.base::forms.fields.scheduled.label'),
            ],
        ]);

        $results = [];
        if (is_array($data) && !empty($data)){
            foreach ($data as $key => $item) {
                $results[$key] = $item;
            }
        }else{
            $results = [
                'publish' => trans('patch.base::forms.nothing_to_show'),
            ];
        }
        return $results;
    }
}


if (!function_exists('getPatchConfig')) {
    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    function getPatchConfig($key, $default = null ){
        return arrayFetch($key, config('patch.base.general'), $default );
    }
}


if (!function_exists('getPatchConfigValue')) {
    /**
     * @param $request
     * @param $path
     * @param null $default
     * @return mixed|null|string
     */
    function getPatchConfigValue($request, $path, $default = null){
        $data = getPatchConfig($path, $default );
        if (is_array($data) && array_key_exists(trim($request), $data)){
            $out = getPatchConfig("$path.$request", $default );
            if (!is_array($out) && !is_object($out)){
                return ucwords($out);
            }
            return $out;
        }
        return null;
    }
}


if (!function_exists('getFormYesNoSelectionList')) {
    /**
     * @return array
     */
    function getFormYesNoSelectionList()
    {
        return buildFormInputSelectionData('bool_yes');
    }
}

if (!function_exists('getFormActivatedSelectionList')) {
    /**
     * @return array
     */
    function getFormActivatedSelectionList()
    {
        return buildFormInputSelectionData('bool_activate');
    }
}

if (!function_exists('getFormPublishModeSelectionList')) {
    /**
     * @return array
     * @author Sang Nguyen
     * @since 16-09-2016
     */
    function getFormPublishModeSelectionList()
    {
        return buildFormInputSelectionData('publish_mode');
    }
}

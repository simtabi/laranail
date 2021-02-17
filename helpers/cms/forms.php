<?php

use Illuminate\Support\Facades\URL;


if (!function_exists('getFormSelectionDataAndHelperText')) {
    /**
     * @param $data
     * @param bool $getData
     * @param string $noDataLangString
     * @param string $helpBlockHasDataLangString
     * @param string $helpBlockNoDataLangString
     * @return array
     */
    function getFormSelectionDataAndHelperText ($data, $getData = true, $noDataLangString = 'patch.base::forms.no_data.label', $helpBlockHasDataLangString = 'patch.base::forms.loading_data.help', $helpBlockNoDataLangString = 'patch.base::forms.no_data.help') {
        if ($getData){
            if (empty($data) || !is_array($data)){
                return [
                    0 => trans((string)$noDataLangString),
                ];
            }else{
                return $data;
            }
        }else{
            if (empty(!$data) && is_array($data)){
                return [
                    'text'  => trans((string)$helpBlockHasDataLangString),
                    'tag'   => 'div',
                    'class' => 'help-block',
                ];
            }else{
                return [
                    'text'  => trans((string)$helpBlockNoDataLangString),
                    'tag'   => 'div',
                    'class' => 'help-block',
                ];
            }
        }

    }
}





















if (!function_exists('getFormSelectionDataAndHelperText')) {
    /**
     * @param $data
     * @param bool $getData
     * @param string $noDataLangString
     * @param string $helpBlockHasDataLangString
     * @param string $helpBlockNoDataLangString
     * @return array
     */
    function getFormSelectionDataAndHelperText ($data, $getData = true, $noDataLangString = 'patch.base::forms.no_data.label', $helpBlockHasDataLangString = 'patch.base::forms.loading_data.help', $helpBlockNoDataLangString = 'patch.base::forms.no_data.help') {
        if ($getData){
            if (empty($data) || !is_array($data)){
                return [
                    0 => trans((string)$noDataLangString),
                ];
            }else{
                return $data;
            }
        }else{
            if (empty(!$data) && is_array($data)){
                return [
                    'text' => trans((string)$helpBlockHasDataLangString),
                    'tag' => 'div',
                    'class' => 'help-block',
                ];
            }else{
                return [
                    'text' => trans((string)$helpBlockNoDataLangString),
                    'tag' => 'div',
                    'class' => 'help-block',
                ];
            }
        }

    }
}

if (!function_exists('getBuiltFormInputSelectionData')) {
    /**
     * @param $request
     * @return array
     */
    function getBuiltFormInputSelectionData($request)
    {
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
        if (is_array($data) && !empty($data)) {
            foreach ($data as $key => $item) {
                $results[$key] = $item;
            }
        } else {
            $results = [
                'publish' => trans('patch.base::forms.nothing_to_show'),
            ];
        }
        return $results;
    }
}

if (!function_exists('getFormYesNoSelectionList')) {
    /**
     * @return array
     */
    function getFormYesNoSelectionList()
    {
        return getBuiltFormInputSelectionData('bool_yes');
    }
}

if (!function_exists('getFormActivatedSelectionList')) {
    /**
     * @return array
     */
    function getFormActivatedSelectionList()
    {
        return getBuiltFormInputSelectionData('bool_activate');
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
        return getBuiltFormInputSelectionData('publish_mode');
    }
}


if (!function_exists('getBuiltSlugInputField')) {
    /**
     * @param $name
     * @return array
     */
    function getBuiltSlugInputField ($name) {
        return [
            'wrapper' => ['class' => 'form-group slug-input-field'],
            'label' => trans('patch.base::forms.fields.'.$name.'.label'),
            'label_attr' => ['class' => 'control-label required'],
            'attr' => [
                'placeholder' => trans('patch.base::forms.fields.'.$name.'.placeholder'),
                'data-counter' => 120,
                'class' => 'form-control slugin',
            ],
            'help_block' => [
                'text' => trans('patch.base::forms.fields.'.$name.'.help'),
                'tag' => 'div',
                'class' => 'help-block',
            ],
        ];
    }
}

if (!function_exists('getBuiltSlugOutputField')) {
    /**
     * @return array
     */
    function getBuiltSlugOutputField () {
        return [
            'wrapper' => ['class' => 'form-group slug-output-field'],
            'label' => trans('patch.base::forms.fields.slug.label'),
            'label_attr' => ['class' => 'control-label required'],
            'help_block' => [
                'text' => trans('patch.base::forms.fields.slug.waiting'),
                'tag' => 'div',
                'class' => 'help-block slug-helper-text',
            ],
            'attr' => [
                'placeholder' => trans('patch.base::forms.fields.slug.placeholder'),
                'data-counter' => 240,
                'data-slug-waiting-text' => trans('patch.base::forms.fields.slug.waiting'),
                'data-slug-generating-text' => trans('patch.base::forms.fields.slug.generating'),
                'data-slug-generated-text' => trans('patch.base::forms.fields.slug.generated'),
                'data-slug-url' => URL::route('slug.generate'),
                'class' => 'form-control slugout',
            ]
        ];
    }
}













if (!function_exists('getFormSelectionDataAndHelperText')) {
    /**
     * @param $data
     * @param bool $getData
     * @param string $noDataLangString
     * @param string $helpBlockHasDataLangString
     * @param string $helpBlockNoDataLangString
     * @return array
     */
    function getFormSelectionDataAndHelperText ($data, $getData = true, $noDataLangString = 'patch.base::forms.no_data.label', $helpBlockHasDataLangString = 'patch.base::forms.loading_data.help', $helpBlockNoDataLangString = 'patch.base::forms.no_data.help') {
        if ($getData){
            if (empty($data) || !is_array($data)){
                return [
                    0 => trans((string)$noDataLangString),
                ];
            }else{
                return $data;
            }
        }else{
            if (empty(!$data) && is_array($data)){
                return [
                    'text' => trans((string)$helpBlockHasDataLangString),
                    'tag' => 'div',
                    'class' => 'help-block',
                ];
            }else{
                return [
                    'text' => trans((string)$helpBlockNoDataLangString),
                    'tag' => 'div',
                    'class' => 'help-block',
                ];
            }
        }

    }
}



if (!function_exists('buildFormInputSelectionData')) {
    /**
     * @param $request
     * @return array
     */
    function buildFormInputSelectionData($request)
    {
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
        if (is_array($data) && !empty($data)) {
            foreach ($data as $key => $item) {
                $results[$key] = $item;
            }
        } else {
            $results = [
                'publish' => trans('patch.base::forms.nothing_to_show'),
            ];
        }
        return $results;
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

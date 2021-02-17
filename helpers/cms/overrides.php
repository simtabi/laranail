<?php

if (!function_exists('table_is_default_status')) {
    /**
     * @param $selected
     * @param array $statuses
     * @return string
     * @internal param $status
     * @internal param null $activated_text
     * @internal param null $deactivated_text
     * @author Sang Nguyen
     * @throws Throwable
     */
    function table_is_default_status($selected, $statuses = [])
    {
        if (empty($statuses) || !is_array($statuses)) {
            $statuses = [
                0 => [
                    'class' => 'label-danger',
                    'text' => trans('patch.base::forms.fields.no.label'),
                ],
                1 => [
                    'class' => 'label-success',
                    'text' => trans('patch.base::forms.fields.yes.label'),
                ],
            ];
        }
        return view('core.base::elements.tables.status', compact('selected', 'statuses'))->render();
    }
}

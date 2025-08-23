<?php

namespace Modules\Base\Http\Services;

class BaseApiService extends BaseService
{
    /**
     * Fields that are not necessary for creating or updating the model.
     */
    protected $unnecessaryFieldsForCrud = [];

    public function __construct() {
        parent::__construct();
    }

    /**
     * Remove unnecessary fields from the data array before creating or updating the model.
     *
     * @param array $data
     * @return array
     */
    protected function prepareModelData(array $data) : array
    {
        $data = array_diff_key($data, array_flip($this->unnecessaryFieldsForCrud));

        return $data;
    }
}

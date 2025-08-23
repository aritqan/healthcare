<?php

namespace Modules\Crm\Http\Services;

use Illuminate\Support\Facades\DB;
use Modules\Crm\Enums\ContactusStatuses;
use Modules\Crm\Models\Contactus as CrudModel;
use Modules\Base\Http\Services\BaseCrudService;
use Modules\Notification\Http\Services\SendGridService;

class ContactusService extends BaseCrudService
{
    public function __construct(private SendGridService $sendGridService)
    {
        parent::__construct();
    }
    /**
     * The unnecessary fields for crud.
     * Example: if the data has translation fields, you can add them here. As a ('title', 'description')
     */
    protected $unnecessaryFieldsForCrud = [];

    /**
     * Create a new Model instance.
     *
     * @param array $data
     * @return CrudModel
     */
    public function createModel(array $data): CrudModel
    {
        $modelData = $this->prepareModelData($data);
        $otherData = [
            'locale'     => app()->getLocale(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];
        $modelData = array_merge($modelData, $otherData);

        $model = DB::transaction(function () use($modelData){
            $model = CrudModel::create($modelData);

            return $model;
        });

        return $model;
    }

    /**
     * Update a Model instance.
     *
     * @param CrudModel $model
     * @param array $data
     * @return CrudModel
     */
    public function sendReply(CrudModel $model, array $data)
    {
        if(! $model->canReply()) {
            return sendFailInternalResponse(customMessage: trans('crm::contactus.cant_reply'));
        }

        $sendEmailResponse = $this->sendGridService->sendEmail($model->email, $model->full_name, trans('crm::contactus.email_subject', [], $model->locale), $data['reply']);

        if(!$sendEmailResponse['success']) {
            return sendFailInternalResponse(customMessage: $sendEmailResponse['message']);
        }

        DB::transaction(function () use($data, $model){
            $model->update($data);

            $model->status = ContactusStatuses::REPLIED;
            $model->save();
        });

        return sendSuccessInternalResponse('reply_sent_successfully');
    }
}

<?php

namespace Modules\Base\Http\Controllers\Admin;

use Modules\Base\Http\Controllers\BaseController;

class TestController extends BaseController
{
    public function index()
    {
        return 'TestController@index';
    }

    public function previewEmail()
    {
        $this->data['content'] = '
            <div class="content">
                <h1>Hello John Doe,</h1>
                <p>We are excited to have you on board. Thank you for choosing our service. We aim to provide you with the best experience possible.</p>
                <p>To get started, please take a moment to explore the features and tools available on your dashboard. If you have any questions, feel free to reach out to our support team.</p>

                <p>We look forward to helping you achieve your goals!</p>
            </div>
        ';

        $emailContent = view('admin::emails.templates.template', $this->data)->render();

        return $emailContent;
        // dd($emailContent);
    }
}

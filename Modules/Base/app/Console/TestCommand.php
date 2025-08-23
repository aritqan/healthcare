<?php

namespace Modules\Base\Console;

use Modules\Auth\Models\User;
use Modules\Cms\Models\Content;
use Modules\Base\Console\BaseCommand;
use Modules\Notification\Http\Services\NotificationService;

class TestCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:command';

    /**
     * The console command description.
     */
    protected $description = 'An example command description.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Test Command');
        $this->testSlug();die;
        $this->testTemplateNotification();die;
    }

    private function testTemplateNotification()
    {
        $user = User::first();

        $notificationService = app(NotificationService::class);

        try{
            $notificationService->send($user, 'welcome_in_our_platform', [
                'username' => $user->full_name
            ]);
        }catch (\Exception $e){
            $this->error($e->getMessage());
        }
    }

    private function testSlug()
    {
        $content = Content::create([
            'type' => 'article',
        ]);

        $content->translateOrNew('en')->title = 'Hello World';
        $content->translateOrNew('ar')->title = 'مرحباً بالعالم';

        $content->save();
    }

}

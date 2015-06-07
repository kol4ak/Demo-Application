<?php namespace Modules\Reset\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Modules\Media\Repositories\FileRepository;
use Modules\Menu\Repositories\MenuRepository;
use Modules\Page\Repositories\PageRepository;
use Modules\Setting\Repositories\SettingRepository;
use Modules\User\Repositories\UserRepository;

class ResetDataCommand extends Command
{
    protected $name = 'reset:tables';
    protected $description = 'Reset application tables';

    /**
     * @var PageRepository
     */
    private $page;
    /**
     * @var UserRepository
     */
    private $user;
    /**
     * @var SettingRepository
     */
    private $setting;
    /**
     * @var MenuRepository
     */
    private $menu;
    /**
     * @var FileRepository
     */
    private $file;

    public function __construct(
        UserRepository $user, PageRepository $page, SettingRepository $setting, MenuRepository $menu,
        FileRepository $file
    )
    {
        parent::__construct();
        $this->page = $page;
        $this->user = $user;
        $this->setting = $setting;
        $this->menu = $menu;
        $this->file = $file;
    }

    public function fire()
    {
        $this->emptyUserTable();
        $this->emptySettingsTable();
        $this->emptyMenuTable();
        $this->emptyMedia();
        $this->emptyPagesTable();

        Artisan::call('module:seed', ['module' => 'Setting']);
        Artisan::call('module:seed', ['module' => 'Page']);
        Artisan::call('module:seed', ['module' => 'Reset']);

        $this->info('Everything is reset.');
    }

    /**
     *
     */
    private function emptyUserTable()
    {
        foreach ($this->user->all() as $user) {
            $this->user->delete($user->id);
        }
    }

    /**
     *
     */
    private function emptySettingsTable()
    {
        foreach ($this->setting->all() as $setting) {
            $this->setting->destroy($setting);
        }
    }

    /**
     *
     */
    private function emptyMenuTable()
    {
        foreach ($this->menu->all() as $menu) {
            $this->menu->destroy($menu);
        }
    }

    /**
     *
     */
    private function emptyMedia()
    {
        foreach ($this->file->all() as $file) {
            $this->file->destroy($file);
        }
    }

    /**
     *
     */
    private function emptyPagesTable()
    {
        foreach ($this->page->all() as $page) {
            $this->page->destroy($page);
        }
    }
}
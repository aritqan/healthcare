<?php

namespace Modules\Admin\Classes;

class AdminHelper
{
    /**
     * @var \Illuminate\Support\Collection
     */
    public $asideMenu;

    /**
     * @var array
     */
    public $breadcrumbs;

    public function __construct()
    {
        $this->asideMenu      = collect([]);
        $this->breadcrumbs[]  = ['title' => trans('admin::dashboard.aside_menu.home'), 'link' => route('admin.dashboard.index')];
    }

    public function asideMenu(array $options) : void
    {
        $id = $this->asideMenu->count() + 1;

        $menu = array_merge([
            'id'        => $id,
            'parent_id' => null,
            'type'      => 'item',
            'link'      => 'javascript:;',
            'title'     => '',
            'icon_type' => null,
            'icon'      => '',
            'order'     => $id,
            'children'  => [],
        ], $options);

        $menu['is_active'] = request()->fullUrl() == $menu['link'] ? true : false;

        $this->asideMenu->push($menu);
    }

    public function asideMenuGet() : array
    {
        $menuItems = $this->asideMenu->where('parent_id', null)->sortBy('order')->values()->all();
        $hasActive = false;

        foreach($menuItems as $key => $menuItem) {
            $menuItems[$key]['children'] = $this->asideMenu->where('parent_id', $menuItem['id'])->sortBy('order')->values()->all();

            foreach($menuItems[$key]['children'] as $childKey => $child) {
                $menuItems[$key]['children'][$childKey]['children']  = $this->asideMenu->where('parent_id', $child['id'])->sortBy('order')->values()->all();

                foreach($menuItems[$key]['children'][$childKey]['children'] as $subChild) {
                    if(request()->fullUrl() == $subChild['link']) {
                        $menuItems[$key]['children'][$childKey]['is_active'] = true;
                        $menuItems[$key]['is_active'] = true;
                        $hasActive = true;
                    }
                }
            }
        }

        /**
         * If the current url is not in the menu, check if the current url contains the title of the menu item
         * Example: /en/admin/roles/update/1
         * The title of the menu item is "roles"
         * If the current url contains "roles", then the menu item is active
         * This is useful for the update pages
         */
        if(! $hasActive) {
            $currentUrl = $this->handleUrl();

            foreach($menuItems as $key => $menuItem) {
                foreach($menuItem['children'] as $childKey => $child) {
                    // Check if the current url contains the title of the menu item
                    if(str_contains(strtolower($child['link']), $currentUrl)) {
                        $menuItems[$key]['children'][$childKey]['is_active'] = true;
                        $hasActive = true;
                        break;
                    }

                    foreach($menuItems[$key]['children'][$childKey]['children'] as $subChild) {
                        if(str_contains(strtolower($subChild['link']), $currentUrl)) {
                            $menuItems[$key]['children'][$childKey]['is_active'] = true;
                            $hasActive = true;
                            break;
                        }
                    }

                }

            }
        }

        return $menuItems;
    }

    private function handleUrl()
    {
        $currentUrl = strtolower(request()->path());
        /**
         * from this path en/admin/permissions/update/12 and this path  /en/admin/contents/sliders/update/2 and this path /en/admin/dashboard
         * we want to get  permissions and contents/slider and dashboard to check if the current url contains the title of the menu item
         * we wnat as a dynamic way to get the title of the menu item from the url (After the admin prefix and Before the update)
         */

        // Convert the URL to an array
        $currentUrl = explode('/', trim($currentUrl, '/'));

        // Find the position of 'admin' in the array
        $adminIndex = array_search('admin', $currentUrl);

        // Initialize the extracted part of the URL
        $extractedUrl = [];

        // If 'admin' is found and there are elements after it
        if ($adminIndex !== false && isset($currentUrl[$adminIndex + 1])) {
            // Start slicing after 'admin'
            $extractedUrl = array_slice($currentUrl, $adminIndex + 1);

            // Find the position of 'update' or 'edit' or any other similar action keywords
            $actionIndex = array_search('update', $extractedUrl);
            if ($actionIndex === false) {
                $actionIndex = array_search('edit', $extractedUrl); // Add other actions if needed
            }

            // If an action is found, remove it and everything after it
            if ($actionIndex !== false) {
                $extractedUrl = array_slice($extractedUrl, 0, $actionIndex);
            }

            // Convert the extracted array to a string
            $currentUrl = implode('/', $extractedUrl);
        }

        // $currentUrl will now contain 'permissions', 'contents/sliders', or 'dashboard'

        return $currentUrl;
    }

    public function addBreadcrumbs(string $title, string $link = 'javascript:;') : void
    {
        $this->breadcrumbs[] = [
            'title' => $title,
            'link'  => $link,
        ];
    }

    public function getBreadcrumbs() : array
    {
        return $this->breadcrumbs;
    }
}

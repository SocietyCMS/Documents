<?php

namespace Modules\Documents\MenuExtenders;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Contracts\Authentication;

class SidebarExtender implements \Maatwebsite\Sidebar\SidebarExtender
{
    /**
     * @var Authentication
     */
    protected $auth;

    /**
     * @param Authentication $auth
     *
     * @internal param Guard $guard
     */
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param Menu $menu
     *
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(trans('core::sidebar.collaboration'), function (Group $group) {
            $group->weight(12);

            $group->item(trans('documents::module.title'), function (Item $item) {

                $item->route('backend::documents.documents.index');
                $item->authorize(
                    $this->auth->can('documents::access-documents')
                );
            });

        });

       return $menu;
    }
}

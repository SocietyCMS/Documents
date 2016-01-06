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
        $menu->group(trans('documents::documents.title.documents'), function (Group $group) {
            $group->weight(20);

            $group->item('Public', function (Item $item) {

                $item->route('backend::documents.documents.index');
                $item->authorize(
                    $this->auth->hasAccess('documents.documents.index')
                );
            });

            $group->item('SocietyCMS', function (Item $item) {

                $item->route('backend::documents.documents.index');
                $item->authorize(
                    $this->auth->hasAccess('documents.documents.index')
                );
            });


        });

       return $menu;
    }
}

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | The default title of your admin panel, this goes into the title tag
    | of your page. You can override it per page with the title section.
    | You can optionally also specify a title prefix and/or postfix.
    |
    */

    'title' => 'Abaco Tecnologia',

    'title_prefix' => '',

    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | This logo is displayed at the upper left corner of your admin panel.
    | You can use basic HTML here if you want. The logo has also a mini
    | variant, used for the mini side bar. Make it 3 letters or so
    |
    */

    'logo' => '<b>Abaco</b>Systems',

    'logo_mini' => '<b>A</b>Sys',

    /*
    |--------------------------------------------------------------------------
    | Skin Color
    |--------------------------------------------------------------------------
    |
    | Choose a skin color for your admin panel. The available skin colors:
    | blue, black, purple, yellow, red, and green. Each skin also has a
    | ligth variant: blue-light, purple-light, purple-light, etc.
    |
    */

    'skin' => 'black',

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Choose a layout for your admin panel. The available layout options:
    | null, 'boxed', 'fixed', 'top-nav'. null is the default, top-nav
    | removes the sidebar and places your menu in the top navbar
    |
    */

    'layout' => null,

    /*
    |--------------------------------------------------------------------------
    | Collapse Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we choose and option to be able to start with a collapsed side
    | bar. To adjust your sidebar layout simply set this  either true
    | this is compatible with layouts except top-nav layout option
    |
    */

    'collapse_sidebar' => false,

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Register here your dashboard, logout, login and register URLs. The
    | logout URL automatically sends a POST request in Laravel 5.3 or higher.
    | You can set the request to a GET or POST with logout_method.
    | Set register_url to null if you don't want a register link.
    |
    */

    'dashboard_url' => 'home',

    'logout_url' => 'logout',

    'logout_method' => null,

    'login_url' => 'login',

    'register_url' => 'register',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Specify your menu items to display in the left sidebar. Each menu item
    | should have a text and and a URL. You can also specify an icon from
    | Font Awesome. A string instead of an array represents a header in sidebar
    | layout. The 'can' is a filter on Laravel's built in Gate functionality.
    |
    */

    'menu' => [
        'Navegação Principal',
        [
            'text'    => 'G3',
            'icon'    => 'briefcase',
            'submenu' => [

                
                [
                    'text' => 'general.Providers',
                    'url'  => 'g3/companies',
                    'icon' => 'building',
                    'notcan'  => 'fornecedor',
                    
                ],
                [
                    'text' => 'general.Stores',
                    'url'  => 'g3/clients',
                    'icon' => 'building',
                    'notcan'  => 'fornecedor',
                ],
                
                /* [
                    'text' => 'general.Branches',
                    'url'  => 'g3/branches',
                    'icon' => 'building',
                    'can'  => 'G3 Admin',
                    
                ], */
                
               /*  [
                    'text' => 'general.Services',
                    'url'  => 'g3/services',
                    'icon' => 'wrench',
                    'can'  => 'G3 Admin',
                ], */

                [
                    'text' => 'general.Peoples',
                    'url'  => 'g3/employees',
                    'icon' => 'user',
                    'can'  => 'master',
                ],
              /*   [
                    'text' => 'general.Outsourceds',
                    'url'  => 'g3/employees',
                    'icon' => 'user',
                    'notcan'  => 'master,fornecedor',
                ], */
                [
                    'text' => 'general.Documents',
                    'url'  => 'g3/documents',
                    'icon' => 'book',
                    'can'  => 'master',
                ],
                [
                    'text' => 'general.Delivereds',
                    'url'  => 'g3/delivereds',
                    'icon' => 'archive',
                    'can'  => 'master',
                ],
               /*  [
                    'text' => 'general.Training Schedules',
                    'url'  => 'g3/trainingschedule',
                    'icon' => 'calendar',
                    'notcan'  => 'fornecedor',
                ], */
                [
                    'text' => 'general.Scheduled services',
                    'url'  => 'g3/companies/servicesscheduled',
                    'icon' => 'calendar',
                    'notcan'  => 'fornecedor',
                ],
                /* [
                    'text' => 'general.Training Reserve',
                    'url'  => 'g3/provider/trainingreserve',
                    'icon' => 'calendar',
                    'can'  => 'fornecedor',
                ], */
                /* [
                    'text' => 'general.Tests',
                    'url'  => 'g3/tests',
                    'icon' => 'file',
                    'notcan'  => 'fornecedor',
                ], */
                [
                    'text' => 'general.Company',
                    'icon' => 'building',
                    'can'  => 'fornecedor',
                    'url'  => 'g3/provider/documents',
                ],
                [
                    'text' => 'general.Employees',
                    'icon' => 'users',
                    'can'  => 'fornecedor',
                    'url'  => 'g3/employees',
                ],
                [
                    'text' => 'general.Scheduled services',
                    'url'  => 'g3/companies/servicesscheduled',
                    'icon' => 'calendar',
                    'can'  => 'fornecedor',
                ],
                


                [
                    'text' => 'general.Reports',
                    'icon' => 'file-text-o',
                    'notcan' => 'fornecedor, gerente, geral',
                    'submenu' => [
                        [
                            'text' => 'general.Expireds',
                            'url'  => 'g3/reports/expired',
                            'icon' => 'thumbs-o-down',
                        ],
                        [
                            'text' => 'general.Outsourceds',
                            'url'  => 'g3/reports/outsourceds_documents',
                            'icon' => 'users',
                        ],
                        [
                            'text' => 'general.Outsourceds V2',
                            'url'  => 'g3/reports/outsourceds_documentsv2',
                            'icon' => 'users',
                        ],
                        [
                            'text' => 'general.Providers',
                            'url'  => 'g3/reports/companies_documents',
                            'icon' => 'building',
                        ],
                        [
                            'text' => 'general.Aprove',
                            'url'  => 'g3/reports/aprove',
                            'icon' => 'check',
                            'can' => 'master',
                        ],
                    ]
                ],
                [
                    'text' => 'general.Settings',
                    'icon' => 'gear',
                    'can' => 'master',
                    'submenu' => [
                        [
                            'text' => 'general.Documents',
                            'url'  => 'g3/settings/documents_alerts',
                            'icon' => 'book',
                        ],

                    ]
                ],
               /* [
                    'text' => 'general.Settings',
                    'icon' => 'gear',
                    'submenu' => [
                        [
                            'text' => 'Documentos / Alertas',
                            'url'  => 'g3/settings/documents_alerts',
                            'icon' => 'thumbs-o-down',
                        ],

                    ]
                ], */
        ],
    ],


         [
            'text' => 'general.Users',
            'icon' => 'users',
            'can'  => 'Admin Users',
            'submenu'=>[
                 [
                    'text' => 'general.Users',
                    'url'  => 'users',
                    'icon' => 'users',
                    //'can'  => 'manage-blog',
                ],
                [
                    'text' => 'general.Roles',
                    'url'  => 'roles',
                    'icon' => 'lock',
                    'can'  => 'Administer roles & permissions',
                ],
               /* [
                    'text' => 'general.Permissions',
                    'url'  => 'permissions',
                    'icon' => 'key',
                    'can'  => 'Administer roles & permissions',
                ],*/

            [


                'text'    => 'Multilevel',
                'icon'    => 'share',
                'can'     => 'hiden',
                'submenu' => [
                    [
                        'text' => 'Level One',
                        'url'  => '#',
                    ],
                    [
                        'text'    => 'Level One',
                        'url'     => '#',
                        'submenu' => [
                            [
                                'text' => 'Level Two',
                                'url'  => '#',
                            ],
                            [
                                'text'    => 'Level Two',
                                'url'     => '#',
                                'submenu' => [
                                    [
                                        'text' => 'Level Three',
                                        'url'  => '#',
                                    ],
                                    [
                                        'text' => 'Level Three',
                                        'url'  => '#',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'text' => 'Level One',
                        'url'  => '#',
                    ],
                ],
            ],
        ],
        ],
        'Personal',
        /* [
            'text' => 'general.Profile',
            'url'  => 'profile',
            'icon' => 'user',
        ],*/
        [
            'text' => 'general.Change password',
            'url'  => 'profile',
            'icon' => 'lock',
        ],

        /*
        'LABELS',
        [
            'text'       => 'Important',
            'icon_color' => 'red',
        ],
        [
            'text'       => 'Warning',
            'icon_color' => 'yellow',
        ],
        [
            'text'       => 'Information',
            'icon_color' => 'aqua',
        ],*/
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Choose what filters you want to include for rendering the menu.
    | You can add your own filters to this array after you've created them.
    | You can comment out the GateFilter if you don't want to use Laravel's
    | built in Gate functionality
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SubmenuFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Choose which JavaScript plugins should be included. At this moment,
    | only DataTables is supported as a plugin. Set the value to true
    | to include the JavaScript file from a CDN via a script tag.
    |
    */

    'plugins' => [
        'datatables' => true,
        'select2'    => true,
        'chartjs'    => true,
    ],
];

<?php

namespace ThemeHouse\UIX;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

	// Upgrading from XF1 version of add-on
    public function upgrade2000031Step1()
    {
        $schemaManager = \XF::db()->getSchemaManager();
        $schemaManager->alterTable('xf_style', function(\XF\Db\Schema\Alter $table) {
            $table->dropColumns([
                'audentio',
                'uix_pid',
                'uix_version',
                'uix_latest_version',
                'uix_update_available',
            ]);
        });

        $schemaManager->alterTable('xf_forum', function(\XF\Db\Schema\Alter $table) {
            $table->dropColumns([
                'uix_last_sticky_action',
            ]);
        });

        $this->db()->delete('xf_node_type', 'node_type_id = \'uix_nodeLayoutSeparator\'');
        $schemaManager->dropTable('uix_node_layout');
        $schemaManager->dropTable('uix_node_fields');
    }

    public function upgrade2000031Step2()
    {
        $schemaManager = \XF::db()->getSchemaManager();
        $schemaManager->alterTable('xf_user_option', function(\XF\Db\Schema\Alter $table) {
            $table->dropColumns([
                'uix_sidebar',
                'uix_collapse_stuck_threads',
                'uix_sticky_navigation',
                'uix_sticky_userbar',
                'uix_sticky_sidebar',
                'uix_width',
                'uix_collapse_user_info',
                'uix_collapse_stuck_threads',
                'uix_collapse_signature',
            ]);
        });
    }

    public function upgrade2000031Step3()
    {
        $schema = \XF::db()->getSchemaManager();
        $schema->alterTable('xf_style', function(\XF\Db\Schema\Alter $table) {
            $table->addColumn('th_product_id_uix', 'int')->setDefault(0);
            $table->addColumn('th_product_version_uix', 'varchar', 250)->nullable()->setDefault(null);
        });

        $this->createWidget('footer_forumStatistics', 'forum_statistics', [
            'positions' => ['th_footer_uix' => 10]
        ]);
        $this->createWidget('footer_newPosts', 'new_posts', [
            'positions' => ['th_footer_uix' => 20]
        ]);
        $this->createWidget('footer_onlineStatistics', 'online_statistics', [
            'positions' => ['th_footer_uix' => 30]
        ]);
        $this->createWidget('footer_sharePage', 'share_page', [
            'positions' => ['th_footer_uix' => 40]
        ]);
    }

    public function upgrade2000032Step1()
    {
        $schemaManager = $this->schemaManager();
        $schemaManager->alterTable('xf_user_option', function(\XF\Db\Schema\Alter $table) {
            if ($table->getColumnDefinition('th_width_uix')) {
                $table->dropColumns([
                    'th_width_uix',
                    'th_sidebar_collapsed_uix',
                ]);
            }
        });
    }

    public function upgrade2000411Step1()
    {
        $schemaManager = $this->schemaManager();
        $schemaManager->alterTable('xf_style', function(\XF\Db\Schema\Alter $table) {
            $table->addColumn('th_primary_child_uix', 'boolean')->setDefault(0);
        });
    }

    public function upgrade2000531Step1()
    {
        $schemaManager = $this->schemaManager();
        $schemaManager->alterTable('xf_style', function(\XF\Db\Schema\Alter $table) {
            $table->addColumn('th_child_style_xml_uix', 'varchar', 100)->setDefault('');
            $table->addColumn('th_child_style_cache_uix', 'blob')->nullable();
        });
    }

    public function postUpgrade($previousVersion, array &$stateChanges)
    {
        if ($this->applyDefaultPermissions($previousVersion)) {
            $this->app->jobManager()->enqueueUnique(
                'permissionRebuild',
                'XF:PermissionRebuild',
                [],
                false
            );
        }
    }

    public function installStep1()
    {
        $schema = \XF::db()->getSchemaManager();
        $schema->alterTable('xf_style', function(\XF\Db\Schema\Alter $table) {
            $table->addColumn('th_product_id_uix', 'int')->setDefault(0);
            $table->addColumn('th_product_version_uix', 'varchar', 250)->nullable()->setDefault(null);
            $table->addColumn('th_primary_child_uix', 'boolean')->setDefault(0);
            $table->addColumn('th_child_style_xml_uix', 'varchar', 100)->setDefault('');
            $table->addColumn('th_child_style_cache_uix', 'blob')->nullable();
        });
    }

    public function installStep2()
    {
        $this->applyDefaultWidgets();
    }

    public function installStep3()
    {
        $this->applyDefaultPermissions();
    }

    public function uninstallStep1()
    {
        $schema = \XF::db()->getSchemaManager();
        $schema->alterTable('xf_style', function(\XF\Db\Schema\Alter $table) {
            $table->dropColumns(['th_product_id_uix', 'th_product_version_uix', 'th_primary_child_uix', 'th_child_style_xml_uix']);
        });
    }

    protected function applyDefaultWidgets($previousVersion = 0)
    {
        $widgets = [];

        if ($previousVersion < 2000000) {
            $widgets[] = [
                'key' => 'uix_footer_forumStatistics',
                'definition_id' => 'forum_statistics',
                'config' => [
                    'positions' => [
                        'th_footer_uix' => 10,
                    ],
                ],
            ];

            $widgets[] = [
                'key' => 'uix_footer_newPosts',
                'definition_id' => 'new_posts',
                'config' => [
                    'positions' => [
                        'th_footer_uix' => 20,
                    ],
                ],
            ];

            $widgets[] = [
                'key' => 'uix_footer_onlineStatistics',
                'definition_id' => 'online_statistics',
                'config' => [
                    'positions' => [
                        'th_footer_uix' => 30,
                    ],
                ],
            ];

            $widgets[] = [
                'key' => 'uix_footer_sharePage',
                'definition_id' => 'share_page',
                'config' => [
                    'positions' => [
                        'th_footer_uix' => 40,
                    ],
                ],
            ];
        }

        if ($previousVersion < 2000070) {
            $widgets[] = [
                'key' => 'uix_sidebar_postNewThread',
                'definition_id' => 'th_post_thread_uix',
                'title' => 'Can\'t find a topic?',
                'config' => [
                    'positions' => [
                        'forum_list_sidebar' => 0,
                    ],
                    'options' => [
                        'description' => 'Share your experiences with like-minded people.',
                    ],
                ],
            ];
        }

        foreach ($widgets as $widget) {
            $title = '';
            if (!empty($widget['title'])) {
                $title = $widget['title'];
            }
            $this->createWidget($widget['key'], $widget['definition_id'], $widget['config'], $title);
        }

        if (empty($widgets)) {
            return false;
        }

        return true;
    }

    protected function applyDefaultPermissions($previousVersion = 0)
    {
        $permissions = [];
        $db = $this->db();
        if ($previousVersion < 2000032) {
            $permissions[] = [
                'user_group_id' => 1,
                'permission_id' => 'showWelcomeSection',
                'permission_value' => 'allow',
            ];

            $permissions[] = [
                'user_group_id' => 1,
                'permission_id' => 'togglePageWidth',
                'permission_value' => 'allow',
            ];
            $permissions[] = [
                'user_group_id' => 2,
                'permission_id' => 'togglePageWidth',
                'permission_value' => 'allow',
            ];

            $permissions[] = [
                'user_group_id' => 1,
                'permission_id' => 'collapseCategories',
                'permission_value' => 'allow',
            ];
            $permissions[] = [
                'user_group_id' => 2,
                'permission_id' => 'collapseCategories',
                'permission_value' => 'allow',
            ];

            $permissions[] = [
                'user_group_id' => 1,
                'permission_id' => 'collapseSidebar',
                'permission_value' => 'allow',
            ];
            $permissions[] = [
                'user_group_id' => 2,
                'permission_id' => 'collapseSidebar',
                'permission_value' => 'allow',
            ];
        }

        foreach ($permissions as $permission) {
            $permission = array_merge([
                'user_id' => 0,
                'permission_group_id' => 'th_uix',
                'permission_value' => 'use_int',
                'permission_value_int' => 0,
            ], $permission);

            try {
                $db->insert('xf_permission_entry', $permission);
            } catch (\Exception $e) {}
        }

        if (empty($permissions)) {
            return false;
        }

        return true;
    }
}
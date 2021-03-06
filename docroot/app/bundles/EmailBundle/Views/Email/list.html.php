<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
if ('index' == $tmpl) {
    $view->extend('MauticEmailBundle:Email:index.html.php');
}
?>

<?php if (count($items)): ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered email-list">
            <thead>
            <tr>
                <?php
                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'checkall'        => 'true',
                        'routeBase'       => 'email',
                        'templateButtons' => [
                            'delete' => $permissions['email:emails:deleteown'] || $permissions['email:emails:deleteother'],
                        ],
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'email',
                        'orderBy'    => 'e.name',
                        'text'       => 'mautic.core.name',
                        'class'      => 'col-email-name',
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'email',
                        'orderBy'    => 'c.title',
                        'text'       => 'mautic.core.category',
                        'class'      => 'visible-md visible-lg col-email-category',
                    ]
                );
                ?>

                <th class="visible-sm visible-md visible-lg col-email-stats"><?php echo $view['translator']->trans('mautic.core.stats'); ?></th>

                <?php
                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'email',
                        'orderBy'    => 'e.dateAdded',
                        'text'       => 'mautic.lead.import.label.dateAdded',
                        'class'      => 'visible-lg col-email-dateAdded',
                    ]
                );
                ?>
                <?php
                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'email',
                        'orderBy'    => 'e.dateModified',
                        'defaultDir' => 'DESC',
                        'text'       => 'mautic.lead.import.label.dateModified',
                        'class'      => 'visible-lg col-email-dateModified',
                        'default'    => true,
                    ]
                );
                ?>
                <?php
                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'email',
                        'orderBy'    => 'e.createdByUser',
                        'text'       => 'mautic.core.createdby',
                        'class'      => 'visible-lg col-email-createdByUser',
                    ]
                );
                ?>
                <?php
                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'email',
                        'orderBy'    => 'e.id',
                        'text'       => 'mautic.core.id',
                        'class'      => 'visible-md visible-lg col-email-id',
                    ]
                );
                ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <?php
                $hasVariants                = $item->isVariant();
                $hasTranslations            = $item->isTranslation();
                $type                       = $item->getEmailType();
                $mauticTemplateVars['item'] = $item;
                ?>
                <tr>
                    <td>
                        <?php
                        $edit = $view['security']->hasEntityAccess(
                            $permissions['email:emails:editown'],
                            $permissions['email:emails:editother'],
                            $item->getCreatedBy()
                        );
                        $customButtons = ('list' == $type) ? [
                            [
                                'attr' => [
                                    'data-toggle' => 'ajax',
                                    'href'        => $view['router']->path(
                                        'mautic_email_action',
                                        ['objectAction' => 'send', 'objectId' => $item->getId()]
                                    ),
                                ],
                                'iconClass' => 'fa fa-send-o',
                                'btnText'   => 'mautic.email.send',
                            ],
                        ] : [];
                        echo $view->render(
                            'MauticCoreBundle:Helper:list_actions.html.php',
                            [
                                'item'            => $item,
                                'templateButtons' => [
                                    'edit'   => $edit,
                                    'clone'  => $permissions['email:emails:create'],
                                    'delete' => $view['security']->hasEntityAccess(
                                        $permissions['email:emails:deleteown'],
                                        $permissions['email:emails:deleteother'],
                                        $item->getCreatedBy()
                                    ),
                                    'abtest' => (!$hasVariants && $edit && $permissions['email:emails:create']),
                                ],
                                'routeBase'     => 'email',
                                'customButtons' => $customButtons,
                            ]
                        );
                        ?>
                    </td>
                    <td>
                        <div>
                            <?php echo $view->render('MauticCoreBundle:Helper:publishstatus_icon.html.php', ['item' => $item, 'model' => 'email']); ?>
                            <a href="<?php echo $view['router']->path(
                                'mautic_email_action',
                                ['objectAction' => 'view', 'objectId' => $item->getId()]
                            ); ?>" data-toggle="ajax">
                                <?php echo $item->getName(); ?>
                                <?php if ($hasVariants): ?>
                                <span data-toggle="tooltip" title="<?php echo $view['translator']->trans('mautic.core.icon_tooltip.ab_test'); ?>">
                                    <i class="fa fa-fw fa-sitemap"></i>
                                </span>
                                <?php endif; ?>
                                <?php if ($hasTranslations): ?>
                                <span data-toggle="tooltip" title="<?php echo $view['translator']->trans(
                                        'mautic.core.icon_tooltip.translation'
                                    ); ?>">
                                    <i class="fa fa-fw fa-language"></i>
                                </span>
                                <?php endif; ?>
                                <?php if ('list' == $type): ?>
                                <span data-toggle="tooltip" title="<?php echo $view['translator']->trans(
                                        'mautic.email.icon_tooltip.list_email'
                                    ); ?>">
                                    <i class="fa fa-fw fa-pie-chart"></i>
                                </span>
                                <?php endif; ?>
                                <?php echo $view['content']->getCustomContent('email.name', $mauticTemplateVars); ?>
                            </a>
                        </div>
                        <?php if ($description = $item->getDescription()): ?>
                            <div class="text-muted mt-4">
                                <small><?php echo $description; ?></small>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="visible-md visible-lg">
                        <?php $category = $item->getCategory(); ?>
                        <?php $catName  = ($category) ? $category->getTitle() : $view['translator']->trans('mautic.core.form.uncategorized'); ?>
                        <?php $color    = ($category) ? '#'.$category->getColor() : 'inherit'; ?>
                        <span style="white-space: nowrap;">
                            <span class="label label-default pa-4" style="border: 1px solid #d5d5d5; background: <?php echo $color; ?>;"> </span> <span><?php echo $catName; ?></span>
                        </span>
                    </td>
                    <td class="visible-sm visible-md visible-lg col-stats" data-stats="<?php echo $item->getId(); ?>">
                        <?php echo $view['content']->getCustomContent('email.stats.above', $mauticTemplateVars); ?>
                        <span class="mt-xs label label-default has-click-event clickable-stat<?php echo $item->getPendingCount() > 0 && 'list' === $item->getEmailType() ? '' : ' hide'; ?>"
                              id="pending-<?php echo $item->getId(); ?>"
                              data-toggle="tooltip"
                              title="<?php echo $view['translator']->trans('mautic.email.stat.leadcount.tooltip'); ?>">
                            <a href="<?php echo $view['router']->path(
                                'mautic_contact_index',
                                ['search' => $view['translator']->trans('mautic.lead.lead.searchcommand.email_pending').':'.$item->getId()]
                            ); ?>">
                                <?php echo $view['translator']->trans('mautic.email.stat.leadcount', ['%count%' => $item->getPendingCount()]); ?>
                            </a>
                        </span>
                        <span class="mt-xs label label-default has-click-event clickable-stat<?php echo $item->getQueuedCount() > 0 ? '' : ' hide'; ?>"
                              id="queued-<?php echo $item->getId(); ?>"
                              data-toggle="tooltip"
                              title="<?php echo $view['translator']->trans('mautic.email.stat.queued.tooltip'); ?>">
                            <a href="<?php echo $view['router']->path(
                                'mautic_contact_index',
                                ['search' => $view['translator']->trans('mautic.lead.lead.searchcommand.email_queued').':'.$item->getId()]
                            ); ?>">
                                <?php echo $view['translator']->trans('mautic.email.stat.queued', ['%count%' => $item->getQueuedCount()]); ?>
                            </a>
                        </span>
                        <span class="mt-xs label label-warning has-click-event clickable-stat"
                              id="sent-count-<?php echo $item->getId(); ?>">
                            <a href="<?php echo $view['router']->path(
                                'mautic_contact_index',
                                ['search' => $view['translator']->trans('mautic.lead.lead.searchcommand.email_sent').':'.$item->getId()]
                            ); ?>" data-toggle="tooltip"
                               title="<?php echo $view['translator']->trans('mautic.email.stat.tooltip'); ?>">
                                <?php echo $view['translator']->trans('mautic.email.stat.sentcount', ['%count%' => $item->getSentCount(true)]); ?>
                            </a>
                        </span>
                        <span class="mt-xs label label-success has-click-event clickable-stat"
                              id="read-count-<?php echo $item->getId(); ?>">
                            <a href="<?php echo $view['router']->path(
                                'mautic_contact_index',
                                ['search' => $view['translator']->trans('mautic.lead.lead.searchcommand.email_read').':'.$item->getId()]
                            ); ?>" data-toggle="tooltip"
                               title="<?php echo $view['translator']->trans('mautic.email.stat.tooltip'); ?>">
                                <?php echo $view['translator']->trans('mautic.email.stat.readcount', ['%count%' => $item->getReadCount(true)]); ?>
                            </a>
                        </span>
                        <span class="mt-xs label label-primary has-click-event clickable-stat"
                              id="read-percent-<?php echo $item->getId(); ?>">
                            <a href="<?php echo $view['router']->path(
                                'mautic_contact_index',
                                ['search' => $view['translator']->trans('mautic.lead.lead.searchcommand.email_read').':'.$item->getId()]
                            ); ?>" data-toggle="tooltip"
                               title="<?php echo $view['translator']->trans('mautic.email.stat.tooltip'); ?>">
                                <?php echo $view['translator']->trans('mautic.email.stat.readpercent', ['%count%' => $item->getReadPercentage(true)]); ?>
                            </a>
                        </span>
                        <?php echo $view['content']->getCustomContent('email.stats', $mauticTemplateVars); ?>
                        <?php echo $view['content']->getCustomContent('email.stats.below', $mauticTemplateVars); ?>
                    </td>
                    <td class="visible-lg" title="<?php echo $item->getDateAdded() ? $view['date']->toFullConcat($item->getDateAdded()) : ''; ?>">
                        <?php echo $item->getDateAdded() ? $view['date']->toDate($item->getDateAdded()) : ''; ?>
                    </td>
                    <td class="visible-lg" title="<?php echo $item->getDateModified() ? $view['date']->toFullConcat($item->getDateModified()) : ''; ?>">
                        <?php echo $item->getDateModified() ? $view['date']->toDate($item->getDateModified()) : ''; ?>
                    </td>
                    <td class="visible-lg"><?php echo $view->escape($item->getCreatedByUser()); ?></td>
                    <td class="visible-md visible-lg"><?php echo $item->getId(); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="panel-footer">
        <?php echo $view->render(
            'MauticCoreBundle:Helper:pagination.html.php',
            [
                'totalItems' => $totalItems,
                'page'       => $page,
                'limit'      => $limit,
                'baseUrl'    => $view['router']->path('mautic_email_index'),
                'sessionVar' => 'email',
            ]
        ); ?>
    </div>
<?php else: ?>
    <?php echo $view->render('MauticCoreBundle:Helper:noresults.html.php'); ?>
<?php endif; ?>

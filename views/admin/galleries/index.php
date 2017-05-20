<div class="wrap satl-settings">
    <?php /*$version = $this->Version->checkLatestVersion();
    if (!$version['latest'] && SATL_PRO) { ?>
        <div class="plugin-update-tr">
            <div class="update-message">
                <?php echo $version['message']; ?>
            </div>
        </div>
    <?php } */?>
    <h2><?php _e('Manage Galleries', SATL_PLUGIN_NAME); ?> <?php echo $this->Html->link(__('Add New'), $this->url . '&amp;method=save', array('class' => "btn btn-primary")); ?></h2>
    <?php
    ?>
    <?php if (!empty($galleries)) : ?>	
        <form id="posts-filter" action="<?php echo $this->url; ?>" method="post">
            <ul class="subsubsub">
                <li><?php echo $paginate->allcount; ?> <?php _e('galleries', SATL_PLUGIN_NAME); ?></li>
            </ul>
        </form>
    <?php endif; ?>

    <?php if (!empty($galleries)) : ?>
        <form onsubmit="if (!confirm('<?php _e('Are you sure you wish to execute this action on the selected galleries?', SATL_PLUGIN_NAME); ?>')) { return false; }" action="<?php echo $this->url; ?>&amp;method=mass" method="post">
            <div class="tablenav">
                <div class="alignleft actions">
                    <select name="action" class="action">
                        <option value="">- <?php _e('Bulk Actions', SATL_PLUGIN_NAME); ?> -</option>
                        <option value="delete"><?php _e('Delete', SATL_PLUGIN_NAME); ?></option>
                    </select>
                    <input type="submit" class="btn btn-primary" value="<?php _e('Apply', SATL_PLUGIN_NAME); ?>" name="execute" />
                </div>
                <?php $this->render('paginate', array('paginate' => $paginate), true, 'admin'); ?>
            </div>

            <table class="widefat">
                <thead>
                    <tr>
                        <th class="check-column"><input type="checkbox" name="checkboxall" id="checkboxall" value="checkboxall" /></th>
                        <th><?php _e('ID', SATL_PLUGIN_NAME); ?></th>
                        <th><?php _e('Title', SATL_PLUGIN_NAME); ?></th>
                        <th><?php _e('Description', SATL_PLUGIN_NAME); ?></th>
                        <th><?php _e('# Slides', SATL_PLUGIN_NAME); ?></th>
                        <th><?php _e('Date', SATL_PLUGIN_NAME); ?></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="check-column"><input type="checkbox" name="checkboxall" id="checkboxall" value="checkboxall" /></th>
                        <th><?php _e('ID', SATL_PLUGIN_NAME); ?></th>
                        <th><?php _e('Title', SATL_PLUGIN_NAME); ?></th>
                        <th><?php _e('Description', SATL_PLUGIN_NAME); ?></th>
                        <th><?php _e('# Slides', SATL_PLUGIN_NAME); ?></th>
                        <th><?php _e('Date', SATL_PLUGIN_NAME); ?></th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    foreach ($galleries as $gallery) :
                        ?>

                        <tr class="<?php echo $class = (empty($class)) ? 'alternate' : ''; ?>">
                            <th class="check-column"><input type="checkbox" name="Gallery[checklist][]" value="<?php echo $gallery->id; ?>" id="checklist<?php echo $gallery->id; ?>" /></th>
                            <td><?php echo ((int) $gallery->id); ?></td>
                            <td>
                                <a class="row-title" href="<?php echo $this->url; ?>&amp;method=save&amp;id=<?php echo $gallery->id; ?>" title=""><?php echo $gallery->title; ?></a>
                                <div class="row-actions">
                                    <span class="edit"><?php echo $this->Html->link(__('Edit', SATL_PLUGIN_NAME), "?page=satellite-galleries&amp;method=save&amp;id=" . $gallery->id); ?> |</span>
                                    <span class="delete"><?php echo $this->Html->link(__('Delete', SATL_PLUGIN_NAME), "?page=satellite-galleries&amp;method=delete&amp;id=" . $gallery->id, array('class' => "submitdelete", 'onclick' => "if (!confirm('" . __('Are you sure you want to permanently remove this gallery?', SATL_PLUGIN_NAME) . "')) { return false; }")); ?> |</span>
                                    <span class="view"><?php echo $this->Html->link(__('View the Slides', SATL_PLUGIN_NAME), "?page=satellite-slides&amp;single=" . $gallery->id); ?> </span>
                                </div>
                            </td>
                            <td style="width:400px"><?php echo ($gallery->description); ?></td>                            
                            <td><?php echo ( $this -> Slide -> slideCount( $gallery->id ) ); ?></td>                            
                            <td><abbr title="<?php echo $gallery->modified; ?>"><?php echo date("Y-m-d", strtotime($gallery->modified)); ?></abbr></td>
                        </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="tablenav">

                <?php $this->render('paginate', array('paginate' => $paginate), true, 'admin'); ?>
            </div>
        </form>
    <?php else : ?>
        <p style="color:red;"><?php _e('No galleries found', SATL_PLUGIN_NAME); ?></p>
    <?php endif; ?>
</div>
<img src="<?php echo(SATL_PLUGIN_URL.'/images/Satellite-Logo-sm.png');?>" style="height:100px" class="alignright"/>
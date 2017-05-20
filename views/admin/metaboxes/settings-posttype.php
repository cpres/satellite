<?php
    $Config = new SatelliteConfigHelper;
    $Form = new SatelliteFormHelper;
    $options = $Config -> displayOption('post_type', 'PostType');

?>
<table class="form-table">
	<tbody>
    <?php $Form -> display($options, 'PostType'); ?>        
    </tbody>
</table>
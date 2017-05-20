<div class="submitbox" id="submitpost">
	<div id="minor-publishing">
		<div id="misc-publishing-actions">
			<div class="misc-pub-section misc-pub-section-last">
				<a href="<?php echo $this -> url; ?>&amp;method=reset" title="<?php _e('Reset all configuration settings to their default values', SATL_PLUGIN_NAME); ?>" onclick="if (!confirm('<?php _e('Are you sure you wish to reset all configuration settings?', SATL_PLUGIN_NAME); ?>')) { return false; }"><?php _e('Reset to Defaults', SATL_PLUGIN_NAME); ?></a>
			</div>
		</div>
	</div>
	<div id="major-publishing-actions">
		<div id="publishing-action">
			<input class="btn btn-primary" type="submit" name="save" value="<?php _e('Save Configuration', SATL_PLUGIN_NAME); ?>" />
		</div>
		<br class="clear" />
	</div>
</div>
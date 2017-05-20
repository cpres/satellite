<?php if (!empty($slide -> caption)) : ?>

	<a title="<?php $slide -> title ?>" id="imglink" href="<?php $slide -> caption ?>" onClick="append_href(this)">&nbsp;</a>

<?php else : ?>

	<a rel="lightbox" class="lightbox" title="<?php $slide -> title ?>" id="imglink" href="" onClick="append_href(this)">&nbsp;</a>

<?php endif; ?>
<ul>
  <li class="slide-holder row-fluid" ng-repeat="i in items" ng-mouseover="onhover($event)" ng-mouseout="onhover($event)">
    <div class="fl-l loader-check check-column"><input type="checkbox" name="Slide[checklist][]" value="{{i.id}}" id="checklist{{i.id}}" /></div>
    <div class="fl-l loader-image"><a href="{{i.image}}"><img ng-src="{{ i.thumb }}"/></a></div>
    <div class="fl-l loader-title">
      <a class="row-title" id="showtitle{{i.id}}" href="<?php echo $this -> url; ?>&amp;method=save&amp;id={{i.id}}&amp;single=<?php echo($single);?>" title="">{{i.title}}</a>
      <span id="edittitle{{i.id}}" data-title="{{i.title}}"></span>
      <div ng-show="hover">
        <span class="edit"><span class="satl-edit" ng-click="quickEdit(i.id)" data-id="{{ i.id }}"><?php _e('Quick Edit', SATL_PLUGIN_NAME); ?></span> |</span>
        <span class="edit"><?php echo $this -> Html -> link(__('Edit', SATL_PLUGIN_NAME), "?page=satellite-slides&amp;method=save&amp;single=".$single."&amp;id={{i.id}}"); ?> |</span>
        <span class="delete"><?php echo $this -> Html -> link(__('Delete', SATL_PLUGIN_NAME), "?page=satellite-slides&amp;method=delete&amp;single=".$single."&amp;id={{i.id}}", array('class' => "submitdelete", 'onclick' => "if (!confirm('" . __('Are you sure you want to permanently remove this slide?', SATL_PLUGIN_NAME) . "')) { return false; }")); ?></span>
      </div>
      <div class="loader-title" style="display:none" showonhoverparent>
         <span ng-click="deleteEntry(entry)"><a class="btn btn-danger" href="#">Delete</a></span>
      </div>

    </div>
    
    <div class="fl-r loader-date">{{i.date}}</div>
    <div class="fl-r loader-uselink">{{i.uselink}}</div>
    <div class="fl-r loader-order">{{i.slide_order}}</div>
    <div class="fl-r loader-section" id="showgal{{i.id}}">{{i.section}}</div>
    <span class="fl-r loader-section" id="editgal{{i.id}}" data-gallery="{{i.section}}"></span>
  </li>
</ul>  
        
<!-- Satellite Slideshow Infinite Scroll -->
<?php
$this->Gallery->loadData($slides[0]->section);
$fontsize = $this -> Gallery -> data -> font;
?>
<script type='text/javascript' src='<?php echo(SATL_PLUGIN_URL);?>/js/angular.min.js?ver=<?php echo(SATL_VERSION);?>'></script>
<script type='text/javascript' src='<?php echo(SATL_PLUGIN_URL);?>/js/ng-infinite-scroll.min.js?ver=<?php echo(SATL_VERSION);?>'></script>

<div class="wrap scroll-guarantee" ng-app="scrollApp" ng-controller="ScrollController">
  <div  infinite-scroll='loadMore()' infinite-scroll-distance='2'>
    <div class="satellite-infinite" ng-repeat="i in items" ng-mouseover="onhover($event)" ng-mouseout="onhover($event)">
      <div class="info-hold" ng-show="hover">
        <span class="title size-<?php echo $fontsize;?>">{{i.title}}</span>
      </div>
      <img ng-cloak class="ng-cloak" src="{{i.image}}"/>
    </div>
  </div>
</div>
<script type="text/javascript">
  var Slides = new Array();
<?php
$i=0;
foreach ($slides as $slide) {
?>
  Slides[<?php echo($i) ?>] = new Array();
  Slides[<?php echo($i) ?>].title = '<?php echo(addslashes($slide->title)) ?>';
  Slides[<?php echo($i) ?>].image = '<?php echo(addslashes( $this -> Html -> image_url($slide -> image))) ?>';
  
<?php
$i++;
}
?>
//<![CDATA[ 

var scrollApp = angular.module('scrollApp', ['infinite-scroll']);
scrollApp.controller('ScrollController', function($scope) {
  //$scope.images = [1, 2, 3, 4, 5, 6, 7, 8];
  $scope.items = [];

  $scope.loadMore = function() {
    //var last = this.items.length;
    var last = $scope.items.length;
    var total = Slides.length;
    if (last >= total) { 
      return;
    }
    for (var i = 0; i < 6; i++) {
        $scope.items.push({
          title: Slides[last + i].title,
          image: Slides[last + i].image
        });
    }
  };

  $scope.hover = false;
  $scope.onhover = function (e) {
    this.hover = e.type === 'mouseover';
  };

});

//]]>  

</script>
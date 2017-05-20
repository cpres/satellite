<script type="text/javascript">
  var Slides = new Array();
<?php
$i=0;
foreach ($slides as $slide) {
?>
  Slides[<?php echo($i) ?>] = new Array();
  Slides[<?php echo($i) ?>].id = <?php echo(addslashes($slide->id)) ?>;
  Slides[<?php echo($i) ?>].title = '<?php echo(addslashes($slide->title)) ?>';
  Slides[<?php echo($i) ?>].section = '<?php echo(addslashes($slide->section)) ?>';
  Slides[<?php echo($i) ?>].uselink = '<?php echo(addslashes($slide->uselink)) ?>';
  Slides[<?php echo($i) ?>].slide_order = '<?php echo(addslashes($slide->slide_order)+1) ?>';
  Slides[<?php echo($i) ?>].date = '<?php echo(date("Y-m-d", strtotime($slide -> modified))) ?>';
  Slides[<?php echo($i) ?>].image = '<?php echo(addslashes( $this -> Html -> image_url($slide -> image))) ?>';
  Slides[<?php echo($i) ?>].thumb = '<?php echo(addslashes( $this -> Html -> image_url($this->Html->thumbname($slide->image)))) ?>';
  
<?php
$i++;
}
?>
//<![CDATA[ 

var slideApp = angular.module('slideApp', ['infinite-scroll']);
slideApp.controller('SlideController', function($scope) {
  //$scope.images = [1, 2, 3, 4, 5, 6, 7, 8];
  $scope.items = [];

  $scope.loadMore = function() {
    //var last = this.items.length;
    var last = $scope.items.length;
    var total = Slides.length;
    if (last >= total) { 
      return;
    }
    for (var i = 0; i < 5; i++) {
      if (Slides[last + i] == null) {
          break;
      }
        $scope.items.push({
          id: Slides[last + i].id,
          title: Slides[last + i].title,
          thumb: Slides[last + i].thumb,
          section: Slides[last + i].section,
          slide_order: Slides[last + i].slide_order,
          date: Slides[last + i].date,
          uselink: Slides[last + i].uselink,
          image: Slides[last + i].image
        });
    }
  };

  $scope.hover = false;
  $scope.onhover = function (e) {
    this.hover = e.type === 'mouseover';
  };

  $scope.quickEdit = function(id){
    editSatlSlide(id)
  };

});

//]]>  

</script>
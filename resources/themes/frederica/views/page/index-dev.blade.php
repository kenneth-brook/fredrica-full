@extends('atlantis::' . config('atlantis.frontend_shell_view'))

@section('content')
<div id="body" class="twoColumn index">
  <div id="pageContainer">
    <?php if (isset($navtop)) : echo $navtop; endif; ?>
    <?php if (isset($header)) : echo $header; endif; ?>
    <div id="contentContainer">
      <div id="paper">
        <?php if (isset($Hero)) : echo $Hero; endif; ?>
        <div class="contents home-page-content">
          <?php if (isset($contentTop)) : echo $contentTop; endif; ?>
          <ul class="columns-wrapper">
            <li class="left-side"> {!! $content or  '' !!} </li>
            <li class="side-menu">
              <?php if (isset($indexSide)) : echo $indexSide; endif; ?>
            </li>
          </ul>
          <div style="clear:both;"></div>
        </div>
        <?php if (isset($contentBottom)) : echo $contentBottom; endif; ?>
        <br class="clear">
      </div>
    </div>
    <?php if (isset($footer)) : echo $footer; endif; ?>
  </div>
</div>
@stop

@section('js')
@parent
<script>
equalheight=function(t){var e,i=0,h=0,n=new Array;$(t).each(function(){if(e=$(this),$(e).height("auto"),topPostion=e.position().top,h!=topPostion){for(currentDiv=0;currentDiv<n.length;currentDiv++)n[currentDiv].height(i);n.length=0,h=topPostion,i=e.height(),n.push(e)}else n.push(e),i=i<e.height()?e.height():i;for(currentDiv=0;currentDiv<n.length;currentDiv++)n[currentDiv].height(i)})},$(window).load(function(){equalheight(".equal-height")}),$(window).resize(function(){equalheight(".equal-height")});
</script>
@stop
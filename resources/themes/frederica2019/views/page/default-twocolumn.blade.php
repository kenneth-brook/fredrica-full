@extends('atlantis::' . config('atlantis.frontend_shell_view'))

@section('content')
<div class="default">
<div id="body" class="threeColumn">
  <div id="pageContainer">
    <?php if (isset($navtop)) : echo $navtop; endif; ?>
    <?php if (isset($header)) : echo $header; endif; ?>
    <div id="contentContainer">
      <div id="paper">
        <?php if (isset($Hero)) : echo $Hero; endif; ?>
        <div id="navColumn">
          <?php if (isset($leftColumn)) : echo $leftColumn; endif; ?>
        </div>
        <div id="mainColumn" class="contents">
          <?php if (isset($contentTop)) : echo $contentTop; endif; ?>
          {!! $content or  '' !!} 
          <div id="subColumn">
            <?php if (isset($contentRight)) : echo $contentRight; endif; ?>
          </div>
        </div>
        <?php if (isset($contentBottom)) : echo $contentBottom; endif; ?>
        <br class="clear">
      </div>
    </div>
    <?php if (isset($footer)) : echo $footer; endif; ?>
  </div>
</div>
</div>
@stop
@extends('atlantis::' . config('atlantis.frontend_shell_view'))

@section('content')
<div id="body" class="oneColumn">
	<div id="pageContainer">
		<?php if (isset($navtop)) : echo $navtop; endif; ?>
		<?php if (isset($header)) : echo $header; endif; ?>
		<div style="clear:both;"></div>
		<div id="contentContainer">    

			<div id="paper">
				<?php if (isset($Hero)) : echo $Hero; endif; ?>
				<div id="mainColumn" class="contents">
					<?php if (isset($contentTop)) : echo $contentTop; endif; ?>
					 {!! $content or  '' !!} 
				</div>    

				<?php if (isset($contentBottom)) : echo $contentBottom; endif; ?>

				<br class="clear">
			</div>
		</div>



		<?php if (isset($footer)) : echo $footer; endif; ?>
	</div>
</div>
@stop


@section('styles')
@parent
<link rel="stylesheet" type="text/css" href="/{{ config('atlantis.theme_path') }}/media/css/style-sem.css">
@stop

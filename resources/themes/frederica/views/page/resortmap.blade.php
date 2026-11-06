@extends('atlantis::' . config('atlantis.frontend_shell_view'))


@section('scripts')
@parent
<script src="/{{ config('atlantis.theme_path') }}/media/js/map.js"></script>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyAoRNz5enqHeRufpal3jIo3CmfMRFsrFNY"></script>
<script type="text/javascript" src="/{{ config('atlantis.theme_path') }}/media/js/infobox.js"></script>

@stop

@section('content')
<?php if (isset($pageScripts)) : echo $pageScripts; endif; ?>
<div id="body" class="oneColumn">
	<div id="pageContainer">
		<?php if (isset($navtop)) : echo $navtop; endif; ?>
		<?php if (isset($header)) : echo $header; endif; ?>
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
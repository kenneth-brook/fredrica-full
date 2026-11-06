<div class="row">
	<div class="column large-6">
		<i class="fa fa-check success" aria-hidden="true"></i> {{ $categories['saved'] }} out of {{ $categories['total'] }}	categories saved successfully.<br>

		<i class="fa fa-exclamation alert" aria-hidden="true"></i> {{ $categories['failed'] }} out of {{ $categories['total'] }} categories failed<br>
	</div>
	<div class="column large-6">
		<i class="fa fa-check success" aria-hidden="true"></i> {{ $posts['saved'] }} out of {{ $posts['total'] }} posts saved successfully.<br>

		<i class="fa fa-exclamation alert" aria-hidden="true"></i> {{ $posts['failed'] }} out of {{ $posts['total'] }} posts failed.<br>
	</div>
</div>
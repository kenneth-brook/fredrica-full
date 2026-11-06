<div class="widget_archive">
    <h3 class="widget-title">Archives</h3>
    <ul>
        @foreach($list as $l)
            @if(!is_null($l->posted_date))
                <li><a href="/blog-archives/?month={!! $l->created !!}">{!! strftime('%B %Y', strtotime($l->posted_date)) !!}</a></li>
            @endif
        @endforeach
    </ul>
</div>
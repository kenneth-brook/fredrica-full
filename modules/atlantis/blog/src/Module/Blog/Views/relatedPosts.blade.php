<div class="latest_wrapper">
  @foreach($related as $r)
  <!-- Single Popular Post -->
  <div class="latest_item">
    <?php
$previeImage = MediaTools::getImage($r->imgs);
?>
    @if ($previeImage && !is_null($previeImage))
    <div class="img_wrapper">
    <a href="{{ url($single_url. $r->url) }}"><img src="{{ $previeImage->thumbnail }}" alt=""></a>
    </div>
     @else
        <div class="img_wrapper">
         <a href="{{ url($single_url.'/'. $r->url) }}">
                <img width="130" src="/resources/themes/csuanimalcancercenter/assets/images/img/blog.jpg" alt="">
           </a>
        </div>

        @endif
    <div class="info_wrapper">
      <h4><a href="{{ url($single_url. $r->url) }}">
        {{ $r->title }}
      </a></h4>
      @if(!is_null($r->posted_date))
      <p class="date">{!! \Carbon\Carbon::createFromFormat('Y-m-d h:i:s',$r->posted_date)->format('F d, Y') !!}</p>
      @endif
    </div>
  </div>
  <!-- Single Popular Post -->
  @endforeach
</div>
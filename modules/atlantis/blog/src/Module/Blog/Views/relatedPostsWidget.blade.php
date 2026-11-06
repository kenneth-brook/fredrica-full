<?php //dd($related); ?>
@if(count($related) > 0)
<div class="more-posts full-width">
    <div class="container">
        <h2>Recommended Posts</h2>
        <div class="latest_wrapper row">
            @foreach($related as $r)
            <!-- Single Popular Post -->
            <div class="latest_item col-lg-6">
                <?php
                $previeImage = MediaTools::getImage($r->imgs);
                ?>
                @if ($previeImage && !is_null($previeImage))
                <div class="img_wrapper">
                    <a href="{{ url($single_url. $r->url) }}"><img width = "175" src="{{ $previeImage->thumbnail }}" alt=""></a>
                </div>
                @else
                <div class="img_wrapper">
                    <a href="{{ url($single_url. $r->url) }}">
                        <img width="175" src="/resources/themes/be_theme/assets/images/img/blog.jpg" alt="">
                    </a>
                </div>

                @endif
                <div class="info_wrapper">
                    <h4><a href="{{ url($single_url. $r->url) }}">{{ $r->title }}</a></h4>
                            <!--@if(!is_null($r->posted_date))
                             <p class="date">{!! \Carbon\Carbon::createFromFormat('Y-m-d h:i:s',$r->posted_date)->format('F d, Y') !!}</p>   @endif-->

                             @if($blog->use_blurb == 0)
                             {{ \Illuminate\Support\Str::words( strip_tags($blog->body), $blog->body_words, '...') }}
                             @else
                             {{ $blog->blurb }}
                             @endif
                         </div>
                     </div>
                     <!-- Single Popular Post -->
                     @endforeach
                 </div>
             </div>
         </div>
         @endif
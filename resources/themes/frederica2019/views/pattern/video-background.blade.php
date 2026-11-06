
    <div class="container">
      <div class="row">
        <div class="col-lg-8 pd-b-50" data-aos="fade-left" data-aos-once="true">
          <h2 class="entry-title"><?=$title?></h2>
          <?=$text ?>
        </div>
      </div>
      @if (isset($square_image))
      <div class="square-image"><img class = "lazy" data-src="<?=$square_image ?>"></div>
      @endif
    </div>
    <div class="video pd-top-30 lazy" data-src = "<?=$background_image ?>">
      <div class="play">
        <div class="icon-play"><svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 190 190">
            <path fill="#fff" d="M92.05,17.29a77.6,77.6,0,1,0,77.6,77.6A77.69,77.69,0,0,0,92.05,17.29Zm0,150.81a73.21,73.21,0,1,1,73.21-73.21A73.3,73.3,0,0,1,92.05,168.1Z" />
            <path fill="#fff" d="M115.64,95.55a1.41,1.41,0,0,0,.06-.22,1.66,1.66,0,0,0,0-.22,1.64,1.64,0,0,0,0-.22,1.49,1.49,0,0,0,0-.21,1.81,1.81,0,0,0,0-.23,1.66,1.66,0,0,0-.06-.21,1.8,1.8,0,0,0-.06-.22c0-.1-.1-.2-.16-.3l0-.09h0a1.81,1.81,0,0,0-.25-.3.21.21,0,0,1,0-.06l0,0a3.92,3.92,0,0,0-.33-.27h0L72.51,65.21a2.89,2.89,0,0,0-4.17,2.06v55.24a2.29,2.29,0,0,0,2.1,2.28,2.25,2.25,0,0,0,.81.16,2.34,2.34,0,0,0,1.26-.37l42.2-27.77a1.24,1.24,0,0,0,.18-.14l.13-.11a2.23,2.23,0,0,0,.35-.4h0l0-.1a2.11,2.11,0,0,0,.16-.3A1.66,1.66,0,0,0,115.64,95.55ZM72.94,71l36.32,23.89L72.94,118.78Z" /></svg></div>
      </div>
      <div class="video-background">
        <div class="video-foreground">
          <div id="id-video" data-id="<?=$video_ID ?>"  @if (isset($seconds)) data-seconds="<?=$seconds ?>" @else data-seconds="30" @endif data-aos-once="true"></div><iframe src="" frameborder="0" allow="autoplay; fullscreen"></iframe>
        </div>
      </div>
    </div>
    @if (isset($CTA_text))
    <div class="button-center pd-top-50" data-aos="fade-up" data-aos-once="true" data-aos-delay = "20"><a href="<?=$CTA_link ?>" class="btn btn-blue"><?=$CTA_text ?></a></div>
    @endif
   
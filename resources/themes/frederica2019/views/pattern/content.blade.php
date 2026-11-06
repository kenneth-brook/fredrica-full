<div class="container">
    @if (isset($title))
    <div data-aos="fade-up" data-aos-once="true"> <span class="square"></span></div>
    <h1 class="text-center aos-init" data-aos="fade-up" data-aos-once="true"><?=$title; ?></h1>
    @endif
    @if (isset($text) && $text!== '')
    <div class="inner-intro {{(isset($class) && $class!== '') ? $class : ''}}" data-aos="fade-in" data-aos-once="true" data-aos-delay="50">
        @if (isset($blurb))
        <div class="text-center intro">
            <p><?=$blurb; ?></p>
        </div>
        @endif  
        <?=$text ?>               
    </div>
    @else
    <div class="text-center intro" data-aos="fade-in" data-aos-once="true" data-aos-delay="50">
    	@if (isset($blurb))
            <p><?=$blurb; ?></p>
         @endif      
    </div>
    @endif
    @if (isset($CTA_text))
    <div class="button-center pd-top-30" data-aos="fade-in" data-aos-once="true" data-aos-delay="90"><a href="<?=$CTA_link ?>" class="btn btn-blue"><?=$CTA_text ?></a></div>
    @endif
</div>

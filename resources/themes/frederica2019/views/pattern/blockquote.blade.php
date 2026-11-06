    <div class="container">
      <blockquote class="text-center" data-aos="zoom-out-up" data-aos-once="true">
           <?=$text; ?>  
      </blockquote>
       @if (isset($person))
       <span class="person text-center" data-aos="zoom-out-up" data-aos-once="true"><?=$person; ?></span>
       @endif
    </div>
    @if (isset($CTA_text))
    <div class="button-center pd-top-30" data-aos="fade-in" data-aos-once="true"><a href="<?=$CTA_link ?>" class="btn btn-blue"><?=$CTA_text ?></a>      
    </div>
    @endif
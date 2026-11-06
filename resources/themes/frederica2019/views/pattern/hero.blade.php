@if(isset($background_image))
<section class="hero-image">    
    <div class="container-title" data-aos="fade-up" data-aos-once="true">
      <img alt="<?=$text ?>" src="<?=$background_image ?>" style = "display:none">
      <h1 class="text-center"><?=$text ?></h1>
    </div>
    <div class="icon-arrow"></div>
</section>
@endif
    <div class="row ">
      <ul class="view">
        <li class="list {{ app('request')->input('view') === 'grid' ? '' : 'active' }}"><a href="/st-simons-homes-for-sale"><svg id="list" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 38 38">
              <path fill="#8A9DA3" d="M30.79,7.79H6.66c-.44,0-.8-.67-.8-1.5s.36-1.5.8-1.5H30.79c.45,0,.81.67.81,1.5s-.36,1.5-.81,1.5"></path>
              <path fill="#8A9DA3" d="M30.79,20.79H6.66c-.44,0-.8-.67-.8-1.5s.36-1.5.8-1.5H30.79c.45,0,.81.67.81,1.5s-.36,1.5-.81,1.5"></path>
              <path fill="#8A9DA3" d="M30.79,33.79H6.66c-.44,0-.8-.67-.8-1.5s.36-1.5.8-1.5H30.79c.45,0,.81.67.81,1.5s-.36,1.5-.81,1.5"></path>
            </svg> List View</a></li>
        <li class="grid {{ app('request')->input('view') === 'grid' ? 'active' : '' }}"><a href="/st-simons-homes-for-sale?view=grid"><svg id="grid" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 38 38">
              <path fill="#8A9DA3" d="M7.1,15.35h8V6.94h-8Zm9,2.1H6.05A1,1,0,0,1,5,16.4V5.89a1,1,0,0,1,1-1H16.14a1,1,0,0,1,1.05,1V16.4a1,1,0,0,1-1.05,1.05"></path>
              <path fill="#8A9DA3" d="M22.23,15.35h8V6.94h-8Zm9,2.1H21.18a1,1,0,0,1-1.05-1.05V5.89a1,1,0,0,1,1.05-1H31.27a1,1,0,0,1,1.05,1V16.4a1,1,0,0,1-1.05,1.05"></path>
              <path fill="#8A9DA3" d="M7.1,30.9h8V22.49h-8Zm9,2.1H6.05A1,1,0,0,1,5,32V21.44a1,1,0,0,1,1-1.05H16.14a1,1,0,0,1,1.05,1.05V32A1,1,0,0,1,16.14,33"></path>
              <path fill="#8A9DA3" d="M22.23,30.9h8V22.49h-8Zm9,2.1H21.18A1,1,0,0,1,20.13,32V21.44a1,1,0,0,1,1.05-1.05H31.27a1,1,0,0,1,1.05,1.05V32A1,1,0,0,1,31.27,33"></path>
            </svg>Grid View</a></li>
        <li><a href="https://properties.fredericaliving.com/idx/results/listings?idxID=e442&pt=1&a_subdivision%5B%5D=Frederica+Golf+Club"><svg id="search" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 38 38">
              <path fill="#8A9DA3" d="M16,6a10,10,0,1,1-7.07,2.93A10,10,0,0,1,16,6m0-2A12,12,0,1,0,28,16,12,12,0,0,0,16,4Z" />
              <path fill="#8A9DA3" d="M31.54,31.54a1.5,1.5,0,0,1-2.12,0L24.9,27a1.5,1.5,0,0,1,2.12-2.12l4.52,4.52A1.5,1.5,0,0,1,31.54,31.54Z" />
            </svg>Advanced Search</a></li>
      </ul>
    </div>
    <div class="row {{ app('request')->input('view') === 'grid' ? 'grid-homes' : 'list-homes' }}">

      @foreach($listings as $listing)

      @if(app('request')->input('view') === 'grid')
      <div class="col-lg-6 col-sm-6 col-12">
        <div class="box-homes" data-aos="fade-up" data-aos-once="true" data-aos-delay="50"><a href="/st-simons-homes-for-sale/{{ $listing['url'] }}">
            <?php $imgs = '';
            if ($listing->images != ''):
              $imgs = explode(',', $listing->images);
            endif;
            ?>
            @if ($imgs != '')
            @if (!is_null(MediaTools::getImage($imgs[0])))

            <?php $featured = MediaTools::getImage($imgs[0]); ?>

            <img class="lazy" data-src="/{{ $featured->thumbnail }}">

            @else

            <img class="lazy" data-src="/resources/media/a2users/{{ $imgs[0] }}">

            @endif
            @endif
            <p>{{ $listing['title'] }}</p>
            <p class="price">Offered: {{ $listing['price'] }}</p>
            <div class="info">
              <div>View details</div>
            </div>
          </a>
        </div>
      </div>
      @else
      <div class="col-12">
        <a href="/st-simons-homes-for-sale/{{ $listing['url'] }}" class="list-home row">
          <div class="col-xl-7 col-lg-12 col-sm-12 col-12 col">
            <?php $imgs = '';
            if ($listing->images != ''):
              $imgs = explode(',', $listing->images);
            endif;
            ?>
            @if ($imgs != '')
            @if (!is_null(MediaTools::getImage($imgs[0])))

            <?php $featured = MediaTools::getImage($imgs[0]); ?>

            <img class="lazy" data-src="/{{ $featured->thumbnail }}">

            @else

            <img class="lazy" data-src="/resources/media/a2users/{{ $imgs[0] }}">

            @endif
            @endif
          </div>
          <div class="col-xl-5 col-lg-12 col-12 col info-details">
            <p>{{ $listing['title'] }}</p>
            <p class="price">Offered: {{ $listing['price'] }}</p>
            <p class="foot">{{ $listing['heated_sq_ft'] }} heated square feet</p>
            <div class="info">
              <div class="btn btn-blue">View details </div>
            </div>
          </div>
        </a>
      </div>
      @endif
      @endforeach

    </div>
    {{ $listings }}
@php
$content=getContent('breadcrumb.content',true);
@endphp

<section class="inner-banner bg_img" style="background: url({{getImage('assets/images/frontend/breadcrumb/'.@$content->data_values->background_image,'900x600')}}) center;">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6 text-center">
          <h2 class="title text-white">{{ __($pageTitle) }}</h2>
        </div>
      </div>
    </div>
  </section>

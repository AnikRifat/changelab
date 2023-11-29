@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="blog-section blog-details pt-80 pb-80">
        <div class="container">
            <div class="row gy-4 gy-md-5">
                <div class="col-lg-8 col-md-12 pe-lg-4 pe-xl-5">
                    <div class="post-item p-0">
                        <div class="post-item__thumb">
                            <img src="{{ getImage('assets/images/frontend/blog/' . @$blog->data_values->blog_image, '820x440') }}">
                        </div>
                        <div class="post-item__content mt-3 mt-sm-4 p-0">
                            <ul class="d-flex flex-wrap gap-4 mb-3 align-items-center">
                                <li><i class="far fa-calendar"></i> {{ @$blog->created_at->format('d M Y') }}</li>
                            </ul>
                            <h3 class="post-item__content-title">{{ __(@$blog->data_values->title) }}</h3>
                            <div class="mb-3">
                                @php echo $blog->data_values->description; @endphp
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h5 class="blog-sidebar__title mt-0 mb-2">@lang('Share')</h5>
                        <ul class="list list--row flex-wrap social-list">
                            <li>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" class="social-list__icon">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://twitter.com/intent/tweet?text={{ __(@$blog->data_values->title) }}%0A{{ url()->current() }}" class="social-list__icon">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a class="social-list__icon" href="http://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&description={{ __(@$blog->data_values->title) }}&media={{ getImage('assets/images/frontend/blog/' . @$blog->data_values->blog_image, '800x580') }}">
                                    <i class="fab fa-pinterest-p"></i>
                                </a>
                            </li>
                            <li>
                                <a class="social-list__icon" href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ __(@$blog->data_values->title) }}&amp;summary={{ __(@$blog->data_values->short_details) }}">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="fb-comments" data-href="{{ route('blog.details', [$blog->id, slug(@$blog->data_values->title)]) }}" data-numposts="5"></div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-sidebar">
                        <h4 class="blog-sidebar__title">@lang('Latest Blogs')</h4>
                        <ul class="latest-posts m-0">
                            @foreach ($blogs as $blog)
                                <li>
                                    <div class="post-thumb">
                                        <a href="{{ route('blog.details', ['id' => $blog->id, 'slug' => slug($blog->data_values->title)]) }}">
                                            <img src="{{ getImage('assets/images/frontend/blog/thumb_' . @$blog->data_values->blog_image, '410x220') }}">
                                        </a>
                                    </div>
                                    <div class="post-info">
                                        <h6 class="title">
                                            <a href="{{ route('blog.details', ['id' => $blog->id, 'slug' => slug(@$blog->data_values->title)]) }}">
                                                {{ strLimit(__(@$blog->data_values->title), 55) }}
                                            </a>
                                        </h6>
                                        <span class="posts-date"><i class="far fa-calendar-alt"></i> {{ @$blog->created_at->format('d M Y') }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('fbComment')
    @php echo loadExtension('fb-comment') @endphp
@endpush

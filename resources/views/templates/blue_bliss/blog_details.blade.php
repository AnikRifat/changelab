@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="blog-section padding-top padding-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <article>
                        <div class="post-item post-classic post-details">
                            <div class="post-thumb c-thumb w-100">
                                <img src="{{ getImage('assets/images/frontend/blog/' . @$blog->data_values->blog_image, '820x440') }}">
                            </div>
                            <div class="post-content">
                                <div class="meta-post">
                                    <div class="date text-muted">
                                        <i class="far fa-calendar-alt"></i>
                                        {{ @$blog->created_at->format('d M Y') }}
                                    </div>
                                </div>
                                <div class="blog-header">
                                    <h4 class="title mt-0">{{ __($blog->data_values->title) }}</h4>
                                </div>

                                <div class="entry-content">
                                    @php echo $blog->data_values->description; @endphp
                                </div>

                                <h5 class="mb-2 mt-5">@lang('Like this post? share it your social network')</h5>

                                <ul class="list list--row social-list justify-content-start">
                                    <li>
                                        <a target="_blank" class="t-link social-list__icon" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}">
                                            <i class="lab la-facebook-f"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" class="t-link social-list__icon" href="https://twitter.com/intent/tweet?text={{ __(@$blog->data_values->title) }}%0A{{ url()->current() }}">
                                            <i class="lab la-twitter"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" class="t-link social-list__icon" href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ __(@$blog->data_values->title) }}&amp;summary={{ __(@$blog->data_values->description) }}">
                                            <i class="lab la-linkedin-in"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" class="t-link social-list__icon" href="http://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&description={{ __(@$blog->data_values->title) }}&media={{ getImage('assets/images/frontend/blog/' . $blog->data_values->blog_image, '840x480') }}">
                                            <i class="lab la-pinterest"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </article>
                    <div class="comment-area">
                        <div class="fb-comments" data-href="{{ route('blog.details', [$blog->id, slug($blog->data_values->title)]) }}" data-numposts="5"></div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="b-sidebar">
                        <div class="widget widget-category">
                            <h6 class="title text-start">@lang('Recent Blogs')</h6>
                            <div class="widget-body p-0">
                                <ul class="blog-sidebar-list">
                                    @foreach ($blogs as $blog)
                                        <li class="blog-sidebar-item d-flex gap-3 mb-3 pb-3">
                                            <div>
                                                <img src="{{ getImage('assets/images/frontend/blog/thumb_' . @$blog->data_values->blog_image, '410x220') }}" class="blog-image-thumb" alt="">
                                            </div>
                                            <div>
                                                <a class="p-0" href="{{ route('blog.details', ['id' => $blog->id, 'slug' => slug(@$blog->data_values->title)]) }}">
                                                    {{ strLimit(@$blog->data_values->title, 50) }}
                                                </a>
                                                <small class="text-muted">
                                                    <i class="las la-calendar"></i>
                                                    {{ showDateTime($blog->created_at, 'M d, Y') }}
                                                </small>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('fbComment')
    @php echo loadExtension('fb-comment') @endphp
@endpush

@push('style')
    <style>
        .blog-image-thumb {
            width: 70px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
@endpush

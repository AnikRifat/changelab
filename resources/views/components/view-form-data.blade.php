@if(@$data)
    @foreach($data as $k=> $item)
        <div class="row mb-3">
            <div class="col-md-12">
                <h6>{{ __(keyToTitle(@$item->name)) }}</h6>
                @if($item->type == 'checkbox')
                    {{ implode(',',$item->value) }}
                @elseif($item->type == 'file')
                    @if($item->value)
                        @if (auth()->guard('admin')->user())
                        <a href="{{ route('admin.download.attachment',encrypt(getFilePath('verify').'/'.$item->value)) }}"
                            class="me-3"><i class="fa fa-file"></i> @lang('Attachment') </a>
                        @else
                        <a href="{{ route('user.download.attachment',encrypt(getFilePath('verify').'/'.$item->value)) }}"
                            class="me-3"><i class="fa fa-file"></i> @lang('Attachment') </a>
                        @endif
                    @else
                        @lang('No file or file path not found....')
                        @endif
                    @else
                        <p>{{ __($item->value) }}</p>
                    @endif
            </div>
        </div>
    @endforeach
@endif

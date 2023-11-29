@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Currency')</th>
                                    <th>@lang('Buy At')</th>
                                    <th>@lang('Sell At')</th>
                                    <th>@lang('Reserve Amount')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($currencies as $currency)
                                    <tr>
                                        <td>
                                            <div class="user">
                                                <div class="thumb">
                                                    <img src="{{ getImage(getFilePath('currency') . '/' . $currency->image, getFileSize('currency')) }}">
                                                </div>
                                                <span class="name">{{ __($currency->name) }} - {{ __($currency->cur_sym) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            {{ showAmount($currency->buy_at) }} {{ __($general->cur_text) }}
                                        </td>
                                        <td>
                                            {{ showAmount($currency->sell_at) }} {{ __($general->cur_text) }}
                                        </td>
                                        <td>{{ showAmount($currency->reserve) }} {{ __($currency->cur_sym) }}</td>
                                        <td> @php echo $currency->statusBadge; @endphp </td>
                                        <td>
                                            <a href="{{ route('admin.currency.edit', $currency->id) }}" class="btn btn-sm btn-outline--primary">
                                                <i class="la la-pencil"></i>@lang('Edit')
                                            </a>
                                            @if ($currency->status == Status::DISABLE)
                                                <button type="button" class="btn btn-sm btn-outline--success confirmationBtn" data-action="{{ route('admin.currency.status', $currency->id) }}" data-question="@lang('Are you sure to enable this currency?')">
                                                    <i class="la la-eye"></i>@lang('Enable')
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline--danger  confirmationBtn" data-action="{{ route('admin.currency.status', $currency->id) }}" data-question="@lang('Are you sure to disable this currency?')">
                                                    <i class="la la-eye-slash"></i> @lang('Disable')
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($currencies->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($currencies) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form />
    <a class="btn btn-outline--primary" href="{{ route('admin.currency.create') }}" /><i class="las la-plus"></i>@lang('Add New')</a>
@endpush

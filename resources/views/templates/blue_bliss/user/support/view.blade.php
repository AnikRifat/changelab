@extends($activeTemplate . 'layouts.' . $layout)
@section('content')
<div class="container {{ $layout == 'frontend' ? 'padding-top padding-bottom' : '' }}">
    <div class="row justify-content-center gy-4">
        <div class="col-md-10">
            <div class="card custom--card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                    <h5 class="card-title">
                        @php echo $myTicket->statusBadge; @endphp
                        [@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}
                    </h5>
                    <div>
                        @auth
                            <a class="btn btn--base btn-sm " href="{{ route('ticket.index') }}"> <i class="la la-reply"></i>
                                @lang('My Tickets')
                            </a>
                        @endauth
                        @if ($myTicket->status != Status::TICKET_CLOSE && $myTicket->user)
                            <button class="btn btn--danger close-button btn-sm confirmationBtn" type="button" data-question="@lang('Are you sure to close this ticket?')" data-action="{{ route('ticket.close', $myTicket->id) }}"><i class="fa fa-times-circle"></i> @lang('Close Ticket')
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if ($myTicket->status != 4)
                        <form method="post" action="{{ route('ticket.reply', $myTicket->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-between">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea name="message" class="form-control form--control" rows="4" required>{{ old('message') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mb-3">
                                <a href="javascript:void(0)" class="text-muted addFile"><i class="las la-paperclip"></i> <span class="attachment-text">@lang('Attach Files')</span></a>
                            </div>

                            <div class="form-group attachment-wrapper d-none">

                                <div class="file-upload"></div>

                                <div id="fileUploadsContainer"></div>
                                <p class="ticket-attachments-message text-muted mt-2">
                                    @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'),
                                    .@lang('pdf'), .@lang('doc'), .@lang('docx'). &nbsp;
                                    <small class="text--danger">@lang('Max 5 files can be uploaded'). @lang('Maximum upload size is') {{ ini_get('upload_max_filesize') }}
                                    </small>
                                </p>

                            </div>

                            <button type="submit" class="btn btn--base w-100">
                                <i class="fa fa-reply"></i> @lang('Reply')
                            </button>
                        </form>
                    @endif
                </div>
            </div>

        </div>

        <div class="col-md-10">
            <div class="card custom--card">
                <div class="card-body">
                    @foreach ($messages as $message)
                        @if ($message->admin_id == 0)
                            <div class="row border border--base border-radius-3 my-3 py-3 mx-2">
                                <div class="col-md-3 border-end text-end">
                                    <h5 class="my-3">{{ $message->ticket->name }}</h5>
                                </div>
                                <div class="col-md-9">
                                    <p class="text-muted fw-bold my-3">
                                        @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                    <p>{{ $message->message }}</p>
                                    @if ($message->attachments->count() > 0)
                                        <div class="mt-2">
                                            @foreach ($message->attachments as $k => $image)
                                                <a href="{{ route('ticket.download', encrypt($image->id)) }}" class="me-3 text--base"><i class="fa fa-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="row border border--warning-light border-radius-3 my-3 py-3 mx-2 bg--warning-light">
                                <div class="col-md-3 border-end text-end">
                                    <h5 class="my-3">{{ $message->admin->name }}</h5>
                                    <p class="lead text-muted">@lang('Staff')</p>
                                </div>
                                <div class="col-md-9">
                                    <p class="text-muted fw-bold my-3">
                                        @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}
                                    </p>
                                    <p>{{ $message->message }}</p>
                                    @if ($message->attachments->count() > 0)
                                        <div class="mt-2">
                                            @foreach ($message->attachments as $k => $image)
                                                <a href="{{ route('ticket.download', encrypt($image->id)) }}" class="me-3"><i class="fa fa-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<x-confirmation-modal />
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addFile').on('click', function() {
                if (fileAdded >= 5) {
                    notify('error', 'You\'ve added maximum number of file');
                    return false;
                }

                fileAdded++;
                $("#fileUploadsContainer").append(`
                    <div class="d-flex gap-2 mt-3">
                        <input type="file" name="attachments[]" class="form-control form--control" required />
                        <button type="button" class="input-group-text btn--danger remove-btn btn"><i class="fa fa-times"></i></button>
                    </div>
                `);

                attachmentInfo();

            });


            $(document).on('click', '.remove-btn', function() {
                fileAdded--;
                $(this).closest('.d-flex').remove();
                attachmentInfo();
            });

            function attachmentInfo() {
                if ($('[name="attachments[]"]').length > 0) {
                    $('.attachment-text').text('Add More');
                    $('.attachment-wrapper').removeClass('d-none');
                } else {
                    $('.attachment-text').text('Attach Files');
                    $('.attachment-wrapper').addClass('d-none');
                }
            }
        })(jQuery);

         ///customize confirmation modal
         window.addEventListener('DOMContentLoaded', function (e) {
            let confirmationModal=$('#confirmationModal');
            if(confirmationModal.length > 0){
                $(confirmationModal).find('.btn--primary').addClass('btn--base btn-sm').removeClass('btn--primary');
                $(confirmationModal).find('.btn--dark').addClass('btn-sm');
            }
        });

    </script>
@endpush

@push('style')
    <style>
        .input-group-text:focus {
            box-shadow: none !important;
        }

        .close {
            cursor: pointer;
        }
    </style>
@endpush

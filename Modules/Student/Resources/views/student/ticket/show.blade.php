@extends('student::student.layouts.master')
@section('title', 'Student | Tickets')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Tickets</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.ticket.index') }}">Tickets</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="container">
                        <!-- Ticket Details -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4>{{ $ticket->subject }}</h4>
                                <p class="text-muted">Status: {{ getTicketStatus($ticket->status) }}</p>
                            </div>
                            <div class="card-body">
                                <p>{{ $ticket->description }}</p>
                                @if($ticket->attachments->count())
                                <h5>Attachments:</h5>
                                <ul>
                                    @foreach($ticket->attachments as $attachment)
                                    <li><a href="{{ asset($attachment->path) }}" target="_blank">{{ str_replace("images/tickets/", "", $attachment->path) }}</a></li>
                                    @endforeach
                                </ul>
                                @endif
                                @if($ticket->status == '3')
                                <p class="text-danger"><strong>Closed on:</strong> {{ $closed->created_at->format('Y-m-d H:i') }} by {{ userName($closed->user_type, $closed->user_id) }}</p>
                                @endif
                                @if($ticket->status == '4')
                                <p class="text-success"><strong>Reopened on:</strong> {{ $reopned->created_at->format('Y-m-d H:i') }} by {{ userName($reopned->user_type, $reopned->user_id) }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Replies Section -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Replies</h5>
                            </div>
                            <div class="card-body">
                                @if($ticket->replies->count())
                                @foreach($ticket->replies as $reply)
                                <div class="media mb-3">
                                    @php
                                    if ($reply->user_type == 'Student')
                                    $image = $reply->student->image;
                                    else
                                    $image = $reply->user->image;
                                    @endphp
                                    <img src="{{ asset($image) }}" class="mr-3" alt="User Avatar" style="width: 50px; height: 50px;">
                                    <div class="media-body">
                                        <h6 class="mt-0">{{ userName($reply->user_type, $reply->user_id) }} <small class="text-muted">{{ $reply->created_at->format('Y-m-d H:i') }}</small></h6>
                                        <p>{{ $reply->message }}</p>

                                        <!-- Reply Attachments -->
                                        @if($reply->attachments->count())
                                        <h6>Attachments:</h6>
                                        <ul>
                                            @foreach($reply->attachments as $attachment)
                                            <li><a href="{{ asset($attachment->path) }}" target="_blank">{{ str_replace("images/tickets/", "", $attachment->path) }}</a></li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <p>No replies yet.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Reply Form -->
                        @if($ticket->status == '2' || $ticket->status == '4')
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Reply to this Ticket</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('student.ticket.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="message">Message</label>
                                        <textarea name="message" class="form-control" rows="4" required></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="attachments">Attachments</label>
                                        <input type="file" name="attachments[]" class="form-control-file" multiple>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Send Reply</button>
                                </form>
                            </div>
                        </div>
                        @elseif ($ticket->status == '3')
                        <p class="text-muted">This ticket is closed. You cannot reply to it.</p>
                        @endif

                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
</section>
@endsection

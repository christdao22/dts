{{-- Developer: Christian P. Daohog --}}
{{-- Module: Profile Page --}}

@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h3 class="mb-sm-0">Profile Information</h3>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DTS</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('user.updateProfile') }}" method="POST" enctype="multipart/form-data">
                        <div class="mb-4 d-flex flex-column gap-3">
                            @method('patch')
                            @csrf
                            <div class="form-check form-switch ps-0">
                                <div class="d-flex justify-content-start align-items-center">
                                    <label class="form-check-label d-flex align-items-center gap-2 me-5" for="is_active">
                                        <span id="is_active_icon" style="font-size: 25px">{!! auth()->user()->is_active? '<i class="ri-user-follow-fill" ></i>':'<i class="ri-user-unfollow-fill"></i>' !!}</span>
                                        <span id="is_active_status">Active Status: {!! auth()->user()->is_active? '<strong class="text-success">ON</strong>' : '<strong class="text-danger">OFF</strong>' !!}</span>
                                    </label>
                                    <input class="form-check-input ms-3 mt-0" type="checkbox" name="is_active" id="is_active" style="width: 3.5em; height: 1.5em;" {!! auth()->user()->is_active || old('is_active') == 'on'? 'checked':'' !!}>
                                </div>
                                @error('is_active')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4 col-sm-12 mb-2">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control"
                                        placeholder="Enter first name..."
                                        value="{{ old('first_name') ?? $user->first_name }}" required>
                                    @error('first_name')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4 col-sm-12 mb-2">
                                    <label for="middle_name" class="form-label">Middle Name (Optional)</label>
                                    <input type="text" name="middle_name" id="middle_name" class="form-control"
                                        placeholder="Enter middle name..."
                                        value="{{ old('middle_name') ?? $user->middle_name }}">
                                    @error('middle_name')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4 col-sm-12 mb-2">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control"
                                        placeholder="Enter last name..."
                                        value="{{ old('last_name') ?? $user->last_name }}" required>
                                    @error('last_name')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="office_id" class="form-label">Office/Unit</label>
                                <select name="office_id" id="office_id" class="form-select"
                                    placeholder="Choose Office/Unit" required>
                                    <option value="">Choose Office/Unit</option>
                                    @foreach ($offices as $office)
                                    <option value="{{ $office->id }}"
                                        {{ (old('office_id') == $user->id) || ($office->id == $user->office_id)? 'selected':'' }}>
                                        {{ $office->office_name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('office_id')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="terminal_name" class="form-label">Terminal Name</label>
                                <small><strong>(Example: ICT - John Doe)</strong></small>
                                <input type="text" name="terminal_name" id="terminal_name" class="form-control"
                                    placeholder="Enter terminal name..."
                                    value="{{ old('terminal_name') ?? $user->terminal->terminal_name }}" required>
                                @error('terminal_name')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="Enter email..." value="{{ old('email') ?? $user->email }}" required>
                                @error('email')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-between gap-2">
                                <div class="form-group w-100">
                                    <label for="password" class="form-label">Password</label>
                                    <input name="password" type="password" id="password" class="form-control"
                                        placeholder="*********" required>
                                    @error('password')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group w-100">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input name="password_confirmation" type="password" id="password_confirmation"
                                        class="form-control" placeholder="*********" required>
                                    @error('password_confirmation')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('document.incoming') }}" class="btn btn-danger">Cancel</a>
                                <button class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#is_active').on('change', function (e) {
            $('#is_active_icon').html(this.checked? '<i class="ri-user-follow-fill"></i>':'<i class="ri-user-unfollow-fill"></i>');
            $('#is_active_status').html(this.checked? 'Active Status: <strong class="text-success">ON</strong>':'Active Status: <strong class="text-danger">OFF</strong>');
        });
    });
</script>

@endsection

{{-- <div id="blockId-lkl4ncggfr21710136657437" class="tawk-margin-small-bottom tawk-flex tawk-flex-bottom tawk-message-block"><!----><div class="tawk-message-group tawk-flex-1 tawk-margin-auto-left"><div><div id="messageId-zume1pijqle1710136657432" class="tawk-message-bubble"><!----><div class="tawk-flex tawk-flex-bottom tawk-visitor-chat"><div class="tawk-flex-none" style="min-width: 40px;"><time class="tawk-timeago tawk-time-display">13:57</time></div><div class="tawk-message-body tawk-margin-xsmall-left"><!----><div class="tawk-chat-bubble tawk-visitor-chat-bubble tawk-text-regular-3"><p><span class="" style="">Good afternoon, we don't have an internet connection.</span></p><!----><!----><!----><!----></div><!----></div><!----></div><!----><!----><div class="clearfix"></div></div></div><!----></div></div>
<div id="blockId-in0aifra8bdo1710136699344" class="tawk-margin-small-bottom tawk-flex tawk-flex-bottom tawk-message-block"><div class="tawk-avatar tawk-avatar-small tawk-message-profile tawk-flex-none"><div class="tawk-avatar-image"><img src="https://s3.amazonaws.com/tawk-to-pi/a/64abad2206dfd821fd04ea9e" alt="Agent profile image"></div></div><div class="tawk-message-group tawk-flex-1"><div><div id="messageId-x36mr8k65igg1710136699334" class="tawk-message-bubble"><!----><div class="tawk-flex tawk-flex-bottom tawk-agent-chat"><!----><div class="tawk-message-body tawk-margin-xsmall-left tawk-margin-xsmall-right"><!----><div class="tawk-chat-bubble tawk-agent-chat-bubble tawk-text-regular-3"><p><span class="" style="">Let me check on ot. For awhile please.</span></p><!----><!----><!----><!----></div><!----></div><div class="tawk-flex-none" style="min-width: 40px;"><time class="tawk-timeago tawk-time-display">13:58</time></div></div><!----><!----><div class="clearfix"></div></div></div><p class="tawk-margin-xsmall-left tawk-text-regular-2 tawk-text-truncate" style="display: none;"> Annalyn </p></div></div>
<div id="blockId-in0aifra8bdo1710136699344" class="tawk-margin-small-bottom tawk-flex tawk-flex-bottom tawk-message-block"><div class="tawk-avatar tawk-avatar-small tawk-message-profile tawk-flex-none"><div class="tawk-avatar-image"><img src="https://s3.amazonaws.com/tawk-to-pi/a/64abad2206dfd821fd04ea9e" alt="Agent profile image"></div></div><div class="tawk-message-group tawk-flex-1"><div><div id="messageId-x36mr8k65igg1710136699334" class="tawk-message-bubble"><!----><div class="tawk-flex tawk-flex-bottom tawk-agent-chat"><!----><div class="tawk-message-body tawk-margin-xsmall-left tawk-margin-xsmall-right"><!----><div class="tawk-chat-bubble tawk-agent-chat-bubble tawk-text-regular-3"><p><span class="" style="">I would like to verify if there is a red blinking on your modem right now?</span></p><!----><!----><!----><!----></div><!----></div><div class="tawk-flex-none" style="min-width: 40px;"><time class="tawk-timeago tawk-time-display">13:58</time></div></div><!----><!----><div class="clearfix"></div></div></div><p class="tawk-margin-xsmall-left tawk-text-regular-2 tawk-text-truncate" style="display: none;"> Annalyn </p></div></div>
<div id="blockId-lkl4ncggfr21710136657437" class="tawk-margin-small-bottom tawk-flex tawk-flex-bottom tawk-message-block"><!----><div class="tawk-message-group tawk-flex-1 tawk-margin-auto-left"><div><div id="messageId-zume1pijqle1710136657432" class="tawk-message-bubble"><!----><div class="tawk-flex tawk-flex-bottom tawk-visitor-chat"><div class="tawk-flex-none" style="min-width: 40px;"><time class="tawk-timeago tawk-time-display">13:57</time></div><div class="tawk-message-body tawk-margin-xsmall-left"><!----><div class="tawk-chat-bubble tawk-visitor-chat-bubble tawk-text-regular-3"><p><span class="" style="">Yes.</span></p><!----><!----><!----><!----></div><!----></div><!----></div><!----><!----><div class="clearfix"></div></div></div><!----></div></div>
<div id="blockId-in0aifra8bdo1710136699344" class="tawk-margin-small-bottom tawk-flex tawk-flex-bottom tawk-message-block"><div class="tawk-avatar tawk-avatar-small tawk-message-profile tawk-flex-none"><div class="tawk-avatar-image"><img src="https://s3.amazonaws.com/tawk-to-pi/a/64abad2206dfd821fd04ea9e" alt="Agent profile image"></div></div><div class="tawk-message-group tawk-flex-1"><div><div id="messageId-x36mr8k65igg1710136699334" class="tawk-message-bubble"><!----><div class="tawk-flex tawk-flex-bottom tawk-agent-chat"><!----><div class="tawk-message-body tawk-margin-xsmall-left tawk-margin-xsmall-right"><!----><div class="tawk-chat-bubble tawk-agent-chat-bubble tawk-text-regular-3"><p><span class="" style="">I see, let me create a work order request for having a LOS connection.</span></p><!----><!----><!----><!----></div><!----></div><div class="tawk-flex-none" style="min-width: 40px;"><time class="tawk-timeago tawk-time-display">13:58</time></div></div><!----><!----><div class="clearfix"></div></div></div><p class="tawk-margin-xsmall-left tawk-text-regular-2 tawk-text-truncate" style="display: none;"> Annalyn </p></div></div>
<div id="blockId-in0aifra8bdo1710136699344" class="tawk-margin-small-bottom tawk-flex tawk-flex-bottom tawk-message-block"><div class="tawk-avatar tawk-avatar-small tawk-message-profile tawk-flex-none"><div class="tawk-avatar-image"><img src="https://s3.amazonaws.com/tawk-to-pi/a/64abad2206dfd821fd04ea9e" alt="Agent profile image"></div></div><div class="tawk-message-group tawk-flex-1"><div><div id="messageId-x36mr8k65igg1710136699334" class="tawk-message-bubble"><!----><div class="tawk-flex tawk-flex-bottom tawk-agent-chat"><!----><div class="tawk-message-body tawk-margin-xsmall-left tawk-margin-xsmall-right"><!----><div class="tawk-chat-bubble tawk-agent-chat-bubble tawk-text-regular-3"><p><span class="" style="">Our technical team will visit anytime tomorrow as we have other work orders in line before yours. Thank you and enjoy the rest of the day</span></p><!----><!----><!----><!----></div><!----></div><div class="tawk-flex-none" style="min-width: 40px;"><time class="tawk-timeago tawk-time-display">14:02</time></div></div><!----><!----><div class="clearfix"></div></div></div><p class="tawk-margin-xsmall-left tawk-text-regular-2 tawk-text-truncate" style="display: none;"> Annalyn </p></div></div>
<div id="blockId-lkl4ncggfr21710136657437" class="tawk-margin-small-bottom tawk-flex tawk-flex-bottom tawk-message-block"><!----><div class="tawk-message-group tawk-flex-1 tawk-margin-auto-left"><div><div id="messageId-zume1pijqle1710136657432" class="tawk-message-bubble"><!----><div class="tawk-flex tawk-flex-bottom tawk-visitor-chat"><div class="tawk-flex-none" style="min-width: 40px;"><time class="tawk-timeago tawk-time-display">13:57</time></div><div class="tawk-message-body tawk-margin-xsmall-left"><!----><div class="tawk-chat-bubble tawk-visitor-chat-bubble tawk-text-regular-3"><p><span class="" style="">I hope it will be fixed soon as my classes are affected.</span></p><!----><!----><!----><!----></div><!----></div><!----></div><!----><!----><div class="clearfix"></div></div></div><!----></div></div> --}}

@extends('layouts.student')

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if (session('status') === 'profile-updated')
                        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ __('Your profile has been updated.') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form method="post" action="{{ route('student.profile.update') }}" class="mt-6 space-y-6">
                        @csrf

                        <div>
                            <label for="first_name" class="form-label">First Name</label>
                            <input id="first_name" name="first_name" type="text" class="form-control" value="{{ old('first_name', $user->first_name) }}" required autofocus autocomplete="first_name" />
                            @error('first_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                          </div>

                          <div>
                            <label for="last_name" class="form-label">Last Name</label>
                            <input id="last_name" name="last_name" type="text" class="form-control" value="{{ old('last_name', $user->last_name) }}" required autocomplete="last_name" />
                            @error('last_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                          </div>

                        <div>
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="email" />
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center gap-4 mt-3">
                            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

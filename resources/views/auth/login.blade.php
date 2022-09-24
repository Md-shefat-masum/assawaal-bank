@extends('layouts.app')

@section('content')

@if (env('EMAIL_VERIFICATION') == false)
    <div class="container">
        <div style="height: 100vh;" class="d-flex justify-content-center align-items-center align-content-center">
            <div class="card shadow border-0">
                <div class="card-body">
                    <form @submit.prevent="login($event)" autocomplete="off">
                    {{-- <form method="POST" action="/login" > --}}
                        @csrf

                        {{-- <div v-if="error_message" class="mb-3">
                            <p class="text-danger mb-2" >@{{error_message}}</p>
                            <a href="/remove-all-active-device" @click.prevent="logout_from_all" class="btn btn-danger">
                                Logout from another devices
                            </a>
                        </div>
                        <div v-if="success_message">
                            <p class="text-success mb-0" >@{{success_message}}</p>
                        </div> --}}

                        <div class="row mb-3">
                            <label for="email" class="col-md-12 col-form-label text-md-start">{{ __('Email Address') }}</label>

                            <div class="col-md-12">
                                <input id="email" type="email" autocomplete="off" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <p class="text-danger mb-0" v-if="email_error_message">@{{email_error_message}}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-12 col-form-label text-md-start">{{ __('Password') }}</label>

                            <div class="col-md-12">
                                <input id="password" type="password" autocomplete="off" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                <p class="text-danger mb-0" v-if="password_error_message">@{{password_error_message}}</p>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-12 text-center ">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('cjs')
        <script defer>
            new Vue({
                el: '#app',
                created: function(){
                    $('#loader').toggleClass('d-none');
                    setTimeout(() => {
                        $('form').trigger('reset');
                    }, 600);
                },
                data: function(){
                    return {
                        mode: 'login',
                        code_start_time: null,
                        left_time_min: 0,
                        left_time_sec: 0,
                        code_error_message: null,
                        email_error_message: null,
                        password_error_message: null,
                        error_message: null,
                        success_message: null,
                    }
                },
                methods: {
                    login: function(event){
                        $('#loader').toggleClass('d-none');
                        let formData = new FormData(event.target);
                        this.email_error_message = null;
                        this.password_error_message = null;
                        axios.post('/api/v1/user/api-login',formData)
                            .then(res=>{
                                // console.log(res.data);
                                setTimeout(() => {
                                    $('form').trigger('reset');
                                }, 600);
                                $('#loader').toggleClass('d-none');

                                window.localStorage.setItem('token',res.data.access_token);
                                window.localStorage.setItem('user',JSON.stringify(res.data.user));
                                localStorage.setItem('last_time',new moment());
                                window.location = '/dashboard';
                            })
                            .catch(err=>{
                                console.log(err.response);
                                $('#loader').toggleClass('d-none');
                                this.email_error_message = err.response.data?.data?.email[0];
                                this.password_error_message = err.response.data?.data?.password[0];
                                this.error_message = err.response.data.message;
                            })
                    },
                }
            })
        </script>
    @endpush
@else
    @include('auth.login_verify')
@endif

@endsection

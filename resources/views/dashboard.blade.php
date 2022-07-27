<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="token" content="{{$token}}" />

        <title>Question Bank</title>

        <!-- Scripts -->

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com" />
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" />

        <!-- Styles -->
        <link href="{{asset('')}}/css/app.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
        <link rel="stylesheet" href="{{ asset('contents/admin/admin.css') }}">
        <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@latest/pace-theme-default.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
        <script>
            window.localStorage.setItem('token',document.querySelector('meta[name="token"]').content);
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            window.s_alert = (icon, title) => {
                Toast.fire({
                    icon: icon,
                    title: title,
                })
            }
            // window.s_alert = () => '';
        </script>
    </head>

    <body>
        <div id="backend_body">
            <div id="QuestionBankManagementAppBody">
                <header class="d-flex p-0 shadow">
                    <div class="dashboard_logo">
                        <a class="mr-0 me-0" href="#/">
                            Question Bank
                        </a>
                        <button onclick="sidebarMenu.classList.toggle('show')" class="navbar-toggler border-0" type="button">
                            <span class="fa fa-align-right"></span>
                        </button>
                    </div>
                    <div class="navbar-right">
                        <ul>
                            <li>
                                <a href="#/">
                                    <i class="fa fa-globe"></i>
                                </a>
                            </li>
                            <li>
                                <div class="watch">
                                    <div class="hour">@{{hour}}</div>:
                                    <div class="min">@{{min}}</div>:
                                    <div class="sec">@{{sec}}</div>
                                    <div class="meridian">@{{meridian}}</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </header>
                <div class="container-fluid">
                    <div>
                        <question-bank-management-app user="{{ auth()->user() }}"></question-bank-management-app>
                    </div>
                </div>
            </div>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="/js/question_bank_management.js"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    </body>
</html>


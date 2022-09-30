<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="token" content="" />

        <title>Question Bank</title>

        <!-- Styles -->
        <link href="{{asset('')}}/css/app.css" rel="stylesheet" />
        <link rel="stylesheet" href="/contents/admin/bootstrap.min.css">
        <link rel="stylesheet" href="/contents/admin/font-awesome-all.min.css" />
        <link rel="stylesheet" href="{{ asset('contents/admin/admin.css') }}">
        <script src="/contents/admin/pace.min.js"></script>
        <link rel="stylesheet" href="/contents/admin/pace-theme-default.min.css">
        <script src="/contents/admin/sweetalert2@9.js"></script>
        <style>
            @font-face{
                font-family: 'Nunito', sans-serif;
                src: url("/fonts/Nunito-Regular.ttf");
            }
            #app_pre_loader{
                position: fixed;
                top: 0;
                left: 0;
                backdrop-filter: blur(4px);
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                width: 100vw;
                z-index: 999;
                background: #000000d1;
            }
        </style>
        <script>
            if(!window.localStorage.getItem('token')){
                window.location = '/login'
            }

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
        <div id="app_pre_loader">
            <img src="/loader.svg" alt="">
        </div>

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
                                <a href="#/setting">
                                    <i class="fa fa-gear"></i>
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
                        <question-bank-management-app></question-bank-management-app>
                    </div>
                </div>
            </div>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

        <link href="/contents/admin/select2.min.css" rel="stylesheet" />
        <script src="/js/question_bank_management.js"></script>
        <script defer src="/contents/admin/select2.min.js"></script>

    </body>
</html>


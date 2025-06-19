 <!-- SIDEBAR -->

 <aside class="app-sidebar sticky" id="sidebar">

     <!-- Start::main-sidebar-header -->
     <div class="main-sidebar-header">
         <a href="" class="header-logo">
             <img class="mb-2 mt-3" src="../assets/images/brand-logos/desktop-dark.png"" alt="">

             {{-- <img src="/.svg" alt="logo" class="toggle-dark">
            <img src="/assets/images/others/logo.svg" alt="logo" class="desktop-white">
            <img src="/assets/images/others/logo.svg" alt="logo" class="toggle-white"> --}}
         </a>
     </div>

     <!-- Start::main-sidebar -->
     <div class="main-sidebar" id="sidebar-scroll">
         <!-- Start::nav -->
         <nav class="main-menu-container nav nav-pills flex-column sub-open">
             <div class="slide-left" id="slide-left">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                     viewBox="0 0 24 24">
                     <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                 </svg>
             </div>
             <li class="slide__category"><span class="category-name">Main</span></li>
                <ul class="main-menu">
                    <li class="slide">
                        <a href="{{ url('dashboard') }}" class="side-menu__item">
                            <i class="bx bx-home side-menu__icon"></i>
                            <span class="side-menu__label">Dashboard</span>
                        </a>
                    </li>

                    <li class="slide mt-2">
                        <a href="{{ url('adduser') }}" class="side-menu__item">
                            <i class="bx bxs-user-plus side-menu__icon fs-17"></i>
                            <span class="side-menu__label">Add User</span>
                        </a>
                    </li>

                    <li class="slide mt-2">
                        <a href="{{ url('sale') }}" class="side-menu__item">
                            <i class="bx bx-cart side-menu__icon fs-17"></i>
                            <span class="side-menu__label">Sale</span>
                        </a>
                    </li>

                    <li class="slide mt-2">
                        <a href="{{ url('purchase') }}" class="side-menu__item">
                            <i class="bx bx-package side-menu__icon fs-17"></i>
                            <span class="side-menu__label">Purchase</span>
                        </a>
                    </li>

                    <li class="slide mt-2">
                        <a href="{{ url('return') }}" class="side-menu__item">
                            <i class="bx bx-undo side-menu__icon fs-17"></i>
                            <span class="side-menu__label">Return</span>
                        </a>
                    </li>

                    @auth
                        <li class="slide mt-2">
                            <a href="#" class="side-menu__item"
                            onclick="event.preventDefault(); document.getElementById('logout-link').submit();">
                                <i class="bx bx-log-out side-menu__icon fs-17"></i>
                                <span class="side-menu__label">Logout</span>
                            </a>
                            <form id="logout-link" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @endauth
                </ul>
             <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                     width="24" height="24" viewBox="0 0 24 24">
                     <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                 </svg>
             </div>
         </nav>
     </div>
 </aside>

@php

    $system = \App\Models\SystemSetting::first();

@endphp

<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box">

                <a href="{{ route('dashboard') }}" class="logo logo-light" style="height: 30px;width: 100px">
                    <span class="logo-lg">
                        @if (!empty($system->logo))
                            {{-- <img class="mb-3" width="100px" height="30px" src="{{ asset($system->logo ?? "") }}"
                                alt="logo" /> --}}
                            <img height="40" src="{{ $system->logo ?? '' }}" alt="logo" />
                        @else
                            <img src="{{ asset('backend/images/logo-light.png') }}" alt="" height="24">
                        @endif
                    </span>
                </a>
                <a href="{{ route('dashboard') }}" class="logo logo-dark">
                    <span class="logo-lg">
                        @if (!empty($system->logo))
                            {{-- <img class="mb-3" width="100px" height="30px" src="{{ asset($system->logo ?? "") }}"
                                alt="logo" /> --}}
                            <img height="40" src="{{ $system->logo ?? '' }}" alt="logo" />
                        @else
                            <img src="{{ asset('backend/images/logo-dark.png') }}" alt="" height="24">
                        @endif
                    </span>
                </a>

            </div>

            <ul id="side-menu">

                <!-- <li class="menu-title">Menu</li> -->

                <li>
                    <a href="{{ route('dashboard') }}">
                        <i data-feather="home"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                <!-- <li class="menu-title">Users</li> -->

                <li>
                    <a class="{{ Request::routeIs('user.*') ? 'active open' : ' ' }}" href="#sidebarUser"
                        data-bs-toggle="collapse">
                        <i data-feather="users"></i>
                        <span> User </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarUser">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('user.index') }}"
                                    class="{{ Request::routeIs('user.index') ? 'active' : ' ' }} tp-link">User List</a>
                            </li>

                            <li>
                                <a href="{{ route('user.create') }}"
                                    class="{{ Request::routeIs('user.create') ? 'active' : ' ' }} tp-link">User
                                    Create</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a class="{{ Request::routeIs('job_category.*') || Request::routeIs('job.*') || Request::routeIs('job.application.*') ? 'active open' : ' ' }}"
                        href="#sidebarJobs" data-bs-toggle="collapse">
                        <i data-feather="briefcase"></i>
                        <span> Jobs </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse {{ Request::routeIs('job_category.*') || Request::routeIs('job.*') || Request::routeIs('job.application.*') ? 'show' : '' }}"
                        id="sidebarJobs">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('job_category.index') }}"
                                    class="{{ Request::routeIs('job_category.*') ? 'active' : ' ' }} tp-link">Job
                                    Category List</a>
                            </li>

                            <li>
                                <a href="{{ route('job.index') }}"
                                    class="{{ Request::routeIs('job.*') ? 'active' : ' ' }} tp-link">Job List</a>
                            </li>

                            <li>
                                <a href="{{ route('job.application.index') }}"
                                    class="{{ Request::routeIs('job.application.*') ? 'active' : ' ' }} tp-link">Job
                                    Application List</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- <li class="menu-title mt-2">Settings</li> -->

                <li>
                    <a class="{{ Request::routeIs('settings.*') ? 'active open' : ' ' }}" href="#sidebarSettings"
                        data-bs-toggle="collapse">
                        <i data-feather="settings"></i>
                        <span> Settings </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarSettings">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('settings.profile') }}"
                                    class="tp-link {{ Request::routeIs('settings.profile') ? 'active' : ' ' }}">Profile
                                    Settings</a>
                            </li>
                            <li>
                                <a href="{{ route('settings.system.index') }}"
                                    class="tp-link {{ Request::routeIs('settings.system.index') ? 'active' : ' ' }}">System
                                    Settings</a>
                            </li>
                            <li>
                                <a href="{{ route('settings.mail') }}"
                                    class="tp-link {{ Request::routeIs('settings.mail') ? 'active' : ' ' }}">Mail
                                    Settings</a>
                            </li>
                        </ul>
                    </div>
                </li>


            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>
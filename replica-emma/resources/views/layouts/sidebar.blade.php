@php
$menus = [ 'admin' => [ ['label' => 'Dashboard', 'url' => '/dashboard',
'icon' => 'fa-solid fa-chart-simple'], ['label' => 'User Management', 'url' =>
'/user-management', 'icon' => 'fa-solid fa-user'], ['label' => 'Employee
Management', 'url' => '/employee-management', 'icon' => 'fa-solid fa-user-tie'],
['label' => 'Attendance', 'url' => '/attendance', 'icon' => 'fa-solid
fa-users-viewfinder'], ['label' => 'Time Off', 'url' => '/time-off', 'icon' =>
'fa-solid fa-calendar'], ['label' => 'Position', 'url' => '/position', 'icon' =>
'fa-solid fa-sitemap'], ['label' => 'Salary Settings', 'url' =>
'/salary-settings', 'icon' => 'fa-solid fa-money-bill-wave'], ['label' =>
'Salary', 'url' => '/salary', 'icon' => 'fa-solid fa-credit-card'], ['label' =>
'Report', 'url' => '/report', 'icon' => 'fa-solid fa-file-lines'], ], 'employee'
=> [ ['label' => 'Dashboard', 'url' => '/dashboard', 'icon' => 'fa-solid
fa-chart-simple'], ['label' => 'Attendance', 'url' => '/attendance', 'icon' =>
'fa-solid fa-users-viewfinder'], ['label' => 'Time Off', 'url' => '/time-off',
'icon' => 'fa-solid fa-calendar'], ['label' => 'Salary', 'url' => '/salary',
'icon' => 'fa-solid fa-credit-card'], ] ]; $user = auth()->user(); $roleMenus =
$menus[$user->role] ?? [];
@endphp

<!--begin::Sidebar-->
<aside
    class="app-sidebar bg-light shadow"
    data-bs-theme="light"
    data-bs-theme="dark"
>
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="#0" class="brand-link">
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">Emma</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
                class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="menu"
                data-accordion="false"
            >
                @foreach ($roleMenus as $menu)
                    @php $isActive = request()->is(ltrim($menu['url'], '/')) ?
                    'active' : '';
                    @endphp
                <li class="nav-item">
                    <a
                        href="{{ $menu['url'] }}"
                        class="nav-link {{ $isActive }}"
                    >
                        <i class="nav-icon {{ $menu['icon'] }}"></i>
                        <p>{{ $menu["label"] }}</p>
                    </a>
                </li>
                @endforeach
                <li class="nav-item">
                    <a href="/api/auth/logout" class="nav-link">
                        <i class="nav-icon fa-solid fa-right-from-bracket"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->

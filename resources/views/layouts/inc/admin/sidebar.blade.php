<aside id="layout-menu" class="layout-menu menu-vertical menu">
    <div class="app-brand demo">
      <a href="{{url('admin/dashboard')}}" class="app-brand-link">
        <span class="app-brand-logo demo">
          <span class="text-primary">
            <img width="32" height="22" src="{{asset('images/logo/logo.png')}}" alt="">
          </span>
        </span>
        <span class="app-brand-text demo menu-text fw-bold ms-3">Creative Spa</span>
      </a>

      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
        <i class="icon-base ti tabler-x d-block d-xl-none"></i>
      </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
      <!-- Dashboards -->



        @if(auth()->check() && auth()->user()->role_id == 0)

        <li class="menu-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
            <a href="{{url('admin/dashboard')}}" class="menu-link">
              <i class="menu-icon icon-base ti tabler-smart-home"></i>
              <div data-i18n="Dashboards">Dashboards</div>
            </a>
          </li>
        <li class="menu-item {{ Request::is('admin/branches/index') ? 'active' : '' }}">
            <a href="{{ url('admin/branches/index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-flower"></i>
                <div data-i18n="Branch Management">Branch Management</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('admin/users/index') ? 'active' : '' }}">
            <a href="{{url('admin/users/index')}}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-users"></i>
                <div data-i18n="Users Management">Users Managementt</div>
            </a>
          </li>
          <li class="menu-item {{ Request::is('admin/therapists/index') ? 'active' : '' }}">
            <a href="{{ url('admin/therapists/index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-briefcase"></i>
                <div data-i18n="Therapist Management">Therapist Management</div>
            </a>
        </li>

          <li class="menu-item {{ Request::is('admin/customers/index') ? 'active' : '' }}">
            <a href="{{ url('admin/customers/index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-user"></i>
                <div data-i18n="Customer Management">Customer Management</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('admin/receipts/index') ? 'active' : '' }}">
            <a href="{{ url('admin/receipts/index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-receipt"></i> <!-- Expense Icon -->
                <div data-i18n="Receipt Management">Receipt Management</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('admin/expenses/index') ? 'active' : '' }}">
            <a href="{{ url('admin/expenses/index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-cash"></i> <!-- Expense Icon -->
                <div data-i18n="Expense Management">Expense Management</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('admin/savings/index') ? 'active' : '' }}">
            <a href="{{ url('admin/savings/index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-coin"></i>
                <div data-i18n="Savings Management">Savings Management</div>
            </a>
        </li>
        {{-- <li class="menu-item {{ Request::is('admin/package-usage/index') ? 'active' : '' }}">
            <a href="{{ url('admin/package-usage/index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-clipboard"></i> <!-- Package Icon -->
                <div data-i18n="Package Usage">Package Usage</div>
            </a>
        </li> --}}

        <li class="menu-item {{ Request::is('admin/telecaller/index') ? 'active' : '' }}">
            <a href="{{ url('admin/telecaller/index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-phone-call"></i>
                <div data-i18n="Telecaller">Telecaller</div>
            </a>
        </li>

          <li class="menu-item {{ Request::is('admin/roles/index') ? 'active' : '' }}">
            <a href="{{ url('admin/roles/index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-user-cog"></i>
                <div data-i18n="Roles Management">Roles Management</div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('admin/permissions/index') ? 'active' : '' }}">
            <a href="{{ url('admin/permissions/index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-shield-lock"></i>
                <div data-i18n="Permission Management">Permission Management</div>
            </a>
        </li>

        {{-- <li class="menu-item {{ Request::is('admin/therapies/index') ? 'active' : '' }}">
            <a href="{{ url('admin/therapies/index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-tools"></i>
                <div data-i18n="Therapy Management">Therapy Management</div>
            </a>
        </li> --}}

        <li class="menu-item {{ Request::is('admin/therapies/index') || Request::is('admin/therapies/usage') ? 'active' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-tools"></i>
                <div data-i18n="Therapy">Therapy</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('admin/therapies/index') ? 'active' : '' }}">
                    <a href="{{ url('admin/therapies/index') }}" class="menu-link">
                        <div data-i18n="Therapy Management">Therapy Management</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('admin/usage/therapies') ? 'active' : '' }}">
                    <a href="{{ url('admin/usage/therapies') }}" class="menu-link">
                        <div data-i18n="Usage by Customer">Usage by Customer</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- <li class="menu-item {{ Request::is('admin/packages/index') ? 'active' : '' }}">
            <a href="{{ url('admin/packages/index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-package"></i> <!-- Package Icon -->
                <div data-i18n="Package Management">Package Management</div>
            </a>
        </li> --}}

        <li class="menu-item {{ Request::is('admin/packages/index') || Request::is('admin/packages/usage') ? 'active' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-package"></i>
                <div data-i18n="Package">Package</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('admin/packages/index') ? 'active' : '' }}">
                    <a href="{{ url('admin/packages/index') }}" class="menu-link">
                        <div data-i18n="Package Management">Package Management</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('admin/usage/packages') ? 'active' : '' }}">
                    <a href="{{ url('admin/usage/packages') }}" class="menu-link">
                        <div data-i18n="Usage by Customer">Usage by Customer</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ Request::is('admin/reports*') ? 'active' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon icon-base ti tabler-files"></i>
              <div data-i18n="Reports">Reports</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('admin/reports/branch-report') ? 'active' : '' }}">
                    <a href="{{ url('admin/reports/branch-report') }}" class="menu-link">
                      <div data-i18n="Branch Report">Branch Report</div>
                    </a>
                  </li>
              <li class="menu-item {{ Request::is('admin/reports/sales-summary') ? 'active' : '' }}">
                <a href="{{ url('admin/reports/sales-summary') }}" class="menu-link">
                  <div data-i18n="Sales Summary">Sales Summary</div>
                </a>
              </li>
              <li class="menu-item {{ Request::is('admin/reports/customer') ? 'active' : '' }}">
                <a href="{{ url('admin/reports/customer') }}" class="menu-link">
                  <div data-i18n="Customer">Customer</div>
                </a>
              </li>
              <li class="menu-item {{ Request::is('admin/reports/packages') ? 'active' : '' }}">
                <a href="{{ url('admin/reports/packages') }}" class="menu-link">
                  <div data-i18n="Packages">Packages</div>
                </a>
              </li>
              <li class="menu-item {{ Request::is('admin/reports/appointments') ? 'active' : '' }}">
                <a href="{{ url('admin/reports/appointments') }}" class="menu-link">
                  <div data-i18n="Appointments">Appointments</div>
                </a>
              </li>
            </ul>
        </li>



        @else

        {{-- <li class="menu-item {{ Request::is('admin/customers/index') ? 'active' : '' }}">
            <a href="{{ url('admin/customers/index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-user"></i>
                <div data-i18n="Customer Management">Customer Management</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('admin/telecaller/index') ? 'active' : '' }}">
            <a href="{{ url('admin/telecaller/index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-phone-call"></i>
                <div data-i18n="Telecaller">Telecaller</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('admin/receipts/index') ? 'active' : '' }}">
            <a href="{{ url('admin/receipts/index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-receipt"></i> <!-- Expense Icon -->
                <div data-i18n="Receipt Management">Receipt Management</div>
            </a>
        </li> --}}

        {{-- <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                <i class="menu-icon icon-base ti tabler-user-plus"></i>
                <div data-i18n="Add Customer">Add Customer</div>
            </a>
        </li> --}}


        <li class="menu-item {{ Request::is('admin/customers/index') ? 'active' : '' }}">
            <a href="{{ url('admin/customers/index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-user-plus"></i>
                <div data-i18n="Add Customer">Add Customer</div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('admin/receipts/index') ? 'active' : '' }}">
            <a href="{{ url('admin/receipts/index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-receipt"></i>
                <div data-i18n="Add Receipt">Add Receipt</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('admin/expenses/index') ? 'active' : '' }}">
            <a href="{{ url('admin/expenses/index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-cash"></i> <!-- Expense Icon -->
                <div data-i18n="Add Expense">Add Expense</div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('admin/savings/index') ? 'active' : '' }}">
            <a href="{{ url('admin/savings/index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-coin"></i> <!-- Expense Icon -->
                <div data-i18n="Add Savings">Add Savings</div>
            </a>
        </li>



        {{-- <li class="menu-item {{ Request::is('admin/packages/index') ? 'active' : '' }}">
            <a href="{{ url('admin/packages/index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-package"></i>
                <div data-i18n="Add Package">Add Package</div>
            </a>
        </li> --}}

        <li class="menu-item {{ Request::is('admin/telecaller/index') ? 'active' : '' }}">
            <a href="{{ url('admin/telecaller/index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-phone-call"></i>
                <div data-i18n="Telecaller">Telecaller</div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('admin/usage/packages') ? 'active' : '' }}">
            <a href="{{ url('admin/usage/packages') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-package"></i>
                <div data-i18n="Package Usage">Package Usage</div>
            </a>
        </li>

        {{-- <li class="menu-item {{ Request::is('admin/usage/therapies') ? 'active' : '' }}">
            <a href="{{ url('admin/usage/therapies') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-tools"></i>
                <div data-i18n="Therapy Usage">Therapy Usage</div>
            </a>
        </li> --}}

        @endif



    </ul>
  </aside>

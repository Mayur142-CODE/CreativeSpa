<nav
class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
id="layout-navbar">
<div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
  <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
    <i class="icon-base ti tabler-menu-2 icon-md"></i>
  </a>
</div>

<div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
  <!-- Search -->
  <div class="navbar-nav align-items-center">
    <div class="nav-item navbar-search-wrapper px-md-0 px-2 mb-0">
      <a class="nav-item nav-link search-toggler d-flex align-items-center px-0" href="javascript:void(0);">
        <span class="d-inline-block text-body-secondary fw-normal" id="autocomplete"></span>
      </a>
    </div>
  </div>

  <!-- /Search -->

  <ul class="navbar-nav flex-row align-items-center ms-md-auto">

    {{-- <li class="nav-item dropdown-language dropdown">
        <a
          class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill"
          href="javascript:void(0);"
          data-bs-toggle="dropdown">
          <i class="icon-base ti tabler-language icon-22px text-heading"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="javascript:void(0);" data-language="en" data-text-direction="ltr">
              <span>English</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="javascript:void(0);" data-language="hi" data-text-direction="ltr">
              <span>Hindi</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="javascript:void(0);" data-language="gu" data-text-direction="ltr">
              <span>Gujrati</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="javascript:void(0);" data-language="fr" data-text-direction="ltr">
              <span>French</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="javascript:void(0);" data-language="ar" data-text-direction="rtl">
              <span>Arabic</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="javascript:void(0);" data-language="de" data-text-direction="ltr">
              <span>German</span>
            </a>
          </li>
        </ul>
    </li> --}}

    {{-- <style>
        #google_translate_element select {
            display: none; /* Hide default select */
        }
        #google_translate_element{
            display: none;
        }

        .custom-translate {
            position: relative;
            display: inline-block;
        }

        .custom-translate button {
            /* background: white; */
            border: none;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 5px;
            /* box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1); */
        }

        .custom-translate button::after {
            content: '▼';
            margin-left: 10px;
            font-size: 12px;
        }

        .translate-dropdown {
            display: none;
            position: absolute;
            /* background: white; */
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            padding: 10px;
            width: 120px;
            top: 100%;
            left: 0;
            z-index: 100;
        }

        .translate-dropdown div {
            padding: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .translate-dropdown div:hover {
            background: #ff9f43;
            border-radius: 5px;
        }

        .selected-lang {
            color: #6c5ce7;
            font-weight: bold;
        }

        /* Hide Google Translate footer */
        .goog-logo-link {
            display: none !important;
        }
        .skiptranslate .goog-te-gadget {
            display: none !important;
        }
    </style>
    <div class="custom-translate">
        <button id="translate-btn">🌐 Select Language</button>
        <div class="translate-dropdown">
            <div data-lang="en" class="selected-lang">English</div>
            <div data-lang="gu">Gujarati</div>
            <div data-lang="hi">Hindi</div>
        </div>
    </div> --}}
    <div id="google_translate_element"></div>


    <!--/ Language -->

    <!-- Style Switcher -->
    <li class="nav-item dropdown">
      <a
        class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill"
        id="nav-theme"
        href="javascript:void(0);"
        data-bs-toggle="dropdown">
        <i class="icon-base ti tabler-sun icon-22px theme-icon-active text-heading"></i>
        <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
      </a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
        <li>
          <button
            type="button"
            class="dropdown-item align-items-center active"
            data-bs-theme-value="light"
            aria-pressed="false">
            <span><i class="icon-base ti tabler-sun icon-22px me-3" data-icon="sun"></i>Light</span>
          </button>
        </li>
        <li>
          <button
            type="button"
            class="dropdown-item align-items-center"
            data-bs-theme-value="dark"
            aria-pressed="true">
            <span
              ><i class="icon-base ti tabler-moon-stars icon-22px me-3" data-icon="moon-stars"></i
              >Dark</span
            >
          </button>
        </li>
        <li>
          <button
            type="button"
            class="dropdown-item align-items-center"
            data-bs-theme-value="system"
            aria-pressed="false">
            <span
              ><i
                class="icon-base ti tabler-device-desktop-analytics icon-22px me-3"
                data-icon="device-desktop-analytics"></i
              >System</span
            >
          </button>
        </li>
      </ul>
    </li>
    <!-- / Style Switcher-->

    <!-- Quick links  -->
    {{-- <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown">
      <a
        class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill"
        href="javascript:void(0);"
        data-bs-toggle="dropdown"
        data-bs-auto-close="outside"
        aria-expanded="false">
        <i class="icon-base ti tabler-layout-grid-add icon-22px text-heading"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-end p-0">
        <div class="dropdown-menu-header border-bottom">
          <div class="dropdown-header d-flex align-items-center py-3">
            <h6 class="mb-0 me-auto">Shortcuts</h6>
            <a
              href="javascript:void(0)"
              class="dropdown-shortcuts-add py-2 btn btn-text-secondary rounded-pill btn-icon"
              data-bs-toggle="tooltip"
              data-bs-placement="top"
              title="Add shortcuts"
              ><i class="icon-base ti tabler-plus icon-20px text-heading"></i
            ></a>
          </div>
        </div>
        <div class="dropdown-shortcuts-list scrollable-container">
          <div class="row row-bordered overflow-visible g-0">
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ti tabler-calendar icon-26px text-heading"></i>
              </span>
              <a href="app-calendar.html" class="stretched-link">Calendar</a>
              <small>Appointments</small>
            </div>
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ti tabler-file-dollar icon-26px text-heading"></i>
              </span>
              <a href="app-invoice-list.html" class="stretched-link">Invoice App</a>
              <small>Manage Accounts</small>
            </div>
          </div>
          <div class="row row-bordered overflow-visible g-0">
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ti tabler-user icon-26px text-heading"></i>
              </span>
              <a href="app-user-list.html" class="stretched-link">User App</a>
              <small>Manage Users</small>
            </div>
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ti tabler-users icon-26px text-heading"></i>
              </span>
              <a href="app-access-roles.html" class="stretched-link">Role Management</a>
              <small>Permission</small>
            </div>
          </div>
          <div class="row row-bordered overflow-visible g-0">
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ti tabler-device-desktop-analytics icon-26px text-heading"></i>
              </span>
              <a href="index.html" class="stretched-link">Dashboard</a>
              <small>User Dashboard</small>
            </div>
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ti tabler-settings icon-26px text-heading"></i>
              </span>
              <a href="pages-account-settings-account.html" class="stretched-link">Setting</a>
              <small>Account Settings</small>
            </div>
          </div>
          <div class="row row-bordered overflow-visible g-0">
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ti tabler-help-circle icon-26px text-heading"></i>
              </span>
              <a href="pages-faq.html" class="stretched-link">FAQs</a>
              <small>FAQs & Articles</small>
            </div>
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ti tabler-square icon-26px text-heading"></i>
              </span>
              <a href="modal-examples.html" class="stretched-link">Modals</a>
              <small>Useful Popups</small>
            </div>
          </div>
        </div>
      </div>
    </li> --}}
    <!-- Quick links -->

    <!-- Notification -->
    {{-- <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
      <a
        class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill"
        href="javascript:void(0);"
        data-bs-toggle="dropdown"
        data-bs-auto-close="outside"
        aria-expanded="false">
        <span class="position-relative">
          <i class="icon-base ti tabler-bell icon-22px text-heading"></i>
          <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
        </span>
      </a>
      <ul class="dropdown-menu dropdown-menu-end p-0">
        <li class="dropdown-menu-header border-bottom">
          <div class="dropdown-header d-flex align-items-center py-3">
            <h6 class="mb-0 me-auto">Notification</h6>
            <div class="d-flex align-items-center h6 mb-0">
              <span class="badge bg-label-primary me-2">8 New</span>
              <a
                href="javascript:void(0)"
                class="dropdown-notifications-all p-2 btn btn-icon"
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                title="Mark all as read"
                ><i class="icon-base ti tabler-mail-opened text-heading"></i
              ></a>
            </div>
          </div>
        </li>
        <li class="dropdown-notifications-list scrollable-container">
          <ul class="list-group list-group-flush">
            <li class="list-group-item list-group-item-action dropdown-notifications-item">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar">
                    <img src="../../assets/img/avatars/1.png" alt class="rounded-circle" />
                  </div>
                </div>
                <div class="flex-grow-1">
                  <h6 class="small mb-1">Congratulation Lettie 🎉</h6>
                  <small class="mb-1 d-block text-body">Won the monthly best seller gold badge</small>
                  <small class="text-body-secondary">1h ago</small>
                </div>
                <div class="flex-shrink-0 dropdown-notifications-actions">
                  <a href="javascript:void(0)" class="dropdown-notifications-read"
                    ><span class="badge badge-dot"></span
                  ></a>
                  <a href="javascript:void(0)" class="dropdown-notifications-archive"
                    ><span class="icon-base ti tabler-x"></span
                  ></a>
                </div>
              </div>
            </li>
            <li class="list-group-item list-group-item-action dropdown-notifications-item">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar">
                    <span class="avatar-initial rounded-circle bg-label-danger">CF</span>
                  </div>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-1 small">Charles Franklin</h6>
                  <small class="mb-1 d-block text-body">Accepted your connection</small>
                  <small class="text-body-secondary">12hr ago</small>
                </div>
                <div class="flex-shrink-0 dropdown-notifications-actions">
                  <a href="javascript:void(0)" class="dropdown-notifications-read"
                    ><span class="badge badge-dot"></span
                  ></a>
                  <a href="javascript:void(0)" class="dropdown-notifications-archive"
                    ><span class="icon-base ti tabler-x"></span
                  ></a>
                </div>
              </div>
            </li>
            <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar">
                    <img src="../../assets/img/avatars/2.png" alt class="rounded-circle" />
                  </div>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-1 small">New Message ✉️</h6>
                  <small class="mb-1 d-block text-body">You have new message from Natalie</small>
                  <small class="text-body-secondary">1h ago</small>
                </div>
                <div class="flex-shrink-0 dropdown-notifications-actions">
                  <a href="javascript:void(0)" class="dropdown-notifications-read"
                    ><span class="badge badge-dot"></span
                  ></a>
                  <a href="javascript:void(0)" class="dropdown-notifications-archive"
                    ><span class="icon-base ti tabler-x"></span
                  ></a>
                </div>
              </div>
            </li>
            <li class="list-group-item list-group-item-action dropdown-notifications-item">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar">
                    <span class="avatar-initial rounded-circle bg-label-success"
                      ><i class="icon-base ti tabler-shopping-cart"></i
                    ></span>
                  </div>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-1 small">Whoo! You have new order 🛒</h6>
                  <small class="mb-1 d-block text-body">ACME Inc. made new order $1,154</small>
                  <small class="text-body-secondary">1 day ago</small>
                </div>
                <div class="flex-shrink-0 dropdown-notifications-actions">
                  <a href="javascript:void(0)" class="dropdown-notifications-read"
                    ><span class="badge badge-dot"></span
                  ></a>
                  <a href="javascript:void(0)" class="dropdown-notifications-archive"
                    ><span class="icon-base ti tabler-x"></span
                  ></a>
                </div>
              </div>
            </li>
            <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar">
                    <img src="../../assets/img/avatars/9.png" alt class="rounded-circle" />
                  </div>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-1 small">Application has been approved 🚀</h6>
                  <small class="mb-1 d-block text-body"
                    >Your ABC project application has been approved.</small
                  >
                  <small class="text-body-secondary">2 days ago</small>
                </div>
                <div class="flex-shrink-0 dropdown-notifications-actions">
                  <a href="javascript:void(0)" class="dropdown-notifications-read"
                    ><span class="badge badge-dot"></span
                  ></a>
                  <a href="javascript:void(0)" class="dropdown-notifications-archive"
                    ><span class="icon-base ti tabler-x"></span
                  ></a>
                </div>
              </div>
            </li>
            <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar">
                    <span class="avatar-initial rounded-circle bg-label-success"
                      ><i class="icon-base ti tabler-chart-pie"></i
                    ></span>
                  </div>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-1 small">Monthly report is generated</h6>
                  <small class="mb-1 d-block text-body">July monthly financial report is generated </small>
                  <small class="text-body-secondary">3 days ago</small>
                </div>
                <div class="flex-shrink-0 dropdown-notifications-actions">
                  <a href="javascript:void(0)" class="dropdown-notifications-read"
                    ><span class="badge badge-dot"></span
                  ></a>
                  <a href="javascript:void(0)" class="dropdown-notifications-archive"
                    ><span class="icon-base ti tabler-x"></span
                  ></a>
                </div>
              </div>
            </li>
            <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar">
                    <img src="../../assets/img/avatars/5.png" alt class="rounded-circle" />
                  </div>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-1 small">Send connection request</h6>
                  <small class="mb-1 d-block text-body">Peter sent you connection request</small>
                  <small class="text-body-secondary">4 days ago</small>
                </div>
                <div class="flex-shrink-0 dropdown-notifications-actions">
                  <a href="javascript:void(0)" class="dropdown-notifications-read"
                    ><span class="badge badge-dot"></span
                  ></a>
                  <a href="javascript:void(0)" class="dropdown-notifications-archive"
                    ><span class="icon-base ti tabler-x"></span
                  ></a>
                </div>
              </div>
            </li>
            <li class="list-group-item list-group-item-action dropdown-notifications-item">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar">
                    <img src="../../assets/img/avatars/6.png" alt class="rounded-circle" />
                  </div>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-1 small">New message from Jane</h6>
                  <small class="mb-1 d-block text-body">Your have new message from Jane</small>
                  <small class="text-body-secondary">5 days ago</small>
                </div>
                <div class="flex-shrink-0 dropdown-notifications-actions">
                  <a href="javascript:void(0)" class="dropdown-notifications-read"
                    ><span class="badge badge-dot"></span
                  ></a>
                  <a href="javascript:void(0)" class="dropdown-notifications-archive"
                    ><span class="icon-base ti tabler-x"></span
                  ></a>
                </div>
              </div>
            </li>
            <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar">
                    <span class="avatar-initial rounded-circle bg-label-warning"
                      ><i class="icon-base ti tabler-alert-triangle"></i
                    ></span>
                  </div>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-1 small">CPU is running high</h6>
                  <small class="mb-1 d-block text-body"
                    >CPU Utilization Percent is currently at 88.63%,</small
                  >
                  <small class="text-body-secondary">5 days ago</small>
                </div>
                <div class="flex-shrink-0 dropdown-notifications-actions">
                  <a href="javascript:void(0)" class="dropdown-notifications-read"
                    ><span class="badge badge-dot"></span
                  ></a>
                  <a href="javascript:void(0)" class="dropdown-notifications-archive"
                    ><span class="icon-base ti tabler-x"></span
                  ></a>
                </div>
              </div>
            </li>
          </ul>
        </li>
        <li class="border-top">
          <div class="d-grid p-4">
            <a class="btn btn-primary btn-sm d-flex" href="javascript:void(0);">
              <small class="align-middle">View all notifications</small>
            </a>
          </div>
        </li>
      </ul>
    </li> --}}
    <!--/ Notification -->

    <!-- User -->
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
      <a
        class="nav-link dropdown-toggle hide-arrow p-0"
        href="javascript:void(0);"
        data-bs-toggle="dropdown">
        <div class="avatar avatar-online">
          <img src="{{asset(auth()->user()->profile_picture ?: 'images/uploads/pfp/default.png')}}" alt class="rounded-circle" />
        </div>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li>
          <a class="dropdown-item mt-0" href="">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0 me-2">
                <div class="avatar avatar-online">
                  <img src="{{asset(auth()->user()->profile_picture ?: 'images/uploads/pfp/default.png')}}" alt class="rounded-circle" />
                </div>
              </div>
              <div class="flex-grow-1">
                <h6 class="mb-0">{{auth()->user()->name}}</h6>
                <small class="text-body-secondary">{{auth()->user()->name}}</small>
              </div>
            </div>
          </a>
        </li>
        <li>
          <div class="dropdown-divider my-1 mx-n2"></div>
        </li>
        {{-- <li>
          <a class="dropdown-item" href="pages-profile-user.html">
            <i class="icon-base ti tabler-user me-3 icon-md"></i
            ><span class="align-middle">My Profile</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="pages-account-settings-account.html">
            <i class="icon-base ti tabler-settings me-3 icon-md"></i
            ><span class="align-middle">Settings</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="pages-account-settings-billing.html">
            <span class="d-flex align-items-center align-middle">
              <i class="flex-shrink-0 icon-base ti tabler-file-dollar me-3 icon-md"></i
              ><span class="flex-grow-1 align-middle">Billing</span>
              <span class="flex-shrink-0 badge bg-danger d-flex align-items-center justify-content-center"
                >4</span
              >
            </span>
          </a>
        </li>
        <li>
          <div class="dropdown-divider my-1 mx-n2"></div>
        </li>
        <li>
          <a class="dropdown-item" href="pages-pricing.html">
            <i class="icon-base ti tabler-currency-dollar me-3 icon-md"></i
            ><span class="align-middle">Pricing</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="pages-faq.html">
            <i class="icon-base ti tabler-question-mark me-3 icon-md"></i
            ><span class="align-middle">FAQ</span>
          </a>
        </li> --}}
        <li>
            <div class="d-grid px-2 pt-2 pb-1">
                <form action="{{ url('admin/logout') }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-danger d-flex">
                    <small class="align-middle">Logout</small>
                    <i class="icon-base ti tabler-logout ms-2 icon-14px"></i>
                  </button>
                </form>
              </div>
        </li>
      </ul>
    </li>
  </ul>
</div>
</nav>

{{-- <script>
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
        }, 'google_translate_element');
    }

    $(document).ready(function () {
        // Toggle dropdown
        $("#translate-btn").click(function () {
            $(".translate-dropdown").toggle();
        });

        // Language selection
        $(".translate-dropdown div").click(function () {
            var lang = $(this).attr("data-lang");

            $(".translate-dropdown div").removeClass("selected-lang");
            $(this).addClass("selected-lang");

            // Change language
            var select = document.querySelector("select.goog-te-combo");
            if (select) {
                select.value = lang;
                select.dispatchEvent(new Event("change"));
            }

            // Update button text
            $("#translate-btn").text($(this).text());
            $(".translate-dropdown").hide();

            // Explicitly reinitialize translation after change
            setTimeout(function() {
                var select = document.querySelector("select.goog-te-combo");
                if (select) {
                    select.value = lang;
                    select.dispatchEvent(new Event("change"));
                }
            }, 500); // Add delay to ensure re-initialization
        });

        // Close dropdown when clicking outside
        $(document).click(function (event) {
            if (!$(event.target).closest(".custom-translate").length) {
                $("#translate-btn").text("🌐 Select Language");
                $(".translate-dropdown").hide();
            }
        });
    });
</script> --}}


<script>



  $(document).ready(function(){
    $('#google_translate_element').bind('DOMNodeInserted', function(event) {
      $('.goog-te-menu-value span:first').html('LANGUAGE');
      $('.goog-te-menu-frame.skiptranslate').load(function(){
        setTimeout(function(){
          $('.goog-te-menu-frame.skiptranslate').contents().find('.goog-te-menu2-item-selected .text').html('LANGUAGE');
        }, 100);
      });
    });
  });
</script>

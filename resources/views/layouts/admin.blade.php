<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Panel</title>

    <link rel="icon" type="image/x-icon" href="{{asset('admin/assets/img/favicon/favicon.ico')}}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{asset('admin/assets/vendor/fonts/iconify-icons.css')}}" />
    <link rel="stylesheet" href="{{asset('admin/assets/vendor/libs/node-waves/node-waves.css')}}" />
    <link rel="stylesheet" href="{{asset('admin/assets/vendor/libs/pickr/pickr-themes.css')}}" />
    <link rel="stylesheet" href="{{asset('admin/assets/vendor/css/core.css')}}" />
    <link rel="stylesheet" href="{{asset('admin/assets/css/demo.css')}}" />
    <link rel="stylesheet" href="{{asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
    <link rel="stylesheet" href="{{asset('admin/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
    <link rel="stylesheet" href="{{asset('admin/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
    <link rel="stylesheet" href="{{asset('admin/assets/vendor/fonts/flag-icons.css')}}" />
    <link rel="stylesheet" href="{{asset('admin/assets/vendor/css/pages/cards-advance.css')}}" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{asset('admin/assets/vendor/js/helpers.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/js/template-customizer.js')}}"></script>
    <script src="{{asset('admin/assets/js/config.js')}}"></script>
    <script type="text/javascript">
        function googleTranslateElementInit() {
          new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: "en,hi,gu,fr,ar,es", layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
        }
    </script>

    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <style>
        .goog-te-gadget-icon {
        display: none;
        }

        .goog-te-gadget-simple {
        background-color: #ffffff !important;
        border: 0 !important;
        font-size: 10pt;
        font-weight: 800;

        display: inline-block;
        padding: 10px 10px !important;
        cursor: pointer;
        zoom: 1;
        }
        @media (max-width: 768px) {
    .goog-te-gadget-simple {
        font-size: 9pt; /* Slightly smaller font */
        padding: 8px 8px !important; /* Reduced padding */
    }
}

/* Mobile screens (max-width: 480px) */
@media (max-width: 480px) {
    .goog-te-gadget-simple {
        font-size: 8pt; /* Even smaller font */
        padding: 6px 6px !important; /* Further reduced padding */
    }
}
        .goog-te-gadget-simple span {
        color: #3e3065 !important;
        }
    </style>
</head>
<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('layouts.inc.admin.sidebar')

            <div class="layout-page">
                @include('layouts.inc.admin.navbar')
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @if(session('message'))
                            <div class="alert alert-solid-success d-flex align-items-center alert-dismissible fade show" role="alert">
                                <span class="alert-icon rounded">
                                    <i class="icon-base ti tabler-user icon-md"></i>
                                </span>
                                {{ session('message') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-solid-success d-flex align-items-center alert-dismissible fade show" role="alert">
                                <span class="alert-icon rounded">
                                    <i class="icon-base ti tabler-check icon-md"></i>
                                </span>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Danger Alert -->
                        @if(session('danger'))
                            <div class="alert alert-solid-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                                <span class="alert-icon rounded">
                                    <i class="icon-base ti tabler-ban icon-md"></i>
                                </span>
                                {{ session('danger') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <!-- Danger Alert -->
                        @if(session('error'))
                            <div class="alert alert-solid-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                                <span class="alert-icon rounded">
                                    <i class="icon-base ti tabler-ban icon-md"></i>
                                </span>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @yield('content')
                    </div>
                    @include('layouts.inc.admin.footer')
                </div>
            </div>
        </div>
    </div>

    <script src="{{asset('admin/assets/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/libs/node-waves/node-waves.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/libs/@algolia/autocomplete-js.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/libs/pickr/pickr.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/libs/hammer/hammer.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/libs/i18n/i18n.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/js/menu.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/libs/moment/moment.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
    <script src="{{asset('admin/assets/js/main.js')}}"></script>
    {{-- <script src="{{asset('admin/assets/js/tables-datatables-advanced.js')}}"></script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const dt_filter_table = document.querySelector('.dt-column-search');
            if (dt_filter_table) {
                // Setup - add a text input to each footer cell
                const thead = document.querySelector('.dt-column-search thead');
                const firstRowCells = thead.querySelector('tr').children;

                // Dynamically define columns based on table headers
                let columns = [];
                Array.from(firstRowCells).forEach(th => {
                    const columnName = th.textContent.trim().toUpperCase().replace(/\s+/g, '_');  // Replace spaces with underscores and uppercase the name
                    columns.push({ data: columnName });
                });

                // Clone the first row and append it as the second row
                const cloneRow = thead.querySelector('tr').cloneNode(true);
                thead.appendChild(cloneRow);

                // Select the newly added second row (the cloned one)
                const secondRowCells = thead.querySelectorAll('tr:nth-child(2) th');

                secondRowCells.forEach((th, i) => {
                    const title = th.textContent;
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.className = 'form-control';
                    input.placeholder = `Search ${title}`;

                    // Add left and right border styles to the parent element
                    th.style.borderLeft = 'none';
                    if (i === secondRowCells.length - 1) {
                        th.style.borderRight = 'none';
                    }

                    th.innerHTML = '';
                    th.appendChild(input);

                    // Event listener for search functionality
                    input.addEventListener('keyup', function () {
                        if (dt_filter.column(i).search() !== this.value) {
                            dt_filter.column(i).search(this.value).draw();
                        }
                    });

                    input.addEventListener('change', function () {
                        if (dt_filter.column(i).search() !== this.value) {
                            dt_filter.column(i).search(this.value).draw();
                        }
                    });
                });

                let dt_filter = new DataTable(dt_filter_table, {
                    columns: columns,  // Use the dynamically generated columns array
                    order: [[4, 'desc']], // THIS IS THE ONLY CHANGE - sort by 5th column (index 4) descending
                    orderCellsTop: true,
                    layout: {
                        topStart: {
                            rowClass: 'row mx-3 my-0 justify-content-between',
                            features: [
                                {
                                    pageLength: {
                                        menu: [7, 10, 25, 50, 100],
                                        text: 'Show_MENU_entries'
                                    }
                                }
                            ]
                        },
                        topEnd: {
                            search: {
                                placeholder: 'Type search here'
                            }
                        },
                        bottomStart: {
                            rowClass: 'row mx-3 justify-content-between',
                            features: ['info']
                        },
                        bottomEnd: 'paging'
                    },
                    language: {
                        paginate: {
                            next: '<i class="icon-base ti tabler-chevron-right scaleX-n1-rtl icon-18px"></i>',
                            previous: '<i class="icon-base ti tabler-chevron-left scaleX-n1-rtl icon-18px"></i>',
                            first: '<i class="icon-base ti tabler-chevrons-left scaleX-n1-rtl icon-18px"></i>',
                            last: '<i class="icon-base ti tabler-chevrons-right scaleX-n1-rtl icon-18px"></i>'
                        }
                    }
                });
            }
        });
    </script>

</body>
</html>

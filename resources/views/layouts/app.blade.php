<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ config('app.name', 'Inventra') }}
    </title>


    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <style>

        /* =====================================
           GLOBAL
        ===================================== */

        body {
            margin: 0;
            background: #f4f7fb;
            font-family:
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }


        .app-wrapper {
            min-height: 100vh;
            display: flex;
        }


        /* =====================================
           SIDEBAR
        ===================================== */

        .sidebar {
            width: 275px;
            min-height: 100vh;

            background: #212529;

            padding: 25px 20px;

            display: flex;
            flex-direction: column;

            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;

            overflow-y: auto;
        }


        .brand-area {
            display: flex;
            align-items: center;

            margin-bottom: 30px;
        }


        .brand-logo {
            width: 46px;
            height: 46px;

            object-fit: contain;

            margin-right: 10px;
        }


        .brand-title {
            color: white;

            font-size: 24px;
            font-weight: 700;

            margin: 0;
        }


        .brand-subtitle {
            color: #8d99a6;

            font-size: 14px;

            margin: 0;
        }


        .sidebar-nav {
            display: flex;
            flex-direction: column;

            gap: 4px;
        }


        .sidebar a {
            display: flex;
            align-items: center;

            gap: 12px;

            padding: 11px 14px;

            margin-bottom: 6px;

            border-radius: 10px;

            color: #e9ecef;

            text-decoration: none;

            transition:
                background-color 0.25s ease,
                color 0.25s ease,
                transform 0.25s ease,
                box-shadow 0.25s ease;
        }


        .sidebar a i {
            width: 22px;

            font-size: 18px;

            text-align: center;
        }


        .sidebar a:hover {
            color: white;

            background: rgba(
                255,
                255,
                255,
                0.08
            );

            transform: translateX(4px);
        }


        .sidebar a.active {
            color: white;

            font-weight: 600;

            background:
                linear-gradient(
                    135deg,
                    #0d6efd,
                    #3d8bfd
                );

            box-shadow:
                0 5px 15px
                rgba(
                    13,
                    110,
                    253,
                    0.25
                );
        }


        .sidebar-user {
            margin-top: auto;

            padding-top: 20px;

            border-top:
                1px solid #495057;
        }


        .sidebar-user small {
            color: #8d99a6;
        }


        .sidebar-user strong {
            color: white;

            display: block;

            margin-top: 3px;
        }


        /* =====================================
           MAIN CONTENT
        ===================================== */

        .main-content {
            width: calc(100% - 275px);

            margin-left: 275px;

            padding: 28px;

            min-height: 100vh;

            animation:
                pageFadeIn
                0.5s ease-out;
        }


        @keyframes pageFadeIn {

            from {
                opacity: 0;

                transform:
                    translateY(10px);
            }

            to {
                opacity: 1;

                transform:
                    translateY(0);
            }

        }


        /* =====================================
           CARDS
        ===================================== */

        .card,
        .stat-card {
            border: none;

            border-radius: 18px;

            background:
                linear-gradient(
                    135deg,
                    #ffffff 0%,
                    #f8fbff 100%
                );

            box-shadow:
                0 5px 18px
                rgba(
                    0,
                    0,
                    0,
                    0.05
                );

            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease;
        }


        .card:hover,
        .stat-card:hover {
            transform:
                translateY(-4px);

            box-shadow:
                0 10px 25px
                rgba(
                    0,
                    0,
                    0,
                    0.08
                );
        }


        .stat-card {
            position: relative;
            overflow: hidden;

            border:
                1px solid
                rgba(
                    13,
                    110,
                    253,
                    0.08
                );
        }


        .stat-card::before {
            content: "";

            position: absolute;

            top: 0;
            left: 0;

            width: 100%;
            height: 4px;

            background:
                linear-gradient(
                    90deg,
                    #0d6efd,
                    #6ea8fe
                );
        }


        .stat-card h3,
        .stat-card h4 {
            font-weight: 700;
        }


        /* =====================================
           BUTTONS
        ===================================== */

        .btn {
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }


        .btn:hover {
            transform:
                translateY(-2px);

            box-shadow:
                0 5px 12px
                rgba(
                    0,
                    0,
                    0,
                    0.12
                );
        }


        /* =====================================
           TABLE
        ===================================== */

        .table tbody tr {
            transition:
                background-color 0.2s ease,
                transform 0.2s ease;
        }


        .table tbody tr:hover {
            transform:
                scale(1.002);
        }


        /* =====================================
           INPUTS
        ===================================== */

        .form-control,
        .form-select {
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease;
        }


        .form-control:focus,
        .form-select:focus {
            box-shadow:
                0 0 0
                0.2rem
                rgba(
                    13,
                    110,
                    253,
                    0.12
                );
        }


        /* =====================================
           NORMAL FLASH POPUPS
        ===================================== */

        .flash-popup {
            position: fixed;

            bottom: 20px;
            right: 20px;

            z-index: 15000;

            min-width: 180px;
            max-width: 280px;

            padding: 9px 13px;

            margin: 0;

            font-size: 13px;

            border-radius: 10px;

            box-shadow:
                0 8px 22px
                rgba(
                    0,
                    0,
                    0,
                    0.16
                );

            animation:
                alertSlideIn
                0.4s ease forwards;
        }


        @keyframes alertSlideIn {

            from {
                opacity: 0;

                transform:
                    translateY(20px);
            }

            to {
                opacity: 1;

                transform:
                    translateY(0);
            }

        }


        /* =====================================
           LOW STOCK
        ===================================== */

        .low-stock-alert {
            position: fixed;

            bottom: 70px;
            right: 20px;

            z-index: 15001;

            min-width: 180px;
            max-width: 300px;

            padding: 9px 13px;

            margin: 0;

            background: #d39e00;

            color: white;

            border:
                1px solid #b38600;

            font-size: 13px;

            border-radius: 10px;

            box-shadow:
                0 8px 22px
                rgba(
                    0,
                    0,
                    0,
                    0.18
                );

            animation:
                alertSlideIn
                0.4s ease forwards;
        }


        /* =====================================
           OUT OF STOCK
        ===================================== */

        .out-of-stock-alert {
            position: fixed;

            bottom: 120px;
            right: 20px;

            z-index: 15002;

            min-width: 180px;
            max-width: 300px;

            padding: 9px 13px;

            margin: 0;

            background: #dc3545;

            color: white;

            border:
                1px solid #b02a37;

            font-size: 13px;

            border-radius: 10px;

            box-shadow:
                0 8px 22px
                rgba(
                    0,
                    0,
                    0,
                    0.18
                );

            animation:
                alertSlideIn
                0.4s ease forwards;
        }


        /* =====================================
           CUSTOM CONFIRMATION MODAL
        ===================================== */

        .confirm-overlay {
            position: fixed;

            inset: 0;

            background:
                rgba(
                    15,
                    23,
                    42,
                    0.48
                );

            backdrop-filter:
                blur(5px);

            display: flex;

            align-items: center;
            justify-content: center;

            z-index: 20000;

            opacity: 0;

            visibility: hidden;

            transition:
                opacity 0.25s ease,
                visibility 0.25s ease;
        }


        .confirm-overlay.show {
            opacity: 1;

            visibility: visible;
        }


        .confirm-dialog {
            width:
                calc(100% - 40px);

            max-width: 420px;

            padding: 30px;

            background: white;

            border-radius: 20px;

            text-align: center;

            box-shadow:
                0 25px 60px
                rgba(
                    0,
                    0,
                    0,
                    0.24
                );

            opacity: 0;

            transform:
                translateY(25px)
                scale(0.90);

            transition:
                opacity 0.3s ease,
                transform 0.3s ease;
        }


        .confirm-overlay.show
        .confirm-dialog {

            opacity: 1;

            transform:
                translateY(0)
                scale(1);
        }


        .confirm-icon {
            width: 68px;
            height: 68px;

            margin:
                0 auto 18px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #ffe5e8;

            color: #dc3545;

            font-size: 30px;

            animation:
                confirmIconPop
                0.4s ease;
        }


        @keyframes confirmIconPop {

            from {
                transform:
                    scale(0.5);

                opacity: 0;
            }

            to {
                transform:
                    scale(1);

                opacity: 1;
            }

        }


        .confirm-title {
            font-size: 22px;

            font-weight: 700;

            margin-bottom: 8px;
        }


        .confirm-message {
            color: #6c757d;

            font-size: 14px;

            line-height: 1.6;

            margin-bottom: 24px;
        }


        .confirm-actions {
            display: flex;

            justify-content: center;

            gap: 10px;
        }


        .confirm-actions .btn {
            min-width: 115px;
        }


        /* =====================================
           MOBILE
        ===================================== */

        @media (
            max-width: 900px
        ) {

            .sidebar {
                width: 220px;
            }


            .main-content {
                width:
                    calc(
                        100% - 220px
                    );

                margin-left:
                    220px;

                padding: 20px;
            }

        }


        /* =====================================
           REDUCED MOTION
        ===================================== */

        @media (
            prefers-reduced-motion: reduce
        ) {

            *,
            *::before,
            *::after {
                animation: none !important;

                transition: none !important;
            }

        }

    </style>

</head>


<body>

<div class="app-wrapper">


    {{-- ===============================
         SIDEBAR
    =============================== --}}

    <aside class="sidebar">


        <div class="brand-area">

            <img
                src="{{ asset('images/inventra-logo.png') }}"
                alt="Inventra Logo"
                class="brand-logo"
            >


            <div>

                <h1 class="brand-title">
                    Inventra
                </h1>

                <p class="brand-subtitle">
                    Inventory & Sales System
                </p>

            </div>

        </div>


        <nav class="sidebar-nav">


            <a
                href="{{ route('dashboard') }}"
                class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
            >
                <i class="bi bi-speedometer2"></i>

                <span>
                    Dashboard
                </span>
            </a>


            <a
                href="{{ route('categories.index') }}"
                class="{{ request()->routeIs('categories.*') ? 'active' : '' }}"
            >
                <i class="bi bi-grid"></i>

                <span>
                    Categories
                </span>
            </a>


            <a
                href="{{ route('products.index') }}"
                class="{{ request()->routeIs('products.*') ? 'active' : '' }}"
            >
                <i class="bi bi-box-seam"></i>

                <span>
                    Products
                </span>
            </a>


            <a
                href="{{ route('customers.index') }}"
                class="{{ request()->routeIs('customers.*') ? 'active' : '' }}"
            >
                <i class="bi bi-people"></i>

                <span>
                    Customers
                </span>
            </a>


            <a
                href="{{ route('sales.index') }}"
                class="{{ request()->routeIs('sales.*') ? 'active' : '' }}"
            >
                <i class="bi bi-receipt"></i>

                <span>
                    Sales
                </span>
            </a>


            <a
                href="{{ route('reports.index') }}"
                class="{{ request()->routeIs('reports.*') ? 'active' : '' }}"
            >
                <i class="bi bi-bar-chart-line"></i>

                <span>
                    Reports
                </span>
            </a>

        </nav>


        @auth

            <div class="sidebar-user">

                <small>
                    Logged in as
                </small>

                <strong>
                    {{ auth()->user()->name }}
                </strong>


                <form
                    action="{{ route('logout') }}"
                    method="POST"
                    class="mt-3"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-outline-light w-100"
                    >
                        <i class="bi bi-box-arrow-right me-1"></i>

                        Logout
                    </button>

                </form>

            </div>

        @endauth


    </aside>


    {{-- ===============================
         MAIN CONTENT
    =============================== --}}

    <main class="main-content">

        @yield('content')

    </main>


</div>



{{-- =====================================
     CONFIRMATION MODAL
===================================== --}}

<div
    id="confirmOverlay"
    class="confirm-overlay"
>

    <div class="confirm-dialog">


        <div class="confirm-icon">

            <i
                class="bi bi-exclamation-triangle-fill"
            ></i>

        </div>


        <div
            id="confirmTitle"
            class="confirm-title"
        >
            Are you sure?
        </div>


        <div
            id="confirmMessage"
            class="confirm-message"
        >
            Please confirm this action.
        </div>


        <div class="confirm-actions">


            <button
                type="button"
                id="confirmCancelBtn"
                class="btn btn-secondary"
            >
                Cancel
            </button>


            <button
                type="button"
                id="confirmActionBtn"
                class="btn btn-danger"
            >
                Delete
            </button>


        </div>

    </div>

</div>



{{-- Bootstrap --}}
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
></script>



{{-- =====================================
     COUNT-UP ANIMATION
===================================== --}}

<script>
document.addEventListener(
    'DOMContentLoaded',
    function () {

        const counters =
            document.querySelectorAll(
                '.count-up'
            );


        counters.forEach(
            function (counter) {

                const target =
                    parseFloat(
                        counter.dataset.target
                    ) || 0;


                const decimals =
                    parseInt(
                        counter.dataset.decimals || 0
                    );


                const duration = 900;

                const startTime =
                    performance.now();


                function updateCounter(
                    currentTime
                ) {

                    const progress =
                        Math.min(
                            (
                                currentTime -
                                startTime
                            ) /
                            duration,
                            1
                        );


                    const easedProgress =
                        1 -
                        Math.pow(
                            1 - progress,
                            3
                        );


                    const value =
                        target *
                        easedProgress;


                    counter.textContent =
                        value.toLocaleString(
                            'en-US',
                            {
                                minimumFractionDigits:
                                    decimals,

                                maximumFractionDigits:
                                    decimals
                            }
                        );


                    if (
                        progress < 1
                    ) {

                        requestAnimationFrame(
                            updateCounter
                        );
                    }

                }


                requestAnimationFrame(
                    updateCounter
                );

            });

    }
);
</script>



{{-- =====================================
     PAGE SHOW ANIMATION
===================================== --}}

<script>
window.addEventListener(
    'pageshow',
    function () {

        const mainContent =
            document.querySelector(
                '.main-content'
            );


        if (mainContent) {

            mainContent.style.animation =
                'none';


            void mainContent.offsetWidth;


            mainContent.style.animation =
                'pageFadeIn 0.5s ease-out';
        }

    }
);
</script>



{{-- =====================================
     STOCK SOUND + POPUP ANIMATIONS
===================================== --}}

<script>
document.addEventListener(
    'DOMContentLoaded',
    function () {

        const alerts =
            document.querySelectorAll(
                '.alert'
            );


        const lowStockAlert =
            document.querySelector(
                '.low-stock-alert'
            );


        const outOfStockAlert =
            document.querySelector(
                '.out-of-stock-alert'
            );


        /* -------------------------
           Sound
        ------------------------- */

        function playStockSound(
            type
        ) {

            try {

                const AudioContextClass =
                    window.AudioContext ||
                    window.webkitAudioContext;


                if (!AudioContextClass) {
                    return;
                }


                const audioContext =
                    new AudioContextClass();


                if (
                    audioContext.state ===
                    'suspended'
                ) {

                    audioContext.resume();
                }


                function beep(
                    frequency,
                    start,
                    duration,
                    volume
                ) {

                    const oscillator =
                        audioContext
                            .createOscillator();


                    const gain =
                        audioContext
                            .createGain();


                    oscillator.connect(
                        gain
                    );


                    gain.connect(
                        audioContext.destination
                    );


                    oscillator.type =
                        'sine';


                    oscillator.frequency.value =
                        frequency;


                    gain.gain.setValueAtTime(
                        volume,
                        audioContext.currentTime +
                        start
                    );


                    gain.gain
                        .exponentialRampToValueAtTime(
                            0.01,
                            audioContext.currentTime +
                            start +
                            duration
                        );


                    oscillator.start(
                        audioContext.currentTime +
                        start
                    );


                    oscillator.stop(
                        audioContext.currentTime +
                        start +
                        duration
                    );

                }


                if (
                    type === 'low'
                ) {

                    beep(
                        750,
                        0,
                        0.18,
                        0.15
                    );


                    beep(
                        900,
                        0.25,
                        0.18,
                        0.15
                    );

                }


                if (
                    type === 'out'
                ) {

                    beep(
                        950,
                        0,
                        0.20,
                        0.20
                    );


                    beep(
                        700,
                        0.24,
                        0.20,
                        0.20
                    );


                    beep(
                        500,
                        0.48,
                        0.30,
                        0.22
                    );

                }

            }
            catch (error) {

                console.log(
                    'Stock alert sound unavailable.'
                );
            }

        }


        if (outOfStockAlert) {

            playStockSound(
                'out'
            );

        }
        else if (lowStockAlert) {

            playStockSound(
                'low'
            );
        }


        /* -------------------------
           Flash messages
        ------------------------- */

        alerts.forEach(
            function (alert) {

                const message =
                    alert.textContent
                        .toLowerCase();


                const isStockAlert =
                    alert.classList
                        .contains(
                            'low-stock-alert'
                        )
                    ||
                    alert.classList
                        .contains(
                            'out-of-stock-alert'
                        );


                const isDelete =
                    message.includes(
                        'deleted'
                    );


                const isUpdate =
                    message.includes(
                        'updated'
                    )
                    ||
                    message.includes(
                        'edited'
                    );


                const isCreate =
                    message.includes(
                        'created'
                    )
                    ||
                    message.includes(
                        'added'
                    )
                    ||
                    message.includes(
                        'successfully'
                    );


                if (
                    !isStockAlert &&
                    !isDelete &&
                    !isUpdate &&
                    !isCreate
                ) {

                    return;
                }


                if (
                    !isStockAlert
                ) {

                    alert.classList.add(
                        'flash-popup'
                    );
                }


                if (isDelete) {

                    alert.classList.remove(
                        'alert-success',
                        'alert-warning'
                    );


                    alert.classList.add(
                        'alert-danger'
                    );

                }
                else if (isUpdate) {

                    alert.classList.remove(
                        'alert-success',
                        'alert-danger'
                    );


                    alert.classList.add(
                        'alert-warning'
                    );

                }


                setTimeout(
                    function () {

                        alert.style.transition =
                            'opacity 0.5s ease, transform 0.5s ease';


                        alert.style.opacity =
                            '0';


                        alert.style.transform =
                            'translateY(20px)';


                        setTimeout(
                            function () {

                                alert.remove();

                            },
                            500
                        );

                    },
                    2000
                );

            });

    }
);
</script>



{{-- =====================================
     CUSTOM CONFIRMATION
===================================== --}}

<script>
document.addEventListener(
    'DOMContentLoaded',
    function () {

        const overlay =
            document.getElementById(
                'confirmOverlay'
            );


        const title =
            document.getElementById(
                'confirmTitle'
            );


        const message =
            document.getElementById(
                'confirmMessage'
            );


        const cancelBtn =
            document.getElementById(
                'confirmCancelBtn'
            );


        const actionBtn =
            document.getElementById(
                'confirmActionBtn'
            );


        let pendingForm = null;


        document.addEventListener(
            'submit',
            function (event) {

                const form =
                    event.target;


                if (
                    !form.matches(
                        'form[data-confirm]'
                    )
                ) {

                    return;
                }


                if (
                    form.dataset.confirmed ===
                    'true'
                ) {

                    return;
                }


                event.preventDefault();


                pendingForm =
                    form;


                title.textContent =
                    form.dataset
                        .confirmTitle
                    ||
                    'Are you sure?';


                message.textContent =
                    form.dataset
                        .confirmMessage
                    ||
                    'Please confirm this action.';


                actionBtn.textContent =
                    form.dataset
                        .confirmButton
                    ||
                    'Confirm';


                const type =
                    form.dataset
                        .confirmType
                    ||
                    'danger';


                actionBtn.className =
                    'btn';


                if (
                    type === 'danger'
                ) {

                    actionBtn.classList.add(
                        'btn-danger'
                    );

                }
                else if (
                    type === 'warning'
                ) {

                    actionBtn.classList.add(
                        'btn-warning'
                    );

                }
                else {

                    actionBtn.classList.add(
                        'btn-primary'
                    );

                }


                overlay.classList.add(
                    'show'
                );

            }
        );


        cancelBtn.addEventListener(
            'click',
            function () {

                overlay.classList.remove(
                    'show'
                );


                pendingForm =
                    null;

            }
        );


        actionBtn.addEventListener(
            'click',
            function () {

                if (!pendingForm) {
                    return;
                }


                const formToSubmit =
                    pendingForm;


                pendingForm =
                    null;


                overlay.classList.remove(
                    'show'
                );


                formToSubmit.dataset
                    .confirmed =
                    'true';


                formToSubmit.submit();

            }
        );


        overlay.addEventListener(
            'click',
            function (event) {

                if (
                    event.target ===
                    overlay
                ) {

                    overlay.classList.remove(
                        'show'
                    );


                    pendingForm =
                        null;

                }

            }
        );


        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key ===
                    'Escape'
                ) {

                    overlay.classList.remove(
                        'show'
                    );


                    pendingForm =
                        null;

                }

            }
        );

    }
);
</script>



{{-- =====================================
     BROWSER BACK CACHE FIX
===================================== --}}

<script>
window.addEventListener(
    'pageshow',
    function (event) {

        if (
            event.persisted
        ) {

            window.location.reload();

        }

    }
);
</script>


</body>

</html>
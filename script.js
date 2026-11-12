    /* =====================================================
    LGU DAPA RESERVATION SYSTEM
    MAIN JAVASCRIPT
    Team Member 1 & Team Member 2
    ===================================================== */


    /* =====================================================
    LOGIN STATE
    ===================================================== */

    let isLoggedIn =
        localStorage.getItem("lguLoggedIn") === "true";

        

    let loggedInUser =
        localStorage.getItem("lguUserName") || "";


    /* =====================================================
    COOL NOTIFICATION / SUCCESS POPUP
    FIXED - USER CAN TRY AGAIN
    ===================================================== */

    function showNotification(
        title,
        message,
        type = "success",
        redirect = null
    ) {

        // Remove existing notification if there is one
        const oldNotification =
            document.getElementById("customNotification");

        if (oldNotification) {
            oldNotification.remove();
        }


        /* =================================================
        SELECT NOTIFICATION ICON
        ================================================= */

        let icon = "fa-circle-check";

        if (type === "error") {
            icon = "fa-circle-xmark";
        }

        if (type === "warning") {
            icon = "fa-triangle-exclamation";
        }


        /* =================================================
        CREATE NOTIFICATION
        ================================================= */

        const notification =
            document.createElement("div");

        notification.id =
            "customNotification";

        notification.innerHTML = `

            <div class="notification-overlay">

                <div class="notification-box ${type}">

                    <div class="notification-icon">

                        <i class="fa-solid ${icon}"></i>

                    </div>

                    <div class="notification-content">

                        <h2>${title}</h2>

                        <p>${message}</p>

                    </div>

                    <div class="notification-progress"></div>

                </div>

            </div>

        `;


        document.body.appendChild(notification);


        /* =================================================
        REQUIRED DOM OUTPUT
        Team Member 1
        ================================================= */

        const here =
            document.getElementById("here");

        if (here) {
            here.innerHTML = "";
        }


        /* =================================================
        SUCCESS MESSAGE WITH REDIRECT
        ================================================= */

        if (redirect) {

            setTimeout(function () {

                notification.classList.add(
                    "notification-hide"
                );

            }, 2400);


            setTimeout(function () {

                window.location.href = redirect;

            }, 2800);


            return;
        }


        /* =================================================
        ERROR / WARNING
        DISAPPEARS SO USER CAN TRY AGAIN
        ================================================= */

        setTimeout(function () {

            notification.classList.add(
                "notification-hide"
            );


            setTimeout(function () {

                const currentNotification =
                    document.getElementById(
                        "customNotification"
                    );

                if (currentNotification) {

                    currentNotification.remove();

                }

            }, 350);


        }, 1800);

    }


    /* =====================================================
    POPUP FUNCTIONS
    ===================================================== */

    function openPopup(content) {

        const existingPopup =
            document.getElementById("popupOverlay");

        if (existingPopup) {
            existingPopup.remove();
        }


        const popup =
            document.createElement("div");

        popup.id = "popupOverlay";

        popup.className = "popup-overlay";


        popup.innerHTML = `

            <div class="popup-box">

                <button
                    class="popup-close"
                    onclick="closePopup()">

                    <i class="fa-solid fa-xmark"></i>

                </button>

                ${content}

            </div>

        `;


        document.body.appendChild(popup);


        popup.addEventListener("click", function (event) {

            if (event.target === popup) {
                closePopup();
            }

        });

    }


    function closePopup() {

        const popup =
            document.getElementById("popupOverlay");

        if (popup) {

            popup.classList.add("popup-closing");

            setTimeout(function () {

                popup.remove();

            }, 250);

        }

    }


    /* =====================================================
    MOBILE MENU
    ===================================================== */

    function toggleMobileMenu() {

        const nav =
            document.querySelector(".nav-links");

        if (nav) {
            nav.classList.toggle("active");
        }

    }


    function closeMobileMenu() {

        const nav =
            document.querySelector(".nav-links");

        if (nav) {
            nav.classList.remove("active");
        }

    }


    /* =====================================================
    HOME
    ===================================================== */

    function goHome() {

        window.location.href = "pages/home.html";

    }


    /* =====================================================
    GET STARTED
    ===================================================== */

    function handleGetStarted() {

        if (isLoggedIn) {

            showFacilities();

        } else {

            showLogin();

        }

    }


    /* =====================================================
    CHECK LOGIN
    ===================================================== */

    function checkLogin() {

        if (!isLoggedIn) {

            showNotification(
                "Login Required",
                "Please login first to make a reservation.",
                "warning"
            );

            setTimeout(function () {

                showLogin();

            }, 1800);

            return false;

        }

        return true;

    }


    /* =====================================================
    SHOW FACILITIES
    Team Member 1 & Team Member 2
    ===================================================== */

    function showFacilities() {

        /* LOGIN IS REQUIRED BEFORE BROWSING */
        if (!checkLogin()) {
            return;
        }


        openPopup(`

            <div class="popup-content">

                <div class="popup-title">

                    <i class="fa-solid fa-building"></i>

                    <h2>Available Facilities</h2>

                </div>

                <p class="popup-subtitle">
                    Select a facility you want to reserve.
                </p>


                <div class="resource-grid">


                    <!-- DAPA GYMNASIUM -->
                    <div class="resource-card">

                        <div class="resource-icon">
                            <i class="fa-solid fa-basketball"></i>
                        </div>

                        <h3>Dapa Gymnasium</h3>

                        <p>
                            Available for sports activities,
                            meetings and community events.
                        </p>

                        <button
                            type="button"
                            class="primary-btn"
                            onclick="reserve('Dapa Gymnasium')">

                            <i class="fa-solid fa-calendar-plus"></i>

                            Reserve

                        </button>

                    </div>


                    <!-- TEATRO -->
                    <div class="resource-card">

                        <div class="resource-icon">
                            <i class="fa-solid fa-masks-theater"></i>
                        </div>

                        <h3>Teatro</h3>

                        <p>
                            Suitable for performances,
                            programs and community events.
                        </p>

                        <button
                            type="button"
                            class="primary-btn"
                            onclick="reserve('Teatro')">

                            <i class="fa-solid fa-calendar-plus"></i>

                            Reserve

                        </button>

                    </div>


                    <!-- COVERED COURT -->
                    <div class="resource-card">

                        <div class="resource-icon">
                            <i class="fa-solid fa-basketball"></i>
                        </div>

                        <h3>Covered Court</h3>

                        <p>
                            Perfect for sports activities,
                            meetings and community events.
                        </p>

                        <button
                            type="button"
                            class="primary-btn"
                            onclick="reserve('Covered Court')">

                            <i class="fa-solid fa-calendar-plus"></i>

                            Reserve

                        </button>

                    </div>


                </div>

            </div>

        `);

    }


    /* =====================================================
    SHOW EQUIPMENT
    Team Member 1 & Team Member 2
    ===================================================== */

    function showEquipment() {

        /* LOGIN IS REQUIRED BEFORE BROWSING */
        if (!checkLogin()) {
            return;
        }


        openPopup(`

            <div class="popup-content">

                <div class="popup-title">

                    <i class="fa-solid fa-box-open"></i>

                    <h2>Available Equipment</h2>

                </div>

                <p class="popup-subtitle">
                    Select equipment you want to reserve.
                </p>


                <div class="resource-grid">


                    <!-- CHAIRS -->
                    <div class="resource-card">

                        <div class="resource-icon">
                            <i class="fa-solid fa-chair"></i>
                        </div>

                        <h3>Chairs</h3>

                        <p>
                            Chairs available for
                            events and community gatherings.
                        </p>

                        <button
                            type="button"
                            class="primary-btn"
                            onclick="reserve('Chairs')">

                            <i class="fa-solid fa-calendar-plus"></i>

                            Reserve

                        </button>

                    </div>


                    <!-- TABLES -->
                    <div class="resource-card">

                        <div class="resource-icon">
                            <i class="fa-solid fa-table"></i>
                        </div>

                        <h3>Tables</h3>

                        <p>
                            Tables available for
                            community activities and events.
                        </p>

                        <button
                            type="button"
                            class="primary-btn"
                            onclick="reserve('Tables')">

                            <i class="fa-solid fa-calendar-plus"></i>

                            Reserve

                        </button>

                    </div>


                    <!-- TENTS -->
                    <div class="resource-card">

                        <div class="resource-icon">
                            <i class="fa-solid fa-tent"></i>
                        </div>

                        <h3>Tents</h3>

                        <p>
                            Tents available for outdoor
                            events and community activities.
                        </p>

                        <button
                            type="button"
                            class="primary-btn"
                            onclick="reserve('Tents')">

                            <i class="fa-solid fa-calendar-plus"></i>

                            Reserve

                        </button>

                    </div>


                </div>

            </div>

        `);

    }


    /* =====================================================
    RESERVATION FORM
    ===================================================== */

    function reserve(item) {

        if (!checkLogin()) {
            return;
        }


        openPopup(`

            <div class="popup-content reservation-popup">

                <div class="popup-title">

                    <i class="fa-solid fa-calendar-check"></i>

                    <h2>Reserve ${item}</h2>

                </div>

                <p class="popup-subtitle">
                    Fill in the details below.
                </p>


                <div class="form-group">

                    <label>
                        Full Name
                    </label>

                    <div class="input-wrapper">

                        <i class="fa-solid fa-user"></i>

                        <input
                            type="text"
                            id="reserveName"
                            value="${loggedInUser}"
                            placeholder="Enter your name"
                            required>

                    </div>

                </div>


                <div class="form-group">

                    <label>
                        Date
                    </label>

                    <div class="input-wrapper">

                        <i class="fa-solid fa-calendar"></i>

                        <input
                            type="date"
                            id="reserveDate"
                            required>

                    </div>

                </div>


                <div class="form-group">

                    <label>
                        Time
                    </label>

                    <div class="input-wrapper">

                        <i class="fa-solid fa-clock"></i>

                        <input
                            type="time"
                            id="reserveTime"
                            required>

                    </div>

                </div>


                <button
                    class="auth-submit"
                    onclick="submitReservation('${item}')">

                    <i class="fa-solid fa-paper-plane"></i>

                    Submit Reservation

                </button>

            </div>

        `);

    }

    /* =====================================================
    SUBMIT RESERVATION
    DATABASE CONNECTED VERSION
    ===================================================== */

    function submitReservation(item) {

        const date =
            document.getElementById("reserveDate").value;

        const time =
            document.getElementById("reserveTime").value;


        /* LOGIC: Check required reservation details */

        if (date === "" || time === "") {

            showNotification(
                "Incomplete Information",
                "Please select a date and time.",
                "warning"
            );

            return;
        }


        /* LOGIC: Send ONE reservation request to reserve.php */

        fetch("reserve.php", {

            method: "POST",

            headers: {
                "Content-Type":
                    "application/x-www-form-urlencoded"
            },

            body: new URLSearchParams({

                item: item,

                date: date,

                time: time

            })

        })

        .then(function(response) {

            /* LOGIC: Check server response */

            if (!response.ok) {

                throw new Error(
                    "Server returned status " +
                    response.status
                );

            }

            return response.json();

        })

        .then(function(data) {

            console.log(
                "Reservation PHP Response:",
                data
            );


            /* LOGIC: Check reservation result */

            if (data.success) {

                closePopup();


                showNotification(
                    "Reservation Submitted!",
                    data.message ||
                    "Your reservation has been sent successfully and is waiting for staff approval.",
                    "success"
                );


                /* LOGIC: Refresh My Reservations */

                if (
                    typeof loadMyReservations ===
                    "function"
                ) {

                    loadMyReservations();

                }

            } else {

                showNotification(
                    "Reservation Failed",
                    data.message ||
                    "Unable to submit reservation.",
                    "warning"
                );


                console.error(
                    "Reservation PHP Error:",
                    data
                );

            }

        })

        .catch(function(error) {

            console.error(
                "Reservation Error:",
                error
            );


            showNotification(
                "Connection Error",
                "Could not connect to the reservation server.",
                "warning"
            );

        });

    }

    /* =====================================================
    MY RESERVATIONS
    DATABASE CONNECTED VERSION
    ===================================================== */

    function loadMyReservations() {

        const container =
            document.getElementById("myReservationsList");

        if (!container) {
            return;
        }

        fetch("my_reservations.php")
            .then(function(response) {

                if (!response.ok) {

                    throw new Error(
                        "Server returned status " +
                        response.status
                    );

                }

                return response.json();

            })
            .then(function(data) {

                console.log(
                    "My Reservations PHP Response:",
                    data
                );

                if (!data.success) {

                    container.innerHTML = `
                        <div class="reservation-empty">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <h3>Please Login</h3>
                            <p>Login to view your reservations.</p>
                        </div>
                    `;

                    return;
                }

                if (
                    !data.reservations ||
                    data.reservations.length === 0
                ) {

                    container.innerHTML = `
                        <div class="reservation-empty">
                            <i class="fa-solid fa-calendar-xmark"></i>
                            <h3>No Reservations Yet</h3>
                            <p>You have not submitted any reservation requests yet.</p>
                        </div>
                    `;

                    return;
                }

                container.innerHTML = "";


                data.reservations.forEach(
                    function(reservation) {

                        let statusClass =
                            "status-pending";

                        if (
                            reservation.status ===
                            "Approved"
                        ) {

                            statusClass =
                                "status-approved";

                        }

                        if (
                            reservation.status ===
                            "Rejected"
                        ) {

                            statusClass =
                                "status-rejected";

                        }


                        /* LOGIC: Only Pending reservations
                        can show the Cancel button */

                        let cancelButton = "";

                        if (
                            reservation.status ===
                            "Pending"
                        ) {

                            cancelButton = `
                                <button
                                    class="cancel-reservation-btn"
                                    onclick="cancelReservation(${reservation.id})"
                                >
                                    <i class="fa-solid fa-xmark"></i>
                                    Cancel Reservation
                                </button>
                            `;

                        }


                        const card =
                            document.createElement("div");

                        card.className =
                            "reservation-card";


                        card.innerHTML = `

                            <h3>
                                <i class="fa-solid fa-calendar-check"></i>
                                ${reservation.resource_name}
                            </h3>

                            <p>
                                <strong>Type:</strong>
                                ${reservation.resource_type}
                            </p>

                            <p>
                                <strong>Date:</strong>
                                ${reservation.reservation_date}
                            </p>

                            <p>
                                <strong>Time:</strong>
                                ${reservation.reservation_time}
                            </p>

                            <p>
                                <strong>Status:</strong>

                                <span
                                    class="status-badge ${statusClass}"
                                >
                                    ${reservation.status}
                                </span>
                            </p>

                            ${cancelButton}

                        `;


                        container.appendChild(card);

                    }
                );

            })
            .catch(function(error) {

                console.error(
                    "Reservation loading error:",
                    error
                );

                container.innerHTML = `
                    <div class="reservation-empty">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <h3>Unable to Load Reservations</h3>
                        <p>Please check your connection and try again.</p>
                    </div>
                `;

            });

    }


    /* =====================================================
   CANCEL RESERVATION
   ANIMATED CONFIRMATION + RESULT
===================================================== */

let cancelReservationId = null;


/* =====================================================
   SHOW CANCEL CONFIRMATION
===================================================== */

function cancelReservation(id) {

    cancelReservationId = id;

    const overlay =
        document.getElementById("cancelConfirmOverlay");

    if (!overlay) {

        console.error(
            "Cancel confirmation modal not found."
        );

        return;

    }

    overlay.classList.add("show");

}


/* =====================================================
   CLOSE CANCEL CONFIRMATION
===================================================== */

function closeCancelConfirm() {

    const overlay =
        document.getElementById("cancelConfirmOverlay");

    if (overlay) {

        overlay.classList.remove("show");

    }

    cancelReservationId = null;

}


/* =====================================================
   CONFIRM CANCEL ACTION
===================================================== */

function confirmCancelReservation() {

    if (!cancelReservationId) {
        return;
    }


    const id = cancelReservationId;


    /* Close confirmation first */

    closeCancelConfirm();


    /* LOGIC: Send cancellation request to PHP */

    fetch("cancel_reservation.php", {

        method: "POST",

        headers: {

            "Content-Type":
                "application/x-www-form-urlencoded",

            "Accept":
                "application/json"

        },

        body: new URLSearchParams({

            id: id

        })

    })

    .then(function(response) {

        /* LOGIC: Check server response */

        if (!response.ok) {

            throw new Error(
                "Server returned status " +
                response.status
            );

        }

        return response.json();

    })

    .then(function(data) {

        console.log(
            "Cancel Reservation Response:",
            data
        );


        /* =================================================
           LOGIC: Check cancellation result
        ================================================= */

        if (data.success) {

            showCancelResult(
                "success",
                "Reservation Cancelled!",
                data.message ||
                "Your reservation has been cancelled successfully."
            );


            /* LOGIC: Refresh My Reservations */

            loadMyReservations();

        } else {

            showCancelResult(
                "error",
                "Cancellation Failed",
                data.message ||
                "Unable to cancel this reservation."
            );

        }

    })

    .catch(function(error) {

        console.error(
            "Cancellation Error:",
            error
        );


        showCancelResult(
            "error",
            "Connection Error",
            "Could not connect to the reservation server."
        );

    });

}


/* =====================================================
   SHOW CANCEL RESULT
===================================================== */

function showCancelResult(
    type,
    title,
    message
) {

    const overlay =
        document.getElementById(
            "cancelResultOverlay"
        );

    const icon =
        document.querySelector(
            "#cancelResultOverlay .cancel-result-icon"
        );

    const iconSymbol =
        document.querySelector(
            "#cancelResultOverlay .cancel-result-icon i"
        );

    const resultTitle =
        document.getElementById(
            "cancelResultTitle"
        );

    const resultMessage =
        document.getElementById(
            "cancelResultMessage"
        );


    if (!overlay) {

        console.error(
            "Cancel result modal not found."
        );

        return;

    }


    /* =================================================
       SUCCESS RESULT
    ================================================= */

    if (type === "success") {

        icon.className =
            "cancel-result-icon success";

        iconSymbol.className =
            "fa-solid fa-circle-check";

    }


    /* =================================================
       ERROR RESULT
    ================================================= */

    else {

        icon.className =
            "cancel-result-icon error";

        iconSymbol.className =
            "fa-solid fa-circle-xmark";

    }


    resultTitle.textContent =
        title;

    resultMessage.textContent =
        message;


    overlay.classList.add("show");

}


/* =====================================================
   CLOSE CANCEL RESULT
===================================================== */

function closeCancelResult() {

    const overlay =
        document.getElementById(
            "cancelResultOverlay"
        );

    if (overlay) {

        overlay.classList.remove("show");

    }

}
/* =====================================================
   CANCEL CONFIRM BUTTON
===================================================== */

document.addEventListener(
    "DOMContentLoaded",
    function () {

        const cancelButton =
            document.getElementById(
                "cancelConfirmYes"
            );


        if (cancelButton) {

            cancelButton.onclick =
                function () {

                    confirmCancelReservation();

                };

        }

    }
);

   


    /* =====================================================
    LOAD RESERVATIONS WHEN PAGE OPENS
    ===================================================== */

    document.addEventListener(
        "DOMContentLoaded",
        function() {

            loadMyReservations();

        }
    );


    /* =====================================================
    LOGIN PAGE
    ===================================================== */

    function showLogin() {

        if (window.location.pathname.includes("/pages/")) {

            window.location.href = "login.html";

        } else {

            window.location.href = "pages/login.html";

        }

    }   


    /* =====================================================
    LOGIN FUNCTION
    ===================================================== */

    function login(event) {

        if (event) {
            event.preventDefault();
        }


        const emailElement =
            document.getElementById("loginEmail");

        const passwordElement =
            document.getElementById("loginPassword");


        if (
            !emailElement ||
            !passwordElement
        ) {
            return;
        }


        const email =
            emailElement.value.trim();

        const password =
            passwordElement.value;


        if (
            email === "" ||
            password === ""
        ) {

            showNotification(
                "Missing Information",
                "Please enter your email and password.",
                "warning"
            );

            return;

        }


        const registeredEmail =
            localStorage.getItem("registeredEmail");

        const registeredPassword =
            localStorage.getItem("registeredPassword");

        const registeredName =
            localStorage.getItem("registeredName");


        if (
            !registeredEmail ||
            !registeredPassword
        ) {

            showNotification(
                "No Account Found",
                "Please register an account before logging in.",
                "warning"
            );


            setTimeout(function () {

                window.location.href =
                    "pages/register.html";

            }, 1800);

            return;

        }


        if (
            email !== registeredEmail ||
            password !== registeredPassword
        ) {

            showNotification(
                "Login Failed",
                "Incorrect email or password. Please try again.",
                "error"
            );

            return;

        }


        isLoggedIn = true;

        loggedInUser =
            registeredName || email;


        localStorage.setItem(
            "lguLoggedIn",
            "true"
        );

        localStorage.setItem(
            "lguUserName",
            loggedInUser
        );


        showNotification(
            "Login Successful!",
            `Welcome back, ${loggedInUser}! You can now make a reservation.`,
            "success",
            "home.html"
        );

    }


    /* =====================================================    
    REGISTER PAGE
    ===================================================== */

function showRegister() {

    if (window.location.pathname.includes("/pages/")) {

        window.location.href = "register.html";

    } else {

        window.location.href = "pages/register.html";

    }

}

    /* =====================================================
    REGISTER FUNCTION
    ===================================================== */

    function register(event) {

        if (event) {
            event.preventDefault();
        }


        const nameElement =
            document.getElementById("registerName");

        const emailElement =
            document.getElementById("registerEmail");

        const passwordElement =
            document.getElementById("registerPassword");

        const confirmElement =
            document.getElementById("registerConfirmPassword");


        if (
            !nameElement ||
            !emailElement ||
            !passwordElement
        ) {
            return;
        }


        const name =
            nameElement.value.trim();

        const email =
            emailElement.value.trim();

        const password =
            passwordElement.value;

        const confirmPassword =
            confirmElement
                ? confirmElement.value
                : password;


        if (
            name === "" ||
            email === "" ||
            password === "" ||
            confirmPassword === ""
        ) {

            showNotification(
                "Incomplete Registration",
                "Please complete all fields.",
                "warning"
            );

            return;

        }


        if (password !== confirmPassword) {

            showNotification(
                "Passwords Do Not Match",
                "Please make sure both passwords are the same.",
                "error"
            );

            return;

        }


        if (password.length < 6) {

            showNotification(
                "Password Too Short",
                "Your password must contain at least 6 characters.",
                "warning"
            );

            return;

        }


        const existingEmail =
            localStorage.getItem("registeredEmail");


        if (existingEmail === email) {

            showNotification(
                "Account Already Exists",
                "This email is already registered. Please login instead.",
                "warning"
            );


            setTimeout(function () {

                window.location.href =
                    "pages/login.html";

            }, 2200);

            return;

        }


        localStorage.setItem(
            "registeredName",
            name
        );

        localStorage.setItem(
            "registeredEmail",
            email
        );

        localStorage.setItem(
            "registeredPassword",
            password
        );


        showNotification(
            "Registration Successful!",
            `Welcome, ${name}! Your account has been created successfully.`,
            "success",
            "login.html"
        );

    }


    /* =====================================================
    LOGOUT
    ===================================================== */

    function logout() {

        isLoggedIn = false;

        loggedInUser = "";


        localStorage.removeItem(
            "lguLoggedIn"
        );

        localStorage.removeItem(
            "lguUserName"
        );


        showNotification(
            "Logged Out",
            "You have been safely logged out.",
            "success"
        );


        setTimeout(function () {

            window.location.href =
                "home.html";

        }, 1800);

    }


    /* =====================================================
    UPDATE LOGIN BUTTON
    ===================================================== */

    function updateLoginButton() {

        const loginButton =
            document.querySelector(
                ".nav-buttons .login"
            );

        const registerButton =
            document.querySelector(
                ".nav-buttons .register"
            );


        if (isLoggedIn) {

            if (loginButton) {

                loginButton.innerHTML =
                    `<i class="fa-solid fa-user"></i> ${loggedInUser}`;

                loginButton.onclick =
                    function () {
                        showUserMenu();
                    };

            }


            if (registerButton) {

                registerButton.innerHTML =
                    `<i class="fa-solid fa-right-from-bracket"></i> Logout`;

                registerButton.onclick =
                    function () {
                        logout();
                    };

            }

        }

    }


    /* =====================================================
    USER MENU
    ===================================================== */

    function showUserMenu() {

        openPopup(`

            <div class="popup-content user-menu">

                <div class="user-menu-icon">

                    <i class="fa-solid fa-user"></i>

                </div>

                <h2>Welcome, ${loggedInUser}!</h2>

                <p>
                    You are currently logged in.
                </p>


                <div class="user-menu-actions">

                    <button
                        class="primary-btn"
                        onclick="showFacilities()">

                        <i class="fa-solid fa-building"></i>

                        Reserve Facility

                    </button>


                    <button
                        class="primary-btn"
                        onclick="showEquipment()">

                        <i class="fa-solid fa-box-open"></i>

                        Reserve Equipment

                    </button>


                    <button
                        class="danger-btn"
                        onclick="logout()">

                        <i class="fa-solid fa-right-from-bracket"></i>

                        Logout

                    </button>

                </div>

            </div>

        `);

    }


    /* =====================================================
    STAFF - VIEW RESERVATIONS
    ===================================================== */

    function showReservations() {

        let reservations =
            JSON.parse(
                localStorage.getItem("lguReservations")
            ) || [];


        let content = `

            <div class="popup-content">

                <div class="popup-title">

                    <i class="fa-solid fa-calendar-days"></i>

                    <h2>Reservation Records</h2>

                </div>

        `;


        if (reservations.length === 0) {

            content += `

                <div class="empty-state">

                    <i class="fa-solid fa-calendar-xmark"></i>

                    <h3>No Reservations Yet</h3>

                    <p>
                        There are currently no reservation records.
                    </p>

                </div>

            `;

        } else {

            content += `<div class="reservation-list">`;


            reservations
                .slice()
                .reverse()
                .forEach(function (reservation) {

                    content += `

                        <div class="reservation-card">

                            <div class="reservation-info">

                                <h3>
                                    ${reservation.item}
                                </h3>

                                <p>
                                    <strong>Name:</strong>
                                    ${reservation.name}
                                </p>

                                <p>
                                    <strong>Date:</strong>
                                    ${reservation.date}
                                </p>

                                <p>
                                    <strong>Time:</strong>
                                    ${reservation.time}
                                </p>

                                <span class="status ${reservation.status.toLowerCase()}">
                                    ${reservation.status}
                                </span>

                            </div>


                            ${
                                reservation.status === "Pending"

                                ?

                                `

                                <div class="reservation-actions">

                                    <button
                                        class="approve-btn"
                                        onclick="approveReservation(${reservation.id})">

                                        <i class="fa-solid fa-check"></i>

                                        Approve

                                    </button>


                                    <button
                                        class="reject-btn"
                                        onclick="rejectReservation(${reservation.id})">

                                        <i class="fa-solid fa-xmark"></i>

                                        Reject

                                    </button>

                                </div>

                                `

                                :

                                ""

                            }

                        </div>

                    `;

                });


            content += `</div>`;

        }


        content += `</div>`;


        openPopup(content);

    }


    /* =====================================================
    APPROVE RESERVATION
    ===================================================== */

    function approveReservation(id) {

        let reservations =
            JSON.parse(
                localStorage.getItem("lguReservations")
            ) || [];


        reservations =
            reservations.map(function (reservation) {

                if (reservation.id === id) {

                    reservation.status =
                        "Approved";

                }

                return reservation;

            });


        localStorage.setItem(
            "lguReservations",
            JSON.stringify(reservations)
        );


        showNotification(
            "Reservation Approved",
            "The reservation has been approved successfully.",
            "success"
        );


        setTimeout(function () {

            showReservations();

        }, 1500);

    }


    /* =====================================================
    REJECT RESERVATION
    ===================================================== */

    function rejectReservation(id) {

        let reservations =
            JSON.parse(
                localStorage.getItem("lguReservations")
            ) || [];


        reservations =
            reservations.map(function (reservation) {

                if (reservation.id === id) {

                    reservation.status =
                        "Rejected";

                }

                return reservation;

            });


        localStorage.setItem(
            "lguReservations",
            JSON.stringify(reservations)
        );


        showNotification(
            "Reservation Rejected",
            "The reservation has been rejected.",
            "error"
        );


        setTimeout(function () {

            showReservations();

        }, 1500);

    }


    /* =====================================================
    STAFF - FACILITIES
    ===================================================== */

    function showStaffFacilities() {

        openPopup(`

            <div class="popup-content">

                <div class="popup-title">

                    <i class="fa-solid fa-building"></i>

                    <h2>Available Facilities</h2>

                </div>


                <div class="staff-resource-list">

                    <div class="staff-resource">

                        <i class="fa-solid fa-basketball"></i>

                        <span>Covered Court</span>

                        <strong>Available</strong>

                    </div>


                    <div class="staff-resource">

                        <i class="fa-solid fa-building"></i>

                        <span>Municipal Hall</span>

                        <strong>Available</strong>

                    </div>


                    <div class="staff-resource">

                        <i class="fa-solid fa-people-group"></i>

                        <span>Function Hall</span>

                        <strong>Available</strong>

                    </div>

                </div>

            </div>

        `);

    }


    /* =====================================================
    STAFF - EQUIPMENT
    ===================================================== */

    function showStaffEquipment() {

        openPopup(`

            <div class="popup-content">

                <div class="popup-title">

                    <i class="fa-solid fa-box-open"></i>

                    <h2>Available Equipment</h2>

                </div>


                <div class="staff-resource-list">

                    <div class="staff-resource">

                        <i class="fa-solid fa-volume-high"></i>

                        <span>Sound System</span>

                        <strong>Available</strong>

                    </div>


                    <div class="staff-resource">

                        <i class="fa-solid fa-chair"></i>

                        <span>Chairs</span>

                        <strong>Available</strong>

                    </div>


                    <div class="staff-resource">

                        <i class="fa-solid fa-table"></i>

                        <span>Tables</span>

                        <strong>Available</strong>

                    </div>

                </div>

            </div>

        `);

    }


    /* =====================================================
    STAFF - REPORTS
    ===================================================== */

    function showReports() {

        const reservations =
            JSON.parse(
                localStorage.getItem("lguReservations")
            ) || [];


        const total =
            reservations.length;


        const pending =
            reservations.filter(
                r => r.status === "Pending"
            ).length;


        const approved =
            reservations.filter(
                r => r.status === "Approved"
            ).length;


        const rejected =
            reservations.filter(
                r => r.status === "Rejected"
            ).length;


        openPopup(`

            <div class="popup-content">

                <div class="popup-title">

                    <i class="fa-solid fa-chart-column"></i>

                    <h2>Reservation Reports</h2>

                </div>


                <div class="report-grid">

                    <div class="report-card">

                        <i class="fa-solid fa-calendar-days"></i>

                        <h3>${total}</h3>

                        <p>Total Reservations</p>

                    </div>


                    <div class="report-card">

                        <i class="fa-solid fa-clock"></i>

                        <h3>${pending}</h3>

                        <p>Pending</p>

                    </div>


                    <div class="report-card">

                        <i class="fa-solid fa-circle-check"></i>

                        <h3>${approved}</h3>

                        <p>Approved</p>

                    </div>


                    <div class="report-card">

                        <i class="fa-solid fa-circle-xmark"></i>

                        <h3>${rejected}</h3>

                        <p>Rejected</p>

                    </div>

                </div>

            </div>

        `);

    }


    /* =====================================================
    ESC KEY
    ===================================================== */

    document.addEventListener(
        "keydown",
        function (event) {

            if (event.key === "Escape") {

                closePopup();

            }

        }
    );


    /* =====================================================
    PAGE LOAD
    ===================================================== */

    document.addEventListener(
        "DOMContentLoaded",
        function () {

            updateLoginButton();


            const here =
                document.getElementById("here");


            if (here) {

                here.innerHTML = "";

            }


            const currentYear =
                document.getElementById("currentYear");

            if (currentYear) {

                currentYear.textContent =
                    new Date().getFullYear();

            }

        }
    );


    /* =====================================================
    PAGE EXIT ANIMATION
    ===================================================== */

    document.addEventListener(
        "DOMContentLoaded",
        function () {

            const links =
                document.querySelectorAll(
                    'a[href$=".html"]'
                );


            links.forEach(function (link) {

                link.addEventListener(
                    "click",
                    function (event) {

                        const destination =
                            this.href;


                        if (
                            !destination ||
                            destination.includes("#")
                        ) {
                            return;
                        }


                        event.preventDefault();


                        document.body.classList.add(
                            "page-exit"
                        );


                        setTimeout(
                            function () {

                                window.location.href =
                                    destination;

                            },
                            300
                        );

                    }
                );

            });

        }
    );


    /* =====================================================
    SCROLL REVEAL ANIMATION
    ===================================================== */

    document.addEventListener(
        "DOMContentLoaded",
        function () {

            const elements =
                document.querySelectorAll(
                    ".feature-card, .objective-card, .step-card, .resource-card"
                );


            if (
                "IntersectionObserver"
                in window
            ) {

                const observer =
                    new IntersectionObserver(
                        function (entries) {

                            entries.forEach(
                                function (entry) {

                                    if (
                                        entry.isIntersecting
                                    ) {

                                        entry.target.classList.add(
                                            "show"
                                        );

                                        observer.unobserve(
                                            entry.target
                                        );

                                    }

                                }
                            );

                        },
                        {
                            threshold: 0.15
                        }
                    );


                elements.forEach(
                    function (element) {

                        observer.observe(element);

                    }
                );

            }

        }
    );

    
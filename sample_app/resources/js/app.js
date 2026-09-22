(() => {
    "use strict";

    /*
    ============================================================
    COMMUNITYCONNECT
    Front-end demo using localStorage
    ============================================================
    */

    const STORAGE = {
        USERS: "communityconnect_users",
        ISSUES: "communityconnect_issues",
        SESSION: "communityconnect_session",
        ADMIN: "communityconnect_admin_session",
        COUNTER: "communityconnect_case_counter"
    };


    /*
    ============================================================
    INITIALIZATION
    ============================================================
    */

    document.addEventListener("DOMContentLoaded", () => {

        try {

            seedData();
            bindEvents();
            renderEverything();

        } catch (error) {

            console.error(
                "CommunityConnect failed to initialize:",
                error
            );

        }

    });


    /*
    ============================================================
    STORAGE
    ============================================================
    */

    function readStorage(key, fallback) {

        try {

            const value = localStorage.getItem(key);

            if (value === null) {
                return fallback;
            }

            return JSON.parse(value);

        } catch (error) {

            console.error(
                `Storage read error: ${key}`,
                error
            );

            return fallback;
        }
    }


    function writeStorage(key, value) {

        try {

            localStorage.setItem(
                key,
                JSON.stringify(value)
            );

            return true;

        } catch (error) {

            console.error(
                `Storage write error: ${key}`,
                error
            );

            showToast(
                "Your browser could not save this change.",
                "error"
            );

            return false;
        }
    }


    function removeStorage(key) {

        try {
            localStorage.removeItem(key);
        } catch (error) {
            console.error(error);
        }

    }


    function getUsers() {
        return readStorage(
            STORAGE.USERS,
            []
        );
    }


    function setUsers(users) {
        writeStorage(
            STORAGE.USERS,
            users
        );
    }


    function getIssues() {
        return readStorage(
            STORAGE.ISSUES,
            []
        );
    }


    function setIssues(issues) {
        writeStorage(
            STORAGE.ISSUES,
            issues
        );
    }


    function getSession() {
        return readStorage(
            STORAGE.SESSION,
            null
        );
    }


    function setSession(email) {
        writeStorage(
            STORAGE.SESSION,
            email
        );
    }


    function clearSession() {
        removeStorage(
            STORAGE.SESSION
        );
    }


    function getAdminSession() {
        return readStorage(
            STORAGE.ADMIN,
            null
        );
    }


    function setAdminSession(username) {
        writeStorage(
            STORAGE.ADMIN,
            username
        );
    }


    function clearAdminSession() {
        removeStorage(
            STORAGE.ADMIN
        );
    }


    /*
    ============================================================
    SEED DATA
    ============================================================
    */

    function seedData() {

        const users = getUsers();

        if (!Array.isArray(users) || users.length === 0) {

            setUsers([
                {
                    name: "City Administrator",
                    email: "admin",
                    password: "admin123",
                    isAdmin: true
                }
            ]);

        } else {

            const adminExists = users.some(
                user => user.isAdmin === true
            );

            if (!adminExists) {

                users.push({
                    name: "City Administrator",
                    email: "admin",
                    password: "admin123",
                    isAdmin: true
                });

                setUsers(users);
            }

        }


        const existingIssues = localStorage.getItem(
            STORAGE.ISSUES
        );

        if (existingIssues === null) {

            const now = Date.now();

            const demoIssues = [

                {
                    id: "demo-1",
                    caseNo: 1,
                    category: "Streetlight",
                    title: "Streetlight out near Elm & 4th",
                    description:
                        "The streetlight has been dark for several nights and the intersection is difficult to see after dark.",
                    location:
                        "Elm Street & 4th Avenue",
                    photo: null,
                    reporterName: "Dana R.",
                    reporterEmail: "dana@example.com",
                    anonymous: false,
                    status: "In Progress",
                    createdAt:
                        now - 1000 * 60 * 60 * 26
                },

                {
                    id: "demo-2",
                    caseNo: 2,
                    category: "Road",
                    title: "Large pothole near bus stop",
                    description:
                        "A large pothole has formed near the bus stop. Vehicles are moving around it to avoid the damaged road.",
                    location:
                        "Cedar Road near Maple Stop",
                    photo: null,
                    reporterName: "Anonymous",
                    reporterEmail: "",
                    anonymous: true,
                    status: "Pending",
                    createdAt:
                        now - 1000 * 60 * 60 * 7
                },

                {
                    id: "demo-3",
                    caseNo: 3,
                    category: "Sanitation",
                    title: "Overflowing garbage bin",
                    description:
                        "The public garbage bin has been overflowing and appears to have missed several collection schedules.",
                    location:
                        "Riverside Park",
                    photo: null,
                    reporterName: "Kofi A.",
                    reporterEmail: "kofi@example.com",
                    anonymous: false,
                    status: "Resolved",
                    createdAt:
                        now - 1000 * 60 * 60 * 72
                }

            ];

            setIssues(
                demoIssues
            );

            writeStorage(
                STORAGE.COUNTER,
                3
            );
        }

    }


    /*
    ============================================================
    DOM HELPERS
    ============================================================
    */

    function $(id) {
        return document.getElementById(id);
    }


    function escapeHTML(value) {

        return String(value ?? "")
            .replace(
                /[&<>"']/g,
                character => ({
                    "&": "&amp;",
                    "<": "&lt;",
                    ">": "&gt;",
                    '"': "&quot;",
                    "'": "&#039;"
                }[character])
            );

    }


    function initials(name) {

        return String(name || "?")
            .trim()
            .split(/\s+/)
            .map(part => part.charAt(0))
            .slice(0, 2)
            .join("")
            .toUpperCase();

    }


    function getCurrentUser() {

        const email = getSession();

        if (!email) {
            return null;
        }

        return getUsers().find(
            user =>
                user.email === email &&
                !user.isAdmin
        ) || null;

    }


    function getCurrentAdmin() {

        const username = getAdminSession();

        if (!username) {
            return null;
        }

        return getUsers().find(
            user =>
                user.isAdmin &&
                user.email === username
        ) || null;

    }


    function nextCaseNumber() {

        let counter = Number(
            readStorage(
                STORAGE.COUNTER,
                0
            )
        );

        counter += 1;

        writeStorage(
            STORAGE.COUNTER,
            counter
        );

        return counter;

    }


    function caseNumber(number) {

        return String(number)
            .padStart(4, "0");

    }


    function statusClass(status) {

        return "badge-" +
            String(status)
                .replace(/\s+/g, "");

    }


    function formatDate(timestamp) {

        const date =
            new Date(timestamp);

        return date.toLocaleDateString(
            undefined,
            {
                month: "short",
                day: "numeric",
                year: "numeric"
            }
        );

    }


    function timeAgo(timestamp) {

        const seconds =
            Math.max(
                0,
                Math.floor(
                    (Date.now() - timestamp) / 1000
                )
            );

        if (seconds < 60) {
            return "just now";
        }

        const minutes =
            Math.floor(seconds / 60);

        if (minutes < 60) {
            return `${minutes}m ago`;
        }

        const hours =
            Math.floor(minutes / 60);

        if (hours < 24) {
            return `${hours}h ago`;
        }

        const days =
            Math.floor(hours / 24);

        return `${days}d ago`;

    }


    /*
    ============================================================
    TOAST
    ============================================================
    */

    function showToast(message, type = "") {

        const stack =
            $("toastStack");

        if (!stack) {
            return;
        }

        const toast =
            document.createElement("div");

        toast.className =
            `toast ${type ? "toast-" + type : ""}`;

        toast.textContent =
            message;

        stack.appendChild(
            toast
        );

        window.setTimeout(
            () => {
                toast.remove();
            },
            3200
        );

    }


    /*
    ============================================================
    EVENT BINDING
    ============================================================
    */

    function bindEvents() {

        /*
        ----------------------------
        Navigation
        ----------------------------
        */

        const navToggle =
            $("navToggle");

        const mainNav =
            $("mainNav");

        if (navToggle && mainNav) {

            navToggle.addEventListener(
                "click",
                () => {

                    const open =
                        mainNav.classList.toggle(
                            "open"
                        );

                    navToggle.setAttribute(
                        "aria-expanded",
                        String(open)
                    );

                }
            );

            mainNav
                .querySelectorAll("a")
                .forEach(link => {

                    link.addEventListener(
                        "click",
                        () => {

                            mainNav.classList.remove(
                                "open"
                            );

                            navToggle.setAttribute(
                                "aria-expanded",
                                "false"
                            );

                        }
                    );

                });

        }


        /*
        ----------------------------
        Auth modal
        ----------------------------
        */

        $("authModalClose")
            ?.addEventListener(
                "click",
                closeAuthModal
            );


        $("authModalOverlay")
            ?.addEventListener(
                "click",
                event => {

                    if (
                        event.target ===
                        $("authModalOverlay")
                    ) {
                        closeAuthModal();
                    }

                }
            );


        $("tabSignIn")
            ?.addEventListener(
                "click",
                () => {
                    switchAuthTab("signin");
                }
            );


        $("tabSignUp")
            ?.addEventListener(
                "click",
                () => {
                    switchAuthTab("signup");
                }
            );


        $("signInForm")
            ?.addEventListener(
                "submit",
                handleSignIn
            );


        $("signUpForm")
            ?.addEventListener(
                "submit",
                handleSignUp
            );


        /*
        ----------------------------
        Report
        ----------------------------
        */

        $("reportForm")
            ?.addEventListener(
                "submit",
                handleReportSubmit
            );


        $("useLocationBtn")
            ?.addEventListener(
                "click",
                getLocation
            );


        $("photo")
            ?.addEventListener(
                "change",
                handlePhoto
            );


        $("photoRemoveBtn")
            ?.addEventListener(
                "click",
                removePhoto
            );


        /*
        ----------------------------
        Browse
        ----------------------------
        */

        $("searchInput")
            ?.addEventListener(
                "input",
                renderFeed
            );


        $("filterCategory")
            ?.addEventListener(
                "change",
                renderFeed
            );


        $("filterStatus")
            ?.addEventListener(
                "change",
                renderFeed
            );


        /*
        ----------------------------
        Admin
        ----------------------------
        */

        $("adminLoginForm")
            ?.addEventListener(
                "submit",
                handleAdminLogin
            );


        $("adminLogoutBtn")
            ?.addEventListener(
                "click",
                handleAdminLogout
            );


        $("adminSearch")
            ?.addEventListener(
                "input",
                renderAdminTable
            );


        $("adminFilterCategory")
            ?.addEventListener(
                "change",
                renderAdminTable
            );


        $("adminFilterStatus")
            ?.addEventListener(
                "change",
                renderAdminTable
            );


        /*
        ----------------------------
        Detail modal
        ----------------------------
        */

        $("detailClose")
            ?.addEventListener(
                "click",
                closeDetail
            );


        $("detailOverlay")
            ?.addEventListener(
                "click",
                event => {

                    if (
                        event.target ===
                        $("detailOverlay")
                    ) {
                        closeDetail();
                    }

                }
            );


        /*
        ----------------------------
        Lightbox
        ----------------------------
        */

        $("lightboxClose")
            ?.addEventListener(
                "click",
                closeLightbox
            );


        $("lightboxOverlay")
            ?.addEventListener(
                "click",
                event => {

                    if (
                        event.target ===
                        $("lightboxOverlay")
                    ) {
                        closeLightbox();
                    }

                }
            );


        /*
        ----------------------------
        Escape key
        ----------------------------
        */

        document.addEventListener(
            "keydown",
            event => {

                if (event.key !== "Escape") {
                    return;
                }

                closeAuthModal();
                closeDetail();
                closeLightbox();

            }
        );

    }


    /*
    ============================================================
    RENDER EVERYTHING
    ============================================================
    */

    function renderEverything() {

        closeAuthModal();
        closeDetail();
        closeLightbox();

        renderAuthArea();
        renderAccountToggle();
        renderTicker();
        renderFeed();
        renderAdmin();

    }


    /*
    ============================================================
    AUTH AREA
    ============================================================
    */

    function renderAuthArea() {

        const area =
            $("authArea");

        if (!area) {
            return;
        }

        const user =
            getCurrentUser();

        if (user) {

            area.innerHTML = `

                <div class="user-chip">

                    <span class="avatar">
                        ${escapeHTML(
                            initials(user.name)
                        )}
                    </span>

                    <span>
                        ${escapeHTML(
                            user.name
                        )}
                    </span>

                </div>

                <button
                    type="button"
                    class="link-btn"
                    id="signOutBtn"
                >
                    Sign out
                </button>
            `;


            $("signOutBtn")
                ?.addEventListener(
                    "click",
                    () => {

                        clearSession();

                        showToast(
                            "You have been signed out.",
                            "success"
                        );

                        renderEverything();

                    }
                );

        } else {

            area.innerHTML = `

                <button
                    type="button"
                    class="btn btn-primary btn-small"
                    id="openAuthBtn"
                >
                    Sign in
                </button>

            `;


            $("openAuthBtn")
                ?.addEventListener(
                    "click",
                    () => {
                        openAuthModal("signin");
                    }
                );

        }

    }


    /*
    ============================================================
    AUTH MODAL
    ============================================================
    */

    function openAuthModal(tab = "signin") {

        const overlay =
            $("authModalOverlay");

        if (!overlay) {
            return;
        }

        overlay.hidden = false;

        document.body.classList.add(
            "modal-open"
        );

        switchAuthTab(
            tab
        );

    }


    function closeAuthModal() {

        const overlay =
            $("authModalOverlay");

        if (!overlay) {
            return;
        }

        overlay.hidden = true;

        document.body.classList.remove(
            "modal-open"
        );

        $("signInForm")
            ?.reset();

        $("signUpForm")
            ?.reset();

        if ($("signInError")) {
            $("signInError").hidden = true;
        }

        if ($("signUpError")) {
            $("signUpError").hidden = true;
        }

    }


    function switchAuthTab(tab) {

        const signIn =
            tab === "signin";

        $("tabSignIn")
            ?.classList
            .toggle(
                "active",
                signIn
            );

        $("tabSignUp")
            ?.classList
            .toggle(
                "active",
                !signIn
            );


        if ($("signInForm")) {
            $("signInForm").hidden =
                !signIn;
        }

        if ($("signUpForm")) {
            $("signUpForm").hidden =
                signIn;
        }


        if ($("signInError")) {
            $("signInError").hidden = true;
        }

        if ($("signUpError")) {
            $("signUpError").hidden = true;
        }

    }


    function handleSignIn(event) {

        event.preventDefault();

        const email =
            $("signInEmail")
                .value
                .trim()
                .toLowerCase();

        const password =
            $("signInPassword")
                .value;


        const user =
            getUsers().find(
                candidate =>
                    candidate.email
                        .toLowerCase() === email &&
                    candidate.password === password &&
                    !candidate.isAdmin
            );


        if (!user) {

            $("signInError").hidden =
                false;

            return;
        }


        setSession(
            user.email
        );

        closeAuthModal();

        showToast(
            `Welcome back, ${user.name.split(" ")[0]}.`,
            "success"
        );

        renderEverything();

    }


    function handleSignUp(event) {

        event.preventDefault();

        const name =
            $("signUpName")
                .value
                .trim();

        const email =
            $("signUpEmail")
                .value
                .trim()
                .toLowerCase();

        const password =
            $("signUpPassword")
                .value;


        const users =
            getUsers();


        const exists =
            users.some(
                user =>
                    user.email
                        .toLowerCase() === email
            );


        if (exists) {

            $("signUpError").hidden =
                false;

            return;
        }


        const newUser = {

            name,
            email,
            password,
            isAdmin: false

        };


        users.push(
            newUser
        );

        setUsers(
            users
        );

        setSession(
            email
        );

        closeAuthModal();

        showToast(
            `Account created. Welcome, ${name.split(" ")[0]}.`,
            "success"
        );

        renderEverything();

    }


    /*
    ============================================================
    REPORT ACCOUNT MODE
    ============================================================
    */

    let reportAsAnonymous = true;

    let photoDataURL = null;


    function renderAccountToggle() {

        const box =
            $("accountToggle");

        const note =
            $("formNote");

        if (!box || !note) {
            return;
        }


        const user =
            getCurrentUser();


        if (user) {

            box.innerHTML = `

                <div class="signed-in-note">

                    <span>
                        Reporting as
                        <strong>
                            ${escapeHTML(user.name)}
                        </strong>
                        (${escapeHTML(user.email)}).
                    </span>

                    <button
                        type="button"
                        class="link-btn"
                        id="switchAnonymousBtn"
                    >
                        Report anonymously instead
                    </button>

                </div>

            `;


            note.innerHTML = `
                Reporting as
                <strong>
                    ${escapeHTML(user.name)}
                </strong>.
            `;


            $("switchAnonymousBtn")
                ?.addEventListener(
                    "click",
                    () => {

                        clearSession();

                        reportAsAnonymous =
                            true;

                        renderEverything();

                        showToast(
                            "You can now submit anonymously."
                        );

                    }
                );


            return;
        }


        box.innerHTML = `

            <div class="account-choice">

                <label>

                    <input
                        type="radio"
                        name="acctMode"
                        value="anon"
                        ${reportAsAnonymous ? "checked" : ""}
                    >

                    Report without an account

                </label>


                <label>

                    <input
                        type="radio"
                        name="acctMode"
                        value="account"
                        ${!reportAsAnonymous ? "checked" : ""}
                    >

                    Sign in to attach my name

                </label>

            </div>


            <div
                class="anon-name-field"
                id="anonNameField"
                ${reportAsAnonymous ? "" : "hidden"}
            >

                <label for="anonName">
                    Your name
                    <span class="label-optional">
                        (optional)
                    </span>
                </label>

                <input
                    type="text"
                    id="anonName"
                    placeholder="Leave blank to remain anonymous"
                >

            </div>

        `;


        const radios =
            box.querySelectorAll(
                'input[name="acctMode"]'
            );


        radios.forEach(
            radio => {

                radio.addEventListener(
                    "change",
                    event => {

                        if (
                            event.target.value ===
                            "account"
                        ) {

                            reportAsAnonymous =
                                false;

                            openAuthModal(
                                "signin"
                            );

                        } else {

                            reportAsAnonymous =
                                true;

                            renderAccountToggle();

                        }

                    }
                );

            }
        );


        note.innerHTML = `
            Reporting
            <strong>
                without an account
            </strong>.
        `;

    }


    /*
    ============================================================
    LOCATION
    ============================================================
    */

    function getLocation() {

        const button =
            $("useLocationBtn");

        if (!button) {
            return;
        }


        if (!navigator.geolocation) {

            showToast(
                "Location is not supported by this browser.",
                "error"
            );

            return;
        }


        const originalText =
            button.textContent;


        button.disabled =
            true;

        button.textContent =
            "Locating...";


        navigator.geolocation.getCurrentPosition(

            position => {

                const latitude =
                    position.coords.latitude;

                const longitude =
                    position.coords.longitude;


                $("location").value =
                    `${latitude.toFixed(5)}, ${longitude.toFixed(5)}`;


                button.disabled =
                    false;

                button.textContent =
                    originalText;


                showToast(
                    "Location added.",
                    "success"
                );

            },


            error => {

                console.error(
                    "Geolocation error:",
                    error
                );


                button.disabled =
                    false;

                button.textContent =
                    originalText;


                showToast(
                    "Could not get your location. Please type it manually.",
                    "error"
                );

            },


            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 60000
            }

        );

    }


    /*
    ============================================================
    PHOTO
    ============================================================
    */

    function handlePhoto(event) {

        const file =
            event.target.files?.[0];


        if (!file) {
            return;
        }


        if (
            !file.type.startsWith(
                "image/"
            )
        ) {

            showToast(
                "Please select an image.",
                "error"
            );

            event.target.value =
                "";

            return;
        }


        /*
        3 MB limit
        */

        if (
            file.size >
            3 * 1024 * 1024
        ) {

            showToast(
                "Photo must be smaller than 3 MB.",
                "error"
            );

            event.target.value =
                "";

            return;
        }


        const reader =
            new FileReader();


        reader.onload = () => {

            photoDataURL =
                reader.result;


            $("photoPreviewImg").src =
                photoDataURL;

            $("photoPreview").hidden =
                false;

        };


        reader.onerror = () => {

            showToast(
                "Could not read the photo.",
                "error"
            );

        };


        reader.readAsDataURL(
            file
        );

    }


    function removePhoto() {

        photoDataURL =
            null;

        if ($("photo")) {
            $("photo").value =
                "";
        }

        if ($("photoPreview")) {
            $("photoPreview").hidden =
                true;
        }

        if ($("photoPreviewImg")) {
            $("photoPreviewImg").src =
                "";
        }

    }


    /*
    ============================================================
    SUBMIT REPORT
    ============================================================
    */

    function handleReportSubmit(event) {

        event.preventDefault();


        const category =
            $("category")
                .value
                .trim();

        const location =
            $("location")
                .value
                .trim();

        const title =
            $("title")
                .value
                .trim();

        const description =
            $("description")
                .value
                .trim();


        if (
            !category ||
            !location ||
            !title ||
            !description
        ) {

            showToast(
                "Please complete all required fields.",
                "error"
            );

            return;
        }


        const user =
            getCurrentUser();


        let reporterName =
            "Anonymous";

        let reporterEmail =
            "";

        let anonymous =
            true;


        if (user) {

            reporterName =
                user.name;

            reporterEmail =
                user.email;

            anonymous =
                false;

        } else {

            const nameField =
                $("anonName");


            if (
                nameField &&
                nameField.value.trim()
            ) {

                reporterName =
                    nameField.value.trim();

            }

            anonymous =
                true;

        }


        const issues =
            getIssues();


        const newCaseNumber =
            nextCaseNumber();


        const issue = {

            id:
                `issue-${Date.now()}-${Math.random()
                    .toString(36)
                    .slice(2, 8)}`,

            caseNo:
                newCaseNumber,

            category,

            title,

            description,

            location,

            photo:
                photoDataURL,

            reporterName,

            reporterEmail,

            anonymous,

            status:
                "Pending",

            createdAt:
                Date.now()

        };


        issues.unshift(
            issue
        );


        setIssues(
            issues
        );


        /*
        Reset form
        */

        event.target.reset();

        removePhoto();

        reportAsAnonymous =
            !getCurrentUser();


        renderAccountToggle();

        renderTicker();

        renderFeed();

        renderAdmin();


        showToast(
            `Report #${caseNumber(newCaseNumber)} submitted successfully.`,
            "success"
        );


        /*
        Send user to board
        */

        window.location.hash =
            "browse";

    }


    /*
    ============================================================
    TICKER
    ============================================================
    */

    function renderTicker() {

        const list =
            $("tickerList");

        if (!list) {
            return;
        }


        const issues =
            getIssues()
                .sort(
                    (a, b) =>
                        b.createdAt -
                        a.createdAt
                )
                .slice(0, 5);


        if (!issues.length) {

            list.innerHTML = `
                <p class="ticker-empty">
                    No reports yet.
                </p>
            `;

            return;
        }


        list.innerHTML =
            issues
                .map(
                    issue => `

                        <div class="ticker-item">

                            <span class="ticker-case">
                                #${caseNumber(issue.caseNo)}
                            </span>

                            <div class="ticker-body">

                                <strong>
                                    ${escapeHTML(issue.title)}
                                </strong>

                                <span class="ticker-meta">
                                    ${escapeHTML(issue.category)}
                                    ·
                                    ${escapeHTML(issue.location)}
                                    ·
                                    ${timeAgo(issue.createdAt)}
                                </span>

                            </div>

                        </div>

                    `
                )
                .join("");

    }


    /*
    ============================================================
    COMMUNITY FEED
    ============================================================
    */

    function renderFeed() {

        const grid =
            $("issuesGrid");

        const empty =
            $("emptyState");

        if (!grid || !empty) {
            return;
        }


        const query =
            $("searchInput")
                ?.value
                .trim()
                .toLowerCase() || "";


        const category =
            $("filterCategory")
                ?.value || "";


        const status =
            $("filterStatus")
                ?.value || "";


        let issues =
            getIssues();


        if (category) {

            issues =
                issues.filter(
                    issue =>
                        issue.category ===
                        category
                );

        }


        if (status) {

            issues =
                issues.filter(
                    issue =>
                        issue.status ===
                        status
                );

        }


        if (query) {

            issues =
                issues.filter(
                    issue => {

                        const searchable =
                            [
                                issue.title,
                                issue.description,
                                issue.location,
                                issue.category,
                                issue.reporterName
                            ]
                                .join(" ")
                                .toLowerCase();

                        return searchable.includes(
                            query
                        );

                    }
                );

        }


        issues.sort(
            (a, b) =>
                b.createdAt -
                a.createdAt
        );


        empty.hidden =
            issues.length !== 0;


        if (!issues.length) {

            grid.innerHTML =
                "";

            return;
        }


        grid.innerHTML =
            issues
                .map(
                    issue =>
                        createIssueCard(
                            issue
                        )
                )
                .join("");


        grid.querySelectorAll(
            ".issue-card"
        )
        .forEach(
            card => {

                card.addEventListener(
                    "click",
                    () => {

                        openDetail(
                            card.dataset.id
                        );

                    }
                );


                card.addEventListener(
                    "keydown",
                    event => {

                        if (
                            event.key ===
                            "Enter" ||
                            event.key ===
                            " "
                        ) {

                            event.preventDefault();

                            openDetail(
                                card.dataset.id
                            );

                        }

                    }
                );

            }
        );

    }


    function createIssueCard(issue) {

        const photo =
            issue.photo
                ? `
                    <img
                        class="card-thumb"
                        src="${issue.photo}"
                        alt="Report photo"
                    >
                `
                : "";


        return `

            <article
                class="issue-card"
                data-id="${escapeHTML(issue.id)}"
                tabindex="0"
                role="button"
            >

                <div class="card-top">

                    <span class="card-case">
                        #${caseNumber(issue.caseNo)}
                    </span>

                    <span class="badge ${statusClass(issue.status)}">
                        ${escapeHTML(issue.status)}
                    </span>

                </div>


                ${photo}


                <p class="card-category">
                    ${escapeHTML(issue.category)}
                </p>


                <h3 class="card-title">
                    ${escapeHTML(issue.title)}
                </h3>


                <p class="card-desc">
                    ${escapeHTML(
                        issue.description.length > 120
                            ? issue.description.substring(0, 120) + "..."
                            : issue.description
                    )}
                </p>


                <div class="card-meta">

                    <span>
                        ${escapeHTML(issue.location)}
                    </span>

                    <span>
                        ${timeAgo(issue.createdAt)}
                    </span>

                </div>

            </article>

        `;

    }


    /*
    ============================================================
    DETAIL MODAL
    ============================================================
    */

    function openDetail(id) {

        const issue =
            getIssues().find(
                item =>
                    String(item.id) ===
                    String(id)
            );


        if (!issue) {
            return;
        }


        const body =
            $("detailBody");

        const overlay =
            $("detailOverlay");


        if (!body || !overlay) {
            return;
        }


        const reporter =
            issue.anonymous
                ? (
                    issue.reporterName &&
                    issue.reporterName !== "Anonymous"
                        ? `Anonymous (${issue.reporterName})`
                        : "Anonymous"
                )
                : issue.reporterName;


        body.innerHTML = `

            <div class="detail-badge-row">

                <span class="card-case">
                    #${caseNumber(issue.caseNo)}
                </span>

                <span class="badge ${statusClass(issue.status)}">
                    ${escapeHTML(issue.status)}
                </span>

            </div>


            <h3 id="detailTitle">
                ${escapeHTML(issue.title)}
            </h3>


            ${
                issue.photo
                    ? `
                        <img
                            class="detail-img"
                            id="detailImage"
                            src="${issue.photo}"
                            alt="Report photo"
                        >
                    `
                    : ""
            }


            <p>
                ${escapeHTML(issue.description)}
            </p>


            <dl class="detail-grid">

                <div>
                    <dt>Category</dt>
                    <dd>
                        ${escapeHTML(issue.category)}
                    </dd>
                </div>


                <div>
                    <dt>Location</dt>
                    <dd>
                        ${escapeHTML(issue.location)}
                    </dd>
                </div>


                <div>
                    <dt>Reported by</dt>
                    <dd>
                        ${escapeHTML(reporter)}
                    </dd>
                </div>


                <div>
                    <dt>Filed</dt>
                    <dd>
                        ${formatDate(issue.createdAt)}
                    </dd>
                </div>

            </dl>

        `;


        overlay.hidden =
            false;

        document.body.classList.add(
            "modal-open"
        );


        $("detailImage")
            ?.addEventListener(
                "click",
                () => {

                    openLightbox(
                        issue.photo
                    );

                }
            );

    }


    function closeDetail() {

        const overlay =
            $("detailOverlay");

        if (!overlay) {
            return;
        }

        overlay.hidden =
            true;

        document.body.classList.remove(
            "modal-open"
        );

    }


    /*
    ============================================================
    LIGHTBOX
    ============================================================
    */

    function openLightbox(image) {

        if (!image) {
            return;
        }


        const overlay =
            $("lightboxOverlay");

        const img =
            $("lightboxImg");


        if (!overlay || !img) {
            return;
        }


        img.src =
            image;


        overlay.hidden =
            false;

        document.body.classList.add(
            "modal-open"
        );

    }


    function closeLightbox() {

        const overlay =
            $("lightboxOverlay");

        const img =
            $("lightboxImg");


        if (!overlay) {
            return;
        }


        overlay.hidden =
            true;


        if (img) {
            img.src =
                "";
        }


        document.body.classList.remove(
            "modal-open"
        );

    }


    /*
    ============================================================
    ADMIN
    ============================================================
    */

    function renderAdmin() {

        const login =
            $("adminLoginWrap");

        const dashboard =
            $("adminDashboard");


        if (!login || !dashboard) {
            return;
        }


        const admin =
            getCurrentAdmin();


        login.hidden =
            Boolean(admin);


        dashboard.hidden =
            !admin;


        if (!admin) {
            return;
        }


        if ($("adminName")) {

            $("adminName").textContent =
                admin.name;

        }


        renderAdminStats();
        renderAdminTable();

    }


    function handleAdminLogin(event) {

        event.preventDefault();


        const username =
            $("adminUser")
                .value
                .trim()
                .toLowerCase();


        const password =
            $("adminPass")
                .value;


        const admin =
            getUsers().find(
                user =>
                    user.isAdmin &&
                    user.email
                        .toLowerCase() ===
                        username &&
                    user.password ===
                        password
            );


        if (!admin) {

            $("adminLoginError").hidden =
                false;

            return;
        }


        $("adminLoginError").hidden =
            true;


        setAdminSession(
            admin.email
        );


        $("adminLoginForm")
            .reset();


        showToast(
            "Admin panel unlocked.",
            "success"
        );


        renderAdmin();

    }


    function handleAdminLogout() {

        clearAdminSession();

        renderAdmin();

        showToast(
            "Admin logged out."
        );

    }


    function renderAdminStats() {

        const container =
            $("adminStats");

        if (!container) {
            return;
        }


        const issues =
            getIssues();


        const pending =
            issues.filter(
                issue =>
                    issue.status ===
                    "Pending"
            ).length;


        const progress =
            issues.filter(
                issue =>
                    issue.status ===
                    "In Progress"
            ).length;


        const resolved =
            issues.filter(
                issue =>
                    issue.status ===
                    "Resolved"
            ).length;


        container.innerHTML = `

            <div class="stat-card">

                <span class="stat-num">
                    ${issues.length}
                </span>

                <span class="stat-label">
                    Total reports
                </span>

            </div>


            <div class="stat-card">

                <span class="stat-num">
                    ${pending}
                </span>

                <span class="stat-label">
                    Pending
                </span>

            </div>


            <div class="stat-card">

                <span class="stat-num">
                    ${progress}
                </span>

                <span class="stat-label">
                    In progress
                </span>

            </div>


            <div class="stat-card">

                <span class="stat-num">
                    ${resolved}
                </span>

                <span class="stat-label">
                    Resolved
                </span>

            </div>

        `;

    }


    function renderAdminTable() {

        const table =
            $("adminTableBody");

        if (!table) {
            return;
        }


        if (!getCurrentAdmin()) {
            return;
        }


        const query =
            $("adminSearch")
                ?.value
                .trim()
                .toLowerCase() || "";


        const category =
            $("adminFilterCategory")
                ?.value || "";


        const status =
            $("adminFilterStatus")
                ?.value || "";


        let issues =
            getIssues();


        if (category) {

            issues =
                issues.filter(
                    issue =>
                        issue.category ===
                        category
                );

        }


        if (status) {

            issues =
                issues.filter(
                    issue =>
                        issue.status ===
                        status
                );

        }


        if (query) {

            issues =
                issues.filter(
                    issue => {

                        const text =
                            [
                                issue.title,
                                issue.description,
                                issue.location,
                                issue.category,
                                issue.reporterName
                            ]
                                .join(" ")
                                .toLowerCase();

                        return text.includes(
                            query
                        );

                    }
                );

        }


        issues.sort(
            (a, b) =>
                b.createdAt -
                a.createdAt
        );


        if (!issues.length) {

            table.innerHTML = `
                <tr>
                    <td
                        colspan="8"
                        style="text-align:center;padding:30px;"
                    >
                        No reports found.
                    </td>
                </tr>
            `;

            return;
        }


        const statuses = [
            "Pending",
            "In Progress",
            "Resolved"
        ];


        table.innerHTML =
            issues
                .map(
                    issue => `

                        <tr>

                            <td class="cell-case">
                                #${caseNumber(issue.caseNo)}
                            </td>

                            <td>
                                ${escapeHTML(issue.category)}
                            </td>

                            <td>
                                ${escapeHTML(issue.title)}
                            </td>

                            <td>
                                ${escapeHTML(issue.location)}
                            </td>

                            <td>
                                ${escapeHTML(
                                    issue.anonymous
                                        ? "Anonymous"
                                        : issue.reporterName
                                )}
                            </td>

                            <td>
                                ${formatDate(issue.createdAt)}
                            </td>

                            <td>

                                <select
                                    class="status-select"
                                    data-status-id="${escapeHTML(issue.id)}"
                                >

                                    ${statuses
                                        .map(
                                            option =>
                                                `
                                                <option
                                                    value="${option}"
                                                    ${
                                                        option ===
                                                        issue.status
                                                            ? "selected"
                                                            : ""
                                                    }
                                                >
                                                    ${option}
                                                </option>
                                                `
                                        )
                                        .join("")}

                                </select>

                            </td>

                            <td>

                                <button
                                    type="button"
                                    class="btn btn-small btn-danger"
                                    data-delete-id="${escapeHTML(issue.id)}"
                                >
                                    Delete
                                </button>

                            </td>

                        </tr>

                    `
                )
                .join("");


        table
            .querySelectorAll(
                "[data-status-id]"
            )
            .forEach(
                select => {

                    select.addEventListener(
                        "change",
                        () => {

                            updateIssueStatus(
                                select.dataset.statusId,
                                select.value
                            );

                        }
                    );

                }
            );


        table
            .querySelectorAll(
                "[data-delete-id]"
            )
            .forEach(
                button => {

                    button.addEventListener(
                        "click",
                        () => {

                            deleteIssue(
                                button.dataset.deleteId
                            );

                        }
                    );

                }
            );

    }


    function updateIssueStatus(
        id,
        newStatus
    ) {

        const issues =
            getIssues();


        const issue =
            issues.find(
                item =>
                    String(item.id) ===
                    String(id)
            );


        if (!issue) {
            return;
        }


        issue.status =
            newStatus;


        setIssues(
            issues
        );


        renderAdminStats();
        renderAdminTable();
        renderTicker();
        renderFeed();


        showToast(
            `Case #${caseNumber(issue.caseNo)} marked ${newStatus}.`,
            "success"
        );

    }


    function deleteIssue(id) {

        const confirmed =
            window.confirm(
                "Delete this report? This cannot be undone."
            );


        if (!confirmed) {
            return;
        }


        const issues =
            getIssues();


        const filtered =
            issues.filter(
                issue =>
                    String(issue.id) !==
                    String(id)
            );


        setIssues(
            filtered
        );


        renderAdminStats();
        renderAdminTable();
        renderTicker();
        renderFeed();


        showToast(
            "Report deleted."
        );

    }

})();
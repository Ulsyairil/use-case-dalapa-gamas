'use strict';

let currentPage = 1;
let currentSort = "created_at";
let currentOrder = "desc";
let currentLength = 10;
let totalPages = 1;
let fetchFunction = null;
let searchTimeout;

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    }
});

moment.locale(locale);
moment.tz.setDefault(timezone);

let notyf = new Notyf({
    position: {
        x: "right",
        y: "top"
    },
    dismissible: true
});

$.LoadingOverlaySetup({
    image: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><radialGradient id="a12" cx=".66" fx=".66" cy=".3125" fy=".3125" gradientTransform="scale(1.5)"><stop offset="0" stop-color="#915EFF"></stop><stop offset=".3" stop-color="#915EFF" stop-opacity=".9"></stop><stop offset=".6" stop-color="#915EFF" stop-opacity=".6"></stop><stop offset=".8" stop-color="#915EFF" stop-opacity=".3"></stop><stop offset="1" stop-color="#915EFF" stop-opacity="0"></stop></radialGradient><circle transform-origin="center" fill="none" stroke="url(#a12)" stroke-width="15" stroke-linecap="round" stroke-dasharray="200 1000" stroke-dashoffset="0" cx="100" cy="100" r="70"><animateTransform type="rotate" attributeName="transform" calcMode="spline" dur="2" values="360;0" keyTimes="0;1" keySplines="0 0 1 1" repeatCount="indefinite"></animateTransform></circle><circle transform-origin="center" fill="none" opacity=".2" stroke="#915EFF" stroke-width="15" stroke-linecap="round" cx="100" cy="100" r="70"></circle></svg>`,
    imageAnimation: false,
    imageColor: false,
});

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(";").shift();
}

function setCookie(name, value, days) {
    const expires = new Date(Date.now() + days * 864e5).toUTCString();
    document.cookie = name + "=" + value + "; expires=" + expires + "; path=/";
}

function changeLocale(e) {
    let locale = $(e).data("value");
    
    $.ajax({
        type: "POST",
        url: "/change-locale",
        data: {
            locale: locale
        },
        dataType: "json",
        success: function (response) {
            if (response.code != 0) {
                return notyf.error(response.message);
            }

            let path = window.location.pathname;
            let segments = path.split('/').filter(s => s.length > 0);
            let supportedLocales = ['en', 'id'];
            if (segments.length > 0 && supportedLocales.includes(segments[0])) {
                segments.shift();
            }

            segments.unshift(locale);

            let newPath = '/' + segments.join('/');
            let newUrl = newPath + window.location.search + window.location.hash;

            window.location.href = newUrl;
        },
        error: function (response) {
            console.log(response);
            return notyf.error(response.responseJSON.message);
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const body = document.body;
    const toggle = document.getElementById("darkModeToggle");

    if (getCookie("darkMode") === "true") {
        body.classList.add("dark-mode");
        toggle.innerHTML = '<i class="fas fa-sun mr-1"></i>';
    } else {
        toggle.innerHTML = '<i class="fas fa-moon mr-1"></i>';
    }

    toggle.addEventListener("click", function (e) {
        e.preventDefault();

        body.classList.toggle("dark-mode");
        const isDark = body.classList.contains("dark-mode");

        toggle.innerHTML = isDark
            ? '<i class="fas fa-sun mr-1"></i>'
            : '<i class="fas fa-moon mr-1"></i>';

        setCookie("darkMode", isDark ? "true" : "false", 365);
    });
});

function logout() {
    $.ajax({
        type: "POST",
        url: "/logout-request",
        dataType: "json",
        success: function (response) {
            if (response.code != 0) {
                return notyf.error(response.message);
            }

            location.href = "/";
        },
        error: function (response) {
            return notyf.error(response.responseJSON.message);
        }
    });
}

// For Table Function
function getFetchFunction() {
    for (let key in window) {
        if (key.startsWith("fetch") && typeof window[key] === "function") {
            return window[key]; // Return the detected function
        }
    }
    return null; // No function found
}

function updatePagination(data) {
    let pagination = $(".pagination");
    pagination.empty(); // Clear existing pagination

    totalPages = data.last_page;
    currentPage = data.current_page;

    // First button
    let firstClass = currentPage === 1 ? "disabled" : "";
    pagination.append(
        `<li class="page-item ${firstClass}">
            <a class="page-link page-btn" href="#" data-page="1">${message.first}</a>
        </li>`
    );

    // Previous button
    let prevClass = currentPage === 1 ? "disabled" : "";
    pagination.append(
        `<li class="page-item ${prevClass}">
            <a class="page-link page-btn" href="#" data-page="${currentPage - 1}">${message.previous}</a>
        </li>`
    );

    // Page numbers (show only limited range for better UI)
    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(totalPages, currentPage + 2);

    for (let i = startPage; i <= endPage; i++) {
        let activeClass = i === currentPage ? "active" : "";
        pagination.append(
            `<li class="page-item ${activeClass}">
                <a class="page-link page-btn" href="#" data-page="${i}">${i}</a>
            </li>`
        );
    }

    // Next button
    let nextClass = currentPage === totalPages ? "disabled" : "";
    pagination.append(
        `<li class="page-item ${nextClass}">
            <a class="page-link page-btn" href="#" data-page="${currentPage + 1}">${message.next}</a>
        </li>`
    );

    // Last button
    let lastClass = currentPage === totalPages ? "disabled" : "";
    pagination.append(
        `<li class="page-item ${lastClass}">
            <a class="page-link page-btn" href="#" data-page="${totalPages}">Last</a>
        </li>`
    );
}

$("#recordsPerPage").change(function () {
    currentLength = $(this).val();
    currentPage = 1;
    fetchFunction();
});

$(document).on("click", ".page-btn", function (e) {
    e.preventDefault();
    if ($(this).parent().hasClass("disabled") || $(this).parent().hasClass("active")) {
        return;
    }

    currentPage = parseInt($(this).data("page"));

    fetchFunction();
});

if ($("#data-table").length) {
    let defaultTh = null;

    $("#data-table th").each(function () {
        let column = $(this).data("column");
        let order = $(this).attr("data-order");

        if (order) {
            $(this).data("order", order);
            updateSortIcon($(this), order);
            currentSort = column;
            currentOrder = order;
            defaultTh = $(this);
        }
    });

    // If no default set in HTML, fallback to first sortable column
    if (!defaultTh) {
        defaultTh = $("#data-table th[data-column='created_at'][data-sortable='true']");

        if (defaultTh.length > 0) {
            currentSort = defaultTh.data("column");
            currentOrder = "desc"; // or "asc" if you prefer
            defaultTh.data("order", currentOrder);
            defaultTh.attr("data-order", currentOrder);
            updateSortIcon(defaultTh, currentOrder);
        }
    }
}

// On column click
$("#data-table th").click(function () {
    if ($(this).attr("data-sortable") === "false" || !$(this).attr("data-sortable")) return;

    let column = $(this).data("column");
    let order = $(this).data("order");

    if (!order) {
        order = "asc";
    } else if (order === "asc") {
        order = "desc";
    } else {
        order = "";
    }

    // Reset all
    $("#data-table th").removeAttr("data-order").data("order", "");
    $("#data-table th i.sort-icon").remove();

    if (order === "") {
        currentSort = null;
        currentOrder = null;
    } else {
        $(this).data("order", order);
        $(this).attr("data-order", order);
        updateSortIcon($(this), order);
        currentSort = column;
        currentOrder = order;
    }

    fetchFunction();
});

// Icon helper
function updateSortIcon(thElement, order) {
    let icon = "";
    if (order === "asc") {
        icon = '<i class="fas fa-sort-up ml-1 sort-icon" style="padding-left: 5px;"></i>';
    } else if (order === "desc") {
        icon = '<i class="fas fa-sort-down ml-1 sort-icon" style="padding-left: 5px;"></i>';
    }

    thElement.append(icon);
}

function setupDeleteHandler(tableId, deleteUrl, reloadTable) {
    $(document).off('click', `#${tableId} .delete-btn`); // Remove previous handlers to prevent duplication
    $(document).on('click', `#${tableId} .delete-btn`, function() {
        let itemId = $(this).data('id');
        if (confirm("Are you sure you want to delete this item?")) {
            $.ajax({
                url: deleteUrl,
                type: 'POST',
                data: { id: itemId },
                dataType: 'json',
                success: function(response) {
                    if (response.code != 0) {
                        return $.notify(response.msg, "error");
                    }

                    $.notify(response.msg, "success");

                    if (reloadTable) {
                        reloadTable();
                    }
                },
                error: function(response) {
                    return $.notify(response.responseJSON.message, "error");
                }
            });
        }
    });
}

$("#jumpToPageBtn").click(function () {
    let page = parseInt($("#jumpToPage").val());
    if (isNaN(page) || page < 1 || page > totalPages) {
        return alert("Invalid page number!");
    }
    currentPage = page;
    fetchFunction();
});

$("#jumpToPage").keypress(function (e) {
    if (e.which === 13) { // Enter key
        $("#jumpToPageBtn").click();
    }
});

$(".search").change(function (e) {
    currentPage = 1; // Reset to first page if filter changes
    fetchFunction();
})

$(".search").keyup(function () {
    clearTimeout(searchTimeout); // Clear previous timeout
    searchTimeout = setTimeout(() => {
        fetchFunction();
    }, 500); // Delay fetchFunction() by 500ms
});

// Prevent users from changing the checkbox
$(document).on("click", ".readonly-checkbox", function(e) {
    e.preventDefault();
});
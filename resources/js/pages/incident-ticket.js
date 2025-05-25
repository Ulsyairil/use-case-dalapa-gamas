function fetchIncidentTicket() {
    $("#table_container").LoadingOverlay("show");

    $.ajax({
        url: '/ticket/list-request',
        method: 'POST',
        data: {
            page: currentPage,
            length: currentLength,
            sort: currentSort,
            order: currentOrder,
        },
        dataType: 'JSON',
        success: function(response) {
            if (response.code != 0) {
                return notyf.error(response.message);
            }

            updateListIncidentTicketTable(response.data.data, currentPage, currentLength);
            updatePagination(response.data);
            $("#total-records").text(`${message.total_records}: ${response.data.total}`);
        },
        error: function(response) {
            return notyf.error(response.responseJSON.message);
        },
        complete: function() {
            $("#table_container").LoadingOverlay("hide");
        }
    });

    fetchFunction = fetchIncidentTicket;
}

function resetListIncidentTicketTable() {
    currentPage = 1;
    currentSort = 'created_at';
    currentOrder = 'desc';
    $(".search").val('');
    fetchIncidentTicket();
}

function updateListIncidentTicketTable(rows, page = 1, perPage = 10) {
    let tbody = $("#data-table tbody");
    tbody.empty();

    if (rows.length === 0) {
        tbody.append(`<tr><td colspan="8" style="text-align:center;">${message.no_data_available}</td></tr>`);
        return;
    }

    rows.forEach((row, index) => {
        let counter = (page - 1) * perPage + index + 1;
        let createdAt = moment(row[5]).format('DD MMMM YYYY, HH:mm:ss');
        let updatedAt = moment(row[6]).format('DD MMMM YYYY, HH:mm:ss');

        let status = {
            'closed': '<span class="badge bg-success">CLOSED</span>',
            'open': '<span class="badge bg-warning">OPEN</span>',
            'in_progress': '<span class="badge bg-info">IN PROGRESS</span>'
        }[row[4]] || '-';

        let actionButtons = `
            <div class="d-flex d-md-inline-flex flex-column flex-md-row justify-content-center">
                <a href="/ticket/detail?id=${row[0]}" class="btn btn-sm btn-primary me-md-1 mb-1 mb-md-0">
                    <i class="ri-eye-line"></i> ${message.detail}
                </a>
            </div>
        `;

        let rowHTML = `<tr>
            <td class="text-center">${counter}</td>
            <td class="text-left">${row[1]}</td>
            <td class="text-left">${row[2]}</td>
            <td class="text-left">${row[3]}</td>
            <td class="text-center">${status}</td>
            <td class="text-end">${createdAt}</td>
            <td class="text-end">${updatedAt}</td>
            <td class="text-center">${actionButtons}</td>
        </tr>`;

        tbody.append(rowHTML);
    });
}


function createSimulationTicket() {
    $.ajax({
        type: "POST",
        url: "/ticket/simulate-request",
        dataType: "json",
        success: function (response) {
            if (response.code != 0) {
                return notyf.error(response.message);
            }

            notyf.success(response.message);

            fetchIncidentTicket();
        },
        error: function (response) {
            return notyf.error(response.responseJSON.message);
        },
    });
}

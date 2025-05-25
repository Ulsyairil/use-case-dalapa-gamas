function fetchDetailIncidentTicket(id) {
    if (!id) {
        window.location.href = '/404';
    }

    $("#ticket_content").LoadingOverlay("show");
    $("#technician_content").LoadingOverlay("show");
    $("#segment_content").LoadingOverlay("show");
    $("#technical_content").LoadingOverlay("show");
    $("#material_usage_content").LoadingOverlay("show");
    $("#image_content").LoadingOverlay("show");

    // Incident Ticket
    let ticketNumber = $("#ticket_number");
    let headline = $("#headline");
    let ticketDesc = $("#ticket_description");
    let ticketStatus = $("#ticket_status");

    // Technician
    let techName = $("#technician_name");
    let techNik = $("#nik");
    let techEmail = $("#email");
    let techPhone = $("#phone_number");

    // Segment
    let segment = $("#segment");
    let address = $("#address");
    let map = $("#map"); // a tag

    // Technical Description
    let techDesc = $("#technical_description");

    // Material Usage
    let materialUsage = $("#material_usage"); // div tag

    // WO Detail
    let createdAt = $("#created_at");
    let updatedAt = $("#updated_at");
    let status = $("#status");
    let woStatus = $("#status_wo");

    // Image
    let imageBefore = $("#image_before"); // img tag
    let imageAfter = $("#image_after"); // img tag

    // Note WO Verification
    let note = $("#wo_note");

    $.ajax({
        url: '/ticket/detail-request',
        method: 'POST',
        data: {
            item_id: id,
        },
        dataType: 'JSON',
        success: function(response) {
            if (response.code != 0) {
                if (response.message == "Ticket not found") {
                    return window.location.href = '/404';
                }

                return notyf.error(response.message);
            }

            let data = response.data;
            
            let responseTicketStatus = data[2][3];
            let responseWoStatus = data[1];

            $("#wo_id").val(data[0]);

            // Incident Ticket
            ticketNumber.val(data[2][0]);
            headline.val(data[2][1]);
            ticketDesc.val(data[2][2]);
            ticketStatus.val(data[2][3]);

            // Technician
            techName.val(data[3][0]);
            techNik.val(data[3][1]);
            techEmail.val(data[3][2]);
            techPhone.val(data[3][3]);

            // Segment
            segment.val(data[4][0]);
            address.val(data[4][1]);
            if (data[4][2] != '-') {
                map.attr('href', "https://www.google.com/maps/@" + data[4][2] + ",16z");
            }

            // Technical Description
            techDesc.val(data[5][0]);

            // Material Usage
            if (data[7].length != 0) {
                $("#material-usage-body").empty();
            }

            const tbodyMaterialUsage = document.getElementById('material-usage-body');
            data[7].forEach(element => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${element[0]}</td>
                    <td>${element[1]}</td>
                    <td>${element[3]}</td>
                    <td>${element[4]}</td>
                    <td>${element[6].toLocaleString()}</td>
                    <td>${element[7]}</td>
                    <td>${element[8].toLocaleString()}</td>
                    <td>${element[9].toLocaleString()}</td>
                `;
                tbodyMaterialUsage.appendChild(row);
            });
            
            // WO Detail
            createdAt.val(moment(data[8]).format('DD MMMM YYYY, HH:mm:ss') ?? '-');
            updatedAt.val(moment(data[9]).format('DD MMMM YYYY, HH:mm:ss') ?? '-');
            status.val(data[10][0]);
            woStatus.val(responseWoStatus);

            if (data[10][0] == "ditolak") {
                $("#note_content").removeClass('d-none');
                if (data[10][1] != '-') {
                    note.removeAttr('disabled');
                    note.val(data[10][1]);
                }
            }

            // Image
            if (data[6][0] != '-') {
                $("#btnImageBefore").removeAttr('disabled');
                imageBefore.attr('src', data[6][0]);
            }
            if (data[6][1] != '-') {
                $("#btnImageAfter").removeAttr('disabled');
                imageAfter.attr('src', data[6][1]);
            }

            if (responseWoStatus != 'DRAFT') {
                if (responseTicketStatus == "OPEN" || responseTicketStatus == "IN PROGRESS") {
                    $("#btn_save").removeAttr('disabled');
                    $("#status").removeAttr('disabled');
                }
            }
        },
        error: function(response) {
            return notyf.error(response.responseJSON.message);
        },
        complete: function() {
            $("#ticket_content").LoadingOverlay("hide");
            $("#technician_content").LoadingOverlay("hide");
            $("#segment_content").LoadingOverlay("hide");
            $("#technical_content").LoadingOverlay("hide");
            $("#material_usage_content").LoadingOverlay("hide");
            $("#image_content").LoadingOverlay("hide");
        }
    });
}

function verifyTicket(itemId) {
    if (!confirm("Are you sure you want to verify this ticket?")) {
        return false;
    }

    $("#ticket_content").LoadingOverlay("show");
    $("#technician_content").LoadingOverlay("show");
    $("#segment_content").LoadingOverlay("show");
    $("#technical_content").LoadingOverlay("show");
    $("#material_usage_content").LoadingOverlay("show");
    $("#image_content").LoadingOverlay("show");

    let woId = $("#wo_id").val();
    let statusVerificationWo = $("#status").val();
    let woNote = $("#wo_note").val();

    $.ajax({
        url: '/ticket/verification-request',
        method: 'POST',
        data: {
            item_id: woId,
            status: statusVerificationWo,
            note: woNote
        },
        dataType: 'JSON',
        success: function(response) {
            if (response.code != 0) {
                $("#ticket_content").LoadingOverlay("hide");
                $("#technician_content").LoadingOverlay("hide");
                $("#segment_content").LoadingOverlay("hide");
                $("#technical_content").LoadingOverlay("hide");
                $("#material_usage_content").LoadingOverlay("hide");
                $("#image_content").LoadingOverlay("hide");

                return notyf.error(response.message);
            }

            notyf.success(response.message);

            setTimeout(() => {
                location.reload();
            }, 2000);
        },
        error: function(response) {
            $("#ticket_content").LoadingOverlay("hide");
            $("#technician_content").LoadingOverlay("hide");
            $("#segment_content").LoadingOverlay("hide");
            $("#technical_content").LoadingOverlay("hide");
            $("#material_usage_content").LoadingOverlay("hide");
            $("#image_content").LoadingOverlay("hide");

            return notyf.error(response.responseJSON.message);
        },
    });
}
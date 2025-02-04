document.addEventListener('DOMContentLoaded', (event) => {
    function showModal(requestId, studentId, formType, requestDate, clearance, status) {
        var modal = document.getElementById("myModal");
        var span = document.getElementsByClassName("close")[0];
        var urlParams = new URLSearchParams(window.location.search);
        var currentStatus = urlParams.get('status') || '';

        document.getElementById("modalRequestId").value = requestId;
        document.getElementById("modalStudentId").value = studentId;
        document.getElementById("modalFormType").value = formType;
        document.getElementById("modalRequestDate").value = requestDate;
        document.getElementById("modalClearance").value = clearance;
        document.getElementById("modalStatus").value = status;
        document.getElementById("currentStatusInput").value = currentStatus;

        modal.style.display = "block";

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    }

    window.showModal = showModal;
});
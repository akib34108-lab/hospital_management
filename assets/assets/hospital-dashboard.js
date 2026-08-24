var donutChartData = {
    labels: [
        "Patients",
        "Admitted Patients",
        "Doctors",
        "Donors"
    ],
    datasets: [{
        data: [
            totalPatients,
            totalAdmitted,
            totalDoctors,
            totalDonors
        ],
        backgroundColor: [
            "#127cb9",
            "#52a25b",
            "#4057ab",
            "#d28fbe"
        ],
        borderWidth: 0
    }]
};
var donutctx = document.getElementById('donutgraph').getContext('2d');

new Chart(donutctx, {
    type: 'doughnut',
    data: donutChartData
});
var optionsProfileVisit = {
	annotations: {
		position: 'back'
	},
	dataLabels: {
		enabled:false
	},
	chart: {
		type: 'bar',
		height: 300
	},
	fill: {
		opacity:1
	},
	plotOptions: {
	},
	series: [{
		name: 'sales',
		data: [9,20,30,20,10,20,30,20,10,20,30,20]
	}],
	colors: '#435ebe',
	xaxis: {
		categories: ["Jan","Feb","Mar","Apr","May","Jun","Jul", "Aug","Sep","Oct","Nov","Dec"],
	},
}
let optionsVisitorsProfile  = {
	series: [70, 30],
	labels: ['Male', 'Female'],
	colors: ['#435ebe','#55c6e8'],
	chart: {
		type: 'donut',
		width: '100%',
		height:'350px'
	},
	legend: {
		position: 'bottom'
	},
	plotOptions: {
		pie: {
			donut: {
				size: '30%'
			}
		}
	}
}

var optionsEurope = {
	series: [{
		name: 'series1',
		data: [310, 800, 600, 430, 540, 340, 605, 805,430, 540, 340, 605]
	}],
	chart: {
		height: 80,
		type: 'area',
		toolbar: {
			show:false,
		},
	},
	colors: ['#5350e9'],
	stroke: {
		width: 2,
	},
	grid: {
		show:false,
	},
	dataLabels: {
		enabled: false
	},
	xaxis: {
		type: 'datetime',
		categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z","2018-09-19T07:30:00.000Z","2018-09-19T08:30:00.000Z","2018-09-19T09:30:00.000Z","2018-09-19T10:30:00.000Z","2018-09-19T11:30:00.000Z"],
		axisBorder: {
			show:false
		},
		axisTicks: {
			show:false
		},
		labels: {
			show:false,
		}
	},
	show:false,
	yaxis: {
		labels: {
			show:false,
		},
	},
	tooltip: {
		x: {
			format: 'dd/MM/yy HH:mm'
		},
	},
};

let optionsAmerica = {
	...optionsEurope,
	colors: ['#008b75'],
}
let optionsIndonesia = {
	...optionsEurope,
	colors: ['#dc3545'],
}
document.querySelector('#logoutForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    }).then(() => {
        window.location.href = '/admin/login';
    });
});

const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
tooltips.forEach(tooltipEl => {
    const tooltip = new bootstrap.Tooltip(tooltipEl, {
        trigger: 'manual',
        template: '<div class="tooltip" role="tooltip">' +
            '<div class="tooltip-inner bg-white text-dark shadow-lg rounded-2 p-2"></div>' +
            '</div>',
        customClass: 'custom-tooltip',
        html: true,
        sanitize: false,
        boundary: 'window',
        placement: 'auto'
    });

    tooltipEl.addEventListener('mouseenter', function () {
        tooltip.show();
    });

    tooltipEl.addEventListener('mouseleave', function () {
        tooltip.hide();
    });

});

$(document).ready(function () {
    $('#updateReadAtBtn').on('click', function (){
        $.ajax({
            url: route("admin.dashboard.updateReadAtNotification"),
            type: "POST",
            success: function (response) {
                if(response.success === true){
                    radaAlert.showSwalSuccess(response.message).then(function (confirm) {
                        if (confirm.isDismissed) {
                            location.reload();
                        }
                    });
                } else {
                    radaAlert.showSwalError(response.error);
                }
            },
            error: function (xhr) {
                console.error("Lỗi khi update", xhr);
                radaAlert.showSwalError("Có lỗi xảy ra khi update!");
            }
        });
    });
});

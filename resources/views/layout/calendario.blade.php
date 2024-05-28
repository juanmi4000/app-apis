<div id='calendar'></div>
<script>
	document.addEventListener('DOMContentLoaded', function () {
let calendarEl = document.getElementById('calendar');

var calendar = new FullCalendar.Calendar(calendarEl, {
initialView: 'dayGridMonth',
dayCellDidMount: function (arg) {
let btn = document.createElement("button");
btn.classList.add("btn-calendar");

let span = document.createElement("span");
span.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M11 11V7H13V11H17V13H13V17H11V13H7V11H11ZM12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20Z">
                            </path>
                        </svg>
                    `;
btn.appendChild(span);

arg.el.appendChild(btn);
},
eventClick: function (info) {
alert('Event: ' + info.event.title);
alert('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
alert('View: ' + info.view.type);

// change the border color just for fun
info.el.style.borderColor = 'red';
}

});
calendar.render()
})
</script>
<style>
	#calendar {
		max-width: calc(100% - 200px);
		margin: 0 auto;
		padding: 20px;
		background-color: #f9f9f9;
		border-radius: 10px;
		box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
	}

	.fc-toolbar {
		margin-bottom: 20px;
	}

	.fc-toolbar-title {
		font-size: 1.5em;
		margin-right: 20px;
	}

	.fc-button {
		background-color: #007bff;
		border-color: #007bff;
	}

	.fc-button:hover {
		background-color: #0056b3;
		border-color: #0056b3;
	}

	.fc-button-primary {
		background-color: #007bff;
		border-color: #007bff;
	}

	.fc-button-primary:hover {
		background-color: #0056b3;
		border-color: #0056b3;
	}

	.fc-button-group {
		margin-right: 20px;
	}

	.fc-day-header {
		font-weight: bold;
		font-size: 0.9em;
	}

	.fc-day {
		border: 1px solid #ddd;
		padding: 5px;
	}

	.fc-today {
		background-color: #f0f0f0;
	}

	.fc-event {
		background-color: #007bff;
		border-color: #007bff;
		color: #fff;
	}

	.fc-event:hover {
		background-color: #0056b3;
		border-color: #0056b3;
	}

	.fc-event-title {
		font-weight: bold;
	}

	.fc-prev-button,
	.fc-next-button {
		font-size: 1.2em;
	}

	#calendar {
		position: relative;
	}

	.btn-calendar {
		background: transparent;
		position: absolute;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
	}

	.btn-calendar span {
		display: none;
	}

	.btn-calendar:hover span {
		display: flex;
		justify-content: center;
		align-items: center;
		width: 100%;
		height: 100%;
		> svg {
			width: 35px;
		}
	}
</style>


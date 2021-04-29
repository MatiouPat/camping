
require('../css/reservations/view.css');

let $deleteBookingAlertBox = document.querySelector('#alert_box__bookingDelete');

function viewReturn() {
    if ($deleteBookingAlertBox != null){
        $deleteBookingAlertBox.style.display = 'none';
    }
}

function deleteAlertBooking(e){
    e.stopPropagation();
    $deleteBookingAlertBox.style.display = null;
    document.querySelectorAll('#alert_deleteButton__no').forEach(function (returnButton) {
        returnButton.addEventListener('click', viewReturn)
    })
}

let buttonBooking = document.querySelector('#button_deleteBooking');
if (buttonBooking != null){
    buttonBooking.addEventListener('click', deleteAlertBooking);
}
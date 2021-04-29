
require('../css/clients/view.css');

let $deleteEntryAlertBox = document.querySelector('#alert_box__entryDelete');
let $deleteStayAlertBox = document.querySelector('#alert_box__stayDelete');
let $deleteBookingAlertBox = document.querySelector('#alert_box__bookingDelete');

function viewReturn() {
    $deleteEntryAlertBox.style.display = 'none';
    if ($deleteStayAlertBox != null){
        $deleteStayAlertBox.style.display = 'none';
    }
    if ($deleteBookingAlertBox != null){
        $deleteBookingAlertBox.style.display = 'none';
    }
}

function deleteAlertUser(e){
    $deleteEntryAlertBox.style.display = null;
    document.querySelectorAll('#alert_deleteButton__no').forEach(function (returnButton) {
        returnButton.addEventListener('click', viewReturn)
    })
}

function deleteAlertStay(e){
    $deleteStayAlertBox.style.display = null;
    document.querySelectorAll('#alert_deleteButton__no').forEach(function (returnButton) {
        returnButton.addEventListener('click', viewReturn)
    })
}

function deleteAlertBooking(){
    $deleteBookingAlertBox.style.display = null;
    document.querySelectorAll('#alert_deleteButton__no').forEach(function (returnButton) {
        returnButton.addEventListener('click', viewReturn)
    })
}

document.querySelector('#button_deleteUser').addEventListener('click', deleteAlertUser);
let buttonStay = document.querySelector('#button_deleteStay');
if (buttonStay != null){
    buttonStay.addEventListener('click', deleteAlertStay);
}
let buttonBooking = document.querySelector('#button_deleteBooking');
if (buttonBooking != null){
    buttonBooking.addEventListener('click', deleteAlertBooking);
}
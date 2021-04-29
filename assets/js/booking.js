
require('../css/reservations/index.css');

let getHttpRequest = function () {
    let httpRequest = false;

    if (window.XMLHttpRequest) { // Mozilla, Safari,...
        httpRequest = new XMLHttpRequest();
        if (httpRequest.overrideMimeType) {
            httpRequest.overrideMimeType('text/xml');
        }
    }
    else if (window.ActiveXObject) { // IE
        try {
            httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e) {
            try {
                httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e) {}
        }
    }

    if (!httpRequest) {
        alert('Abandon :( Impossible de créer une instance XMLHTTP');
        return false;
    }

    return httpRequest
}

const post = function (url, vars) {
    return new Promise(function (resolve, reject) {
        let httpRequest = getHttpRequest();

        httpRequest.onreadystatechange = function () {
            if (httpRequest.readyState === 4) {
                if (httpRequest.status !== 200) {
                    let errors = JSON.parse(httpRequest.responseText);
                    reject(errors);
                } else {
                    let results = JSON.parse(httpRequest.responseText);
                    resolve(results);
                }

            }
        }
        httpRequest.open('POST', url, true);
        httpRequest.setRequestHeader("Content-type", "text/plain");
        httpRequest.send(vars);
    })

}

const getNumberbyMonth = function (month) {
    switch (month) {
        case 'Janvier':
            return '01';
        case 'Fevrier':
            return '02';
        case 'Mars':
            return '03';
        case 'Avril':
            return '04';
        case 'Mai':
            return '05';
        case 'Juin':
            return '06';
        case 'Juillet':
            return '07';
        case 'Aout':
            return '08';
        case 'Septembre':
            return '09';
        case 'Octobre':
            return '10';
        case 'Novembre':
            return '11';
        case 'Decembre':
            return '12';
    }
}

const getMonth = async function (status){
    let month = getDate()[0];
    let year = getDate()[1];
    return await post(Routing.generate('booking.index', {month: month, year: year, status: status}));
}

const getDate = function () {
    let date = document.querySelector('.date-number').innerHTML;
    date = date.split(' ');
    return date;
}

const setRoutingView = function () {
    document.querySelectorAll('.day-in').forEach(function (day) {
        day.addEventListener('click', function (e) {
            let number = day.firstElementChild.innerHTML.slice(0,2);
            let date = number + '-' + getNumberbyMonth(getDate()[0]) + '-' + getDate()[1]
            window.location = Routing.generate('booking.view', {date: date});
        })
    })
}



const load = async function() {
    document.querySelector('#day-down').addEventListener('click', async function () {
        console.log('down');
        let r = await getMonth('-');
        document.querySelector('.date-number').innerHTML = r['date'];
        let month = r['month'];
        console.log(r['booking'])
        let tbody = document.querySelector('.tbody');
        tbody.innerHTML = null;
        let indexWeek = 0;
        month.forEach(function (week) {
            indexWeek += 1;
            if (indexWeek === 6){
                document.querySelector('.calendar').classList.add('calendar6')
            }else{
                document.querySelector('.calendar').classList.remove('calendar6')
            }
            tbody.innerHTML += '<tr class="week"></tr>'
            week.forEach(function (day) {
                let dList = document.querySelectorAll('.week');
                let d = dList.item(dList.length-1);
                if (day[2] === false){
                    d.innerHTML += '<td class="day-out"></td>'
                }else if(day[2] === true){
                    if (day[3]){
                        d.innerHTML += '<td class="day-in"><span class="day-number" id=day'+day[1]+'>' + day[1] + '</span></td>'
                        day[3].forEach(function (booking) {
                            console.log(booking)
                            let index = booking['arrived_at'].split('T')[0].split('-')[2];
                            document.querySelector('#day'+index).innerHTML += '<div class="booking">' + booking.name + '</div>'
                        })
                    }else{
                        d.innerHTML += '<td class="day-in"><span class="day-number" id=day'+day[1]+'>' + day[1] + '</span></td>'
                    }
                }
            })
        })
        setRoutingView()
    });
    document.querySelector('#day-up').addEventListener('click', async function () {
        console.log('up');
        let r = await getMonth('+');
        document.querySelector('.date-number').innerHTML = r['date'];
        let month = r['month'];
        let tbody = document.querySelector('.tbody');
        tbody.innerHTML = null;
        let indexWeek = 0;
        month.forEach(function (week) {
            indexWeek += 1;
            if (indexWeek === 6){
                document.querySelector('.calendar').classList.add('calendar6')
            }else{
                document.querySelector('.calendar').classList.remove('calendar6')
            }
            tbody.innerHTML += '<tr class="week"></tr>'
            week.forEach(function (day) {
                let dList = document.querySelectorAll('.week');
                let d = dList.item(dList.length-1);
                if (day[2] === false){
                    d.innerHTML += '<td class="day-out"></td>'
                }else if(day[2] === true){
                    if (day[3]){
                        d.innerHTML += '<td class="day-in"><span class="day-number" id=day'+day[1]+'>' + day[1] + '</span></td>'
                        day[3].forEach(function (booking) {
                            let index = booking['arrived_at'].split('T')[0].split('-')[2];
                            document.querySelector('#day'+index).innerHTML += '<div class="booking">' + booking.name + ' ' + booking.spot + '</div>'
                            console.log(booking)
                        })
                    }else{
                        d.innerHTML += '<td class="day-in"><span class="day-number" id=day'+day[1]+'>' + day[1] + '</span></td>'
                    }
                }
            })
        })
        setRoutingView();
    })
    setRoutingView();
}

load();

require('../css/map.css');

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

const getMonth = function (month) {
    switch (month) {
        case 'Janvier':
            return '01';
        case 'Février':
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
        case 'Août':
            return '08';
        case 'Septembre':
            return '09';
        case 'Octobre':
            return '10';
        case 'Novembre':
            return '11';
        case 'Décembre':
            return '12';
    }
}

const getDate = function () {
    let date = document.querySelector('.date-number').textContent;
    date = date.split(' ');
    date[2] = getMonth(date[2]);
    return date[3] + "-" + date[2] + '-' + date[1];
}

const getWeek = function () {
    return document.querySelector('.week-number').textContent;
}

const getOpacity = function (color){
    if (color === 'rgb(255, 0, 0)'){
        return '#AA0000'
    }
    if (color === 'rgb(255, 255, 0)'){
        return '#AAAA00'
    }
    if (color === '#FF8000'){
        return '#AA5000'
    }
    if (color === 'rgb(0, 170, 0)'){
        return '#006600'
    }
    if (color === 'rgb(216, 216, 216)'){
        return '#737373'
    }
    if (color === 'rgb(27, 72, 157)'){
        return '#070459'
    }

    if (color === 'rgb(170, 0, 0)'){
        return '#FF0000'
    }
    if (color === 'rgb(170, 170, 0)'){
        return '#FFFF00'
    }
    if (color === '#FF8000'){
        return '#AA5000'
    }
    if (color === 'rgb(0, 102, 0)'){
        return '#00AA00'
    }
    if (color === 'rgb(115, 115, 115)'){
        return '#D8D8D8'
    }
    if (color === 'rgb(7, 4, 89)'){
        return '#1b489d'
    }
}

const getStays = async function (date, week, day, weekNumber){
    return await post(Routing.generate('map.info', {date: date, week: week, day: day, weekNumber: weekNumber}));
}

const setColorMap = function (stays){
    document.querySelectorAll('.spot').forEach(function (spot) {
        spot.firstElementChild.style.fill = "#D8D8D8";
    })
    stays.forEach(function (stay){
        let spot = stay[0].spot;
        let paid = stay[0].paid;
        let leavedToday = stay[0].today_leaved;
        let deadGarage = stay[1];
        let booking = stay[0].booking;

        let SPOT = document.querySelector('#' + spot);
        if (SPOT){
            if (booking === true){
                SPOT.firstElementChild.style.fill = "#1b489d";
            }else if(deadGarage === true){
                SPOT.firstElementChild.style.fill = "#FFFF00";
            }else if(leavedToday === true) {
                SPOT.firstElementChild.style.fill = "#FF8000";
            }else if(paid === true && booking === false){
                SPOT.firstElementChild.style.fill = "#FF0000";
            }else if (paid === false && booking === false){
                SPOT.firstElementChild.style.fill = "#00AA00";
            }
        }
    })
}

const setDate = function (date){
    let DATE = document.querySelector('.date-number');
    let WEEK = document.querySelector('.week-number');
    DATE.innerHTML = date[0] + ' ' + date[1] + ' ' + date[3] + ' ' + date[4];
    WEEK.innerHTML = date[2];
}

const load = async function() {
    let stays = await getStays(getDate(), null, null, getWeek());
    document.querySelector('#week-down').addEventListener('click', async function () {
        stays = await getStays(getDate(), '-', null, getWeek());
        setColorMap(stays[0]);
        setDate(stays[1]);
    });
    document.querySelector('#week-up').addEventListener('click', async function () {
        stays = await getStays(getDate(), '+', null, getWeek());
        setColorMap(stays[0]);
        setDate(stays[1]);
    });
    document.querySelector('#day-down').addEventListener('click', async function () {
        stays = await getStays(getDate(), null, '-', getWeek());
        setColorMap(stays[0]);
        setDate(stays[1]);
    });
    document.querySelector('#day-up').addEventListener('click', async function () {
        stays = await getStays(getDate(), null, '+', getWeek());
        setColorMap(stays[0]);
        setDate(stays[1]);
    });

    let $spotBoxInformations = document.querySelector('.spot-box-informations');
    document.querySelectorAll('.spot').forEach(function ($spot) {
        $spot.firstElementChild.style.fill = "#D8D8D8";
        $spot.firstElementChild.addEventListener('mouseover',function (e) {
            e.target.style.fill = getOpacity(e.target.style.fill)
            let x = e.target.getBoundingClientRect().x - 100;
            let y = e.target.getBoundingClientRect().y - 300 + document.documentElement.scrollTop;
            $spotBoxInformations.style.left = x + 'px';
            $spotBoxInformations.style.top = y + 'px';
            $spotBoxInformations.style.display = 'flex';
            $spotBoxInformations.style.flexDirection = 'column';
            stays[0].forEach(function (stay) {
                let limitDate = stay[2]['leaved_at'].split('/');
                limitDate = limitDate[2] + '-' + limitDate[1] + '-' + limitDate[0];
                if (stay[0].spot === e.target.parentElement.id && getDate() < limitDate){
                    if ($spotBoxInformations.innerHTML === ''){
                        $spotBoxInformations.innerHTML = '<div id="spot-number"></div>' +
                            '<hr width="200">' +
                            '<div id="spot-informations">' +
                            '<div class="spot-informations-box" id="spot-stay-date"></div>' +
                            '<div class="spot-informations-box" id="spot-person-name"></div>' +
                            '<hr width="200">' +
                            '<div class="spot-informations-box" id="spot-stay-person"></div>'+
                            '<hr width="50">' +
                            '<div class="spot-informations-box" id="spot-stay-information"></div>'+
                            '<span class="spot-informations-box" id="spot-stay-registration"></span>'+
                            '<hr width="50">' +
                            '<div class="spot-informations-box" id="spot-stay-status"></div>' +
                            '</div>';

                        document.querySelector('#spot-number').innerHTML = e.target.parentElement.id;
                        document.querySelector('#spot-stay-date').innerHTML = "Du " + stay[2]['arrived_at'] + " au " + stay[2]['leaved_at'];
                        document.querySelector('#spot-person-name').innerHTML = stay[0].entry;

                        let $spotStayPerson = document.querySelector('#spot-stay-person');
                        if(stay[0].adult > 0) {
                            $spotStayPerson.innerHTML += '<span>' + stay[0].adult + '</span><img style="height: 30px; width: 30px" src="/img/adult.svg">';
                        }
                        if(stay[0].teenager > 0) {
                            $spotStayPerson.innerHTML += '<span>' + stay[0].teenager + '</span><img style="height: 30px; width: 30px" src="/img/teenager.svg">';
                        }
                        if(stay[0].child > 0){
                            $spotStayPerson.innerHTML += '<span>' + stay[0].child + '</span><img style="height: 30px; width: 30px" src="/img/child.svg">';
                        }

                        let $spotStayInformation = document.querySelector('#spot-stay-information');
                        if(stay[0].tent > 0) {
                            $spotStayInformation.innerHTML += '<span>' + stay[0].tent + '</span><img style="height: 30px; width: 30px" src="/img/tent.svg">';
                        }
                        if(stay[0].car > 0) {
                            $spotStayInformation.innerHTML += '<span>' + stay[0].car + '</span><img style="height: 30px; width: 30px" src="/img/car.svg">';
                        }
                        if(stay[0].caravan > 0){
                            $spotStayInformation.innerHTML += '<span>' + stay[0].caravan + '</span><img style="height: 30px; width: 30px" src="/img/caravan.svg">';
                        }
                        if(stay[0].camping_car > 0){
                            $spotStayInformation.innerHTML += '<span>' + stay[0].camping_car + '</span><img style="height: 30px; width: 30px" src="/img/camping_car.svg">';
                        }
                        if(stay[0].electricity === true){
                            $spotStayInformation.innerHTML += '<img style="height: 30px; width: 30px; margin-left: 15px" src="/img/electricity.svg">';
                        }

                        document.querySelector('#spot-stay-registration').innerHTML = stay[0].registration;

                        let $spotStayStatus = document.querySelector('#spot-stay-status');
                        if (stay[0].booking === true){
                            $spotStayStatus.innerHTML = "Réservation";
                            $spotStayStatus.style.color = "#1b489d";
                            document.querySelector('#spot-stay-date').style.color = "#1b489d";
                        }else if (stay[0].paid === true){
                            $spotStayStatus.innerHTML = "Emplacement payé";
                            $spotStayStatus.style.color = "#EE0000";
                            document.querySelector('#spot-stay-date').style.color = "#EE0000";
                        }else if (stay[0].paid === false){
                            $spotStayStatus.innerHTML = "Emplacement non payé";
                            $spotStayStatus.style.color = "#00AA00";
                            document.querySelector('#spot-stay-date').style.color = "#00AA00";
                        }


                    }
                }
            })
            if ($spotBoxInformations.innerHTML === ''){
                $spotBoxInformations.innerHTML = '<div id="spot-number"></div>' +
                    '<hr width="200">' +
                    '<div id="spot-informations"></div>'
                document.querySelector('#spot-number').innerHTML = e.target.parentElement.id;
                document.querySelector('#spot-informations').innerHTML = "Aucun séjour à cette emplacement<br><hr width=\"50\">Veuillez cliquer pour ajouter un séjour à cette emplacement";

            }
        });
        $spot.firstElementChild.addEventListener('mouseout', function (e) {
            e.target.style.fill = getOpacity(e.target.style.fill)
            $spotBoxInformations.style.display = 'none';
            $spotBoxInformations.innerHTML = null;
        });
        $spot.firstElementChild.addEventListener('click', function (e) {
            let id = e.target.parentElement.id;
            let redir = false;
            stays[0].forEach(function (stay) {
                if(stay[0].spot === e.target.parentElement.id && stay[0].leaved_at > getDate()){
                    redir = true;
                    window.location = Routing.generate('entry.view', {id: stay[0].id});
                }
            })
            if (redir === false){
                window.location = Routing.generate('stay.add', {spot: id});
            }
        });
    })

    setColorMap(stays[0]);

}
load();
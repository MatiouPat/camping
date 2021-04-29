
require('../css/clients/index.css');

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

const load = function() {

    document.querySelector('.research_box__text').addEventListener('keyup', async function (e) {
        console.log(e.target.value);
        let entries = await post(Routing.generate('entry.research', {name: e.target.value}));
        document.querySelector('.tbody').innerHTML = null;
        let key = 1;
        entries.forEach(function (entry) {
            console.log(entry)
            if (key % 2 == 0) {
                document.querySelector('.tbody').innerHTML += '<tr class="row-blue" onclick="document.location=\'' + Routing.generate('entry.view', {id: entry.id}) + '\'">' +
                    '<td><a>' + ((entry.gender === 0) ? 'Mme ' : (entry.gender === 1) ? 'Mr ' : 'Mr & Mme ') + entry.surname + ' ' + entry.name + '</a></td>' +
                    '<td><a>' + entry.address + '</a></td>' +
                    '<td><a>' + entry.postal_code + '</a></td>' +
                    '<td><a>' + entry.city + '</a></td>' +
                    '<td><a>' + entry.nationality + '</a></td>' +
                    '</tr>'
            } else {
                document.querySelector('.tbody').innerHTML += '<tr class="row-grey" onclick="document.location=\''  + Routing.generate('entry.view', {id: entry.id}) + '\'">' +
                    '<td><a>' + ((entry.gender === 0) ? 'Mme ' : (entry.gender === 1) ? 'Mr ' : 'Mr & Mme ') + entry.surname + ' ' + entry.name + '</a></td>' +
                    '<td><a>' + entry.address + '</a></td>' +
                    '<td><a>' + entry.postal_code + '</a></td>' +
                    '<td><a>' + entry.city + '</a></td>' +
                    '<td><a>' + entry.nationality + '</a></td>' +
                    '</tr>'
            }
            key++
        })
    })
}

load();
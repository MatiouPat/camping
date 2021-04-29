
require('../css/sejours/add.css');

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

const reseach = async function() {
    document.querySelector('.research_box__text').addEventListener('keyup', async function (e) {
        let entries = await post(Routing.generate('entry.research', {name: e.target.value}));
        document.querySelector('.stay-entry_list').innerHTML = null;
        let key = 1;
        entries.forEach(function (entry) {
            console.log(entry)
            if (entry.gender === 0){
                document.querySelector('.stay-entry_list').innerHTML += '<div class="stay-entry_box">' +
                    '<span> Mme ' + entry.surname + ' ' + entry.name + '</span>' +
                    '<span>' + entry.address + '</span>' +
                    '<span>' + entry.postal_code + ' ' + entry.city + '</span>' +
                    '</div>'
            }else if (entry.gender === 1){
                document.querySelector('.stay-entry_list').innerHTML += '<div class="stay-entry_box">' +
                    '<span> Mr ' + entry.surname + ' ' + entry.name + '</span>' +
                    '<span>' + entry.address + '</span>' +
                    '<span>' + entry.postal_code + ' ' + entry.city + '</span>' +
                    '</div>'
            }else if (entry.gender === 2){
                document.querySelector('.stay-entry_list').innerHTML += '<div class="stay-entry_box">' +
                    '<span> Mr & Mme ' + entry.surname + ' ' + entry.name + '</span>' +
                    '<span>' + entry.address + '</span>' +
                    '<span>' + entry.postal_code + ' ' + entry.city + '</span>' +
                    '</div>'
            }
        })
        setEntryCheck();
    })
}

const getEntryCheck = function () {
    let element = null;
    document.querySelectorAll('.stay-entry_box').forEach(function (e) {
        if (e.style.backgroundColor === "rgb(157, 197, 204)"){
            element = e;
        }
    })
    return element;
}

const setEntryCheck = function () {
    document.querySelectorAll('.stay-entry_box').forEach(function (e) {
        e.addEventListener('click', function (f) {
            let element = e;
            if(getEntryCheck()) {
                getEntryCheck().style.backgroundColor = null
            }
            element.style.backgroundColor = "#9dc5cc"

            let entriesForm = document.querySelector('#stay_entry').children
            for(let i = 0; i < entriesForm.length; i++){
                if (entriesForm[i].nodeName === "LABEL" && entriesForm[i].innerHTML === element.firstElementChild.firstElementChild.innerHTML) {// && entriesForm[i].checked

                    let entryForm = entriesForm[i].attributes[0].value;
                    for(let y = 0; y < entriesForm.length; y++) {
                        if (entriesForm[y].nodeName === "INPUT" && entriesForm[y].id === entryForm){
                            entriesForm[y].checked = true;
                        }
                    }
                }
            }
        })
    })
}

class ArrivedDate
{

}

const dateChange = function (e) {
    console.log(e.target)
}

const load = async function() {
    document.querySelector('#stay_arrived_at').addEventListener('change', dateChange);
    reseach();
    setEntryCheck();

    let entriesForm = document.querySelector('#stay_entry').children;
    for(let i = 0; i < entriesForm.length; i++){
        if (entriesForm[i].nodeName === "INPUT"){
            entriesForm[i].checked = false;
        }
    }
/*
    document.querySelector('.button_stay__add').addEventListener('click', function (e) {
        if (!getEntryCheck()){
            document.querySelector('.row_errors__entry').style.display = "block";
        }else{
            document.querySelector('.row_errors__entry').style.display = "none";
        }
    })*/
}

load();
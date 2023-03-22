var cl = {
    baseUrl: null,
    tabela: null,
    init: (url) => {
        if (url) {
            cl.baseUrl = url
        }
        console.log('init system')
    },
    httpGetAsync: (theUrl, callback) =>
    {
        var xmlHttp = new XMLHttpRequest()
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                document.getElementById('ico-reload').style.visibility = 'hidden';
                callback(xmlHttp.responseText)
            }
        }
        xmlHttp.open("GET", theUrl, true); // true for asynchronous 
        xmlHttp.send(null)
    },
    loadData: () => {
        document.getElementById('ico-reload').style.visibility = 'visible';
        cl.httpGetAsync(cl.baseUrl + '/site/track', cl.showTable);
    },
    showTable: (response) => {
        const data = JSON.parse(response)
        for (i = 0; i < data.length; i++) {
            cl.addItemTable(data[i])
        }
    },
    addItemTable: (data) => {
        cl.tabela = document.querySelector("#myTable tbody");
        let rows = cl.tabela.rows;
        var indexn = cl.getNewIndexTable(rows, data.id);
        cl.setTable(indexn, data);
    },
    getNewIndexTable: function (rows, id) {
        for (var x = 0; x <= rows.length - 1; x++) {
            if (rows[x].cells[0].getAttribute("uuid") == id) {
                return x;
            }
        }
        return false;
    },
    setTable: function (index, data) {
        var NewRow;
        var nindex = 0;
        if (index !== false) {
            NewRow = cl.tabela.rows[index];
        } else {
            NewRow = cl.tabela.insertRow(0);
            cl.tabela.rows[nindex].insertCell(0);
            cl.tabela.rows[nindex].insertCell(1);
            cl.tabela.rows[nindex].insertCell(2);
            cl.tabela.rows[nindex].insertCell(3);
            cl.tabela.rows[nindex].insertCell(4);
            cl.tabela.rows[nindex].insertCell(5);
        }
        var Newcell1 = NewRow.cells[0];
        var Newcell2 = NewRow.cells[1];
        var Newcell3 = NewRow.cells[2];
        var Newcell4 = NewRow.cells[3];
        var Newcell5 = NewRow.cells[4];
        var Newcell6 = NewRow.cells[5];
        Newcell1.setAttribute("uuid", data.id);
        Newcell1.innerHTML = data.id;
        Newcell2.innerHTML = data.url;
        Newcell3.innerHTML = data.status;
        Newcell4.innerHTML = data.updated;
        Newcell5.innerHTML = data.user;
        Newcell6.innerHTML = data.btn;
    },

}

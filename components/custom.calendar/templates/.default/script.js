BX.ready(function() {
    function closeModal() {
        BX('formModal').style.display = "none";
    }
    function closeModalInfo() {
        BX('formModalInfo').style.display = "none";
    }
    function reloadTable() {
        let data = {
            'date': BX('current-month').textContent,
            'iBlockId': BX('formModal').getAttribute("data-id"),
            'activity': 'reloadTable'
        };
        BX.ajax({
            url: '/local/components/custom.calendar/ajax.php', 
            data: data, 
            method: 'POST', 
            dataType: 'json', 
            onsuccess: function(data) { 
                console.log(data.data); 
                $('#tbody-custom-calendar').empty();
                let emptyCells = data.data.FIRST_DAY_WEEK-1;
                var newRow;
                var newCell;
                if(emptyCells>0) {
                    newRow = $('<tr></tr>');
                    newRow.appendTo('#tbody-custom-calendar');
                    for (let i = 0; i < emptyCells; i++) {
                        newCell = $('<td></td>');
                        newCell.appendTo(newRow);
                    }
                }
                
                for (let day = 1; day <= data.data.DAY_IN_MONTHS; day++) {
                    if ((day + emptyCells) % 7 == 1) {
                        newRow = $('<tr></tr>');
                        newRow.appendTo('#tbody-custom-calendar');
                    }
                    let date = day<10?'0'+day+'.'+BX('current-month').textContent:day+'.'+BX('current-month').textContent;
                    let now = new Date();
                    if(date == (now.getDate()<10?'0'+now.getDate():now.getDate())+'.'+
                        ((now.getMonth()+ 1)<10? '0'+(now.getMonth()+ 1):now.getMonth()+ 1)
                        +'.'+now.getFullYear()) 
                    {
                        newCell = $('<td class="today"></td>');
                    }
                    else {
                        newCell = $('<td></td>');
                    }
                    let newDiv=$("<div class='content-note-calendar'></div>");
                    newDiv.appendTo(newCell);
                    let foundObj;
                    for (let key in data.data.EVENT) {
                        if (data.data.EVENT.hasOwnProperty(key)) {
                            let targetDate = date;
                            if (key === targetDate) {
                                foundObj = data.data.EVENT[key];
                                break;
                            }
                        }
                    }
                    if(foundObj){
                        foundObj.forEach(function(event) {
                            let newEvents = $("<a class='note-calendar' id='" + event.ID + "'>" + event.PROPERTY_TITLE_EVENT_VALUE + "</a>");
                            newEvents.appendTo(newDiv);
                        });
                    }
                    let newDey = $("<p class='day-calendar'>"+day+"</p>");
                    newDiv.appendTo(newCell);
                    newDey.appendTo(newCell);
                    newCell.appendTo(newRow);
                }
                eventNoteDetail();
            }
        });
    }
    BX.bind(BX('addNote'),'click',function(){
        BX('formModal').style.display = "block";
    });
    BX.bind(BX('close-modal'),'click',function(){
        closeModal();
    });
    BX.bind(BX('close-modalInfo'),'click',function(){
        closeModalInfo();
    });
    BX.bind(BX('saveNote'),'click',function(event){
        event.preventDefault();
        let data = {
            'DATE_EVENT': BX('DATE_EVENT').value,
            'TITLE_EVENT': BX('TITLE_EVENT').value,
            'DETAIL_EVENT': BX('DETAIL_EVENT').value,
            'activity': 'saveNote',
            'iBlockId': BX('formModal').getAttribute("data-id")
        };
        BX.ajax({
            url: '/local/components/custom.calendar/ajax.php', 
            data: data, 
            method: 'POST', 
            dataType: 'json', 
            onsuccess: function(data) { 
                reloadTable();
            }
        });
        closeModal();
    });
    function eventNoteDetail() {
        $('.note-calendar').on('click', function(event) {
            BX('formModalInfo').style.display = "block";
            let data = {
                'ID':event.target.id,
                'activity': 'getNote',
                'iBlockId': BX('formModal').getAttribute("data-id")
            };
            BX.ajax({
                url: '/local/components/custom.calendar/ajax.php', 
                data: data, 
                method: 'POST', 
                dataType: 'json', 
                onsuccess: function(data) { 
                    console.log(data.data.EVENT.PROPERTY_DATE_EVENT_VALUE);
                    $('#dateNote').text(data.data.EVENT.PROPERTY_DATE_EVENT_VALUE);
                    $('#titleNote').text(data.data.EVENT.PROPERTY_TITLE_EVENT_VALUE);
                    $('#detailNote').text(data.data.EVENT.PROPERTY_DETAIL_EVENT_VALUE);
                }
            });
        });
    }

    BX.bind(BX('arrow-left'),'click',function(){
        var dateString = '01.'+BX('current-month').textContent;
        var parts = dateString.split('.');
        let currentDate = new Date(parseInt(parts[2], 10), (parseInt(parts[1], 10)-1) - 1, parseInt(parts[0], 10));
        BX('current-month').textContent = ('0' + (currentDate.getMonth()+1)).slice(-2) + '.' + currentDate.getFullYear();
        reloadTable();
    });
    BX.bind(BX('arrow-right'),'click',function(){
        var dateString = '01.'+BX('current-month').textContent;
        var parts = dateString.split('.');
        let currentDate = new Date(parseInt(parts[2], 10), (parseInt(parts[1], 10)-1) + 1, parseInt(parts[0], 10));
        BX('current-month').textContent = ('0' + (currentDate.getMonth()+1)).slice(-2) + '.' + currentDate.getFullYear();
        reloadTable();
    });
    eventNoteDetail();
    window.addEventListener("click", function(event) {
        if (event.target == formModal) {
            closeModal();
        }
        else if (event.target == formModalInfo) {
            closeModalInfo();
        }
    });
});


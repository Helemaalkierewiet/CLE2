<?php
require_once 'includes/database.php';

/** @var mysqli $db */

// Fetch afspraken uit de database
$query = "SELECT * FROM calender_apps";
$result = mysqli_query($db, $query);

// Zet de afspraken om in het juiste formaat voor  het calender scrips xoxox i love oai
$calender_apps = [];
while ($row = mysqli_fetch_assoc($result)) {
    $calender_apps[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'start' => $row['start'],
        'end' => $row['end'],
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afspraken Kalender</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
</head>
<body>

<div id="calendar"></div>

<!-- Voeg een Xtra modal toe-->
<div id="timeSlotModal" title="Kies een tijdslot">
    <p>Kies een tijdslot:</p>
    <form>
        <label><input type="radio" name="timeSlot" value="1"> 09:00 - 11:00</label>
        <label><input type="radio" name="timeSlot" value="2"> 13:30 - 15:30</label>
    </form>
</div>a

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script>
    $(document).ready(function () {
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultView: 'month',
            editable: true,
            events: <?php echo json_encode($calender_apps); ?>,
            selectable: false, // Zet FullCalendar-selectie uit
            eventClick: function (event) {
                if (confirm('Wil je deze afspraak verwijderen?')) {
                    $.ajax({
                        url: 'delete_appointment.php',
                        type: 'POST',
                        data: { id: event.id },
                        success: function () {
                            $('#calendar').fullCalendar('removeEvents', event.id);
                        }
                    });
                }
            },
            eventRender: function(event, element) {
                element.find('.fc-title').html(event.title);
            }
        });

        // Click calender == onclick functie
        $('#calendar').on('click', '.fc-day', function (e) {
            e.preventDefault();
            showModal(moment($(this).data('date')));
        });
    });

    function showModal(selectedDate) {
        $("#timeSlotModal").dialog({
            modal: true,
            buttons: {
                "OK": function () {
                    var selectedTimeSlot = $("input[name='timeSlot']:checked").val();
                    if (selectedTimeSlot) {
                        var title = getTimeSlotTitle(selectedTimeSlot);
                        var eventData = {
                            title: title,
                            start: selectedDate.format(),
                            end: selectedDate.clone().add(2, 'hours').format()
                        };

                        // Controleer of het tijdslot beschikbaar is
                        isTimeSlotAvailable(eventData.start, eventData.end)
                            .then(function (isAvailable) {
                                if (isAvailable) {
                                    $('#calendar').fullCalendar('renderEvent', eventData, true);
                                    addAppointmentToDatabase(eventData);
                                    $(this).dialog("close");
                                } else {
                                    alert('Dit tijdslot is al geboekt. Kies een ander tijdslot.');
                                }
                            })
                            .catch(function (error) {
                                console.error("Error checking availability:", error);
                            });
                    }
                },
                "Annuleren": function () {
                    $(this).dialog("close");
                }
            }
        });
    }

    function getTimeSlotTitle(selectedTimeSlot) {
        // Voeg hier de logica toe om de juiste tekst voor elk tijdslot te bepalen
        if (selectedTimeSlot === '1') {
            return 'Tijdslot 1 (09:00 - 11:00)';
        } else if (selectedTimeSlot === '2') {
            return 'Tijdslot 2 (13:30 - 15:30)';
        } else {
            return 'Onbekend tijdslot';
        }
    }

    function isTimeSlotAvailable(startTime, endTime) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                type: 'POST',
                url: 'check_availability.php',
                data: { startTime: startTime, endTime: endTime },
                success: function (response) {
                    resolve(response === 'available');
                },
                error: function () {
                    reject(false);
                }
            });
        });
    }

    function addAppointmentToDatabase(eventData) {
        console.log("Sending data to server:", eventData); // Voeg deze regel toe voor debugging
        $.ajax({
            type: 'POST',
            url: 'add_appointment.php',
            data: {
                start: eventData.start,
                end: eventData.end,
                timeSlot: eventData.timeSlot
            },
            success: function (response) {
                console.log("Server response:", response); // Voeg deze regel toe voor debugging
                if (response !== 'Error') {
                    eventData.id = response;
                } else {
                    alert('Er is een fout opgetreden bij het toevoegen van de afspraak aan de database.');
                }
            }
        });
    }
</script>

</body>
</html>